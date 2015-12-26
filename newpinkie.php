<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
secureSessionStart();
//------------------------------------------------------------------------------
// The user can start a new pinkie here.
//------------------------------------------------------------------------------
$s_PinkieTitle = 'Untitled Pinkie';
if(isset($_POST['title']))
{
  $s_PinkieTitle  = $_POST['title'];
}
?>
<!DOCTYPE html>
<HTML>
  <HEAD>
    <?php printHeaderInfo(); ?>
    <title>New Pinkie</title>
    <script type="text/javascript" src="js/pinkie.js"></script>
    <link href="./css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <script src="./js/fileinput.min.js" type="text/javascript"></script>
  </HEAD>
  <body>
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
        <H4>Here you will be able to to start a new pinkie object.</H4>
      </div>
    </div>

    <form class="form-horizontal" role="form" action="#" method="POST" name="newPinkieForm">

      <!-- Title, who you are submitting to, who is submitting it. -->
      <div class="container">
        <div class="well">

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="title">Title:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="title" name="title" placeholder="Title of the pinkie" value="<?php echo $_POST['title']; ?>">
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="submittedBy">Submitted By:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="submittedBy" name="submittedBy" placeholder="Submitter" value="<?php echo getName(); ?>" readonly>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="submitTo">Submitted To:</label>
            <div class="col-sm-10">
              <select class="form-control" id="submitTo" name="submitTo">
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
              </select>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="action">Action:</label>
            <div class="col-sm-10">
              <select class="form-control" id="action" name="action">
                  <option>Reimbursement</option>
                  <option selected="selected" >Purchase</option>
                  <option>Payment Request</option>
                  <option>Quote</option>
              </select>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="priority">Priority:</label>
            <div class="col-sm-10">
              <select class="form-control" id="priority" name="priority">
                  <option>Expedite</option>
                  <option>Urgent</option>
                  <option selected="selected" >Routine</option>
                  <option>Cancel</option>
                  <option>Hold</option>
              </select>
            </div>
          </div>

        </div>
      </div>

      <!-- Purchase Objects -->
      <div class="container">
        <div class="well">

          <div class="form-group form-group-lg">
            <H3 class="col-sm-1"><u>Quantity</u></H3>
            <H3 class="col-sm-2"><u>Stock Number</u></H3>
            <H3 class="col-sm-5"><u>Description</u></H3>
            <H3 class="col-sm-2"><u>Unit Price</u></H3>
            <H3 class="col-sm-2"><u>Total Price</u></H3>
          </div>

          <div class="form-group form-group-lg">
            <div class="col-sm-1">
              <input type="text" class="form-control" id="quantity[]" name="quantity[]" placeholder="Quantity">
            </div>
            <div class="col-sm-2">
              <input type="text" class="form-control" id="stockNumber[]" name="stockNumber[]" placeholder="Stock Number">
            </div>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="description[]" name="description[]" placeholder="Description">
            </div>
            <div class="col-sm-2">
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="unitPrice[]" name="unitPrice[]" placeholder="Unit Price">
              </div>
            </div>
            <div class="col-sm-2">
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="totalPrice[]" name="totalPrice[]" placeholder="Total" readonly>
              </div>
            </div>
          </div>

          <div name="moreObjects">

          </div>

          <div class="form-group form-group-lg">
            <div class="col-sm-offset-10 col-sm-2">
              <button type="button" class="btn btn-success" onclick=""><span class="glyphicon glyphicon-plus"></span> Add another Object</button>
            </div>
          </div>

        </div>
      </div>

      <!-- Vendors and justification -->
      <div class="container">
        <div class="well">






          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="justification">Justification:</label>
            <div class="col-sm-10">
              <select class="form-control" id="justification" name="justification">
                  <option selected="selected">Instruction</option>
                  <option>Research</option>
                  <option>Fabrication</option>
                  <option>Other</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <textarea class="form-control" rows="2" id="justificationText" name="justificationText"></textarea>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="equipmentLocation">Equipment Location:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="equipmentLocation" name="equipmentLocation" >
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="ucrPropertyNumber">UCR Property Number:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="ucrPropertyNumber" name="ucrPropertyNumber">
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="classInstructed">Class Instructed:</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" id="classInstructed" name="classInstructed">
            </div>

            <label class="control-label col-sm-2" for="quote">Quote:</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" id="quote" name="quote">
            </div>
          </div>

        </div>
      </div>

      <!-- Funds -->
      <div class="container">
        <div class="well">

          <div class="form-group form-group-lg">
            <H3 class="col-sm-8"><u>Fund</u></H3>
            <H3 class="col-sm-4"><u>Amount</u></H3>
          </div>

          <div class="form-group form-group-lg">
            <div class="col-sm-8">
              <select class="form-control" id="fund[]" name="fund[]">
                  <option selected="selected">--</option>
                  <!-- TODO Populate with all the funds. -->
              </select>
            </div>
            <div class="col-sm-4">
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="amount[]" name="amount[]" placeholder="Amount">
              </div>
            </div>
          </div>

          <div name="moreFunds">

          </div>

          <div class="form-group form-group-lg">
            <div class="col-sm-offset-10 col-sm-2">
              <button type="button" class="btn btn-success" onclick=""><span class="glyphicon glyphicon-plus"></span> Add Fund</button>
            </div>
          </div>

        </div>
      </div>

      <!-- Attachements -->
      <div class="container">
        <div class="well">
          <H2>Attachments</H2>
          <div class="form-group">
              <input id="attachment" name="attachment[]" type="file" class="file-loading" multiple >
              <script>
                $(document).on('ready', function() {
                  $("#attachment").fileinput({
                    maxFileCount: 10,
                    allowedFileExtensions: ["jpg", "pdf", "png"]
                  });
                });
              </script>
          </div>
        </div>

      </div>

    </form>

  </body>
</HTML>
