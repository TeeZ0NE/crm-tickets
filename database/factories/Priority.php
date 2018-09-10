<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Priority::class, function (Faker $faker) {
    return [
        'priority'=>$faker->priority
    ];
});
