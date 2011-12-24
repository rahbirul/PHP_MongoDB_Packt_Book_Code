<?php
//Session started by requiring the script
require('session.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="style.css" /> 
        <title>Using the SessionManager...Page 1</title>
    </head>
    <body>
        <div id="contentarea">
            <div id="innercontentarea">
                <h2>Using the SessionManager...Page 2</h2>
                <p>The random number generated in previous page is still 
                    <span style="font-weight:bold;">
                        <?php echo $_SESSION['random_number']; ?>
                    </span>
                </p>
                <p>PHP session id 
                    <span style="text-decoration:underline;">
                        <?php echo session_id(); ?>
                    </span>
                </p>
            </div>
        </div>
    </body>
</html>