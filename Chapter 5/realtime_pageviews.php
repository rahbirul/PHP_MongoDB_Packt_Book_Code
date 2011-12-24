<?php
require 'dbconnection.php';

$dbConnection = DBConnection::instantiate();
$collection = $dbConnection->getCollection('article_visit_counter_daily');

function getArticleTitle($id)
{
    global $dbConnection;
    $article = $dbConnection->getCollection('articles')->findOne(array('_id' => new MongoId($id)));
    return $article['title'];
}

$objects = $collection->find(array('request_date' => new MongoDate(strtotime('today'))));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>Daily Page views (in realtime)</title> 
        <link rel="stylesheet" href="style.css"/>
        <style type="text/css" media="screen">
            body { font-size: 13px; }
            div#contentarea { width : 680px; }
        </style>
    </head>
    <body>
        <div id="contentarea">
            <div id="innercontentarea">
                <h1>Daily Page views (in realtime)</h1>
                <table class="articles" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Viewed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($objects->sort(array('count' => -1)) as $obj): ?>
                        <tr>
                            <td><?php echo getArticleTitle((string) $obj['article_id']); ?></td>
                            <td><?php echo $obj['count']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        var REFRESH_PERIOD = 5000; //refresh every 5 seconds.
        var t = setInterval("location.reload(true);", REFRESH_PERIOD);
    </script>
</html>