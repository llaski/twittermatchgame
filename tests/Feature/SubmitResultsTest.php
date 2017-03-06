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
            ],
            'email' => 'johndoe@example.com',
            'name' => 'John Doe',
            'time' => 4 * 60 //4 minutes
        ]);

        $response->assertStatus(200)
            ->assertExactJson([
                'id' => $game->id,
                'rank' => 1,
                'num_correct_answers' => 5,
                'total_questions' => 10,
                'name' => 'John Doe',
                'time' => 4 * 60,
            ]);

        $this->assertEquals(4 * 60, $game->fresh()->time);
        $this->assertEquals('johndoe@example.com', $game->fresh()->email);
        $this->assertEquals('John Doe', $game->fresh()->name);
        $this->assertEquals(1, $game->fresh()->rank);
    }

    /** @test */
    public function user_submits_their_results_with_best_time_and_should_have_rank_1()
    {
        $this->disableExceptionHandling();

        factory(Game::class)->states('completed')->create(['time' => 5 * 55]);

        $game = factory(Game::class)->create();

        $response = $this->json('PUT', '/api/games/' . $game->id, [
            'results' => [
                "@BarackObama" => "I read letters like these every single day. It was one of the best parts of the job – hearing from you.",
                "@PGATOUR" => "Jerry Rice is pretty good at golf, too.",
                "@Yankees" => "Ready for April yet? Here's everything you need to know about the State of the Yankees leading into the 2017 season: http://atmlb.com/2l1KKlr",
                "@GolfDigest" => "The 2017 Hot List is HERE: http://glfdig.st/5Dz7DKk",
                "@RickieFowler" => "Great day at the Bear's Club for the @jacknicklaus Children's Healthcare Foundation!! #TheJake",
                "@golf_com" => "Welcome to the new and improved http://GOLF.com , where the game has never looked better - http://bit.ly/2k2PvhA",
                "@McIlroyRory" => "Enjoyed this little back and forth with one of my favourite golf writers over the past few days. Take a look if you have time",
                "@GolfMatchApp" => "WWD's Tisha and Nikki will be hosting our Cali Classic at two of SoCal's best courses in January. Join us!",
                "@katyperry" => "GUYS WHEN WILL U BELIEVE ME FOR ONCE, I DONT LIE, I DONT EVEN EXAGGERATE LOL",
                "@TrackManGolf" => "Congratulations to @DJohnsonPGA #1 in the World!"
            ],
            'email' => 'johndoe@example.com',
            'name' => 'John Doe',
            'time' => 5 * 59
        ]);

        $response->assertStatus(200)
            ->assertExactJson([
                'id' => $game->id,
                'rank' => 1,
                'num_correct_answers' => 10,
                'total_questions' => 10,
                'name' => 'John Doe',
                'time' => 5 * 59
            ]);

        $this->assertEquals('johndoe@example.com', $game->fresh()->email);
        $this->assertEquals('John Doe', $game->fresh()->name);
        $this->assertEquals(5 * 59, $game->fresh()->time);
        $this->assertEquals(1, $game->fresh()->rank);
    }

    /** @test */
    public function user_submits_their_results_with_worse_time_but_more_correct_answers()
    {
        $this->disableExceptionHandling();

        factory(Game::class)->states('completed_with_one_wrong_answer')->create(['time' => 5 * 55]);

        $game = factory(Game::class)->create();

        //8 right ansers?? wtf???
        $response = $this->json('PUT', '/api/games/' . $game->id, [
            'results' => [
                "@BarackObama" => "I read letters like these every single day. It was one of the best parts of the job – hearing from you.",
                "@PGATOUR" => "Jerry Rice is pretty good at golf, too.",
                "@Yankees" => "Ready for April yet? Here's everything you need to know about the State of the Yankees leading into the 2017 season: http://atmlb.com/2l1KKlr",
                "@GolfDigest" => "The 2017 Hot List is HERE: http://glfdig.st/5Dz7DKk",
                "@RickieFowler" => "Great day at the Bear's Club for the @jacknicklaus Children's Healthcare Foundation!! #TheJake",
                "@golf_com" => "Welcome to the new and improved http://GOLF.com , where the game has never looked better - http://bit.ly/2k2PvhA",
                "@McIlroyRory" => "Enjoyed this little back and forth with one of my favourite golf writers over the past few days. Take a look if you have time",
                "@GolfMatchApp" => "WWD's Tisha and Nikki will be hosting our Cali Classic at two of SoCal's best courses in January. Join us!",
                "@katyperry" => "GUYS WHEN WILL U BELIEVE ME FOR ONCE, I DONT LIE, I DONT EVEN EXAGGERATE LOL",
                "@TrackManGolf" => "Congratulations to @DJohnsonPGA #1 in the World!"
            ],
            'email' => 'johndoe@example.com',
            'name' => 'John Doe',
            'time' => 5 * 50
        ]);

        $response->assertStatus(200)
            ->assertExactJson([
                'id' => $game->id,
                'rank' => 1,
                'num_correct_answers' => 10,
                'total_questions' => 10,
                'name' => 'John Doe',
                'time' => 5 * 50
            ]);

        $this->assertEquals('johndoe@example.com', $game->fresh()->email);
        $this->assertEquals('John Doe', $game->fresh()->name);
        $this->assertEquals(5 * 50, $game->fresh()->time);
        $this->assertEquals(1, $game->fresh()->rank);
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
            'results' => 'not valid results',
            'email' => 'johndoe@example.com',
            'name' => 'John Doe',
            'time' => 4 * 60
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
            ],
            'email' => 'johndoe@example.com',
            'name' => 'John Doe',
            'time' => 4 * 60
        ]);

        $this->assertValidationError('results', $response);
    }

    /**
     * @test
     */
    function an_email_is_required_to_submit_results()
    {
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
            ],
            'name' => 'John Doe',
            'time' => 4 * 60
        ]);

        $this->assertValidationError('email', $response);
    }

    /**
     * @test
     */
    function a_valid_email_is_required_to_submit_results()
    {
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
            ],
            'email' => 'invalid-email',
            'name' => 'John Doe',
            'time' => 4 * 60
        ]);

        $this->assertValidationError('email', $response);
    }

    /**
     * @test
     */
    function an_name_is_required_to_submit_results()
    {
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
            ],
            'email' => 'johndoe@example.com',
            'time' => 4 * 60
        ]);

        $this->assertValidationError('name', $response);
    }

    /**
     * @test
     */
    function a_valid_name_is_required_to_submit_results()
    {
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
            ],
            'email' => 'johndoe@example.com',
            'name' => 'L',
            'time' => 4 * 60
        ]);

        $this->assertValidationError('name', $response);
    }

    /**
     * @test
     */
    function a_time_is_required_to_submit_results()
    {
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
            ],
            'email' => 'johndoe@example.com',
            'name' => 'John Doe'
        ]);

        $this->assertValidationError('time', $response);
    }

    /**
     * @test
     */
    function a_time_must_be_a_number_to_submit_results()
    {
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
            ],
            'email' => 'johndoe@example.com',
            'name' => 'John Doe',
            'time' => 'NAN'
        ]);

        $this->assertValidationError('time', $response);
    }

    /**
     * @test
     */
    function a_time_must_be_smaller_than_5_minutes_to_submit_results()
    {
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
            ],
            'email' => 'johndoe@example.com',
            'name' => 'John Doe',
            'time' => 6 * 60
        ]);

        $this->assertValidationError('time', $response);
    }

    /**
     * @test
     */
    function a_time_must_be_greater_than_0_to_submit_results()
    {
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
            ],
            'email' => 'johndoe@example.com',
            'name' => 'John Doe',
            'time' => -1
        ]);

        $this->assertValidationError('time', $response);
    }
}