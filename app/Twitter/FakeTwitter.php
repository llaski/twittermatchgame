<?php

namespace App\Twitter;

class FakeTwitter implements Twitter {

    public $tweets = [
        [
            'handle' => '@BarackObama',
            'tweet' => 'I read letters like these every single day. It was one of the best parts of the job – hearing from you.'
        ],
        [
            'handle' => '@PGATOUR',
            'tweet' => 'Jerry Rice is pretty good at golf, too.'
        ],
        [
            'handle' => '@Yankees',
            'tweet' => 'Ready for April yet? Here\'s everything you need to know about the State of the Yankees leading into the 2017 season: http://atmlb.com/2l1KKlr'
        ],
        [
            'handle' => '@GolfDigest',
            'tweet' => 'The 2017 Hot List is HERE: http://glfdig.st/5Dz7DKk'
        ],
        [
            'handle' => '@RickieFowler',
            'tweet' => 'Great day at the Bear\'s Club for the @jacknicklaus Children\'s Healthcare Foundation!! #TheJake'
        ],
        [
            'handle' => '@golf_com',
            'tweet' => 'Welcome to the new and improved http://GOLF.com , where the game has never looked better - http://bit.ly/2k2PvhA'
        ],
        [
            'handle' => '@McIlroyRory',
            'tweet' => 'Enjoyed this little back and forth with one of my favourite golf writers over the past few days. Take a look if you have time'
        ],
        [
            'handle' => '@GolfMatchApp',
            'tweet' => 'WWD\'s Tisha and Nikki will be hosting our Cali Classic at two of SoCal\'s best courses in January. Join us!'
        ],
        [
            'handle' => '@katyperry',
            'tweet' => 'GUYS WHEN WILL U BELIEVE ME FOR ONCE, I DONT LIE, I DONT EVEN EXAGGERATE LOL'
        ],
        [
            'handle' => '@TrackManGolf',
            'tweet' => 'Congratulations to @DJohnsonPGA #1 in the World!'
        ],
    ];

    public function getTweets($numTweets = 10)
    {
        return array_slice($this->tweets, 0, $numTweets);
    }

}