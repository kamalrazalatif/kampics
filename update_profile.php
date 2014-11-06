<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php include_layout_template('admin_header.php'); ?>

<?php

if(isset($_GET['id'])){
    
    $id = $_GET['id'];
    $user = User::find_by_id($id);
    
}

if(isset($_POST['submit'])){
	
        $id = $_POST['id'];
	$user = User::find_by_id($id);
	$user->password = $_POST['password'];
	$user->update();
	
       
}

?>

<form method="post" action="<?php  echo $_SERVER['PHP_SELF']; ?>">
<p><label for="username">Username</label><input type="text" name="username" value="<?php echo $user->username; ?>"/></p>
<p><label for="password">Password</label><input type="password" name="password" value="<?php echo $user->password; ?>" /></p>
<p><label for="first_name">First Name</label><input type="text" name="first_name" value="<?php echo $user->first_name; ?>" /></p>
<p><label for="last_name">Last Name</label><input type="text" name="last_name" value="<?php echo $user->last_name; ?>" /></p>
<p><input type="hidden" name="id" value="<?php echo $user->id; ?>"  /></p>
<p><input name="submit" type="submit" value="Update Details!"/></p>
</form>


<?php include_layout_template('admin_footer.php'); ?>
		
