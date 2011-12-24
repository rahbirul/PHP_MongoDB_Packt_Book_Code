<?php

require 'customer.php';

printf("Saving new customer object...\n");

$customer              = new Customer();
$customer->firstName   = "Joe";
$customer->lastName    = "Gunchy";
$customer->email       = 'joegunchy42@example.com';
$customer->dateOfBirth = '1982-04-07';

$status = $customer->save();

printf("\tDone. ID %d\n", $customer->id);

printf("Saving Metadata....\n");

$metadata = array(
                    'Middle Name' => 'The Gun',
                    'Social Networking' => array(
                                                    'Twitter Handle' => '@joegunchytw',
                                                    'Facebook Username' => 'joegunchyfb'
                                                ),
                    'Has a Blog?' => True
                 );
 
$customer->setMetaData($metadata);
printf("\tDone\n");

printf("Loading metadata...\n");

print_r($customer->getMetaData());

 
printf("Updating metadata...\n");

$metadata = array(
                    'Marriage Anniversary' => new MongoDate(strtotime('10 September 2005')),
                    'Number of Kids' => 3,
                    'Favorite TV Shows' => array('The Big Bang Theory', 'Star Trek Next Generation')
                 );

print_r($customer->setMetaData($metadata));

printf("\tDone.\n");

printf("Reloading metadata...\n");

print_r($customer->getMetaData());
