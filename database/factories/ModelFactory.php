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

$factory->define(App\Model\User::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->email,
        'password'       => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Model\Event::class, function (Faker\Generator $faker) {
    $startingTime = $faker->dateTimeBetween('now', '+14 days');

    $endingTime = null;
//    if ($faker->optional(.3)) {
//        $endingTime = $faker->dateTimeBetween($startingTime, '+2 days');
//    }

    $url = $faker->url;

    return [
        'title'       => $faker->words(3, true),
        'description' => $faker->sentences(3, true),
        'url'         => $url,
        'hash'        => sha1($url),

        'starting_time' => $startingTime,
        'ending_time'   => $endingTime,
    ];
});

$factory->define(App\Model\Location::class, function (Faker\Generator $faker) {
    return [
        'human_name'           => $faker->company,
        'human_street_address' => $faker->address,

        'lat' => $faker->latitude,
        'lon' => $faker->longitude,

        'url' => $faker->optional()->url,
    ];
});
