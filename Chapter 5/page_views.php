<?php
require 'dbconnection.php';

$dbConnection = DBConnection::instantiate();
$db = $dbConnection->database;

$map = "function() { emit(this.query_params.id, {count: 1, resp_time: this.response_time_ms}) }";

$reduce = "function(key, values) { ".
                "var total_count = 0;".
                "var total_resp_time = 0;".               
                "values.forEach(function(doc) {".
                    "total_count += doc.count;".
                    "total_resp_time += doc.resp_time;".               
                "});".               
                "return {count: total_count, resp_time: total_resp_time};".
          "}";
          
$finalize = "function(key, doc) {".
                 "doc.avg_resp_time = doc.resp_time / doc.count;".
                 "return doc;".
            "}";
            
$db->command(array(
                    'mapreduce' => 'access_log', 
                    'map' => new MongoCode($map),
                    'reduce' => new MongoCode($reduce),
                    'query' => array('page' => '/blog.php', 
                                     'viewed_at' => array('$gt' => new MongoDate(strtotime('-7 days')))),
                    'finalize' => new MongoCode($finalize),
                    'out'   => 'page_views_last_week'
                )
            );

$results = $dbConnection->getCollection('page_views_last_week')
                        ->find();

function getArticleTitle($id)
{
    global $dbConnection;
    $article = $dbConnection->getCollection('articles')->findOne(array('_id' => new MongoId($id)));

    return $article['title'];
}
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
        <head>
            <title>Most viewed articles (Last 7 days)</title> 
            <link rel="stylesheet" href="style.css"/>
            <style type="text/css" media="screen">
                body { font-size: 13px; }
                div#contentarea { width : 680px; }
            </style>
        </head>
        <body>
            <div id="contentarea">
                <div id="innercontentarea">
                    <h1>Most viewed articles (Last 7 days)</h1>
                    <table class="articles" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th width="50%">Article</th>
                                <th width="25%">Page views</th>
                                <th width="*">Avg response time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($results->sort(array('value.count' => -1)) as $result): ?>
                            <tr>
                                <td><?php echo getArticleTitle($result['_id']); ?></td>
                                <td ><?php echo $result['value']['count']; ?></td>
                                <td><?php echo sprintf('%f ms', $result['value']['avg_resp_time']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </body>
    </html>
