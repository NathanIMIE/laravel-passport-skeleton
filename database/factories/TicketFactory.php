<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use App\User;
use App\Ticket;

$factory->define(Ticket::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'description' => $faker->text,
        'priority' => $faker->randomElement(['basse','normale','haute']),
        'state' => $faker->randomElement(['PENDING','WAITING','IN_PROGRESS','DONE']),
        'first_assignation' => now(),
        'last_assignation' => now(),
        'id_proprietaire' => User::inRandomOrder()->first()['id'],
        'id_assignation' => User::inRandomOrder()->first()['id'],
    ];
});