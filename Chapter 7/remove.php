<?php
$mongo    = new Mongo();
$database = $mongo->selectDB('myfiles');
$gridFS   = $database->getGridFS();

//remove the file named beach.png
$gridFS->remove(array('filename' => 'racetrack.jpg'));

$error = $database->lastError();

if(isset($error['err'])) {
    echo 'Files deleted.';
} else {
    echo 'Error deleting files '.$error['err'];
}