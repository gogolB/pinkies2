<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
include_once 'includes/vendor_object.php';
secureSessionStart();

// Check why we are here on this page.
$s_reason='';
if(isset($_GET['reason']))
{
  $s_reason=$_GET['reason'];
}
else
{
  // If nothing was set, by default create the list page.
  $s_reason='list';
}


// Functions
function printAllVendors()
{
  $_db = getMysqli();
  $_stmt = $_db->prepare("SELECT VendorName, VendorID FROM Vendors");
  $_stmt->execute();

  $_stmt->bind_result($s_VendorName, $i_VendorID);
  while($_stmt->fetch())
  {
    $_value = "<tr>
                <td>
                  ".$s_VendorName."
                </td>
                <td>";
    if(isAdmin())
    {
        $_value = $_value."<a href='./vendor.php?reason=edit&vid=".$i_VendorID."' class='btn btn-info' role='button'><span class='glyphicon glyphicon-pencil'></span> Update/Change</a> ";
    }

    {
        $_value = $_value."<a href='./vendor.php?reason=view&vid=".$i_VendorID."' class='btn btn-primary' role='button'><span class='glyphicon glyphicon-search'></span> View</a>";
    }
    $_value = $_value."</td></tr>";
    echo $_value;
  }
  $_stmt->free_result();
  $_db->close();
}


?>
<?php if($s_reason == 'list') :?>
<!DOCTYPE html>
<html>
   <head>
     <?php printHeaderInfo(); ?>
     <title>ePinkies2 Vendors</title>
     <script type="text/javascript" src="js/functions.js"></script>
     <script type="text/javascript" src="js/vendor.js"></script>
   </head>

   <body>

     <div class="jumbotron text-center">
       <H1>ePinkies 2</H1>
       <h2>University of California at Riverside</h2>
       <h3>Department of Electrical and Computer Engineering</h3>
     </div>

     <!-- Sort of the welcome banner.-->
     <div class="container">
       <div class="well">
         <H2>
           Welcome the List of Vendors avaliable to ePinkies2.
         </H2>

         <!-- Only avaliable to those who can create new Vendors.-->
         <?php if(isAdmin()): ?>
           <a href="./vendor.php?reason=add" class="btn btn-success" role="button"><span class="glyphicon glyphicon-plus"></span> Add a new Vendor</a>
         <?php endif; ?>

         <!-- Back to Home button. -->
         <a href="./home.php" class="btn btn-success" role="button"><span class="glyphicon glyphicon-home"></span> Back to Home</a>

       </div>
     </div>

     <!-- The list of Vendors -->
     <div class="container">
       <div class="well">
         <table class="table table-hover">
           <thead>
             <tr>
               <th>Vendor Name</th>
               <th>Options</th>
             </tr>
           </thead>
           <tbody>
             <?php printAllVendors(); ?>
           </tbody>
         </table>
       </div>
     </div>

   </body>
</html>

<?php elseif($s_reason == 'add') :?>
<?php
  // ---------------------------------------------------------------------------
  // Here the user can add a new vendor to ePinkies2.
  // ---------------------------------------------------------------------------

  // Security Check. Make sure that this person is allowed to add a new vendor.
  if(!isAdmin())
  {
    header("Location: ./vendor.php?reason=list");
  }
