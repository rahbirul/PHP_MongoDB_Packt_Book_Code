<?php
require('dbconnection.php');

$mongo = DBConnection::instantiate();
$collection = $mongo->getCollection('sample_articles');

$key = array('author' => 1);
$initial = array('count' => 0, 'total_rating' => 0);
$reduce = "function(obj, counter) { counter.count++; counter.total_rating += obj.rating;}";
$finalize = "function(counter) { counter.avg_rating = Math.round(counter.total_rating / counter.count); }";
$condition = array('published_at' => array('$gte' => new MongoDate(strtotime('-1 day'))));

$result = $collection->group($key, $initial, new MongoCode($reduce),
                             array(
                                    'finalize' => new MongoCode($finalize),
                                    'condition' => $condition
                                  )
                             );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>Author Rating</title> 
        <link rel="stylesheet" href="style.css"/>

    </head>
    <body>
        <div id="contentarea">
            <div id="innercontentarea">
                <h1>Authors' Ratings</h1>
                <table class="table-list" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th width="50%">Author</th>
                            <th width="24%">Articles</th>
                            <th width="*">Average Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($result['retval'] as $obj): ?>
                        <tr>
                            <td><?php echo $obj['author']; ?></td>
                            <td><?php echo $obj['count']; ?></td>
                            <td><?php echo $obj['avg_rating']; ?></td>
                        <tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>