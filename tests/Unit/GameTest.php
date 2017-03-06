<?php

namespace Tests\Unit;

use App\Game;
use App\Twitter\FakeTwitter;
use App\Twitter\Twitter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GameTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->twitter = new FakeTwitter;
        $this->app->instance(Twitter::class, $this->twitter);
    }

    /**
     * @test
     */
    function generating_a_new_game_with_10_tweets()
    {
        //Generate a new game
        $game = Game::generate();

        $this->assertIsJSON($game->getOriginal('tweets'));
        $this->assertArrayItemsHaveKeys($game->tweets, ['handle', 'tweet']);
        $this->assertCount(10, $game->tweets);
        $this->assertEquals(10, $game->total_questions);
    }

    /**
     * @test
     */
    function generating_a_new_game_with_1_tweet()
    {
        //Generate a new game
        $game = Game::generate(1);

        $this->assertIsJSON($game->getOriginal('tweets'));
        $this->assertArrayItemsHaveKeys($game->tweets, ['handle', 'tweet']);
        $this->assertCount(1, $game->tweets);
        $this->assertEquals(1, $game->total_questions);
    }

    /**
     * @test
     */
    function finalizing_results_with_all_answers_correct()
    {
        $game = Game::generate(1);

        $game->finalizeResults([
            '@BarackObama' => 'I read letters like these every single day. It was one of the best parts of the job – hearing from you.'
        ], 'johndoe@example.com', 'John Doe', 5 * 60);

        $this->assertIsJSON($game->getOriginal('answers'));
        $this->assertCount(1, $game->answers);
        $this->assertEquals(1, $game->num_correct_answers);
        $this->assertEquals(5 * 60, $game->time);
    }

    /**
     * @test
     */
    function finalizing_results_with_50_percent_answers_correct()
    {
        $game = Game::generate(2);

        $game->finalizeResults([
            '@BarackObama' => 'I read letters like these every single day. It was one of the best parts of the job – hearing from you.',
            '@incorrect' => 'this is not a correct answer'
        ], 'johndoe@example.com', 'John Doe', 5 * 60);

        $this->assertIsJSON($game->getOriginal('answers'));
        $this->assertCount(2, $game->answers);
        $this->assertEquals(1, $game->num_correct_answers);
        $this->assertEquals(5 * 60, $game->time);
    }

    /**
     * @test
     */
    function finalizing_results_where_game_rank_should_be_1()
    {
        $game = Game::generate(2);

        $game->finalizeResults([
            '@BarackObama' => 'I read letters like these every single day. It was one of the best parts of the job – hearing from you.',
            '@incorrect' => 'this is not a correct answer'
        ], 'johndoe@example.com', 'John Doe', 5 * 60);

        $this->assertEquals(1, $game->rank);
    }

    /**
     * @test
     */
    function finalizing_results_where_game_rank_should_be_2_with_same_num_answers()
    {
        $gameWithRankTwo = Game::generate(2);

        $gameWithRankTwo->finalizeResults([
            '@BarackObama' => 'I read letters like these every single day. It was one of the best parts of the job – hearing from you.',
            '@PGATOUR' => 'Jerry Rice is pretty good at golf, too.'
        ], 'johndoe@example.com', 'John Doe', 5 * 50);

        $gameWithRankOne = Game::generate(2);

        $gameWithRankOne->finalizeResults([
            '@BarackObama' => 'I read letters like these every single day. It was one of the best parts of the job – hearing from you.',
            '@PGATOUR' => 'Jerry Rice is pretty good at golf, too.'
        ], 'johndoe@example.com', 'John Doe', 5 * 55);

        $this->assertEquals(2, $gameWithRankTwo->rank);
        $this->assertEquals(1, $gameWithRankOne->rank);
    }

    /**
     * @test
     */
    function finalizing_results_where_game_rank_should_be_2_with_same_time()
    {
        $gameWithRankTwo = Game::generate(2);

        $gameWithRankTwo->finalizeResults([
            '@BarackObama' => 'I read letters like these every single day. It was one of the best parts of the job – hearing from you.',
            '@PGATOUR' => ''
        ], 'johndoe@example.com', 'John Doe', 5 * 60);

        $gameWithRankOne = Game::generate(2);

        $gameWithRankOne->finalizeResults([
            '@BarackObama' => 'I read letters like these every single day. It was one of the best parts of the job – hearing from you.',
            '@PGATOUR' => 'Jerry Rice is pretty good at golf, too.'
        ], 'johndoe@example.com', 'John Doe', 5 * 60);

        $this->assertEquals(2, $gameWithRankTwo->rank);
        $this->assertEquals(1, $gameWithRankOne->rank);
    }
}