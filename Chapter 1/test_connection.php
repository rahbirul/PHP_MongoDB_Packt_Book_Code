<?php
try{
    $mongo     = new Mongo($server='mongodb://localhost:27017/', $options=array('timeout'=>100)); //create a connection to MongoDB 
    $databases = $mongo->listDBs(); //List all databases;
    echo '<pre>';
    print_r($databases);
    $mongo->close();
} catch(MongoConnectionException $e) {
    die($e->getMessage());
}