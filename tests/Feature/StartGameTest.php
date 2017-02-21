<?php

namespace Tests\Feature;

use App\Twitter\FakeTwitter;
use App\Twitter\Twitter;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class StartGameTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->twitter = new FakeTwitter;
        $this->app->instance(Twitter::class, $this->twitter);
    }

    /** @test */
    public function user_begins_a_new_game()
    {
        $this->disableExceptionHandling();

        $response = $this->json('POST', '/api/games');

        $response->assertStatus(201);
        $this->seeJsonStructure([
            'id',
            'tweets.*' => [
                'handle',
                'tweet'
            ]
        ], $response);

        $this->assertCount(10, $response->decodeResponseJson()['tweets']);
    }
}
