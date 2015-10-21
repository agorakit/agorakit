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

      for ($i = 1; $i <= 10; ++$i) {
          App\User::create([
        'email' => $faker->email,
        'password' => bcrypt('secret'),
        'username' => $faker->name,
        'name' => $faker->name,
        ]);
      }

      // TODO mandatory remove this :
      App\User::create([
        'email' => 'test@test.com',
        'password' => bcrypt('123456'),
        'username' => 'tester',
        'name' => 'Mister tester',
        ]);

        // create 10 groups
        DB::table('groups')->delete();
      DB::table('membership')->delete();

      for ($i = 1; $i <= 3; ++$i) {
          $group = App\Group::create([
            //'name' => $faker->city.'\'s user group',
            'name' => 'Group nr ' . $i,
            'body' => $faker->text,
            ]);
            // attach one random member to each group
            $group->users()->attach(App\User::orderByRaw('RAND()')->first());
      }

          // discussions
          DB::table('discussions')->delete();
      for ($i = 1; $i <= 1000; ++$i) {
          $discussion = App\Discussion::create([
              'name' => $faker->city,
              'body' => $faker->text,
              ]);
              // attach one random author & group to each discussion
              $discussion->user_id = App\User::orderByRaw('RAND()')->first()->id;
          $discussion->group_id = App\Group::orderByRaw('RAND()')->first()->id;
          $discussion->save();
      }

            // comments
            DB::table('comments')->delete();
      for ($i = 1; $i <= 5000; ++$i) {
          $comment = new \App\Comment();
          $comment->body = $faker->text;
          $comment->user_id = App\User::orderByRaw('RAND()')->first()->id;
          App\Discussion::orderByRaw('RAND()')->first()->comments()->save($comment);
      }
  }
}
