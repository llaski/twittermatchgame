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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Game::class, function (Faker\Generator $faker) {
    return [
        'tweets' => json_encode((new \App\Twitter\FakeTwitter)->tweets),
        'total_questions' => count((new \App\Twitter\FakeTwitter)->tweets)
    ];
});

$factory->state(App\Game::class, 'completed', function ($faker) {
    return [
        'tweets' => json_encode((new \App\Twitter\FakeTwitter)->tweets),
        'total_questions' => count((new \App\Twitter\FakeTwitter)->tweets),
        'answers' => json_encode(collect((new \App\Twitter\FakeTwitter)->getTweets(10))->reduce(function($carry, $item) {
            $carry[$item['handle']] = $item['tweet'];
            return $carry;
        }, [])),
        'num_correct_answers' => 10,
        'email' => 'johndoe@example.com',
        'name' => 'John Doe',
        'time' => 5 * 60
    ];
});

$factory->state(App\Game::class, 'completed_with_one_wrong_answer', function ($faker) {
    $answers = collect((new \App\Twitter\FakeTwitter)->getTweets(10))->reduce(function($carry, $item) {
        $carry[$item['handle']] = $item['tweet'];
        return $carry;
    }, []);

    $answers['@TrackManGolf'] = '';

    return [
        'tweets' => json_encode((new \App\Twitter\FakeTwitter)->getTweets(10)),
        'total_questions' => count((new \App\Twitter\FakeTwitter)->getTweets(10)),
        'answers' => json_encode($answers),
        'num_correct_answers' => 9,
        'email' => 'johndoe@example.com',
        'name' => 'John Doe',
        'time' => 5 * 60
    ];
});

$factory->state(App\Game::class, 'one_question', function ($faker) {
    return [
        'tweets' => json_encode((new \App\Twitter\FakeTwitter)->getTweets(1)),
        'total_questions' => count((new \App\Twitter\FakeTwitter)->getTweets(1)),
    ];
});