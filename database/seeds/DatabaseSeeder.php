<?php

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        // create users
        DB::table('users')->delete();

        // first created user is automagically admin
        $admin = App\User::create([
            'email'    => 'admin@agorakit.org',
            'password' => bcrypt('123456'),
            'body'     => $faker->text,
            'name'     => 'Administrator',
            'verified' => 1,
        ]);

        // add avatar to admin user
        Storage::disk('local')->makeDirectory('users/'.$admin->id);
        Image::make($faker->imageUrl(500, 400, 'people'))->widen(500)->save(storage_path().'/app/users/'.$admin->id.'/cover.jpg')->fit(128, 128)->save(storage_path().'/app/users/'.$admin->id.'/thumbnail.jpg');


        // a second normal user
        $normal_user = App\User::create([
            'email'    => 'newbie@agorakit.org',
            'password' => bcrypt('123456'),
            'body'     => $faker->text,
            'name'     => 'Normal user',
            'verified' => 1,
        ]);



        for ($i = 1; $i <= 10; ++$i) {
            $user = App\User::create([
                'email'    => $faker->safeEmail,
                'password' => bcrypt('secret'),
                'name'     => $faker->name,
                'body'     => $faker->text(1000),
            ]);

            // add avatar to every user
            Storage::disk('local')->makeDirectory('users/'.$user->id);
            Image::make($faker->imageUrl(500, 400, 'people'))->widen(500)->save(storage_path().'/app/users/'.$user->id.'/cover.jpg')->fit(128, 128)->save(storage_path().'/app/users/'.$user->id.'/thumbnail.jpg');
        }

        // create 10 groups
        for ($i = 1; $i <= 10; ++$i) {
            $group = App\Group::create([
                'name' => $faker->city.'\'s user group',
                //'name' => 'Group ' . $faker->sentence(3),
                'body' => $faker->text,
            ]);

            // add cover image to groups
            Storage::disk('local')->makeDirectory('groups/'.$group->id);
            Image::make($faker->imageUrl())->widen(800)->save(storage_path().'/app/groups/'.$group->id.'/cover.jpg')->fit(300, 200)->save(storage_path().'/app/groups/'.$group->id.'/thumbnail.jpg');

            // add members to the group
            for ($j = 1; $j <= $faker->numberBetween(5, 20); ++$j) {
                $membership = \App\Membership::firstOrNew(['user_id' => App\User::orderByRaw('RAND()')->first()->id, 'group_id' => $group->id]);
                $membership->membership = \App\Membership::MEMBER;
                $membership->notification_interval = 600;

                // we prented the user has been already notified once, now. The first mail sent will be at the choosen interval from now on.
                $membership->notified_at = Carbon::now();
                $membership->save();
            }

            // add discussions to each group
            for ($k = 1; $k <= $faker->numberBetween(5, 20); ++$k) {
                $discussion = App\Discussion::create([
                    'name' => $faker->city,
                    'body' => $faker->text,
                ]);
                // attach one random author & group to each discussion
                $discussion->user_id = App\User::orderByRaw('RAND()')->first()->id;
                $discussion->group_id = App\Group::orderByRaw('RAND()')->first()->id;
                $discussion->save();

                // Add comments to each discussion

                for ($l = 1; $l <= $faker->numberBetween(5, 20); ++$l) {
                    $comment = new \App\Comment();
                    $comment->body = $faker->text;
                    $comment->user_id = App\User::orderByRaw('RAND()')->first()->id;
                    $discussion->comments()->save($comment);
                }
            }

            // add actions to each group
            for ($m = 1; $m <= $faker->numberBetween(5, 20); ++$m) {
                $start = $faker->dateTimeThisMonth('+2 months');
                $action = App\Action::create([
                    'name' => $faker->sentence(5),
                    'body' => $faker->text,
                    /*'start' => Carbon::now(),
                    'stop' => Carbon::now(),
                    */
                    'start'    => $start,
                    'stop'     => Carbon::instance($start)->addHour(),
                    'location' => $faker->city,
                ]);
                // attach one random author & group to each discussion
                $action->user_id = App\User::orderByRaw('RAND()')->first()->id;
                $action->group_id = App\Group::orderByRaw('RAND()')->first()->id;
                if ($action->isInvalid()) {
                    dd($action->getErrors());
                }
                $action->save();
            }
        }
    }
}
