<title>My Photo Album</title>
<form enctype="multipart/form-data" action="album.php" method = "post" >
   <input type="hidden" name="MAX_FILE_SIZE" value="3000000">
   Upload File: <input type="file" id="filetoupload" name="filetoupload" > <br>
   <input type="submit"  name="submit" value="Upload" >
</form>
<?php
session_start();
// put your generated access token here
$auth_token = 'sl.BSuUVkqFHd0sCL5lmpfS2r5O26mZMAhvdQ2YSubXP-P-QdGTp7y33cubCS5n09Oq1fSdqRzDlafwS2SCtS8OdhkBS6oIoAJCzDPVMjEcJ0TsY6YCMO23jSKtydiF_qXNrwiaH3ydocEu';
// import the Dropbox library
include "dropbox.php";

// set it to true to display debugging info
//$debug = true;

// display all errors on the browser
error_reporting(E_ALL);
ini_set('display_errors','On');

// create a new Dropbox folder called images
createFolder("/images");

if(!isset($_GET["delete"])){
displayList();
}
if(isset($_GET["display"])){
   displayImage((string)$_GET["display"]);
}

if(isset($_GET["delete"])){
   deleteImage((string)$_GET["delete"]);
   displayList();
}

if(isset($_POST["submit"])){

   $name = $_FILES['filetoupload']['name'];
   $newLocation = "album.php?userfile=".$name;
   header("Location: " . $newLocation);
   $path = "/images";

// upload a local file into the Dropbox folder images
   $tempDir = "";
   if(!move_uploaded_file($_FILES['filetoupload']['tmp_name'], $tempDir . $_FILES['filetoupload']['name'])){
		die('Error uploading file');
	}
   upload($tempDir . $_FILES["filetoupload"]["name"],$path);
}

// print the files in the Dropbox folder images
function displayList(){
$result = listFolder("/images");
if(!empty($result)){
   $_SESSION["display"] = array();
   print_r("Uploaded Files: <br><br>");
   print("<div style=\"width:50%;float:left\">");
   print("<table style\"borderCollapse:collapse;textAlign:center\">");
   foreach ($result['entries'] as $x) {
      $fname = $x["name"];
      $path = $x['path_display'];
      $_SESSION["display"][(string)$x['id']] =array("name"=> (string)$fname , "path"=> (string)$path); 
      print("<tr><td>");
      echo "<a href=\"album.php?display=".$fname."\">" .$fname. "</a> &emsp;&emsp;&emsp;&emsp;<br></td>";
      echo "<td><a href=\"album.php?delete=".$fname."\"><input type=\"submit\" name=\"delete\" value=\"Delete\"/></a></td>";
      print("</tr>");   
   }
   print("</table></div>");
   }
}
   function displayImage($img){
      foreach($_SESSION["display"] as $id=> $val){
            if($val["name"] == $img)
            $imgpath = (string)$val["path"];
      }
      download($imgpath,"tmp/tmp.jpg");
      print("<div style=\"width:50%;float:left\">");
      print("<img style=\"width:200px;height:200px\" src=\"tmp/tmp.jpg\"></a>");
      print("</div>");
         
   }

   
function deleteImage($img){
   foreach($_SESSION["display"] as $id => $val){
      if($val["name"] == $img){
		$path = $val["path"];
	  }
	}

   delete($path);
}
?>



