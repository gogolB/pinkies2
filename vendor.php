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
        $_value = $_value."<a href=''./vendor.php?reason=edit&vid='.$i_VendorID.' class='btn btn-info' role='button'><span class='glyphicon glyphicon-pencil'></span></a>";
    }
    else
    {
        $_value = $_value."<a href=''./vendor.php?reason=view&vid='.$i_VendorID.' class='btn btn-info' role='button'><span class='glyphicon glyphicon-search'></span></a>";
    }
    $_value = $_value."</td></tr>";
    echo $_value;
  }

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

<!DOCTYPE html>
<html>
   <head>
     <?php printHeaderInfo(); ?>
     <title>ePinkies2 Vendors</title>
     <script type="text/javascript" src="js/functions.js"></script>
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
         <a href="./vendor.php?reason=list" class="btn btn-info" role="button"><span class="glyphicon glyphicon-list-alt"></span> List of Vendor</a>

       </div>
     </div>

   </body>
</html>

<?php elseif($s_reason == 'edit') :?>

<!DOCTYPE html>
<html>
   <head>
     <?php printHeaderInfo(); ?>
     <title>ePinkies2 Vendors</title>
     <script type="text/javascript" src="js/functions.js"></script>
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
         <a href="./vendor.php?reason=list" class="btn btn-info" role="button"><span class="glyphicon glyphicon-list-alt"></span> List of Vendor</a>

       </div>
     </div>

   </body>
</html>

<?php elseif($s_reason == 'view') :?>

<!DOCTYPE html>
<html>
   <head>
     <?php printHeaderInfo(); ?>
     <title>ePinkies2 Vendors</title>
     <script type="text/javascript" src="js/functions.js"></script>
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
         <a href="./vendor.php?reason=list" class="btn btn-info" role="button"><span class="glyphicon glyphicon-list-alt"></span> List of Vendor</a>

       </div>
     </div>

   </body>
</html>
<?php endif;?>
