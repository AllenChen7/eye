<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Post::class, function (Faker $faker) {
    return [
        'admin_user_id'  => 1,
        'title'     => $faker->text(20),
        'desc'      => $faker->text
    ];
});
