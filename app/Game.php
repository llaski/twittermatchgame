<?php

namespace App;

use App;
use App\Twitter\Twitter;
use Illuminate\Database\Eloquent\Model;
use DB;

class Game extends Model
{
    protected $fillable = ['tweets', 'total_questions', 'num_correct_answers', 'email', 'name'];

    public static function generate($numTweets = 10)
    {
        $tweets = App::make(Twitter::class)->getTweets($numTweets);

        return self::create([
            'tweets' => json_encode($tweets),
            'total_questions' => count($tweets)
        ]);
    }

    public function finalizeResults(array $answers, string $email, string $name, int $time)
    {
        $this->answers = json_encode($answers);
        $this->email = $email;
        $this->name = $name;
        $this->time = $time;

        //Determine what answers are right
        $this->num_correct_answers = collect($answers)
            ->keys()
            ->reduce(function($numCorrectAnswers, $handle) use ($answers) {
                $matchingTweet = collect($this->tweets)->first(function($tweet) use ($handle) {
                    return $tweet['handle'] === $handle;
                });

                if ($matchingTweet && $matchingTweet['tweet'] === $answers[$handle]) {
                    $numCorrectAnswers++;
                }

                return $numCorrectAnswers;
            }, 0);

        $this->save();

        //Update rank
        //@TODO Temp doing this in PHP, ideally done in MySQL but SQLite doesn't support temporary variables... (i.e... SET @rank := 0;)
        self::orderBy('num_correct_answers', 'desc')
            ->orderBy('time', 'desc')
            ->orderBy('created_at', 'asc')
            ->each(function($game, $index) {
                $game->rank = $index + 1;
                $game->save();
            });

        return $this->fresh();
    }

    public function getTweetsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getAnswersAttribute($value)
    {
        return json_decode($value, true);
    }

    public static function topTenRankedGames()
    {
        return self::orderBy('num_correct_answers', 'desc')
            ->orderBy('time', 'desc')
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();
    }
}
