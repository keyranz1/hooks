<?php


    namespace App;

    use Framework\DB;




    $array = DB::get("posts",["postId" => 9]);


    print "<pre>";
    print_r($array);
    print "</pre>";

?>



