<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
include_once 'includes/pinkie.php';
secureSessionStart();
//------------------------------------------------------------------------------
// The user can review a pinkie here.
//------------------------------------------------------------------------------
$i_PinkieID = -1;
if(isset($_GET['pid']))
{
  $i_PinkieID  = $_GET['pid'];
}

if($i_PinkieID < 0)
{
    // You don't have a valid Pinkie ID, need to redirect home.
    header("Location: ./home.php");
}

// Load the pinkie from the pinkie database.
$_pinkie = new Pinkie();
$_pinkie->i_PinkieID = (int)$i_PinkieID;
$_pinkie->fromDatabase();
?>
<!DOCTYPE html>
<HTML>
  <HEAD>
    <?php printHeaderInfo(); ?>
    <title>New Pinkie</title>
    <script type="text/javascript" src="./js/pinkie.js"></script>
  </HEAD>
  <BODY>

    <div class="jumbotron text-center">
      <H1>ePinkies 2</H1>
      <h2>University of California at Riverside</h2>
      <h3>Department of Electrical and Computer Engineering</h3>
    </div>

    <!-- Header Container -->
    <div class="container">
      <div class="well">
        <H2>
          Welcome <?php echo(getName());?> to ePinkies2.
        </H2>
        <H4>Here you will be able view a pinkie object. Then if it is submitted to you, you can approve it and send it to the next person.</H4>

        <!-- Back to Home button. -->
        <a href="./home.php" class="btn btn-success" role="button"><span class="glyphicon glyphicon-home"></span> Back to Home</a>
      </div>
    </div>
    <form class="form-horizontal" role="form" action="#" method="POST" name="viewPinkieForm" id="viewPinkieForm" >
      <!-- Title, who you are submitting to, who is submitting it. -->
      <div class="container">
        <div class="well">

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="title">Title:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="title" name="title" placeholder="Title of the pinkie" value="<?php echo $_pinkie->s_Title; ?>" readonly>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="submittedBy">Requested By:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="submittedBy" name="submittedBy" placeholder="Submitter" value="<?php echo $_pinkie->s_Submitter; ?>" readonly>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="submitTo">Submit To:</label>
            <div class="col-sm-10">
              <select class="form-control" id="submitTo" name="submitTo">
                <?php printSubmitTo(); ?>
              </select>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="action">Action:</label>
            <div class="col-sm-10">
              <select class="form-control" id="action" name="action">
                  <option value="Reimbursement" <?php isSelected($_pinkie->s_Action, "Reimbursement"); ?> >Reimbursement</option>
                  <option value="Purchase" <?php isSelected($_pinkie->s_Action, "Purchase"); ?>>Purchase</option>
                  <option value="Payment Request" <?php isSelected($_pinkie->s_Action, "Payment Request"); ?> >Payment Request</option>
                  <option value="Quote" <?php isSelected($_pinkie->s_Action, "Quote"); ?>>Quote</option>
              </select>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="priority">Priority:</label>
            <div class="col-sm-10">
              <select class="form-control" id="priority" name="priority">
                  <option value="Expedite" <?php isSelected($_pinkie->s_Priority, "Expedite"); ?> >Expedite</option>
                  <option value="Urgent" <?php isSelected($_pinkie->s_Priority, "Urgent"); ?>>Urgent</option>
                  <option value="Routine" <?php isSelected($_pinkie->s_Priority, "Routine"); ?>>Routine</option>
                  <option value="Cancel" <?php isSelected($_pinkie->s_Priority, "Cancel"); ?>>Cancel</option>
                  <option value="Hold" <?php isSelected($_pinkie->s_Priority, "Hold"); ?>>Hold</option>
              </select>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="referenceNumber">Reference Number:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="referenceNumber" name="referenceNumber" value="<?php echo $_pinkie->s_ReferenceNumber; ?>" readonly>
            </div>
          </div>

        </div>
      </div>

    </form>

  </BODY>
</HTML>
