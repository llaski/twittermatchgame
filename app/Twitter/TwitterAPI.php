<?php

namespace App\Twitter;

use Twitter as TwitterAPIPackage;
use Wa72\HtmlPageDom\HtmlPage;

class TwitterAPI implements Twitter {

    public function get10RandomHandles()
    {
        $page = new HtmlPage(file_get_contents('http://friendorfollow.com/twitter/most-followers'));
        $handlerTags = $page->filter('a.tUser');

        return collect(range(0, 99))->map(function($index) use ($handlerTags) {
            return $handlerTags->getNode($index)->nodeValue;
        })->shuffle()->slice(0, 10)->toArray();
    }

    public function getTweets($numTweets = 10)
    {
        //Get 10 random handles
        $handles = $this->get10RandomHandles();
        $data = [];

        //Get 1 random tweet per handle
        foreach ($handles as $handle) {
            $tweets = TwitterAPIPackage::getUserTimeline([
                'screen_name' => $handle,
                'count' => 10
            ]);

            $data[] = [
                'handle' => $handle,
                'tweet' => collect($tweets)->pluck('text')->shuffle()->first()
            ];
        }

        return $data;
    }

}