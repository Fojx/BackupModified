<?php
if(!file_exists("backup_file.txt")){
    $write_files = fopen("backup_file.txt", "w");
    create_file(__DIR__);
    fclose($write_files);
}else
{
    if (time()-filemtime("backup_file.txt") > 48 * 3600){
            echo "Update backup_file.txt";
            create_file(__DIR__);
            return;
        }
    }
    $zip_file_name = date('d-m-y').".zip";
    $search_in_file = file_get_contents("backup_file.txt");
    $search_in_file = explode("\n", $search_in_file);
    $zip_func = new ZipArchive();
    search_file(__DIR__);
}



function search_file($dir){
    global $zip_file_name;
    global $search_in_file;
    global $zip_func;
    $files = scandir($dir);
    unset($files[array_search('.', $files, true)]);
    unset($files[array_search('..', $files, true)]);
    if($files < 1){
        echo "Error can't find files";
        return;
    }
    foreach($files as $file){
        if(is_dir($dir."/".$file))
        {
            search_file($dir."/".$file);
        }
        else
        {
        $file_name = sha1_file($dir."/".$file);
        if(in_array($file_name, $search_in_file)){
            // no modify here !
        }
        else
        {
            if ($zip_func->open($zip_file_name, ZipArchive::CREATE) === TRUE) {
                echo "Done \n";
                $zip_func->addFile($dir."/".$file, $file);
                $zip_func-> close();
            }
    }
}
}
}




// Function create txt file with sha1_file !
function create_file($dir){
    $files = scandir($dir);
    unset($files[array_search('.', $files, true)]);
    unset($files[array_search('..', $files, true)]);
    if($files < 1){
        echo "Error can't file files";
        return;
    }
    foreach($files as $file){
        if(is_dir($dir."/".$file)){
            create_file($dir."/".$file);
        }
        else
        {
            $enc_file = sha1_file($dir."/".$file);
            global $write_files;
            fwrite($write_files, sha1_file($dir."/".$file)."\n");
        }
    }
}
?>
