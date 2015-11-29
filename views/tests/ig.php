<?php

use Framework\Social\Instagram;



$ig = new Instagram();
$posts = $ig
    ->user(2256663036)
    ->count(10)
    ->getPosts();

foreach($posts as $post){
    echo "<h3>" . $post->title ."</h3>";
    echo "<img src='".$post->image."'>";
}



//https://instagram.com/oauth/authorize/?client_id=bfd815ad2a954e26af3bbcb124c46004&redirect_uri=https://dev.akitech.org&response_type=token