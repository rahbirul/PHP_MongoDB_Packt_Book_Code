<?php

$id = $_GET['id'];

require 'dbconnection.php';

$mongo = DBConnection::instantiate();
$gridFS = $mongo->database->getGridFS();

$object = $gridFS->findOne(array('_id' => new MongoId($id)));

//find the chunks for this file
$chunks = $mongo->database->fs->chunks->find(array('files_id' => $object->file['_id']))
                                       ->sort(array('n' => 1));

header('Content-type: '.$object->file['filetype']);
//output the data in chunks
foreach($chunks as $chunk){
    echo $chunk['data']->bin;
}