<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
include_once 'includes/pinkie.php';
secureSessionStart();

if(!isset($_POST['pinkieID']))
{
  onError("onAdminSubmit.php","Failed to submit pinkie because no pinkieID was set.");
}

$_pinkie = new Pinkie();
$_pinkie->i_PinkieID = (int)$_POST['pinkieID'];
$_pinkie->fromDatabase();

if(strcmp($_POST['status'], Done) == 0)
{
  $_pinkie->s_Status = $_POST['status'];
  $_pinkie->toDatabase();
}
else if(strcmp($_POST['status'], Cancelled) == 0)
{
  $_pinkie->s_Status = $_POST['status'];
  $_pinkie->toDatabase();
}

header("Location: ./home.php");

?>
