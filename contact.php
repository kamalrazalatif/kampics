<?php 

require_once("includes/initialize.php"); 
if (!$session->is_logged_in()) { redirect_to("login.php"); }
include_layout_template('logged_header.php'); 
$user = User::find_by_id($_SESSION['user_id']);
$fullname = $user->full_name();

if(isset($_POST['submit'])){

	$recipient = 'razakam79@gmail.com'; 
	$subject ='kampics contact'; 
	
 	$email = $_POST['email'];
	
		if (sizeof($_POST)) {
			$message = "";
			while(list($key, $val) = each($_POST)) {
				if ($key == "Submit") {
				//do nothing
		} else {
			$message .= "$key:\n $val\r\n";
// Checks if $val contains data
if(empty($val)) {
echo ("<h1>Error in form entry</h1> <p>All form entry fields are required to be filled in and some have not been filled in.</p>
<p>Please go <a href='javascript: history.go(-1)'>Back</a> and try again</p>");
exit();
}
}
}}
// Validate email address
if(!preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*" ."@"."([a-z0-9]+([\.-][a-z0-9]+)*)+"."\\.[a-z]{2,}"."$/",$email)){
echo ("<h1>Error in form entry</h1> <p>An invalid email address was entered.</p> <p>Please go <a href='javascript: history.go(-1)'>Back</a> and try again>/p>");
exit();
}
###########	
    function filter_email_header($form_field) {
      return preg_replace('/[\0\n\r\|\!\/\<\>\^\$\%\*\&]+/','',$form_field);
    }

    $email  = filter_email_header($email);
    $headers = "From: $email\n";
	$sent = mail($recipient, $subject, $message, $headers);
	
    if ($sent) {
		$session->message("Your message was sent successfully. We will get back to you soon.");

    } else {
		$message = "Your message could not be sent. Please try again.";
    }
   
	
}

?>

  <h1>Contact Me</h1>
  <p>Please feel free to contact me via email by completing the following form:</p>
  <?php echo output_message($message);  ?>
  <form id="form1" name="form1" method="post" action="<?php  echo $_SERVER['PHP_SELF']; ?>">
    
    <p>&nbsp;</p>
    <p>
      <label for="name">Please enter your name</label>
      <input name="name" type="text" class="highlighted" id="name" maxlength="40"  value="<?php echo $fullname; ?>"/>
      </p>
    <p>
      <label for="email">Please enter your email address</label>
      <input name="email" type="text" class="highlighted" id="email" maxlength="40"/>
      </p>
    <p>
      <label for="message">Please type your message in the space provided</label>
      </p>
    <p>
      <textarea name="message" id="message" cols="45" rows="5"></textarea>
      </p>
    <p>
      <input type="submit" name="submit" id="submit" value="Submit" />
      <label for="clear"></label>
      <input type="reset" name="clear" id="clear" value="Clear form" />
      </p>
    </form>
    
<?php include_layout_template('footer.php'); ?>
  
  