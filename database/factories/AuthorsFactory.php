<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Author;
use Faker\Generator as Faker;

$factory->define(Author::class, function (Faker $faker) {
    return [
        //
        'name'=> $faker->name
    ];
});
