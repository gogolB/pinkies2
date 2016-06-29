<?php
include_once './includes/functions.php';
include_once './includes/sessionFunctions.php';
include_once './includes/fund_object.php';
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
function printAllFunds()
{
  $_db = getMysqli();
  if(canEditFunds())
  {
      $_stmt = $_db->prepare("SELECT FundName, FundID, Balance, Timestamp FROM Funds");
  }
  else
  {
      $_stmt = $_db->prepare("SELECT FundName, FundID, Balance, Timestamp FROM Funds WHERE Active=1");
  }
  $_stmt->execute();

  $_stmt->bind_result($s_FundName, $i_FundID, $s_Balance, $s_Timestamp);
  while($_stmt->fetch())
  {
    $_value = "<tr>
                <td>".$s_FundName."</td>
                <td>".$s_Balance."</td>
                <td>".$s_Timestamp."</td>
                <td>";
    if(canEditFunds())
    {
        $_value = $_value."<a href='./fund.php?reason=edit&fid=".$i_FundID."' class='btn btn-info' role='button'><span class='glyphicon glyphicon-pencil'></span> Update/Change</a> ";
    }

    {
        $_value = $_value."<a href='./fund.php?reason=view&fid=".$i_FundID."' class='btn btn-primary' role='button'><span class='glyphicon glyphicon-search'></span> View</a>";
    }
    $_value = $_value."</td></tr>";
    echo $_value;
  }
  $_stmt->free_result();
  $_db->close();
}


function isSelected($f)
{
    if($f->b_Active == TRUE)
    {
        echo 'selected="selected"';
    }
}

function isNotSelected($f)
{
    if($f->b_Active == FALSE)
    {
        echo 'selected="selected"';
    }
}

?>
<?php if($s_reason == 'list') :?>
<!DOCTYPE html>
<html>
   <head>
     <?php printHeaderInfo(); ?>
     <title>ePinkies2 Funds</title>
     <script type="text/javascript" src="js/functions.js"></script>
     <script type="text/javascript" src="js/fund.js"></script>
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
           Welcome the List of Funds avaliable to ePinkies2.
         </H2>

         <!-- Only avaliable to those who can create new Funds.-->
         <?php if(canEditFunds()): ?>
           <a href="./fund.php?reason=add" class="btn btn-success" role="button"><span class="glyphicon glyphicon-plus"></span> Add a new Fund</a>
         <?php endif; ?>

         <!-- Back to Home button. -->
         <a href="./home.php" class="btn btn-success" role="button"><span class="glyphicon glyphicon-home"></span> Back to Home</a>

       </div>
     </div>

     <!-- The list of Funds -->
     <div class="container">
       <div class="well">
         <table class="table table-hover">
           <thead>
             <tr>
               <th>Fund Name</th>
               <th>Balance</th>
               <th>Last Updated</th>
               <th>Options</th>
             </tr>
           </thead>
           <tbody>
             <?php printAllFunds(); ?>
           </tbody>
         </table>
       </div>
     </div>

   </body>
</html>

<?php elseif($s_reason == 'add') :?>
<?php
  // ---------------------------------------------------------------------------
  // Here the user can add a new Fund to ePinkies2.
  // ---------------------------------------------------------------------------

  // Security Check. Make sure that this person is allowed to add a new Fund.
  if(!canEditFunds())
  {
    header("Location: ./fund.php?reason=list");
  }
?>
<!DOCTYPE html>
<html>
   <head>
     <?php printHeaderInfo(); ?>
     <title>ePinkies2 Funds</title>
     <script type="text/javascript" src="js/functions.js"></script>
     <script type="text/javascript" src="js/fund.js"></script>
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
           Adding a new Funds to ePinkies2.
         </H2>

         <!-- Back to Home button. -->
         <a href="./home.php" class="btn btn-success" role="button"><span class="glyphicon glyphicon-home"></span> Back to Home</a>
         <!-- Back to the list button. -->
         <a href="./fund.php?reason=list" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-list-alt"></span> List of Funds</a>

       </div>
     </div>

     <!-- Form to add a new Fund.-->
     <div class="container">
       <div class="well well-lg">

         <form class="form-horizontal" role="form" action="onFundSubmit.php" method="POST" name="addNewFundForm">

           <div class="form-group form-group-lg">
             <label class="control-label col-sm-2" for="fundName">Fund Name:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="fundName" name="fundName" >
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="activity">Activity:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="activity" name="activity">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="fund">Fund:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="fund" name="fund">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="function">Function:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="function" name="function">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="costCenter">Cost Center:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="costCenter" name="costCenter">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="projectCode">Project Code:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="projectCode" name="projectCode">
             </div>
           </div>


           <div class="form-group">
             <label class="control-label col-sm-2" for="balance">Balance:</label>
             <div class="col-sm-10">
               <div class="input-group">
                 <span class="input-group-addon">$</span>
                 <input type="text" class="form-control" id="balance" name="balance">
               </div>
             </div>
           </div>


           <div class="form-group">
             <label class="control-label col-sm-2" for="active">Active:</label>
             <div class="col-sm-10">
               <select class="form-control" id="active", name="active">
                 <option value="1" >Yes</option>
                 <option value='0' >No</option>
               </select>
           </div>
           </div>

           <div class="form-group form-group-lg">
             <div class="col-sm-offset-2 col-sm-10">
               <button type="button" class="btn btn-success" onclick="submitNewFund(this.form)">Add new Fund</button>
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
    // The user can edit a Funds here.
    // -------------------------------------------------------------------------

    // Error catching.
    if(!isset($_GET['fid']))
    {
      header("Location: ./fund.php?reason=list");
    }

    // Get the FundID
    $_fid = $_GET['fid'];

    // Security Check. Make sure that this person is allowed to edit a Fund.
    if(!canEditFunds())
    {
      header("Location: ./fund.php?reason=view&fid=".$_fid);
    }

    $_fund = new Fund();
    $_fund->i_FundID = (int)$_fid;
    $_fund->fromDatabase();

