<?php
define('MYSQL_HOST', 'localhost');
define('MYSQL_PORT', 8889);
define('MYSQL_USER', 'root');
define('MYSQL_PASSWD', 'root');
define('MYSQL_DBNAME', 'acmeproducts');

function getMySQLConnection(){

    $mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWD, MYSQL_DBNAME, MYSQL_PORT);
    
    if (mysqli_connect_error()) {
        die(sprintf('Error connecting to MySQL. Error No: %d, Error: %s', mysqli_connect_errno(), 
                                                                          mysqli_connect_error()));
    }
    
    return $mysqli;
}