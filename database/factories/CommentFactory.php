<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use App\User;
use App\Ticket;
use App\Comment;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'text' => $faker->text,
        'author' => User::inRandomOrder()->first()['id'],
        'id_ticket' => Ticket::inRandomOrder()->first()['id'],
    ];
});