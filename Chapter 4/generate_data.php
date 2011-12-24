<?php
require('dbconnection.php');
$titles  = array(
                    'Nature always sides with the hidden flaw',
                    'Adding manpower to a late software project makes it later.',
                    'Research supports a specific theory depending on the amount of funds dedicated to it.',
                    'Always draw your curves, then plot your reading.',
                    'Software bugs are hard to detect by anybody except may be the end user.',
                    'There is never time to do it right, but always time to do it over.',
                    'Any given program, when running, is obsolete.',
                    'Profanity is the one language all programmers know best.',
                    'Programmers will act rational when all other possibilities have been exhausted.',
                    'If all else fails, read the documentation.',
                );

$authors = array('Luke Skywalker', 'Leia Organa', 'Han Solo', 'Darth Vader', 'Spock', 'James Kirk', 'Hikaru Sulu', 'Nyota Uhura');

$description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ".
               "Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ".
               "Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ".
               "Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";

$categories = array('Electronics', 'Mathematics', 'Programming', 'Data Structures', 
                    'Algorithms', 'Operating System', 'Database Management', 
                    'Artificial Intelligence', 
                    'Computer Networking');
                    
$tags = array('programming', 'testing', 'webdesign', 'tutorial', 'howto', 'version-control', 'nosql', 
              'algorithms', 'engineering', 'software', 'hardware', 'security', 'career-advice', 
              'presentation', 'hacking', 'benchmark', 'optimization', 'code', 'opensource', 'productivity');

function getRandomArrayItem($array)
{
    $length = count($array);
    $randomIndex = mt_rand(0, $length - 1);
    
    return $array[$randomIndex];
}

function getRandomTimestamp()
{
    $randomDigit = mt_rand(0, 6) * -1;
    return strtotime($randomDigit . ' day');
}


function createDoc()
{
    global $titles, $authors, $categories, $tags;
    $title    = getRandomArrayItem($titles);
    $author   = getRandomArrayItem($authors);
    $category = getRandomArrayItem($categories);
    
    $articleTags = array();
    $numOfTags   = rand(1,5);
    
    for ($j = 0; $j < $numOfTags; $j++){
        
        $tag = getRandomArrayItem($tags);
        
        if(!in_array($tag, $articleTags)){
            array_push($articleTags, $tag);
        }
        
    }
    
    $publishedAt = new MongoDate(getRandomTimestamp());
    $rating      = mt_rand(1, 10);
    
    return array('title' => $title, 'author' => $author, 'category' => $category,
                 'tags' => $articleTags, 'published_at' => $publishedAt, 
                 'rating' => $rating);
}

$mongo = DBConnection::instantiate();
$collection = $mongo->getCollection('sample_articles');

echo "Generating sample data...";
for ($i = 0; $i < 1000; $i++)
{
    $document = createDoc();
    $collection->insert($document);    
}
echo "Finished!";