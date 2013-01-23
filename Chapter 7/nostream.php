<?php
$filename = 'rafting.jpg';

$mongo    = new Mongo();
$database = $mongo->selectDB('myfiles');
$gridFS   = $database->getGridFS();

// $i = 0;
// while($i < 1000) {
//     $object = $gridFS->findOne(array('filename' => $filename));
//     $object->getBytes();
//     unset($object);
//     $i++;
// }

$object = $gridFS->findOne(array('filename' => $filename));
echo $object->getBytes();
print "\n";
print ceil(memory_get_peak_usage(true) / (1024 * 1024)). " MB\n";
//printf("%d\n", memory_get_peak_usage());