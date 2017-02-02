<?php

namespace Tests\Unit\Twitter;

use App\Twitter\FakeTwitter;
use Tests\TestCase;

class FakeTwitterTest extends TestCase
{
    /**
     * @test
     */
    function generates_starting_data_with_10_items()
    {
        $fakeTwitter = new FakeTwitter;

        $data = $fakeTwitter->generateGameData(10);

        $this->assertCount(10, $data);

        foreach ($data as $item) {
            $this->assertArrayHasKey('handle', $item);
            $this->assertArrayHasKey('tweet', $item);
        }

    }
}