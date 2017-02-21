<?php

namespace Tests\Feature;

use App\Game;
use App\Twitter\FakeTwitter;
use App\Twitter\Twitter;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class SubmitResultsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->twitter = new FakeTwitter;
        $this->app->instance(Twitter::class, $this->twitter);
    }

    /** @test */
    public function userSubmitsTheirGameResults()
    {
        $this->disableExceptionHandling();

        $game = factory(Game::class)->create();

        $response = $this->json('PUT', '/api/games/' . $game->id, [
            'results' => [
                '@BarackObama' => 'I read letters like these every single day. It was one of the best parts of the job – hearing from you.',
                '@PGATOUR' => 'Jerry Rice is pretty good at golf, too.',
                '@Yankees' => 'Ready for April yet? Here\'s everything you need to know about the State of the Yankees leading into the 2017 season: http://atmlb.com/2l1KKlr',
                '@GolfDigest' => 'The 2017 Hot List is HERE: http://glfdig.st/5Dz7DKk',
                '@RickieFowler' => 'Great day at the Bear\'s Club for the @jacknicklaus Children\'s Healthcare Foundation!! #TheJake',
                '@golf_com' => '',
                '@McIlroyRory' => '',
                '@GolfMatchApp' => '',
                '@katyperry' => '',
                '@TrackManGolf' => ''
            ]
        ]);

        $response->assertStatus(200)
            ->assertExactJson([
                'total_questions' => 10,
                'num_correct_answers' => 5,
                'percentage_correct' => 50
            ]);
    }

    /** @test */
    public function user_submits_their_results_with_1_right_answer()
    {
        $this->disableExceptionHandling();

        $game = factory(Game::class)->create();

        $response = $this->json('PUT', '/api/games/' . $game->id, [
            'results' => [
                '@BarackObama' => 'I read letters like these every single day. It was one of the best parts of the job – hearing from you.',
                '@not_right' => 'Jerry Rice is pretty good at golf, too.',
                '@Yankees' => 'wrong match',
                '@GolfDigest' => 'wrong match',
                '@RickieFowler' => 'wrong match',
                '@golf_com' => '',
                '@McIlroyRory' => '',
                '@GolfMatchApp' => '',
                '@katyperry' => '',
                '@TrackManGolf' => ''
            ]
        ]);

        $response->assertStatus(200)
            ->assertExactJson([
                'total_questions' => 10,
                'num_correct_answers' => 1,
                'percentage_correct' => 10
            ]);
    }

    /**
     * @test
     */
    function results_are_required_to_submit_results()
    {
        $game = factory(Game::class)->create();

        $response = $this->json('PUT', '/api/games/' . $game->id, []);

        $this->assertValidationError('results', $response);
    }

    /**
     * @test
     */
    function results_must_be_an_array_to_submit_results()
    {
        $game = factory(Game::class)->create();

        $response = $this->json('PUT', '/api/games/' . $game->id, [
            'results' => 'not valid results'
        ]);

        $this->assertValidationError('results', $response);
    }

    /**
     * @test
     */
    function results_must_match_the_number_of_total_questions_for_the_game_to_submit_results()
    {
        $game = factory(Game::class)->create(); //should have 10 items

        $response = $this->json('PUT', '/api/games/' . $game->id, [
            'results' => [
                '@BarackObama' => 'I read letters like these every single day. It was one of the best parts of the job – hearing from you.'
            ]]);

        $this->assertValidationError('results', $response);
    }
}