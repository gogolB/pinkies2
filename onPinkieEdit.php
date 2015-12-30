<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
include_once 'includes/pinkie.php';
secureSessionStart();

// TODO Needs to have serverside validation of variables.
if(!isset($_POST['pinkieID']))
{
  onError('onPinkieEdit', "Failed to update pinkie because no pinkie ID was set.");
}

// Purchase Objects.
$_objectID = $_POST['objectID'];
$_quantity = $_POST['quantity'];
$_stockNumber = $_POST['stockNumber'];
$_description = $_POST['description'];
$_unitPrice = $_POST['unitPrice'];
$_bc = $_POST['bc'];
$_accountNumber = $_POST['accountNumber'];
// add each of the objects to the pinkie.
foreach($_quantity as $key=>$q)
{
  $_o = new PurchaseObject($_POST['pinkieID']);
  $_o->i_ObjectID = intval($_objectID[$q]);
  //$_o->fromDatabase();
  $_o->s_BC = $_bc[$q];
  $_o->s_AccountNumber = $_accountNumber[$q];
  //$_o->toDatabase();
  var_dump($_o);
  echo "<br>";
  var_dump($_POST);
}


//------------------------------------------------------------------------------

// push it to the database.
//header("Location: ./home.php");

?>
