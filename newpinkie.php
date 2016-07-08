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
    <script type="text/javascript" src="./js/pinkie.js"></script>
    <link href="./css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <script src="./js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
    <script src="./js/fileinput.min.js" type="text/javascript"></script>
    <script>
      var addObjectInput = '          <div class="form-group form-group-lg">\
                  <div class="col-sm-1">\
                    <input type="text" class="form-control" id="quantity[]" name="quantity[]" placeholder="Quantity" onchange="onObjectChange()">\
                  </div>\
                  <div class="col-sm-2">\
                    <input type="text" class="form-control" id="stockNumber[]" name="stockNumber[]" placeholder="Stock Number">\
                  </div>\
                  <div class="col-sm-5">\
                    <input type="text" class="form-control" id="description[]" name="description[]" placeholder="Description">\
                  </div>\
                  <div class="col-sm-2">\
                    <div class="input-group">\
                      <span class="input-group-addon">$</span>\
                      <input type="text" class="form-control" id="unitPrice[]" name="unitPrice[]" placeholder="Unit Price" onchange="onObjectChange()">\
                    </div>\
                  </div>\
                  <div class="col-sm-2">\
                    <div class="input-group">\
                      <span class="input-group-addon">$</span>\
                      <input type="text" class="form-control" id="totalPrice[]" name="totalPrice[]" placeholder="Total" readonly>\
                    </div>\
                  </div>\
                </div>';

      var addFundInput = '          <div class="form-group form-group-lg">\
                  <div class="col-sm-8">\
                    <select data-placeholder="Choose Another Fund..."class="form-control chosen-select-no-results" id="fund[]" name="fund[]">\
                        <?php printFunds(); ?>\
                    </select>\
                  </div>\
                  <div class="col-sm-4">\
                    <div class="input-group">\
                      <span class="input-group-addon">$</span>\
                      <input type="text" class="form-control" id="amount[]" name="amount[]" placeholder="Amount" onchange="onExpenseChange();">\
                    </div>\
                  </div>\
                </div>';

      var maxObject = <?php echo MAX_OBJECTS; ?>;
      var minObject = 1;
      var currentObject = minObject;

      var maxFunds = <?php echo MAX_FUNDS; ?>;
      var minFunds = 1;
      var currentFund = minFunds;

      $(window).load(function(){
        $(".chosen-select-no-results").chosen()
      });

      // Adds a object to the display.
      function addAObject()
      {
        if(currentObject < maxObject)
        {
          var div = document.createElement("div");
              div.id = 'PurchaseObject'+ currentObject;
              div.innerHTML = addObjectInput;
              document.getElementById('moreObjects').appendChild(div);
              currentObject++;
              return false;
        }
        else
        {
          alert("Too many objects with this Pinkie.");
        }
      }

      // Adds a fund to the display.
      function addAFund()
      {
        if(currentFund < maxFunds)
        {
              var div = document.createElement("div");
              div.id = 'Expense'+ currentFund;
              div.innerHTML = addFundInput;
              document.getElementById('moreFunds').appendChild(div);
              currentFund++;
              $('.chosen-select-no-results').chosen('destroy').chosen();
              return false;
        }
        else
        {
          alert("Too many funds with this Pinkie.");
        }
      }

      // Removes an object from the display.
      function removeAObject()
      {
        if(currentObject > minObject)
        {
          cnt = currentObject-1;
          element = document.getElementById('PurchaseObject'+cnt);
          element.parentNode.removeChild(element);
          currentObject--;
          return false;
        }
        else
        {
          alert('Minimum '+minObject+' objects are required.');
          return false;
        }
      }

      // Removes a Fund from the display.
      function removeAFund()
      {
        if(currentFund > minFunds)
        {
          cnt = currentFund-1;
          element = document.getElementById('Expense'+cnt);
          element.parentNode.removeChild(element);
          currentFund--;
          $('.chosen-select-no-results').chosen('destroy').chosen();
          return false;
        }
        else
        {
          alert('Minimum '+minFunds+' funds are required.');
          return false;
        }
      }

    </script>
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

        <!-- Back to Home button. -->
        <a href="./home.php" class="btn btn-success" role="button"><span class="glyphicon glyphicon-home"></span> Back to Home</a>
      </div>
    </div>

    <form class="form-horizontal" role="form" action="onPinkieSubmit.php" method="POST" name="newPinkieForm" id="newPinkieForm" enctype="multipart/form-data">

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
            <label class="control-label col-sm-2" for="submittedBy">Requested By:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="submittedBy" name="submittedBy" placeholder="Submitter" value="<?php echo getName(); ?>" readonly>
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
                  <option value="Reimbursement">Reimbursement</option>
                  <option selected="selected" value="Purchase">Purchase</option>
                  <option value="Payment Request">Payment Request</option>
                  <option value="Quote">Quote</option>
              </select>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="priority">Priority:</label>
            <div class="col-sm-10">
              <select class="form-control" id="priority" name="priority">
                  <option value="Expedite">Expedite</option>
                  <option value="Urgent">Urgent</option>
                  <option selected="selected" value="Routine">Routine</option>
                  <option value="Cancel">Cancel</option>
                  <option value="Hold">Hold</option>
              </select>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="referenceNumber">Reference Number:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="referenceNumber" name="referenceNumber">
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
              <input type="text" class="form-control" id="quantity[]" name="quantity[]" placeholder="Quantity" onchange="onObjectChange()">
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
                <input type="text" class="form-control" id="unitPrice[]" name="unitPrice[]" placeholder="Unit Price" onchange="onObjectChange()">
              </div>
            </div>
            <div class="col-sm-2">
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="totalPrice[]" name="totalPrice[]" placeholder="Total" readonly>
              </div>
            </div>
          </div>

          <div id="moreObjects">

          </div>

          <div class="form-group form-group-lg">
            <div class="col-sm-offset-8 col-sm-4">
              <button type="button" class="btn btn-success" onclick="addAObject();"><span class="glyphicon glyphicon-plus"></span> Add another Object</button>
              <button type="button" class="btn btn-primary" onclick="removeAObject();"><span class="glyphicon glyphicon-minus"></span> Remove a Object</button>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="subtotal">Subtotal:</label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="subtotal" name="subtotal" readonly>
              </div>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="tax">Tax:</label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="tax" name="tax" readonly>
              </div>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="shipping">Shipping Freight:</label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="shipping" name="shipping" onchange="onShippingChange()">
              </div>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="total">Total:</label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="total" name="total" readonly>
              </div>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="typeOfPurchase">What is this:</label>
            <div class="col-sm-10">
              <select class="form-control" id="typeOfPurchase" name="typeOfPurchase">
                  <option selected="selected" value="Computer Software/Hardware">Computer Software/Hardware</option>
                  <option value="Chemical">Chemical</option>
                  <option value="Other">Other (Please Describe Below)</option>
              </select>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="typeOfPurchaseOther">Please Describe:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="typeOfPurchaseOther" name="typeOfPurchaseOther">
            </div>
          </div>

        </div>
      </div>

      <!-- Vendors and justification -->
      <div class="container">
        <div class="well">

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="vendor">Vendor:</label>
            <div class="col-sm-10">
              <select data-placeholder="Choose a Vendor..." class="form-control chosen-select-no-results" id="vendor" name="vendor">
                  <?php printVendors(); ?>
              </select>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="justification">Justification:</label>
            <div class="col-sm-10">
              <select class="form-control" id="justification" name="justification">
                  <option selected="selected" value="Instruction">Instruction</option>
                  <option value="Research">Research</option>
                  <option value="Fabrication">Fabrication</option>
                  <option value="Other">Other</option>
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
              <select data-placeholder="Choose a fund..."class="form-control chosen-select-no-results" id="fund[]" name="fund[]">
                  <?php printFunds(); ?>
              </select>
            </div>
            <div class="col-sm-4">
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" id="amount[]" name="amount[]" placeholder="Amount" onchange="onExpenseChange();">
              </div>
            </div>
          </div>

          <div id="moreFunds">

          </div>

          <div class="form-group form-group-lg">
            <div class="col-sm-offset-8 col-sm-4">
              <button type="button" class="btn btn-success pull-right" onclick="addAFund()"><span class="glyphicon glyphicon-plus"></span></button>
              <button type="button" class="btn btn-primary pull-right" onclick="removeAFund()"><span class="glyphicon glyphicon-minus"></span></button>
            </div>
          </div>

          <div class="form-group form-group-lg">
            <label class="control-label col-sm-2" for="totalExpense">Total Expense:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="totalExpense" name="totalExpense" readonly>
            </div>
          </div>

        </div>
      </div>

      <!-- Attachements -->
      <div class="container">
        <div class="well">
          <H2>Attachments</H2>
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

      <!-- Submit -->
      <div class="container">
        <div class="well">
          <div class="form-group form-group-lg">
            <div class="col-sm-offset-10 col-sm-2">
              <button type="button" class="btn btn-success" onclick="onNewSubmit();"><span class="glyphicon glyphicon-indent-left"></span> Submit this Pinkie</button>
            </div>
          </div>
        </div>
      </div>

    </form>

  </body>
</HTML>
