<?php 

$lat = (float)$_GET['lat'];
$lon = (float)$_GET['lon'];

$mongo = new Mongo();
$collection = $mongo->selectDB('geolocation')
                    ->selectCollection('restaurants');

$query = array('location' => array('$near' => array($lat, $lon)));
$cursor = $collection->find($query);

$response = array();
while($doc = $cursor->getNext()) {
    
    $obj = array(
                 'name' => $doc['name'], 
                 'serves'=> $doc['serves'],
                 'latitude' => $doc['location'][0],
                 'longitude' => $doc['location'][1]
           );
    
    array_push($response, $obj);
}

echo json_encode($response);