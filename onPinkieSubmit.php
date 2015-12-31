<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
include_once 'includes/pinkie.php';
include_once 'includes/logger.php';
secureSessionStart();

// TODO Needs to have serverside validation of variables. Especially the files.

$_pinkie = new Pinkie();

// All the stuff at the top.
$_pinkie->s_Title = $_POST['title'];
$_pinkie->s_Submitter = $_SESSION['Username'];
$_pinkie->s_SubmittedFor = $_POST['submitTo'];
$_pinkie->s_Action = $_POST['action'];
$_pinkie->s_Priority  = $_POST['priority'];
$_pinkie->s_ReferenceNumber = $_POST['referenceNumber'];
$_pinkie->s_Status = $_POST['status'];

//------------------------------------------------------------------------------

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

//------------------------------------------------------------------------------

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

//------------------------------------------------------------------------------
// Please note that if the number of files excedes the max supported by the file
// system it will fail to create a folder.
// Attachments.
for($i=0; $i<count($_FILES['attachment']['name']); $i++)
{
  //Get the temp file path
  $tmpFilePath = $_FILES['attachment']['tmp_name'][$i];

  //Make sure we have a filepath
  if ($tmpFilePath != ""){
    //Setup our new file path
    //-----------------------------
    $now = time();
    $num = date("w");
    if ($num == 0)
    {
      $sub = 6;
    }
    else
    {
      $sub = ($num-1);
    }
    $WeekMon  = mktime(0, 0, 0, date("m", $now)  , date("d", $now)-$sub, date("Y", $now));    //monday week begin calculation
    $todayh = getdate($WeekMon); //monday week begin reconvert

    $d = $todayh['mday'];
    $m = $todayh['mon'];
    $y = $todayh['year'];
    $newFilePath =  PATH_PREFIX."$d-$m-$y/".$_SESSION['Username']."/". $_FILES['attachment']['name'][$i];

    // Make the folder if it doesn't exist.
    if (!is_dir(PATH_PREFIX."$d-$m-$y/".$_SESSION['Username']."/") && !mkdir(PATH_PREFIX."$d-$m-$y/".$_SESSION['Username']."/"))
    {
      onError("onSubmitPinkie failed", "Error creating folder: ".PATH_PREFIX."$d-$m-$y/".$_SESSION['Username']."/");
    }

    //Upload the file into the temp dir
    if(move_uploaded_file($tmpFilePath, $newFilePath))
    {

      //Handle other code here
      $_pinkie->addAttachment($newFilePath);

    }
    else
    {
        onError("onSubmitPinkie","Failed to submit pinkie because file upload failed. Path was: ".$newFilePath);
    }
  }
}

//------------------------------------------------------------------------------

// push it to the database.
//var_dump($_pinkie);
$_pinkie->toDatabase();
logGeneral($_pinkie->i_PinkieID, $_SESSION['Username'], "Pinkie was created.");
header("Location: ./home.php");

?>
