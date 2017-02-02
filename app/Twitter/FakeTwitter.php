<?php

namespace App\Twitter;

use Faker\Factory as Faker;
use Illuminate\Support\Str;

class FakeTwitter implements Twitter {

    public function generateGameData($numItems = 10)
    {
        $faker = Faker::create();

        $data = [];

        for ($i = 1; $i <= $numItems; $i++) {
            $data[] = [
                'handle' => '@' . str_replace(' ', '', $faker->name),
                'tweet' => Str::limit($faker->sentence, 140)
            ];
        }

        return $data;
    }

}