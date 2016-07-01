<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
include_once 'includes/pinkie.php';
include_once 'includes/logger.php';
secureSessionStart();

if(!isset($_POST['pinkieID']))
{
  onError("onSuperSubmit.php","Failed to submit pinkie because no pinkieID was set.");
}

$_pinkie = new Pinkie();
$_pinkie->i_PinkieID = (int)$_POST['pinkieID'];
$_pinkie->fromDatabase();

if(strcmp($_POST['status'], ApprovedBySuper) == 0)
{
  $_pinkie->s_Submitter = $_SESSION['Username'];
  $_pinkie->s_SupervisorApprove = $_SESSION['Username'];
  $_pinkie->s_SubmittedFor = $_POST['submitTo'];
  $_pinkie->s_Status = PendingAdminApproval;
  $_pinkie->toDatabase();
  logGeneral($_pinkie->i_PinkieID, $_SESSION['Username'], "Pinkie was approved by Supervisor: ".getName());
}
else if(strcmp($_POST['status'], RejectedBySuper) == 0)
{
  $_tmp = $_pinkie->s_Submitter;
  $_pinkie->s_Submitter = $_POST['submitTo'];
  $_pinkie->s_SubmittedFor = $tmp;
  $_pinkie->s_Status = $_POST['status'];
  $_pinkie->toDatabase();
  logGeneral($_pinkie->i_PinkieID, $_SESSION['Username'], "Pinkie was rejected by Supervisor: ".getName());
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
