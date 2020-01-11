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
    ];
});

$factory->define(App\Domain::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->url,
        'status' => 200,
        'content_length' => $faker->randomDigit,
        'body' => $faker->randomHtml(2, 3),
        'header1' => 'Header',
        'description' => 'description',
        'keywords' => 'keyword',
        'state' => 'initiated'
    ];
});
