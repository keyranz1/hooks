<?php
if(!isset($_SESSION)){
    session_start();
}
define("LENGTH",80);


function dottedLines(){
    echo "&nbsp;&nbsp;&nbsp;";
    echo "+";
    for ($j = 0; $j < LENGTH - 2; $j++){
        echo "-";
    }
    echo "+<br />";
}

function emptyLines(){
    echo "&nbsp;&nbsp;&nbsp;";
    echo "+";
    for ($j = 0; $j < LENGTH - 2; $j++){
        echo "&nbsp;";
    }
    echo "+<br />";
}
function printLines($lines){
    foreach ($lines as $line){
        $line = trim($line);
        echo "&nbsp;&nbsp;&nbsp;";
        echo "+&nbsp;&nbsp;&nbsp;";

        for ($j = 0; $j < strlen($line); $j++){
            echo $line[$j];
        }

        for($j; $j < LENGTH - 8 ; $j++){
            echo "&nbsp;";
        }


        echo "&nbsp;&nbsp; +<br />";
    }
}

function getLines($string){

    $finalLines = [];

    $lines = explode("\n",$string);


    foreach ($lines as $line)
    {
        $wordsInLine = explode(" ", $line);

        $wrappedLine = "";

        for ($i = 0; $i < count($wordsInLine); $i++)
        {
            $thisWord = $wordsInLine[$i];
            if(strlen($wrappedLine) + strlen($thisWord) < LENGTH-7){
                $wrappedLine .= " " .$thisWord;
            } else {
                $i--;
                $finalLines[] = $wrappedLine;
                $wrappedLine = "";
            }
        }
        $finalLines[] = $wrappedLine; //Last piece of line

    }

    return $finalLines;
}




$doc = isset($_SESSION["doc"]) ? $_SESSION["doc"] : "";
if(isset($_POST["doc"])){
    $doc = trim($_POST["doc"]);
    $_SESSION["doc"] = $doc;
    $docLines = getLines($doc);


    echo "/*<br />";

    dottedLines();
    emptyLines();


    printLines($docLines);


    emptyLines();
    dottedLines();


    echo "<br />*/";




}

?>
<style>
    * {
        font-family: monospace;
    }
</style>
<form method="post">

    <textarea rows="20" name="doc" style="width:100%" ><?= $doc ?></textarea>
    <input type="submit" value="Create Doc">

</form>
