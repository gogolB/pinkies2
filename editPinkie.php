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
    foreach ($_pinkie->a_Objects as $_obj)
    {
      printf('<div class="form-group form-group-lg">
        <div class="col-sm-1">
          <input type="text" class="form-control" id="quantity[]" name="quantity[]" placeholder="Quantity" value="%d" readonly>
        </div>
        <div class="col-sm-2">
          <input type="text" class="form-control" id="stockNumber[]" name="stockNumber[]" placeholder="Stock Number" value="%s" readonly>
        </div>
        <div class="col-sm-3">
          <input type="text" class="form-control" id="description[]" name="description[]" placeholder="Description" value="%s" readonly>
        </div>
        <div class="col-sm-1">
          <input type="text" class="form-control" id="bc[]" name="bc[]" placeholder="BC" value="%s">
        </div>
        <div class="col-sm-1">
          <input type="text" class="form-control" id="accountNumber[]" name="accountNumber[]" placeholder="Account Number" value="%s">
        </div>
        <div class="col-sm-2">
          <div class="input-group">
            <span class="input-group-addon">$</span>
            <input type="text" class="form-control" id="unitPrice[]" name="unitPrice[]" placeholder="Unit Price" value="%.2f" readonly>
          </div>
        </div>
        <div class="col-sm-2">
          <div class="input-group">
            <span class="input-group-addon">$</span>
            <input type="text" class="form-control" id="totalPrice[]" name="totalPrice[]" placeholder="Total" value="%.2f" readonly>
          </div>
        </div>
      </div>
      <input type="hidden" id="objectID[]" name="objectID[]" value="%d">', $_obj->i_Quantity, $_obj->s_StockNumber, $_obj->s_Description, $_obj->s_BC, $_obj->s_AccountNumber, $_obj->d_UnitPrice, $_obj->d_UnitPrice*$_obj->i_Quantity, $_obj->i_ObjectID);
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
      printf('<tr>
                    <td>%s</td>
                    <td>$%.2f</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>$%.2f</td>
                  </tr>', $_fund->s_FundName, $_e->d_Amount, $_fund->s_Activity, $_fund->s_Fund, $_fund->s_Function, $_fund->s_CostCenter, $_fund->s_ProjectCode,$_fund->s_Balance);
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
    <title>New Pinkie</title>
    <script type="text/javascript" src="./js/pinkie.js"></script>
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
        <H4>Here you will be able edit a pinkie object. You many <b><u><i>NOT</i></u></b> modify the pinkie except for adding a BC or an account number to the objects.</H4>

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
              <select class="form-control" id="action" name="action" readonly>
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
              <select class="form-control" id="priority" name="priority" readonly>
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
              <input type="text" class="form-control" id="referenceNumber" name="referenceNumber" value="<?php echo $_pinkie->s_ReferenceNumber; ?>" readonly>
            </div>
          </div>

        </div>
      </div>

      <!-- Purchase Orders-->
      <div class="container">
        <div class="well">

          <div class="table-responsive">

            <div class="form-group form-group-lg">
              <H3 class="col-sm-1"><u>Quantity</u></H3>
              <H3 class="col-sm-2"><u>Stock Number</u></H3>
              <H3 class="col-sm-3"><u>Description</u></H3>
              <H3 class="col-sm-1"><u>BC</u></H3>
              <H3 class="col-sm-1"><u>Account Number</u></H3>
              <H3 class="col-sm-2"><u>Unit Price</u></H3>
              <H3 class="col-sm-2"><u>Total Price</u></H3>
            </div>

            <?php printObjectsTable(); ?>

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
            <label class="control-label col-sm-2" for="tax">Tax:</label>
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

          <div class="table-responsive">
            <table class="table table-bordered table-hover table-condensed">
              <thead>
                <tr>
                  <th>Fund Name</th>
                  <th>Expense Amount</th>
                  <th>Acivity</th>
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
              <select class="form-control" id="justification" name="justification" readonly>
                  <option value="Instruction" <?php isSelected($_pinkie->s_Justification, "Instruction"); ?> >Instruction</option>
                  <option value="Research" <?php isSelected($_pinkie->s_Justification, "Research"); ?> >Research</option>
                  <option value="Fabrication" <?php isSelected($_pinkie->s_Justification, "Fabrication"); ?> >Fabrication</option>
                  <option value="Other" <?php isSelected($_pinkie->s_Justification, "Other"); ?> >Other</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <textarea class="form-control" rows="2" id="justificationText" name="justificationText" readonly><?php echo $_pinkie->s_JustificationText; ?></textarea>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="equipmentLocation">Equipment Location:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="equipmentLocation" name="equipmentLocation"  value="<?php echo $_pinkie->s_EquipmentLocation; ?>" readonly>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="ucrPropertyNumber">UCR Property Number:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="ucrPropertyNumber" name="ucrPropertyNumber" value="<?php echo $_pinkie->s_UCRPropertyTag; ?>" readonly>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="classInstructed">Class Instructed:</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" id="classInstructed" name="classInstructed" value="<?php echo $_pinkie->s_classInstructed; ?>" readonly>
            </div>

            <label class="control-label col-sm-2" for="quote">Quote:</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" id="quote" name="quote" value="<?php echo $_pinkie->s_Quote; ?>" readonly>
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
        </div>
      </div>

      <!-- Approve or Reject -->
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

  </BODY>
</HTML>
