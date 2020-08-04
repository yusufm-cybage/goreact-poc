<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\MediaPost;
use Faker\Generator as Faker;

$factory->define(MediaPost::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'file_name' => $faker->image('public/mediafiles',640,480, null, false),
        'file_type' => 'jpg',
        'title' => $faker->sentence,
        'tag' => $faker->sentence,
        'description' => $faker->paragraph,
        "created_at" => now(),
        "updated_at" => now()
    ];
});
