<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
include_once 'includes/pinkie.php';
secureSessionStart();


$_pinkie = new Pinkie();

// All the stuff at the top.
$_pinkie->s_Title = $_POST['title'];
$_pinkie->s_Submitter = $_SESSION['Username'];
$_pinkie->s_SubmittedFor = $_POST['submitTo'];
$_pinkie->s_Action = $_POST['action'];
$_pinkie->s_Priority  = $_POST['priority'];
$_pinkie->s_ReferenceNumber = $_POST['referenceNumber'];

// Purchase Objects.
$_quantity = $_POST['quantity'];
$_stockNumber = $_POST['stockNumber'];
$_description = $_POST['description'];
$_unitPrice = $_POST['unitPrice'];
// add each of the objects to the pinkie.
foreach($_quantity as $key=>$q)
{
  $_pinkie->addObject(intval($q), $_stockNumber[$key], $_description[$key], '', '', floatval($_unitPrice[$key]));
}
$_pinkie->d_ShippingFreight = floatval($_POST['shipping']);
$_pinkie->d_Total = floatval($_POST['total']);
$_pinkie->s_EquipmentType = $_POST['typeOfPurchase'];
if($_POST['typeOfPurchase'] == 'Other')
{
  $_pinkie->s_EquipmentType = $_POST['typeOfPurchaseOther'];
}

// Vendors and Justification.
$_pinkie->v_Vendor = $_POST['vendor'];
$_pinkie->s_Justification=$_POST['justification'];
$_pinkie->s_JustificationText=$_POST['justificationText'];
$_pinkie->s_EquipmentLocation = $_POST['equipmentLocation'];
$_pinkie->s_UCRPropertyTag = $_POST['ucrPropertyNumber'];
$_pinkie->s_classInstructed = $_POST['classInstructed'];
$_pinkie->s_Quote = $_POST['quote'];

// Funds
$_fund = $_POST['fund'];
$_amt = $_POST['amount'];
// Add them to the pinkie.
foreach ($_fund as $key => $f)
{
    $_pinkie->addExpense(floatval($_amt[$key]), $f);
}

// Attachments.

// push it to the database.
var_dump($_pinkie);
// $_pinkie->toDatabase();

?>
