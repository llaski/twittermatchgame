<?php

namespace App\Twitter;

interface Twitter {

    public function getTweets($numTweets = 10);
}