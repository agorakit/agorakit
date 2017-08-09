<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'body' => $faker->paragraphs(3),
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'verified' => true,
    ];
});


$factory->define(App\Group::class, function (Faker\Generator $faker) {
    return [
        'id' => 996545,
        'name' => $faker->name,
        'description' => $faker->text,
    ];
});
