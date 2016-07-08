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

// Load the Vendor associated with this pinkie.
$_vendor = new Vendor();
$_vendor->i_VendorID = $_pinkie->v_Vendor;
$_vendor->fromDatabase();

// Prints all the objects associated with this pinkie.
function printObjectsTable()
{
    global $_pinkie;
    if(count($_pinkie->a_Objects) == 0)
    {
      echo '<tr>
              <td>No Objects attached to this Pinkie!</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              </tr>';
        return;
    }
    foreach ($_pinkie->a_Objects as $_obj)
    {
        $_po = new PurchaseObject(((int)$_pinkie->i_PinkieID));
        $_po->i_ObjectID = $_obj->i_ObjectID;
        $_po->fromDatabase();
        printf('<tr id="%d">
                      <td>%d</td>
                      <td>%s</td>
                      <td>%s</td>
                      <td>%s</td>
                      <td>%s</td>
                      <td>%.2f</td>
                      <td>%.2f</td>
                    </tr>', (int)$_po->i_ObjectID, (int)$_po->i_Quantity, $_po->s_StockNumber, $_po->s_Description, $_po->s_BC, $_po->s_AccountNumber, $_po->d_UnitPrice, ((float)$_po->d_UnitPrice) * ((int)$_po->i_Quantity));
    }
}

// Prints all the funds associated with this pinkie.
function printFundsTable()
{
  global $_pinkie;
  if(count($_pinkie->a_Expenses) == 0)
  {
    echo '<tr>
            <td>No Funds attached to this Pinkie!</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>';
      return;
  }
  $_fund = 0;
  foreach ($_pinkie->a_Expenses as $_e)
  {
      $_fund = new Fund();
      $_fund->i_FundID = $_e->f_FundID;
      $_fund->fromDatabase();
      printf('<tr id="%s" amt="%s" fname="%s">
                    <td>%s</td>
                    <td>$%.2f</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>$%.2f</td>
                  </tr>', $_e->i_ExpenseID,$_e->d_Amount, $_fund->s_FundName, $_fund->s_FundName, $_e->d_Amount, $_fund->s_Activity, $_fund->s_Fund, $_fund->s_Function, $_fund->s_CostCenter, $_fund->s_ProjectCode,$_fund->s_Balance);
    // printf('<input type="hidden" id="expenseID[]" name="expenseID" value="%s">', $_e->i_ExpenseID);
    // printf('<input type="hidden" id="fund[]" value="%s">', $_e->f_FundID);
    // printf('<input type="hidden" id="amount[]" value="%s">', $_e->d_Amount);
  }
}

function printAllFilesTable()
{
  global $_pinkie;
  if(count($_pinkie->a_Attachments) == 0)
  {
    echo '<tr>
            <td>No Files attached to this Pinkie!</td>
            <td></td>
          </tr>';
      return;
  }
  $_fund = 0;
  foreach ($_pinkie->a_Attachments as $_f)
  {
      echo '<tr>
              <td>'.$_f->s_FilePath.'</td>
              <td><a href="'.$_f->s_FilePath.'" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-download-alt"></span> Download</a></td>
            </tr>';
  }
}
?>
<!DOCTYPE html>
<HTML>
  <HEAD>
    <?php printHeaderInfo(); ?>
    <title>Editing Pinkie</title>
    <link rel="stylesheet" type="text/css" href="./css/jquery.contextMenu.css">';
    <script type="text/javascript" src="./js/pinkie.js"></script>
    <script type="text/javascript" src="./js/pinkieEdit.js"></script>
    <script type="text/javascript" src="./js/jquery.ui.position.js"></script>
    <script type="text/javascript" src="./js/jquery.contextMenu.js"></script>
    <link href="./css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <script src="./js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
    <script src="./js/fileinput.min.js" type="text/javascript"></script>


    <?php if(isSuper()): ?>
      <script type="text/javascript" src="./js/supervisorPinkieReview.js"></script>
    <?php endif; ?>
    <?php if(isAdmin()): ?>
      <script type="text/javascript" src="./js/adminPinkieReview.js"></script>
    <?php endif; ?>
    <?php if(isTrans()): ?>
      <script type="text/javascript" src="./js/transPinkieReview.js"></script>
    <?php endif; ?>
  </HEAD>
  <BODY>

    <div class="jumbotron text-center" style="margin-top:-20px;">
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
        <H4>Here you will be able edit a pinkie object. You may <b><u><i>NOT</i></u></b> modify the pinkie except for adding a BC or an account number to the objects.</H4>

        <!-- Back to Home button. -->
        <a href="./home.php" class="btn btn-success" role="button"><span class="glyphicon glyphicon-home"></span> Back to Home</a>
      </div>
    </div>
      <form class="form-horizontal" role="form" action="onPinkieEdit.php" method="POST" name="editPinkieForm" id="editPinkieForm" >
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
                  <option value="Purchase" <?php isSelected($_pinkie->s_Action, "Purchase"); ?> >Purchase</option>
                  <option value="Payment Request" <?php isSelected($_pinkie->s_Action, "Payment Request"); ?> >Payment Request</option>
                  <option value="Quote" <?php isSelected($_pinkie->s_Action, "Quote"); ?> >Quote</option>
              </select>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="priority">Priority:</label>
            <div class="col-sm-10">
              <select class="form-control" id="priority" name="priority">
                  <option value="Expedite" <?php isSelected($_pinkie->s_Priority, "Expedite"); ?> >Expedite</option>
                  <option value="Urgent" <?php isSelected($_pinkie->s_Priority, "Urgent"); ?> >Urgent</option>
                  <option value="Routine" <?php isSelected($_pinkie->s_Priority, "Routine"); ?> >Routine</option>
                  <option value="Cancel" <?php isSelected($_pinkie->s_Priority, "Cancel"); ?> >Cancel</option>
                  <option value="Hold" <?php isSelected($_pinkie->s_Priority, "Hold"); ?> >Hold</option>
              </select>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="referenceNumber">Reference Number:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="referenceNumber" name="referenceNumber" value="<?php echo $_pinkie->s_ReferenceNumber; ?>">
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="pinkieID">Pinkie Number:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="pinkieID" name="pinkieID" value="<?php echo $i_PinkieID; ?>" readonly>
            </div>
          </div>

        </div>
      </div>

      <!-- Purchase Orders-->
      <div class="container">
        <div class="well">
          <H2><u><b>Purchase Objects</b></u></H2>
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-condensed" id="objectTable" name="objectTable">
              <thead>
                <tr>
                  <th>Quantity</th>
                  <th>Stock Number</th>
                  <th>Description</th>
                  <th>BC</th>
                  <th>Account Number</th>
                  <th>Unit Price</th>
                  <th>Total Price</th>
                </tr>
              </thead>
              <tbody>
                <?php printObjectsTable(); ?>
              </tbody>
            </table>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="subtotal">Subtotal:</label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="subtotal" name="subtotal" readonly value="<?php printf("%.2f",$GLOBALS['_pinkie']->getSubtotal()); ?>">
              </div>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="tax"><input type="checkbox" name="includeTax" id ="includeTax" value="1" style="margin-right:10px;">Tax:</label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="tax" name="tax" readonly value="<?php printf("%.2f",$GLOBALS['_pinkie']->getTax()); ?>">
              </div>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="shipping">Shipping Freight:</label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="shipping" name="shipping" onchange="onShippingChange()" value="<?php printf("%.2f",$_pinkie->d_ShippingFreight); ?>" readonly>
              </div>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="total">Total:</label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="total" name="total" readonly value="<?php printf("%.2f",$GLOBALS['_pinkie']->getTotalExpense()); ?>">
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Funds -->
      <div class="container">
        <div class="well">
          <H2><u><b>Funds</b></u></H2>
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-condensed" id="fundTable" name="fundTable">
              <thead>
                <tr>
                  <th>Fund Name</th>
                  <th>Expense Amount</th>
                  <th>Activity</th>
                  <th>Fund</th>
                  <th>Function</th>
                  <th>Cost Center</th>
                  <th>Project Code</th>
                  <th>Balance In Fund</th>
                </tr>
              </thead>
              <tbody>
                <?php printFundsTable(); ?>
              </tbody>
            </table>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="totalExpense">Total Expense:</label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="totalExpense" name="totalExpense" readonly value="<?php printf("%.2f",$_pinkie->d_Total); ?>">
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Vendor Info -->
      <div class="container">
        <div class="well">
          <H2><u><b>Vendor Information</b></u></H2>
          <!-- From here this is all a direct copy from viewVendor on the vendor.php page. -->
          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="vendorName">Vendor Name:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="vendorName" name="vendorName" value='<?php echo $_vendor->s_VendorName; ?>' readonly>
            </div>
          </div>

          <br>
          <H3><u>Address/Location</u></H3>
          <!-- Vendor Address/Location -->
          <div class="form-group">
            <label class="control-label col-sm-2" for="address">Address:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="address" name="address" value='<?php echo $_vendor->s_Address; ?>' readonly>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-2" for="city">City:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="city" name="city" value='<?php echo $_vendor->s_City; ?>' readonly>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-2" for="state">State:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="state" name="state" value='<?php echo $_vendor->s_State; ?>' readonly>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-2" for="zip">Zip:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="zip" name="zip" value='<?php echo $_vendor->s_Zip; ?>' readonly>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-2" for="country">Country:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="country" name="country" value='<?php echo $_vendor->s_Country; ?>' readonly>
            </div>
          </div>
          <br>
          <!-- Vendor UCR Info -->
          <H3><u>UCR Specific Information</u></H3>
          <div class="form-group">
            <label class="control-label col-sm-2" for="ucrAccountID">UCR Account ID:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="ucrAccountID" name="ucrAccountID" value='<?php echo $_vendor->s_UCRAccountID; ?>' readonly>
            </div>
          </div>
          <br>
          <!-- Vendor Contact info -->
          <H3><u>Contact Info</u></H3>
          <div class="form-group">
            <label class="control-label col-sm-2" for="poc">POC:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="poc" name="poc" value='<?php echo $_vendor->s_POC; ?>' readonly>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-2" for="phoneNumber">Phone Number:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value='<?php echo $_vendor->s_PhoneNumber; ?>' readonly>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-2" for="faxNumber">Fax Number:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="faxNumber" name="faxNumber" value='<?php echo $_vendor->s_FaxNumber; ?>' readonly>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-2" for="internet">Internet:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="internet" name="internet" value='<?php echo $_vendor->s_Internet; ?>' readonly>
            </div>
          </div>

        </div>
      </div>

      <!-- Justification and Extra Info -->
      <div class="container">
        <div class="well">
          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="justification">Justification:</label>
            <div class="col-sm-10">
              <select class="form-control" id="justification" name="justification">
                  <option value="Instruction" <?php isSelected($_pinkie->s_Justification, "Instruction"); ?> >Instruction</option>
                  <option value="Research" <?php isSelected($_pinkie->s_Justification, "Research"); ?> >Research</option>
                  <option value="Fabrication" <?php isSelected($_pinkie->s_Justification, "Fabrication"); ?> >Fabrication</option>
                  <option value="Other" <?php isSelected($_pinkie->s_Justification, "Other"); ?> >Other</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <textarea class="form-control" rows="2" id="justificationText" name="justificationText" style="resize: vertical;"><?php echo $_pinkie->s_JustificationText; ?></textarea>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="equipmentLocation">Equipment Location:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="equipmentLocation" name="equipmentLocation"  value="<?php echo $_pinkie->s_EquipmentLocation; ?>">
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="ucrPropertyNumber">UCR Property Number:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="ucrPropertyNumber" name="ucrPropertyNumber" value="<?php echo $_pinkie->s_UCRPropertyTag; ?>">
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="classInstructed">Class Instructed:</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" id="classInstructed" name="classInstructed" value="<?php echo $_pinkie->s_classInstructed; ?>">
            </div>

            <label class="control-label col-sm-2" for="quote">Quote:</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" id="quote" name="quote" value="<?php echo $_pinkie->s_Quote; ?>">
            </div>
          </div>
        </div>
      </div>

      <!-- Files -->
      <div class="container">
        <div class="well">
          <H2>Attachments</H2>
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-condensed">
              <thead>
                <tr>
                  <th>File Path</th>
                  <th>View</th>
                </tr>
              </thead>
              <tbody>
                <?php printAllFilesTable(); ?>
              </tbody>
            </table>
          </div>
          <H2>Add more Files</H2>
          <div class="form-group">
              <input id="attachment" name="attachment[]" type="file" class="file-loading" multiple data-show-upload="false" data-show-caption="true">
              <script>
                $(document).on('ready', function() {
                  $("#attachment").fileinput({
                    maxFileCount: <?php echo MAX_ATTACHMENTS; ?>,
                    allowedFileExtensions: ["jpg", "pdf", "png", "jpeg"]
                  });
                });
              </script>
          </div>
        </div>
      </div>

      <!-- Update -->
      <div class="container">
        <div class="well">
          <div class="form-group form-group-lg">
            <div class="col-sm-offset-8 col-sm-4">
                <button type="button" class="btn btn-success" onclick="onEdit(<?php echo $_pinkie->i_PinkieID; ?>)"><span class="glyphicon glyphicon-saved"></span> Update</button>
            </div>
          </div>
        </div>
      </div>

    </form>

    <nav class="context-menu" id="fundRightClickMenu">
      <ul class="context-menu__items">
        <li class="context-menu__item">
          <a href="#" class="context-menu__link">
            <i class="fa fa-eye"></i> View Task
          </a>
        </li>
        <li class="context-menu__item">
          <a href="#" class="context-menu__link">
            <i class="fa fa-edit"></i> Edit Task
          </a>
        </li>
        <li class="context-menu__item">
          <a href="#" class="context-menu__link">
            <i class="fa fa-times"></i> Delete Task
          </a>
        </li>
      </ul>
    </nav>

    <!-- Modal Windows -->
<div id="addFundModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add a Fund</h4>
      </div>

      <div class="modal-body container-fluid">
        <div class="form-group">
            <div class="col-md-12 container-fluid">
              <h4>Fund Name</h4>
              <select class="form-control chosen-select-no-results" id="newFund" name="newFund">
                  <?php printFunds(); ?>
              </select>
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Amount</h4>
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="newFundTotal" name="newFundTotal">
              </div>
            </div>

        </div>
      </div>

      <div class="modal-footer">
        <div class="row">
          <button type="button" class="btn btn-primary" onclick="onAddFund()">Add this fund</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-right:10px;">Close</button>
        </div>
      </div>

    </div>

  </div>
</div>

<div id="editFundModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Editing this fund</h4>
      </div>

      <div class="modal-body container-fluid">
        <div class="form-group">
            <div class="col-md-12 container-fluid">
              <h4>Old Fund Name</h4>
              <input type="text" class="form-control" id="currentFund" name="currentFund" readonly>

              <h4>New Fund Name</h4>
              <select data-placeholder="Choose a Country..." class="form-control chosen-select-no-results" id="editFund" name="editFund">
                  <?php printFunds(); ?>
              </select>
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Amount</h4>
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="editTotal" name="editTotal">
              </div>
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Expense ID (Internal)</h4>
              <input type="text" class="form-control" id="editExpenseID" name="editExpenseID" disabled>
            </div>

        </div>
      </div>

      <div class="modal-footer">
        <div class="row">
          <button type="button" class="btn btn-success" onclick="onEditFund()">Confirm changes</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-right:10px;">Close</button>
        </div>
      </div>

    </div>

  </div>
</div>

<div id="deleteFundModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Are you sure you want to remove this fund?</h4>
      </div>

      <div class="modal-body container-fluid">
        <div class="form-group">
            <div class="col-md-12 container-fluid">
              <h4>Fund Name</h4>
              <input type="text" class="form-control" id="deleteFundName" name="deleteFundName" disabled>
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Amount</h4>
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="deleteFundTotal" name="deleteFundTotal" disabled>
              </div>
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Expense ID (Internal)</h4>
              <input type="text" class="form-control" id="deleteExpenseID" name="deleteExpenseID" disabled>
            </div>

        </div>
      </div>

      <div class="modal-footer">
        <div class="row">
          <button type="button" class="btn btn-danger" onclick="onDeleteFund()">Remove fund</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-right:10px;">Close</button>
        </div>
      </div>

    </div>

  </div>
</div>


<!-- object editing modals -->
<div id="addObjectModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add a Purchase Object</h4>
      </div>

      <div class="modal-body container-fluid">
        <div class="form-group">
            <div class="col-md-12 container-fluid">
              <h4>Purchase Object Description</h4>
              <input type="text" class="form-control" id="newPurchaseObjectDescription" name="newPurchaseObjectDescription">
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Unit Price</h4>
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="newPurchaseObjectUnitPrice" name="newPurchaseObjectUnitPrice">
              </div>
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Quantity</h4>
              <input type="text" class="form-control" id="newPurchaseObjectQuantity" name="newPurchaseObjectQuantity">
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Total Price</h4>
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="newPurchaseObjectTotalPrice" name="newPurchaseObjectTotalPrice" readonly>
              </div>
            </div>

        </div>
      </div>

      <div class="modal-footer">
        <div class="row">
          <button type="button" class="btn btn-primary" onclick="onAddObject()">Add this Object</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-right:10px;">Close</button>
        </div>
      </div>

    </div>

  </div>
</div>

<div id="editObjectModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Editing this Purchase Object</h4>
      </div>

      <div class="modal-body container-fluid">
        <div class="form-group">
            <div class="col-md-12 container-fluid">
              <h4>Purchase Object Description</h4>
              <input type="text" class="form-control" id="editPurchaseObjectDescription" name="editPurchaseObjectDescription">
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Unit Price</h4>
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="editPurchaseObjectUnitPrice" name="editPurchaseObjectUnitPrice">
              </div>
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Quantity</h4>
              <input type="text" class="form-control" id="editPurchaseObjectQuantity" name="editPurchaseObjectQuantity">
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Total Price</h4>
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="editPurchaseObjectTotalPrice" name="editPurchaseObjectTotalPrice">
              </div>
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Purchase Object ID (Internal)</h4>
              <input type="text" class="form-control" id="editPurchaseObjectID" name="editPurchaseObjectID" disabled>
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Account Number</h4>
              <input type="text" class="form-control" id="editPurchaseObjectAccountNumber" name="editPurchaseObjectAccountNumber">
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Stock Number</h4>
              <input type="text" class="form-control" id="editPurchaseObjectStockNumber" name="editPurchaseObjectStockNumber">
            </div>

            <div class="col-md-12 container-fluid">
              <h4>BC</h4>
              <input type="text" class="form-control" id="editPurchaseObjectBC" name="editPurchaseObjectBC">
            </div>

        </div>
      </div>

      <div class="modal-footer">
        <div class="row">
          <button type="button" class="btn btn-success" onclick="onEditObject()">Confirm changes</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-right:10px;">Close</button>
        </div>
      </div>

    </div>

  </div>
</div>

<div id="deleteObjectModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Are you sure you want to remove this Purchase Object?</h4>
      </div>

      <div class="modal-body container-fluid">
        <div class="form-group">
            <div class="col-md-12 container-fluid">
              <h4>Purchase Object Description</h4>
              <input type="text" class="form-control" id="deletePurchaseObjectDescription" name="deletePurchaseObjectDescription" disabled>
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Unit Price</h4>
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="deletePurchaseObjectUnitPrice" name="deletePurchaseObjectUnitPrice" disabled>
              </div>
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Quantity</h4>
              <input type="text" class="form-control" id="deletePurchaseObjectQuantity" name="deletePurchaseObjectQuantity" disabled>
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Total Price</h4>
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="deletePurchaseObjectTotalPrice" name="deletePurchaseObjectTotalPrice" disabled>
              </div>
            </div>

            <div class="col-md-12 container-fluid">
              <h4>Purchase Object ID (Internal)</h4>
              <input type="text" class="form-control" id="deletePurchaseObjectID" name="deletePurchaseObjectID" disabled>
            </div>

        </div>
      </div>

      <div class="modal-footer">
        <div class="row">
          <button type="button" class="btn btn-danger" onclick="onDeleteObject()">Remove Object</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-right:10px;">Close</button>
        </div>
      </div>

    </div>

  </div>
</div>

  </BODY>
</HTML>
