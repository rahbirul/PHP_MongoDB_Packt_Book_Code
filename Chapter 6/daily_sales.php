<?php
require 'dbconnection.php';

$action = (isset($_POST['action'])) ? $_POST['action'] : 'default';

function validateInput() {

  if (empty($_POST['year']) || empty($_POST['month']) || empty($_POST['day'])) {
    return False;
  }
  
  $timestamp = strtotime($_POST['year'].'-'.$_POST['month'].'-'.$_POST['day']);
  
  if (!is_numeric($timestamp)) {
    return False;  
  }
  
  return checkdate(date('m', $timestamp), date('d', $timestamp), date('Y', $timestamp));
}
switch($action) {
  case 'Show':
    
    if(validateInput() === True) {
      $inputValidated = True;
      
      $date = sprintf('%d-%d-%d', $_POST['year'], 
                                  $_POST['month'], 
                                  $_POST['day']);

      $mongodate  = new MongoDate(strtotime($date));
      $mongodb    = DBConnection::instantiate();
      $collection = $mongodb->getCollection('daily_sales');

      $doc = $collection->findOne(array('sales_date' => $mongodate));
      
    } 
    else {
      $inputValidated = False;
    }

    break;
  default:
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>Acme Corp | Daily Sales</title> 
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <div id="contentarea">
            <div id="innercontentarea">
                <h1>Daily Sales of Acme Products</h1>
                <form action="<? echo $_SERVER['PHP_SELF']; ?>" method="post">
                  Enter Date (YYYY-MM-DD) <input type="text" name="year" size=4/> - 
                  <input type="text" name="month" size=2/> - 
                  <input type="text" name="day" size=2/>
                  <input type="submit" name="action" value="Show"/>                  
                </form>
                <?php if($action === 'Show'):
                  if ($inputValidated === True):?>
                  
                    <h3><?php echo date('F j, Y', $mongodate->sec) ?></h3>
                      <?php if (!empty($doc)):?>
                      <table class="table-list" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th width="50%">Item</th>
                                <th width="25%">&nbsp;</th>
                                <th width="*">Units Sold</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($doc['items'] as $item => $unitsSold): ?>
                            <tr>
                                <td><?php echo $item; ?></td>
                                <td>&nbsp;</td>
                                <td ><?php echo $unitsSold; ?></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody> 
                      </table>
                      <?php else: echo "<p> No sales record found.</p>"; 
                      endif;
                  else:
                    echo "<h3>Invalid input. Try again.</h3>";
                  endif;
                endif; ?>
            </div>
        </div>
    </body>
</html>