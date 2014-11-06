<?php require_once("includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
  // Find all the users
  $users = User::find_all();
?>
<?php include_layout_template('logged_header.php'); ?>

<h2>Kampics Users:</h2>

<?php echo output_message($message); ?>
<table class="bordered">
  <tr>
  	<th>Profile Pic</th>
    <th>User</th>
    <th>Username</th>
  </tr>
<?php foreach($users as $user): ?>
  <tr>  
    <td><a href="profile.php?id=<?php echo $user->id; ?>"><img src="images/profpic/<?php echo $user->profpic; ?>" height="80"/></a></td>
    <td><a href="profile.php?id=<?php echo $user->id; ?>"><?php echo $user->full_name(); ?></a></td>
    <td><?php echo $user->username; ?></td>
  </tr>
<?php endforeach; ?>
</table>
<br />


<?php include_layout_template('footer.php'); ?>
