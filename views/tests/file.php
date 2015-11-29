<?php

use \Framework\Storage\File;


$files = File::uploadFiles();
echo "<pre>";
print_r($files);
echo "</pre>";

if(count($files)){
    $file = $files[0];
    $cropped =  File::cropImage($file,200,200);
    echo "<img src='" . $cropped ."'/>";
}

?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="fileName">
    <input type="submit" value="Send">
</form>
