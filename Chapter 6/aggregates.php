<?php

require 'mysql.php';
require 'dbconnection.php';

$query = 'SELECT name, DATE(time_of_sales) as date_of_sales, SUM(units_sold) as total_units_sold '.
         'FROM sales s INNER JOIN products p ON (p.id = s.product_id)  '.
         'GROUP BY product_id, DATE(time_of_sales)';
         
$mysql = getMySQLConnection();
$result = $mysql->query($query);

if($result === False){
  die(sprintf("Error executing query %s" % $mysql->error));
}

$salesByDate = array();

while($row = $result->fetch_assoc()) {
  
  $date      = $row['date_of_sales'];
  $product   = $row['name'];
  $totalSold = $row['total_units_sold'];
  
  $salesPerProduct = (isset($salesByDate[$date])) ? $salesByDate[$date] : array();
  
  $salesPerProduct[$product] = $totalSold;
  $salesByDate[$date] = $salesPerProduct;
}

$result->free();
$mysql->close();

$mongodb =  DBConnection::instantiate();
$collection = $mongodb->getCollection('daily_sales');

foreach($salesByDate as $date => $sales) {
  
  $document = array(
                    'sales_date' => new MongoDate(strtotime($date)),
                    'items' => array()
              );
  
  foreach($sales as $product => $unitsSold) {
    $document['items'][$product] = $unitsSold;
  }
  $collection->insert($document);
}