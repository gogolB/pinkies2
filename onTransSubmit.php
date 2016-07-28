<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
include_once 'includes/pinkie.php';
include_once 'includes/logger.php';
secureSessionStart();

if(!isset($_POST['pinkieID']))
{
  onError("onAdminSubmit.php","Failed to submit pinkie because no pinkieID was set.");
}

$_pinkie = new Pinkie();
$_pinkie->i_PinkieID = (int)$_POST['pinkieID'];
$_pinkie->fromDatabase();
$_pinkie->s_TransProcess = $_SESSION['Username'];

if(strcmp($_POST['status'], Done) == 0)
{
  $_pinkie->s_Status = $_POST['status'];
  $_pinkie->s_TransProcess = $_SESSION['Username'];
  $_pinkie->toDatabase();
  logGeneral($_pinkie->i_PinkieID, $_SESSION['Username'], "Pinkie was marked as done by Transactor: ".getName());
}
else if(strcmp($_POST['status'], Cancelled) == 0)
{
  $_pinkie->s_Status = $_POST['status'];
  $_pinkie->toDatabase();
  logGeneral($_pinkie->i_PinkieID, $_SESSION['Username'], "Pinkie was cancelled by: ".getName());
}
else if(strcmp($_POST['status'], Archived) == 0)
{
  $_pinkie->s_Status = $_POST['status'];
  $_pinkie->toDatabase();
  logWarning($_pinkie->i_PinkieID, $_SESSION['Username'], "Pinkie was archived by: ".getName());
}

header("Location: ./home.php");

?>
