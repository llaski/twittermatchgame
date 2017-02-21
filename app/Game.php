<?php

namespace App;

use App;
use App\Twitter\Twitter;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['tweets', 'total_questions', 'num_correct_answers', 'email', 'name'];

    protected $appends = ['percentage_correct'];

    public static function generate($numTweets = 10)
    {
        $tweets = App::make(Twitter::class)->getTweets($numTweets);

        return self::create([
            'tweets' => json_encode($tweets),
            'total_questions' => count($tweets)
        ]);
    }

    public function finalizeResults(array $answers, string $email, string $name)
    {
        $this->answers = json_encode($answers);
        $this->email = $email;
        $this->name = $name;

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

        return $this->save();
    }

    public function getTweetsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getAnswersAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getPercentageCorrectAttribute()
    {
        return round($this->num_correct_answers / $this->total_questions, 2) * 100;
    }
}
