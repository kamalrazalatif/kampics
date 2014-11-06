<?php 

require_once("includes/initialize.php"); 
if (!$session->is_logged_in()) { redirect_to("login.php"); }

if(isset($_GET['id'])){
	$id = $_GET['id'];
	$user = User::find_by_id($id);
	$full_name = $user->full_name();
	$first_name = $user->first_name;
	$last_name = $user->last_name;
	$bio = $user->bio;
	$profpic = $user->profpic;

	$user_id=$_SESSION['user_id'];
	//$albums = Album::find_by_user_id($user_id);
	$sql = "SELECT * FROM albums WHERE user_id={$user_id}";
	$albums = Album::find_by_sql($sql);


} else {
	$message = "Please provide a user id";
}

?>
<?php include_layout_template('logged_header.php'); ?>
<?php echo output_message($message);  ?>
<div id="profileheader">
<div id="profileimage"><img src="images/profpic/<?php echo $profpic; ?>" /></div>
<div id="profileinfo">
<h2><?php echo $full_name; ?>'s Profile</h2>
<p>First Name: <?php echo $first_name; ?></p>
<p>Last Name: <?php echo $last_name; ?></p>
<p>Bio:<?php echo $bio; ?></p>
<p><a href="update_profile.php?id=<?php echo $id;?>">Edit my Profile</a></p>
<p><a href="delete_user.php?id=<?php echo $id;?>">Delete my User Account</a></p>
</div>
</div>
<h2>My Photo Albums:</h2>

<div>
<?php foreach($albums as $album){ ?>

<div id="profilenavbox">
  <a href="photo_album.php?id=<?php echo $album->id; ?>"><?php echo $album->album_name; ?></a>
</div>

<?php } ?>
</div>
<br class="clearfloat" />
<p><a href="add_album.php">Create a New Album</a></p>  



<?php include_layout_template('footer.php'); ?>