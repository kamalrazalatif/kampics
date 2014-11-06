<?php require_once('includes/initialize.php'); ?>

<?php include_layout_template('logged_header.php'); ?>

<?php

if(isset($_POST['submit'])){
	$album = new Album();
	$album->user_id = $_SESSION['user_id'];
	$album->album_name = $_POST['album_name'];
	$album->created = strftime("%Y-%m-%d %H:%M:%S", time());
	
	if($album->create()){
            //echo "Album was created";
	    $session->message("Album was created. You can now upload a photo to the album.");
            // redirect to photo_upload.php?albumid=$album->id;
	    redirect_to("photo_upload.php?albumid=" . $album->id);
        } else {
            echo "Album was not created";
        }
	
        
}

?>

<form method="post" action="<?php  echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
<p><label for="username">Album Name</label><input type="text" name="album_name" /></p>

<p><input name="submit" type="submit" value="Create Album"/></p>
</form>


<?php include_layout_template('footer.php'); ?>
		
