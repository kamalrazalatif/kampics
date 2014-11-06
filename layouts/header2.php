<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8" >
    <title><?php echo $title; ?></title>
    <link href="../stylesheets/mainstyles.css" media="all" rel="stylesheet" type="text/css" />
    <?php echo $jqueryfiles; ?>
  </head>
  <body>.
  <header>
  <div id="headerwrapper2">
        <div id="headerleft">
          <h1><a href="index.php"><img src="images/teambook_header_title.gif" width="500" height="80" alt=			"Teambook Header Title" /></a></h1>
        <!-- end #headerleft --></div>
        <div id="#headerright">
        <?php
        
        if($session->is_logged_in()){
			
        /*$headerpic = get_web_path_tn($_SESSION['userPic']);
		$profileid = $_SESSION['user_id'];
        $headerright = "<a href=\"profile.php?profileid={$profileid}\"><img class=\"hdprofpic\"";
        $headerright .= "src=\"assets/images/uploads/profpic/tn/{$headerpic}\""; 
        $headerright .=	" alt=\"kamal profile pic\" width=\"50\" /></a>";
        $headerright .= "<h3>{$_SESSION['firstname']}". " " ."{$_SESSION['lastname']}</h3>";*/
        $headerright .= "<a href=\"logout.php\">Logout</a>";
        
        echo $headerright;
        }
        
        ?>
        <!-- end #headerright --></div>
   <!-- end #headerwrapper2 --></div>
  
  </header>

