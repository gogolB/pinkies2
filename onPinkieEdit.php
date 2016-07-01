<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
include_once 'includes/pinkie.php';
include_once 'includes/logger.php';
secureSessionStart();

// TODO Needs to have serverside validation of variables.
if(!isset($_POST['pinkieID']))
{
  onError('onPinkieEdit', "Failed to update pinkie because no pinkie ID was set.");
}

// Because this is an edit we need to get the pinkieID and set it. This will
// force a database update.
$_pinkie = new Pinkie();
$_pinkie->i_PinkieID = (int)$_POST['pinkieID'];

// All the stuff at the top.
$_pinkie->s_Title = $_POST['title'];
$_pinkie->s_Submitter = $_SESSION['Username'];
$_pinkie->s_SubmittedFor = $_POST['submitTo'];
$_pinkie->s_Action = $_POST['action'];
$_pinkie->s_Priority  = $_POST['priority'];
$_pinkie->s_ReferenceNumber = $_POST['referenceNumber'];
$_pinkie->s_Status = $_POST['status'];

//------------------------------------------------------------------------------

// objects
// No need to process them, they are processed as AJAX requests on change.

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
// No need to process funds beacuse they are processed as AJAX requests on change.

//------------------------------------------------------------------------------

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
      onError("onPinkieEdit failed", "Error creating folder: ".PATH_PREFIX."$d-$m-$y/".$_SESSION['Username']."/");
    }

    //Upload the file into the temp dir
    if(move_uploaded_file($tmpFilePath, $newFilePath))
    {

      //Handle other code here
      $_pinkie->addAttachment($newFilePath);

    }
    else
    {
        onError("onPinkieEdit","Failed to submit pinkie because file upload failed. Path was: ".$newFilePath);
    }
  }
}
//------------------------------------------------------------------------------
// push it to the database.
var_dump($_pinkie);
$_pinkie->toDatabase();
//------------------------------------------------------------------------------
logGeneral($_pinkie->i_PinkieID, $_SESSION['Username'], "Pinkie was edited by : ".getName());
header("Location: ./home.php");

?>