?>
<!DOCTYPE html>
<html>
   <head>
     <?php printHeaderInfo(); ?>
     <title>ePinkies2 Funds</title>
     <script type="text/javascript" src="js/functions.js"></script>
     <script type="text/javascript" src="js/fund.js"></script>
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
           Editing a Fund.
         </H2>

         <!-- Back to Home button. -->
         <a href="./home.php" class="btn btn-success" role="button"><span class="glyphicon glyphicon-home"></span> Back to Home</a>
         <!-- Back to the list button. -->
         <a href="./fund.php?reason=list" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-list-alt"></span> List of Funds</a>

       </div>
     </div>

     <div class="container">
       <div class="well well-lg">

         <form class="form-horizontal" role="form" action="onFundSubmit.php" method="POST">

           <div class="form-group form-group-lg">
             <label class="control-label col-sm-2" for="fundName">Fund Name:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="fundName" name="fundName" value="<?php echo $_fund->s_FundName; ?>">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="activity">Activity:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="activity" name="activity" value="<?php echo $_fund->s_Activity; ?>">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="fund">Fund:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="fund" name="fund" value="<?php echo $_fund->s_Fund; ?>">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="function">Function:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="function" name="function" value="<?php echo $_fund->s_Function; ?>">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="costCenter">Cost Center:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="costCenter" name="costCenter" value="<?php echo $_fund->s_CostCenter; ?>">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="projectCode">Project Code:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="projectCode" name="projectCode" value="<?php echo $_fund->s_ProjectCode; ?>">
             </div>
           </div>


           <div class="form-group">
             <label class="control-label col-sm-2" for="balance">Balance:</label>
             <div class="input-group">
               <span class="input-group-addon">$</span>
               <input type="text" class="form-control" id="balance" name="balance" value="<?php echo $_fund->s_Balance; ?>">
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="active">Active:</label>
             <div class="col-sm-10">
             <select class="form-control" id="active", name="active">
               <option value="1" <?php isSelected($_fund);?> >Yes</option>
               <option value='0' <?php isNotSelected($_fund);?> >No</option>
             </select>
           </div>
           </div>

           <div class="form-group form-group-lg">
             <div class="col-sm-offset-2 col-sm-10">
               <button type="button" class="btn btn-info" onclick="updateFund(this.form, <?php echo $_fund->i_FundID; ?>)">Update/Change</button>
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
  // The user can view all the variables associated with a single Fund.
  // ---------------------------------------------------------------------------

  // Error catching.
  if(!isset($_GET['fid']))
  {
    header("Location: ./fund.php?reason=list");
  }

  // Get the Funds
  $_fid = $_GET['fid'];

  $_fund = new Fund();
  $_fund->i_FundID = $_fid;
  $_fund->fromDatabase();
?>

<!DOCTYPE html>
<html>
   <head>
     <?php printHeaderInfo(); ?>
     <title>ePinkies2 Fund</title>
     <script type="text/javascript" src="js/functions.js"></script>
     <script type="text/javascript" src="js/fund.js"></script>
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
           Viewing a Fund.
         </H2>

         <!-- Back to Home button. -->
         <a href="./home.php" class="btn btn-success" role="button"><span class="glyphicon glyphicon-home"></span> Back to Home</a>
         <!-- Back to the list button. -->
         <a href="./fund.php?reason=list" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-list-alt"></span> List of Funds</a>

       </div>
     </div>

     <div class="container">
       <div class="well well-lg">

         <form class="form-horizontal" role="form" action="" method="POST">

           <div class="form-group form-group-lg">
             <label class="control-label col-sm-2" for="fundName">Fund Name:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="fundName" name="fundName" value="<?php echo $_fund->s_FundName; ?>" readonly>
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="activity">Activity:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="activity" name="activity" value="<?php echo $_fund->s_Activity; ?>" readonly>
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="fund">Fund:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="fund" name="fund" value="<?php echo $_fund->s_Fund; ?>" readonly>
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="function">Function:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="function" name="function" value="<?php echo $_fund->s_Function; ?>" readonly>
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="costCenter">Cost Center:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="costCenter" name="costCenter" value="<?php echo $_fund->s_CostCenter; ?>" readonly>
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="projectCode">Project Code:</label>
             <div class="col-sm-10">
               <input type="text" class="form-control" id="projectCode" name="projectCode" value="<?php echo $_fund->s_ProjectCode; ?>" readonly>
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="balance">Balance:</label>
             <div class="input-group">
               <span class="input-group-addon">$</span>
               <input type="text" class="form-control" id="balance" name="balance" value="<?php echo $_fund->s_Balance; ?>" readonly>
             </div>
           </div>

           <div class="form-group">
             <label class="control-label col-sm-2" for="active">Active:</label>
             <div class="col-sm-10">
             <select class="form-control" id="active", name="active" disabled>
               <option value="1" <?php isSelected($_fund);?> >Yes</option>
               <option value='0' <?php isNotSelected($_fund);?> >No</option>
             </select>
           </div>
           </div>
         </form>

       </div>
     </div>

   </body>
</html>

<?php endif;?>
