<?php

namespace Tests\Feature;

use App\Twitter\FakeTwitter;
use App\Twitter\Twitter;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RetrieveStartingGameDataTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->twitter = new FakeTwitter;
        $this->app->instance(Twitter::class, $this->twitter);
    }

    /**
     * @test
     */
    public function userReceivesStartingGameData()
    {
        $this->disableExceptionHandling();

        $response = $this->json('GET', '/api/games/start');

        dd($response->decodeResponseJson());
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'handle' => '@BarackObama',
                        'tweet' => 'I read letters like these every single day. It was one of the best parts of the job – hearing from you.'
                    ],
                    [
                        'handle' => '@PGATOUR',
                        'tweet' => 'Jerry Rice is pretty good at golf, too.'
                    ]
                ],
            ]);

        $this->assertArrayHasKey('data.start_time', $response->decodeResponseJson());
    }
}
