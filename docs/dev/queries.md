
#How to manage unread count of discussions ?

Check The query helper for the current (working) solution.

Here is an interesting proposal : http://forum.kohanaframework.org/discussion/comment/35092#Comment_35092

""""
I've been doing something similar and needed to record how many comments signed in users have read too, so I could send them to the first unread comment in each discussion.

I had a look at the latest source code of Vanilla Forums to see how they did it and their approach looks to save a lot of database calls, but requires tighter management.

Here's a basic rundown.

Discussions table
id
name
comment_id
total_comments

Comments table
id
body
discussion_id

DiscussionWatches table
id
user_id
discussion_id
read_comments


When you add a Discussion, you also add a Comment and assign its ID to the Discussion->comment_id. Default Discussion->total_comments to 1. Now whenever a user replies to this Discussion, increment the total_comments count. Whenever a signed in user visits a Discussion, create an entry in the DiscussionWatches table, setting the read_comments to however many comments in the Discussion the user has read. When they return to the Discussion later, send them to read_comments + 1.

Obviously there's more going on with handling the read_comments with pagination and so on but I found this way to be quite straight forward and easy to get going, and seems to save on database calls compared to how I was originally planning on doing it, which was:

Log the last time the signed in user visited the discussion. When they visit it, lookup the last time the visited, and find the first comment added after that time then send them to it. I had some problems going this way with pagination since they might read only page 1 of a 5 page discussion, and when they visit again, it would think they had read all 5 pages instead of just page 1. I could set the log timestamp to the created timestamp of the last read comment on page 1 but then my logs would be off (used for other things) and I may as well have another table to record those times.

The Vanilla Forums way also saves database calls on the list of discussions pages, since instead of doing a call to count how many comments are in each discussion, I already have it in the total_comments property.
""""




Attempts at understanding queries...



This one works (but is ugly and probably not scalable) :
---------------
Get all the unread discussions of a user only for the groups he is a member of...

SELECT *
FROM discussions
WHERE group_id
in (
SELECT membership.group_id
FROM membership
WHERE membership.user_id = 235
) and discussions.id
in (
select comments.discussion_id from comments
left join `user_read_discussion`
on `user_read_discussion`.`discussion_id` = `comments`.`discussion_id`
and `user_read_discussion`.`user_id` = '235'
and `user_read_discussion`.`read_at` >= `comments`.`created_at`
where `comments`.`deleted_at` is null and `comments`.`discussion_id` = discussions.id
and `comments`.`discussion_id` is not null and `user_read_discussion`.`id`
is null)


/* Selectionnner tous les commentaires auxquels quelqu'un est abonné */
SELECT *
from comments
where discussion_id
in
(
select discussion_id FROM discussions
WHERE group_id
in (
SELECT membership.group_id
FROM membership
WHERE membership.user_id =235
)
)


/* Selectionnner toutes les discussions auxquelles quelqu'un est abonné */
SELECT *
FROM discussions
WHERE group_id
in (
SELECT membership.group_id
FROM membership
WHERE membership.user_id =235
)





// get all discussions unread by a user ? : does't work
$discussions = \App\Discussion::with('group')->select('discussions.*')
->leftJoin('user_read_discussion', function($join)
{
  $join->on('user_read_discussion.discussion_id', '=', 'discussions.id')
  ->where('user_read_discussion.user_id', '=', \Auth::user()->id)
  ->on('user_read_discussion.read_at', '>=', 'discussions.created_at');
})
->whereNull('user_read_discussion.id')

->orderBy('discussions.updated_at', 'desc')->paginate(50);
return view('discussions.general_index')
->with('discussions', $discussions);
