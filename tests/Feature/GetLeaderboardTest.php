<?php

namespace Tests\Feature;

use App\Game;
use App\Twitter\FakeTwitter;
use App\Twitter\Twitter;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class GetGamesTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->twitter = new FakeTwitter;
        $this->app->instance(Twitter::class, $this->twitter);
    }

    /** @test */
    public function get_list_of_games()
    {
        $this->disableExceptionHandling();

        factory(Game::class)->states('completed')->times(10)->create();

        $response = $this->json('GET', '/api/games');

        $response->assertStatus(200);
        $this->seeJsonStructure([
            'games' => [
                'id',
                'rank',
                'name',
                'total_questions',
                'num_correct_answers',
                'time'
            ],
        ], $response);

        $this->assertCount(10, $response->decodeResponseJson()['games']);
    }
}