?>
<!DOCTYPE html>
<html>
   <head>
     <?php printHeaderInfo(); ?>
     <title>ePinkies2 Vendors</title>
     <script type="text/javascript" src="js/functions.js"></script>
     <script type="text/javascript" src="js/vendor.js"></script>
   </head>

   <body>

     <div class="jumbotron text-center">
       <H1>ePinkies 2</H1>
       <h2>University of California at Riverside</h2>
       <h3>Department of Electrical and Computer Engineering</h3>
     </div>

     <!-- Sort of the welcome banner.-->
     <div class="container">
       <div class="well">
         <H2>
           Adding a new Vendor to ePinkies2.
         </H2>

         <!-- Back to Home button. -->
         <a href="./home.php" class="btn btn-success" role="button"><span class="glyphicon glyphicon-home"></span> Back to Home</a>
         <!-- Back to the list button. -->
         <a href="./vendor.php?reason=list" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-list-alt"></span> List of Vendors</a>

       </div>
     </div>

     <!-- Form to add a new Vendor.-->
     <div class="container">
       <div class="well well-lg">

         <form class="form-horizontal" role="form" action="onVendorSubmit.php" method="POST" name="addNewVendorForm">

           <div class="form-group form-group-lg">
             <label class="control-label col-sm-2" for="vendorName">Vendor Name:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="vendorName" name="vendorName" >
             </div>
           </div>

           <br>
           <H3><u>Address/Location</u></H3>
           <!-- Address/Location -->
           <div class="form-group">
             <label class="control-label col-sm-2" for="address">Address:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="address" name="address">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="city">City:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="city" name="city">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="state">State:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="state" name="state">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="zip">Zip:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="zip" name="zip">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="country">Country:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="country" name="country">
             </div>
           </div>
           <br>
           <!-- UCR Info -->
           <H3><u>UCR Specific Information</u></H3>
           <div class="form-group">
             <label class="control-label col-sm-2" for="ucrAccountID">UCR Account ID:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="ucrAccountID" name="ucrAccountID">
             </div>
           </div>
           <br>
           <!-- Contact info -->
           <H3><u>Contact Info</u></H3>
           <div class="form-group">
             <label class="control-label col-sm-2" for="poc">POC:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="poc" name="poc">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="phoneNumber">Phone Number:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="phoneNumber" name="phoneNumber">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="faxNumber">Fax Number:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="faxNumber" name="faxNumber">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="internet">Internet:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="internet" name="internet">
             </div>
           </div>

           <div class="form-group form-group-lg">
             <div class="col-sm-offset-2 col-sm-10">
               <button type="button" class="btn btn-success" onclick="submitNewVendor(this.form)">Add new Vendor</button>
             </div>
           </div>
         </form>

       </div>
     </div>

   </body>
</html>

<?php elseif($s_reason == 'edit') :?>
<?php
    // -------------------------------------------------------------------------
    // The user can edit a vendor here.
    // -------------------------------------------------------------------------

    // Error catching.
    if(!isset($_GET['vid']))
    {
      header("Location: ./vendor.php?reason=list");
    }

    // Get the vendorID
    $_vid = $_GET['vid'];

    // Security Check. Make sure that this person is allowed to edit a vendor.
    if(!isAdmin())
    {
      header("Location: ./vendor.php?reason=view&vid=".$_vid);
    }

    $_vendor = new Vendor();
    $_vendor->i_VendorID = (int)$_vid;
    $_vendor->fromDatabase();

