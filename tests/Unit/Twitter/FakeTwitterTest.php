<?php

namespace Tests\Unit\Twitter;

use App\Twitter\FakeTwitter;
use Tests\TestCase;

class FakeTwitterTest extends TestCase
{
    /**
     * @test
     */
    function gets_10_tweets()
    {
        $fakeTwitter = new FakeTwitter;

        $data = $fakeTwitter->getTweets();
        $this->assertArrayItemsHaveKeys($data, ['handle', 'tweet']);
        $this->assertCount(10, $data);
    }

    /**
     * @test
     */
    function gets_5_tweets()
    {
        $fakeTwitter = new FakeTwitter;

        $data = $fakeTwitter->getTweets(5);
        $this->assertArrayItemsHaveKeys($data, ['handle', 'tweet']);
        $this->assertCount(5, $data);
    }
}