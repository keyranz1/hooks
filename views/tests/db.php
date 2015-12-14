<?php


    namespace App;

    use hooks\DB;




    $array = DB::get("posts",["postId" => 9]);


    print "<pre>";
    print_r($array);
    print "</pre>";

?>



