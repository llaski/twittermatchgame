<?php

namespace Tests\Unit\Twitter;

use App\Twitter\TwitterAPI;
use Tests\TestCase;

/**
 * @group integration
 */
class TwitterAPITest extends TestCase
{
    /**
     * @test
     */
    function get_10_random_handles()
    {
        $twitterApi = new TwitterAPI;

        $handlesOne = $twitterApi->get10RandomHandles();

        $this->assertCount(10, $handlesOne);
        foreach ($handlesOne as $handle) {
            $this->assertContains('@', $handle);
        }

        $handlesTwo = $twitterApi->get10RandomHandles();

        $this->assertCount(10, $handlesTwo);
        foreach ($handlesTwo as $handle) {
            $this->assertContains('@', $handle);
        }

        $this->assertNotEquals($handlesOne, $handlesTwo);
    }

    /**
     * @test
     */
    function gets_10_tweets()
    {
        $twitterApi = new TwitterAPI;

        $data = $twitterApi->getTweets();
        $this->assertArrayItemsHaveKeys($data, ['handle', 'tweet']);
        $this->assertCount(10, $data);
    }
}