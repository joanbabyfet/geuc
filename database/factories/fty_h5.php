<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\mod_common;
use App\models\mod_h5;
use Faker\Generator as Faker;

$factory->define(mod_h5::class, function (Faker $faker) {
    return [
        'id'       => mod_common::random('web'),
        'name' => $faker->title,
        'content' => $faker->realText,
        'status' => 1,
        'create_user' => '0',
        'update_time' => $faker->unixTime('now'),
        'create_time' => $faker->unixTime('now'),
        'update_user' => '0',
        'delete_time' => 0,
        'delete_user' => '0',
    ];
});
