
<?php

ini_set('memory_limit','6G');


function list_dirfiles($dir) {
    $ffs = scandir($dir);
//Remove Parent
    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);

//Prevent empty elements
    if ( count($ffs) < 1 ) {
        return;
    }
    foreach($ffs as $ff){
        if( is_dir($dir."/".$ff) ) {
            //is folder
            list_dirfiles($dir."/".$ff);
        }
        else {
            //is file
            global $mytext;
            fwrite($mytext, sha1_file($dir."/".$ff)."\n");
            echo $dir."/".$ff."\n";
        }
    }
}
$mytext = fopen("backup.txt", "w");
list_dirfiles("dir");
fclose($mytext);
