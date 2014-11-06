<?php
require_once('includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php

$max_file_size = 2000000;  // expressed in bytes
                            // 10240 = 10kb
                            // 102400 = 100kb
                            // 1048576 = 1MB
                            // 10485760 = 10 MB
                            
                            
                            
    
    
    if(isset($_POST['submit'])){
        $photo = new Photograph();   // instantiate a new photograph object
        $photo->caption = $_POST['caption'];
		$photo->user_id = $_SESSION['user_id'];
		$photo->album_id = $_POST['album_id'];
        $photo->attach_file($_FILES['file_upload']);
        if($photo->save()){
            // success
            $session->message("Photograph uploaded successfully.");
	    redirect_to('index.php');
        } else {
            // failure
            $message = join("<br />", $photo->errors);
        }
        
    }

?>


<?php include_layout_template('logged_header.php'); ?>

    <h2>Photo Upload</h2>
    <?php echo output_message($message);  ?>
    
    <form action="<?php  echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" >
    <input  type="hidden" name="MAX_FILE_SIZE" value="<?php  echo $max_file_size; ?>" />
    <p><input type="file" name="file_upload"  /></p>
    <p>Album: <select name="album_id">
    			<?php 
				
				if(isset($_GET['albumid'])){
					$album = Album::find_by_id($_GET['albumid']);
					echo "<option value=\"{$album->id}\">{$album->album_name}</option>";
				} else {
						$user_id=$_SESSION['user_id'];
						//$albums = Album::find_by_user_id($user_id);
						$sql = "SELECT * FROM albums WHERE user_id={$user_id}";
						$albums = Album::find_by_sql($sql);
				
					foreach($albums as $album){
						echo "<option value=\"{$album->id}\">{$album->album_name}</option>";
						}
				}
				?>
              </select>
    <a href="add_album.php">Create an Album</a>
    </p>
    <p>Caption: <input  type="text" name="caption" value="" /></p>
    <input type="submit" name="submit" value="Upload"  />
        
    </form>


<?php include_layout_template('footer.php'); ?>
		
