<?php

try {

    $connection = new Mongo();
    $database   = $connection->selectDB('myblogsite');
    $collection = $database->selectCollection('articles');

} catch(MongoConnectionException $e) {
    die("Failed to connect to database ".$e->getMessage());
}

$cursor = $collection->find();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
     <!--
     <style type="text/css" media="screen">
        body {
            background-color: #e1ddd9;
            font-size: 12px;
            font-family: Verdana, Arial, Helvetica, SunSans-Regular, Sans-Serif;
            color:#564b47;  
            padding:20px;
            margin:0px;
            text-align: center;
        }

      div#contentarea {
          text-align: left;
          vertical-align: middle;   
          margin: 0px auto;
          padding: 0px;
          width: 550px;
          background-color: #ffffff;
          border: 1px #564b47;
      }
            
      div#innercontentarea { padding: 10px 50px; }    
      div#innercontentarea form input[type=text] { width: 435px; }
      div#innercontentarea form textarea { width: 435px; }

          </style> -->
            <link rel="stylesheet" href="style.css" /> 
          <title>My Blog Site</title>

          </head>

          <body>
              <div id="contentarea">
                  <div id="innercontentarea">
                      <h1>My Blogs</h1>
                  
                      <?php while ($cursor->hasNext()):
                        $article = $cursor->getNext(); ?>
                        <h2><?php echo $article['title']; ?></h2>
                        <p><?php echo substr($article['content'], 0, 200).'...'; ?></p>
                        <a href="blog.php?id=<?php echo $article['_id']; ?>">Read more</a>
                      <?php endwhile; ?>
                  </div>
              </div>
          </body>
</html>
