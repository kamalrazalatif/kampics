<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8" >
    <title>Kampics</title>
    <link href="stylesheets/mainstyles.css" media="all" rel="stylesheet" type="text/css" />
  </head>
  <body>
  <header>
  <div id="headerwrapper2">
        <div id="headerleft">
          <h1><a href="index.php"><img src="images/kampics_title.png" width="500" height="80" alt="Kampics Header Title" /></a></h1>
        <!-- end #headerleft --></div>
        <div id="#headerright">
        <?php
        
        if(isset($_SESSION['user_id'])){
			
		$user = User::find_by_id($_SESSION['user_id']);	
			
        $headerpic = $user->profpic;
		$profileid = $_SESSION['user_id'];
		$firstname = $user->first_name;
		$lastname = $user->last_name;
		
        $headerright = "<a href=\"profile.php?id={$profileid}\"><img class=\"hdprofpic\"";
        $headerright .= "src=\"images/profpic/{$headerpic}\""; 
        $headerright .=	" alt=\"kamal profile pic\" width=\"50\" /></a>";
        $headerright .= "<h3>{$firstname}". " " ."{$lastname}</h3>";
        $headerright .= "<a href=\"logout.php\">Logout</a>";
        
        echo $headerright;
        }
        
        ?>
        <!-- end #headerright --></div>
   <!-- end #headerwrapper2 --></div>
  
  </header>
  <div id="wrapper">
  <nav>
  	<ul>
    	<li><a href="index.php">Home</a></li>
    	<li><a href="profile.php?id=<?php echo $_SESSION['user_id']; ?>">My Profile</a></li>
        <li><a href="photo_upload.php">Photo Upload</a></li>
    	<li><a href="list_users.php">Users</a></li>
        <li><a href="contact.php">Contact Admin</a></li>
  	</ul>
  </nav>
  <main>

