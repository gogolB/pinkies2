<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
include_once 'includes/pinkie.php';
secureSessionStart();

if(!isset($_POST['pinkieID']))
{
  onError("onUserSubmit.php","Failed to submit pinkie because no pinkieID was set.");
}

if(strcmp($_POST['status'], Cancelled) == 0)
{
  $_pinkie = new Pinkie();
  $_pinkie->i_PinkieID = (int)$_POST['pinkieID'];
  $_pinkie->fromDatabase();
  $_pinkie->s_Status = $_POST['status'];
  $_pinkie->toDatabase();
}

header("Location: ./home.php");

?>
