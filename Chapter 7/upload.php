<?php
require 'dbconnection.php';

$action = (isset($_POST['upload']) && $_POST['upload'] === 'Upload') ? 'upload' : 'view';

switch($action) {
    case 'upload':
        
        //check file upload success
        if($_FILES['image']['error'] !== 0) {
            die('Error uploading file. Error code '.$_FILES['image']['error']);
        }

        //connect to MongoDB sevrer
        $mongo = DBConnection::instantiate();
        //get a MongoGridFS instance
        $gridFS = $mongo->database->getGridFS();
                
        $filename    = $_FILES['image']['name'];
        $filetype    = $_FILES['image']['type'];        
        $tmpfilepath = $_FILES['image']['tmp_name'];
        $caption     = $_POST['caption'];
        
        //storing the uploaded file
        $id = $gridFS->storeFile($tmpfilepath, array('filename' => $filename, 
                                                     'filetype' => $filetype,
                                                     'caption' => $caption));
        
        break;
    
    default:
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="styles.css"/>
      <title>Upload Files</title>

      </head>

          <body>
              <div id="contentarea">
                  <div id="innercontentarea">
                      <h1>Upload Image</h1>
                      <?php if($action === 'upload'): ?>
                      <h3>File Uploaded. Id <?php echo $id; ?> 
                      <a href="<?php echo $_SERVER['PHP_SELF']; ?>">Upload another?</a>
                      </h3>
                      <?php else: ?>
                      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" 
                        accept-charset="utf-8" enctype="multipart/form-data">
                        <h3>Enter Caption&nbsp;<input type="text" name="caption"/><h3/>
                        <p><input type="file" name="image"/></p>
                        <p><input type="submit" value="Upload" name="upload"/></p>
                      </form>
                    <?php endif; ?>
                  </div>
              </div>
          </body>
</html>