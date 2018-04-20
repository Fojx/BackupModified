<?php

ini_set('memory_limit','6G');

function list_dirfiles($dir) {
// name of zip file
    $zipname = "lastdirectory.zip";
// new class ZipArchive
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
            // is folder
            list_dirfiles($dir."/".$ff);
        }
        else {
            //is file
            global $string;
            global $zip;
            $fullfile = sha1_file($dir."/".$ff);
            if(in_array($fullfile, $string)){
                // no modify file here
            }else{
                if ($zip->open($zipname, ZipArchive::CREATE) === TRUE) {
                    if($dir == "dir/path/here"){
                        $zip->addFile($dir."/".$ff, $ff);
                        $zip -> close();
                    }else{
                        $dir2 = str_replace("dir/path/here/", "", $dir);
                        $zip->addFile($dir."/".$ff, $dir2."/".$ff);
                        $zip-> close();
                    }
                }
            }
        }
    }
}
$string = file_get_contents("backup.txt");
$string = explode("\n", $string);
$zip = new ZipArchive();
list_dirfiles("path");
