<?php ob_start(); ?>
<?php require_once('includes/initialize.php'); ?>
<?php

if(isset($_POST['submit'])){
	$user = new User();
	$user->username = $_POST['username'];
	$user->hashed_password = sha1($_POST['password']);
	$user->first_name = $_POST['first_name'];
	$user->last_name = $_POST['last_name'];
	$user->bio = $_POST['bio'];
	
	$profpic = new ProfPic();
	$profpic->attach_file($_FILES['userPic']);
	$user->profpic = $profpic->save();
	
	if($user->create()){
            //echo "User was created";
			$session->message("You successfully joined Kampics. Welcome!");
			$session->login($user);
				log_action('Login', "{$user->username} logged in.");
				$id = $db->insert_id();
            	redirect_to("profile.php?id={$id}");
        } else {
            echo "User was not created";
        }
	
        
}

?>
<?php include_layout_template('header.php'); ?>
<form method="post" action="<?php  echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
<p><label for="username">Username</label><input type="text" name="username" /></p>
<p><label for="password">Password</label><input type="password" name="password" /></p>
<p><label for="first_name">First Name</label><input type="text" name="first_name" /></p>
<p><label for="last_name">Last Name</label><input type="text" name="last_name" /></p>
<p><label for="bio">Bio:</label><textarea name="bio" cols="40" rows="8">Please add some details about yourself here!</textarea></p>
<p><label for="userPic">Profile Picture:</label><input name="userPic" type="file" /></p>
<p><input name="submit" type="submit" value="Join Up!"/></p>
</form>


<?php include_layout_template('footer.php'); ?>
<?php ob_flush(); ?>		
