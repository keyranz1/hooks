<?php

use \hooks\Social\Feed;


$feed = Feed::getAllFeeds();




echo "<pre>";
    print_r($feed);
echo "</pre>";

