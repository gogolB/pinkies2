<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
include_once 'includes/fund_object.php';
secureSessionStart();

if(!isset($_POST['fundName']) || $_POST['fundName'] == '')
{
  onError("Fund Submission Error!", "No Fund name set! Can not update database without Fund name");
}

$_fund = new Fund();
if(isset($_POST['fundID']))
{
  $_fund->i_fundID = $_POST['fundID'];
}

$_fund->s_FundName = $_POST['fundName'];
$_fund->s_Activity = $_POST['activity'];
$_fund->s_Fund = $_POST['fund'];
$_fund->s_Function = $_POST['function'];
$_fund->s_CostCenter = $_POST['costCenter'];
$_fund->s_ProjectCode = $_POST['projectCode'];
$_fund->s_Balance = $_POST['balance'];
$_fund->b_Active = $_POST['active'];


var_dump($_fund);
//$_fund->toDatabase();

// All done, redirect to the vendor list page.
//header("Location: ./fund.php?reason=list");

?>