?>
<!DOCTYPE html>
<html>
   <head>
     <?php printHeaderInfo(); ?>
     <title>ePinkies2 Vendors</title>
     <script type="text/javascript" src="js/functions.js"></script>
     <script type="text/javascript" src="js/vendor.js"></script>
   </head>

   <body>

     <div class="jumbotron text-center">
       <H1>ePinkies 2</H1>
       <h2>University of California at Riverside</h2>
       <h3>Department of Electrical and Computer Engineering</h3>
     </div>

     <!-- Sort of the welcome banner.-->
     <div class="container">
       <div class="well">
         <H2>
           Editing a Vendor.
         </H2>

         <!-- Back to Home button. -->
         <a href="./home.php" class="btn btn-success" role="button"><span class="glyphicon glyphicon-home"></span> Back to Home</a>
         <!-- Back to the list button. -->
         <a href="./vendor.php?reason=list" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-list-alt"></span> List of Vendors</a>

       </div>
     </div>

     <div class="container">
       <div class="well well-lg">

         <form class="form-horizontal" role="form" action="onVendorSubmit.php" method="POST">

           <div class="form-group form-group-lg">
             <label class="control-label col-sm-2" for="vendorName">Vendor Name:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="vendorName" name="vendorName" value='<?php echo $_vendor->s_VendorName; ?>' >
             </div>
           </div>

           <br>
           <H3><u>Address/Location</u></H3>
           <!-- Address/Location -->
           <div class="form-group">
             <label class="control-label col-sm-2" for="address">Address:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="address" name="address" value='<?php echo $_vendor->s_Address; ?>' >
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="city">City:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="city" name="city" value='<?php echo $_vendor->s_City; ?>' >
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="state">State:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="state" name="state" value='<?php echo $_vendor->s_State; ?>' >
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="zip">Zip:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="zip" name="zip" value='<?php echo $_vendor->s_Zip; ?>' >
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="country">Country:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="country" name="country" value='<?php echo $_vendor->s_Country; ?>' >
             </div>
           </div>
           <br>
           <!-- UCR Info -->
           <H3><u>UCR Specific Information</u></H3>
           <div class="form-group">
             <label class="control-label col-sm-2" for="ucrAccountID">UCR Account ID:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="ucrAccountID" name="ucrAccountID" value='<?php echo $_vendor->s_UCRAccountID; ?>' >
             </div>
           </div>
           <br>
           <!-- Contact info -->
           <H3><u>Contact Info</u></H3>
           <div class="form-group">
             <label class="control-label col-sm-2" for="poc">POC:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="poc" name="poc" value='<?php echo $_vendor->s_POC; ?>' >
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="phoneNumber">Phone Number:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value='<?php echo $_vendor->s_PhoneNumber; ?>' >
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="faxNumber">Fax Number:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="faxNumber" name="faxNumber" value='<?php echo $_vendor->s_FaxNumber; ?>' >
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="internet">Internet:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="internet" name="internet" value='<?php echo $_vendor->s_Internet; ?>' >
             </div>
           </div>

         <div class="form-group form-group-lg">
           <div class="col-sm-offset-2 col-sm-10">
             <button type="button" class="btn btn-success" onclick="updateVendor(this.form, <?php echo $_vendor->i_VendorID; ?>)">Update this Vendor</button>
           </div>
         </div>

       </form>

       </div>
     </div>


   </body>
</html>

<?php elseif($s_reason == 'view') :?>
<?php
  // ---------------------------------------------------------------------------
  // The user can view all the variables associated with a single vendor.
  // ---------------------------------------------------------------------------

  // Error catching.
  if(!isset($_GET['vid']))
  {
    header("Location: ./vendor.php?reason=list");
  }

  // Get the vendorID
  $_vid = $_GET['vid'];

  $_vendor = new Vendor();
  $_vendor->i_VendorID = $_vid;
  $_vendor->fromDatabase();
?>

<!DOCTYPE html>
<html>
   <head>
     <?php printHeaderInfo(); ?>
     <title>ePinkies2 Vendors</title>
     <script type="text/javascript" src="js/functions.js"></script>
     <script type="text/javascript" src="js/vendor.js"></script>
   </head>

   <body>

     <div class="jumbotron text-center">
       <H1>ePinkies 2</H1>
       <h2>University of California at Riverside</h2>
       <h3>Department of Electrical and Computer Engineering</h3>
     </div>

     <!-- Sort of the welcome banner.-->
     <div class="container">
       <div class="well">
         <H2>
           Viewing a Vendor.
         </H2>

         <!-- Back to Home button. -->
         <a href="./home.php" class="btn btn-success" role="button"><span class="glyphicon glyphicon-home"></span> Back to Home</a>
         <!-- Back to the list button. -->
         <a href="./vendor.php?reason=list" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-list-alt"></span> List of Vendors</a>

       </div>
     </div>

     <div class="container">
       <div class="well well-lg">

         <form class="form-horizontal" role="form" action="" method="POST">

           <div class="form-group form-group-lg">
             <label class="control-label col-sm-2" for="vendorName">Vendor Name:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="vendorName" name="vendorName" value='<?php echo $_vendor->s_VendorName; ?>' readonly>
             </div>
           </div>

           <br>
           <H3><u>Address/Location</u></H3>
           <!-- Address/Location -->
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
           <!-- UCR Info -->
           <H3><u>UCR Specific Information</u></H3>
           <div class="form-group">
             <label class="control-label col-sm-2" for="ucrAccountID">UCR Account ID:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="ucrAccountID" name="ucrAccountID" value='<?php echo $_vendor->s_UCRAccountID; ?>' readonly>
             </div>
           </div>
           <br>
           <!-- Contact info -->
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
         </form>

       </div>
     </div>

   </body>
</html>
<?php endif;?>
