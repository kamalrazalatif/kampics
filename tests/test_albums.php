<?php 

require_once("../includes/initialize.php"); 
if (!$session->is_logged_in()) { redirect_to("login.php"); }


$user_id=$_SESSION['user_id'];
//$albums = Album::find_by_user_id($user_id);
$sql = "SELECT * FROM albums WHERE user_id={$user_id}";
$albums = Album::find_by_sql($sql);

foreach($albums as $album){ 

echo $album->id; 
echo "<br />";
echo $album->album_name; 
  
} 

?>