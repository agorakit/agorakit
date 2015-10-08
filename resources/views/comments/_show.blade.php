
<div class="comment">

<div class="user">by {{$comment->user->name}}</div>

<div class="body">{{$comment->body}}</div>

<div class="created">{{$comment->created_at->diffForHumans()}}</div>

</div>
