<?php
include_once 'functions.php';
include_once 'PurchaseObject.php';
secureSessionStart();

if (strlen($_POST['objectID']) == 0)
{
  // No Object ID was set, so this isn't a valid object.
  return;
}

$_o = new PurchaseObject((int)($_POST['pinkieID']));
$_o->i_ObjectID = (int)$_POST['objectID'];
$_o->fromDatabase();

echo var_dump($_o);

?>
