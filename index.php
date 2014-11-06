<?php require_once("includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php

        // 1. the current page number ($current_page) - if page not in url query string sets to default of 1
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

	// 2. records per page ($per_page)
	$per_page = 3;

	// 3. total record count ($total_count)
	$total_count = Photograph::count_all();
	

	// Find all photos
	// use pagination instead
	//$photos = Photograph::find_all();
	
	$pagination = new Pagination($page, $per_page, $total_count);
	
	// Instead of finding all records, just find the records 
	// for this page
	$sql = "SELECT * FROM photographs ";
	$sql .= "LIMIT {$per_page} ";
	$sql .= "OFFSET {$pagination->offset()}";
	$photos = Photograph::find_by_sql($sql);
	
	// Need to add ?page=$page to all links we want to 
	// maintain the current page (or store $page in $session)


//header
include_layout_template('logged_header.php'); 
//display_head("homepage");
?>

<div id="welcome">
<p>Welcome to Kampics.</p>
<p>This is a basic photo uploading and sharing web application designed and built by <a href="http://www.kamallatif.com" target="_blank">Kamal Latif</a>.</p>
<p>Please feel free to upload any photos (up to 2mb in size) via the <a href="photo_upload.php">photo upload</a> menu link or by creating an album in your <a href="profile.php?id=<?php echo $_SESSION['user_id']; ?>">My Profile Page</a>.</p></div>

<?php foreach($photos as $photo){ ?>

<div style="float: left; margin-left: 20px;">
  <a href="photo.php?id=<?php echo $photo->id; ?>"><img src="<?php  echo $photo->image_path(); ?>"  width="200" /></a>
  <p><?php  echo $photo->caption; ?></p>  
</div>
  
<?php } ?>

<div id="pagination" style="clear: both;">
<?php
	if($pagination->total_pages() > 1) {
		
		if($pagination->has_previous_page()) { 
    	echo "<a href=\"index.php?page=";
      echo $pagination->previous_page();
      echo "\">&laquo; Previous</a> "; 
    }

		for($i=1; $i <= $pagination->total_pages(); $i++) {
			if($i == $page) {
				echo " <span class=\"selected\">{$i}</span> ";
			} else {
				echo " <a href=\"index.php?page={$i}\">{$i}</a> "; 
			}
		}

		if($pagination->has_next_page()) { 
			echo " <a href=\"index.php?page=";
			echo $pagination->next_page();
			echo "\">Next &raquo;</a> "; 
    }
		
	}

?>
</div>


<?php include_layout_template('footer.php'); ?>
