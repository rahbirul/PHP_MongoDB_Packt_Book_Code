<?php
require 'dbconnection.php';
$mongo = DBConnection::instantiate();
$articles = $mongo->getCollection('articles');

$articleIds = array();
foreach($articles->find(array(), array('_id' => TRUE)) as $article){
    array_push($articleIds, (string)$article['_id']);
}

function getRandomArrayItem($array)
{
    $length = count($array);
    $randomIndex = mt_rand(0, $length - 1);
    
    return $array[$randomIndex];
}
echo 'Simulating blog post reading...';

while(1) {
    $id = getRandomArrayItem($articleIds);
    
    //change the value of $url accordingly on your machine
    $url = sprintf('http://localhost:8888/mongodb/chapter5/blog.php?id=%s', $id);
    
    $curlHandle = curl_init();
    
    curl_setopt($curlHandle, CURLOPT_URL, $url);
    curl_setopt($curlHandle, CURLOPT_HEADER, false);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
    
    curl_exec($curlHandle);
    curl_close($curlHandle);
}