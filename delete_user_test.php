<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php include_layout_template('admin_header.php'); ?>

<?php

if(isset($_GET['id'])){
    
    $id = $_GET['id'];
    $user = User::find_by_id($id);
        if($user->delete()) {
            echo "User " . $user->username . "was deleted";
        } else {
            echo "User NOT deleted";
        }
    
} else {
    echo "Please provide a User Id by selectign a user.";
}

?>

<?php include_layout_template('admin_footer.php'); ?>
		
