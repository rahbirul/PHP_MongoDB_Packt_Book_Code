<?php

try{
    
    $mongodb = new Mongo();
    $articleCollection = $mongodb->myblogsite->articles;

} catch (MongoConnectionException $e) {
    
    die('Failed to connect to MongoDB '.$e->getMessage());
}

$currentPage = (isset($_GET['page'])) ? (int) $_GET['page'] : 1; //current page number
$articlesPerPage = 5; //number of articles to show per page
$skip = ($currentPage - 1) * $articlesPerPage; //number of article to skip from beginning

$cursor = $articleCollection->find(array(), $fields=array('title', 'created_at'));
$totalArticles = $cursor->count(); //total number of articles in database
$totalPages = (int) ceil($totalArticles / $articlesPerPage); //total number of pages to display

$cursor->sort(array('created_at' => -1))->skip($skip)->limit($articlesPerPage);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title>Dashboard</title> 
    <link rel="stylesheet" href="style.css"/>
    <style type="text/css" media="screen">
        body { font-size: 13px; }
        div#contentarea { width : 650px; }
    </style>
    <script type="text/javascript" charset="utf-8">
        function confirmDelete(articleId) {

            var deleteArticle = confirm('Are you sure you want to delete this article?');

            if(deleteArticle){
                window.location.href = 'delete.php?id='+articleId;
            }
            return;
        }
    </script>
</head>

<body>
    <div id="contentarea">
        <div id="innercontentarea">
            <h1>Dashboard</h1>
            <table class="articles" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th width="50%">Title</th>
                        <th width="24%">Saved at</th>
                        <th width="*">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($cursor->hasNext()): 
                    $article = $cursor->getNext();?>
                    <tr>
                        <td><?php echo substr($article['title'], 0, 35) . '...'; ?></td>
                        <td><?php print date('g:i a, F j', $article['created_at']->sec);?></td>
                        <td>
                            <a href="blog.php?id=<?php echo $article['_id'];?>">View</a>
                            | <a href="edit.php?id=<?php echo $article['_id'];?>">Edit</a>
                            | <a href="#" onclick="confirmDelete('<?php echo $article['_id']; ?>')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile;?>
                </tbody>
            </table>
        </div>
        <div id="navigation">
            <div class="prev">
                <?php if($currentPage !== 1): ?>
                <a href="<?php echo $_SERVER['PHP_SELF'].'?page='.($currentPage - 1); ?>">Previous</a>
                <?php endif; ?>
            </div>
            <div class="page-number"> <?php echo $currentPage; ?> </div>
            <div class="next">
                <?php if($currentPage !== $totalPages): ?>
                <a href="<?php echo $_SERVER['PHP_SELF'].'?page='.($currentPage + 1); ?>">Next</a>
                <?php endif; ?>
            </div>
            <br class="clear"/>
        </div>
    </div>
</body>
</html>