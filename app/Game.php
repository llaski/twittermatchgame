<?php

namespace App;

use App;
use App\Twitter\Twitter;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['tweets', 'total_questions', 'num_correct_answers', 'email', 'name'];

    protected $appends = ['rank'];

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

    //Bigger time is better - means it took them less time to solve
    public function getRankAttribute()
    {
        return self::where('num_correct_answers', '>', $this->num_correct_answers)
            ->orWhere(function ($query) {
                $query->where('num_correct_answers', $this->num_correct_answers)
                      ->where('time', '>', $this->time);
            })->count() + 1;
    }

    public static function topTenRankedGames()
    {
        return self::orderBy('num_correct_answers', 'desc')
            ->orderBy('time', 'desc')
            ->limit(10)
            ->get();
    }
}
