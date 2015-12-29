<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
include_once 'includes/pinkie.php';
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
  $_pinkie->s_SubmittedFor = $_POST['submitTo'];
  $_pinkie->s_Status = PendingAdminApproval;
  $_pinkie->toDatabase();
}
else if(strcmp($_POST['status'], RejectedBySuper) == 0)
{
  $_tmp = $_pinkie->s_Submitter;
  $_pinkie->s_Submitter = $_POST['submitTo'];
  $_pinkie->s_SubmittedFor = $tmp;
  $_pinkie->s_Status = $_POST['status'];
  $_pinkie->toDatabase();
}

?>
