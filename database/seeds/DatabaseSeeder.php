<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
  /**
  * Run the database seeds.
  */
  public function run()
  {
    $faker = Faker::create();

    // create 10 users
    DB::table('users')->delete();

    App\User::create([
        'email' => 'admin@mobilisator.be',
        'password' => bcrypt('secret'),
        'name' => $faker->name,
    ]);

    for ($i = 1; $i <= 10; ++$i) {
      App\User::create([
        'email' => $faker->safeEmail,
        'password' => bcrypt('secret'),
        'name' => $faker->name,
        ]);
      }

        // Groups, discussions and comments
        DB::table('groups')->delete();
        DB::table('membership')->delete();
        DB::table('discussions')->delete();
        DB::table('comments')->delete();

        for ($i = 1; $i <= 5; ++$i) {
          $group = App\Group::create([
            //'name' => $faker->city.'\'s user group',
            'name' => 'Group nr '.$i,
            'body' => $faker->text,
            ]);
            // attach one random member to each group
            $group->users()->attach(App\User::orderByRaw('RAND()')->first());

            // add 10 discussions to each group
            for ($j = 1; $j <= 10; ++$j) {
              $discussion = App\Discussion::create([
                'name' => $faker->city,
                'body' => $faker->text,
                ]);
                // attach one random author & group to each discussion
                $discussion->user_id = App\User::orderByRaw('RAND()')->first()->id;
                $discussion->group_id = App\Group::orderByRaw('RAND()')->first()->id;
                $discussion->save();

                // Add 10 comments to each discussion

                for ($k = 1; $k <= 10; ++$k) {
                  $comment = new \App\Comment();
                  $comment->body = $faker->text;
                  $comment->user_id = App\User::orderByRaw('RAND()')->first()->id;
                  $discussion->comments()->save($comment);
                }
              }
            }
          }
        }
