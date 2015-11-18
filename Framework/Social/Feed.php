<?php

namespace Framework\Social;
use App\Blog\BlogApp;


class Feed
{

    public static function getAllFeeds(){
        $fb = new FacebookUtils();
        $fbFeed = $fb->getFeed();

        $ig = new Instagram();
        $igFeed = $ig
            ->user(2256663036)
            ->count(10)
            ->getPosts();

        $blog = new BlogApp();
        $blogFeed = $blog->feed();


        $feed = array_merge($fbFeed,$igFeed, $blogFeed);
        shuffle($feed);
        return $feed;
    }


}