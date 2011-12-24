<?php 

$lat = (float)$_GET['lat'];
$lon = (float)$_GET['lon'];

$mongo = new Mongo();
$db = $mongo->selectDB('geolocation');

$command = array('geoSearch' => 'restaurants', 
                 'near' => array($lat, $lon), 
                 'search' => array('serves' => 'Burger'),
                 'maxDistance' => 3);

$response = $db->command($command);



$jsonResponse = array();

foreach($response['results'] as $result) {
    
    $obj = array(
                 'name' => $result['name'], 
                 'serves'=> $result['serves'],
                 'latitude' => $result['location'][0],
                 'longitude' => $result['location'][1]
           );
    
    array_push($jsonResponse, $obj);
}

echo json_encode($jsonResponse);