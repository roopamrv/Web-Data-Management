<?php

function download ( $path, $target_path ) {
   global $auth_token, $debug;
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $auth_token,
      		    'Content-Type:', 'Dropbox-API-Arg: {"path":"'.$path.'"}'));
   curl_setopt($ch, CURLOPT_URL, 'https://content.dropboxapi.com/2/files/download');
   try {
     $result = curl_exec($ch);
   } catch (Exception $e) {
     echo 'Error: ', $e->getMessage(), "\n";
   }
   file_put_contents($target_path,$result);
   curl_close($ch);
}

function upload ( $file, $path ) {
   global $auth_token, $debug;
   $args = array("path" => $file, "mode" => "add");
   $fp = fopen($file, 'rb');
   $size = filesize($file);
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_PUT, true);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $auth_token,
   		     'Content-Type: application/octet-stream',
           'Dropbox-API-Arg: {"path":"'.$path.'/'.basename($file).'", "mode":"add"}'));
   curl_setopt($ch, CURLOPT_URL, 'https://content.dropboxapi.com/2/files/upload');
   curl_setopt($ch, CURLOPT_INFILE, $fp);
   curl_setopt($ch, CURLOPT_INFILESIZE, $size);
   try {
     $result = curl_exec($ch);
   } catch (Exception $e) {
     echo 'Error: ', $e->getMessage(), "\n";
   }
   if ($debug)
      print_r($result);
   curl_close($ch);
   fclose($fp);
}

function listFolder ( $path ) {
   global $auth_token, $debug;
   $args = array("path" => $path);
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $auth_token,
   		    'Content-Type: application/json'));
   curl_setopt($ch, CURLOPT_URL, 'https://api.dropboxapi.com/2/files/list_folder');
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));
   try {
     $result = curl_exec($ch);
   } catch (Exception $e) {
     echo 'Error: ', $e->getMessage(), "\n";
   }
   if ($debug)
      print_r($result);
   $array = json_decode(trim($result), TRUE);
   if ($debug)
      print_r($array);
   curl_close($ch);
   return $array;
}

function delete ( $path ) {
   global $auth_token, $debug;
   $args = array("path" => $path);
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $auth_token,
   		    'Content-Type: application/json'));
   curl_setopt($ch, CURLOPT_URL, 'https://api.dropboxapi.com/2/files/delete_v2');
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));
   try {
     curl_exec($ch);
   } catch (Exception $e) {
     echo 'Error: ', $e->getMessage(), "\n";
   }
   curl_close($ch);
}

function createFolder ( $path ) {
   global $auth_token, $debug;
   $args = array("path" => $path,"autorename" => false);
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $auth_token,
   		    'Content-Type: application/json'));
   curl_setopt($ch, CURLOPT_URL, 'https://api.dropboxapi.com/2/files/create_folder_v2');
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));
   try {
     curl_exec($ch);
   } catch (Exception $e) {
     echo 'Error: ', $e->getMessage(), "\n";
   }
   curl_close($ch);
}

?>
