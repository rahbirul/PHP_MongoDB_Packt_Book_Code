<?php
require('session.php');
require('user.php');

$user = new User();

if (!$user->isLoggedIn()){
    header('location: login.php');
    exit;
}
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="style.css" /> 
        <title>Welcome <?php echo $user->username; ?></title>
    </head>

    <body>
        <div id="contentarea">
            <div id="innercontentarea">
                <a style="float:right;" href="logout.php">Log out</a>
                <h1>Hello <?php echo $user->username; ?></h1>
                <ul class="profile-list">
                	<li> 
                    	<span class="field">Username</span>
                        <span class="value"><?php echo $user->username; ?></span>
                        <div class="clear"> </div>
                    </li>
                	<li> 
                    	<span class="field">Name</span>
                        <span class="value"><?php echo $user->name; ?></span>
                        <div class="clear"> </div>
                    </li>
                	<li>
                    	<span class="field">Birthday</span>
                        <span class="value"><?php echo date('j F, Y',$user->birthday->sec); ?></span>
                        <div class="clear"> </div>
                    </li>
                    <li>
                    	<span class="field">Address</span>
                        <span class="value"><?php echo $user->address; ?></span>
                        <div class="clear"> </div>
                    </li>
                </ul>
            </div>
        </div>
    </body>
</html>