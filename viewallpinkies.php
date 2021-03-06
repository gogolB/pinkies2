<?php
  // ---------------------------------------------------------------------------
  // ---------------------------------------------------------------------------
  include_once 'includes/functions.php';
  include_once 'includes/sessionFunctions.php';
  secureSessionStart();

  // Shows all pinkies not arcived or Cancelled.
  function printSubmittedToYouTable()
  {
      $_db = getMysqli();
      $statement = $_db->prepare("SELECT * FROM Submitted_By WHERE SubmittedFor=? OR SupervisorApprove=? OR AdminApprove=? OR TransProcess=?");
      $statement->bind_param('ssss', $_SESSION['Username'], $_SESSION['Username'], $_SESSION['Username'], $_SESSION['Username']);
      $statement->execute();

      // Error running the statement.
      if($statement->errno != 0)
      {
        $_tmp = $statement->error;
        $statement->close();
        $_db->close();
        onError("ViewAllPinkies.php::printSubmittedToYouTable()",'There was an error running the query [' . $_tmp . '] Could not fetch Pinkies submitted to: '.$_SESSION['Username']);
      }

      $statement->store_result();
      if($statement->num_rows <= 0)
      {
          echo '<tr>
                  <td> No more Pinkies for you!</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>';
          return;
      }
      // We have a result, lets bind the result to the variables.
      $statement->bind_result($pinkieID, $timestamp, $submitterUser, $submittedFor, $title, $status, $totalvalue, $OriginalSubmitter, $SupervisorApprove, $AdminApprove, $TransProcess);
      while($statement->fetch())
      {
          printf('<tr>
                  <td>%s</td>
                  <td>%s</td>
                  <td>%s</td>
                  <td>%.2f</td>
                  <td>%s</td>
                  <td><a href="./viewpinkie.php?pid=%d" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-search"></span> View</a>
                      <a href="./editPinkie.php?pid=%d" class="btn btn-info" role="button"><span class="glyphicon glyphicon-pencil"></span> Edit</a></td>
                </tr>', $title, $submitterUser, $timestamp, $totalvalue, $status, $pinkieID, $pinkieID);
      }

      // Cleanup.
      $statement->free_result();
      $statement->close();
      $_db->close();
  }

  // Only shows all pinkies not cancelled, archived or done.
  function printSubmittedByYouTable()
  {
    $_db = getMysqli();
    $statement = $_db->prepare("SELECT * FROM Submitted_By WHERE Submitter=? OR OriginalSubmitter=?");
    $statement->bind_param('ss', $_SESSION['Username'],$_SESSION['Username']);
    $statement->execute();

    // Error running the statement.
    if($statement->errno != 0)
    {
      $_tmp = $statement->error;
      $statement->close();
      $_db->close();
      onError("ViewAllPinkies.php::printSubmittedToYouTable()",'There was an error running the query [' . $_tmp . '] Could not fetch Pinkies submitted to: '.$_SESSION['Username']);
    }

    $statement->store_result();
    if($statement->num_rows <= 0)
    {
        echo '<tr>
                <td> No Pinkies submitted by you!</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>';
        return;
    }
    // We have a result, lets bind the result to the variables.
    $statement->bind_result($pinkieID, $timestamp, $submitterUser, $submittedFor, $title, $status, $totalvalue, $OriginalSubmitter, $SupervisorApprove, $AdminApprove, $TransProcess);
    while($statement->fetch())
    {
        printf('<tr>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%.2f</td>
                <td>%s</td>
                <td><a href="./viewpinkie.php?pid=%d" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-search"></span> View</a></td>
              </tr>', $title, $submittedFor, $timestamp, $totalvalue, $status, $pinkieID);
    }

    // Cleanup.
    $statement->free_result();
    $statement->close();
    $_db->close();
  }

  function printAllOthersTable()
  {
    $_db = getMysqli();
    $statement = $_db->prepare("SELECT * FROM Submitted_By WHERE Submitter!=? && SubmittedFor!=?");
    $statement->bind_param('ss', $_SESSION['Username'], $_SESSION['Username']);
    $statement->execute();

    // Error running the statement.
    if($statement->errno != 0)
    {
      $_tmp = $statement->error;
      $statement->close();
      $_db->close();
      onError("ViewAllPinkies.php::printAllOthersTable()",'There was an error running the query [' . $_tmp . '] Could not fetch Pinkies submitted to: '.$_SESSION['Username']);
    }

    $statement->store_result();
    if($statement->num_rows <= 0)
    {
        echo '<tr>
                <td> No OTher Pinkies!</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>';
        return;
    }
    // We have a result, lets bind the result to the variables.
    $statement->bind_result($pinkieID, $timestamp, $submitterUser, $submittedFor, $title, $status, $totalvalue,$OriginalSubmitter, $SupervisorApprove, $AdminApprove, $TransProcess);
    while($statement->fetch())
    {
        printf('<tr>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%.2f</td>
                <td>%s</td>
                <td><a href="./viewpinkie.php?pid=%d" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-search"></span> View</a></td>
              </tr>', $title, $submitterUser, $submittedFor, $timestamp, $totalvalue, $status, $pinkieID);
    }

    // Cleanup.
    $statement->free_result();
    $statement->close();
    $_db->close();
  }

  ?>
  <!DOCTYPE html>
  <html>
     <head>
       <?php printHeaderInfo(); ?>
       <title>View All Pinkies</title>
       <script type="text/javascript" src="js/functions.js"></script>
     </head>

     <body>

       <div class="jumbotron text-center">
         <H1>ePinkies 2</H1>
         <h2>University of California at Riverside</h2>
         <h3>Department of Electrical and Computer Engineering</h3>
       </div>

       <div class="container">
         <div class="well">
           <H2>
             Welcome <?php echo(getName());?> to ePinkies2.
           </H2>
           <h4>Here you will be able to view any and every pinkie you have ever submitted or has been submitted to you.</h4>
           <!-- Back to Home button. -->
           <a href="./home.php" class="btn btn-success" role="button"><span class="glyphicon glyphicon-home"></span> Back to Home</a>
         </div>
       </div>

       <div class="container">
         <div class="well">
           <H2><u>Submitted to you</u></H2>
           <div class="table-responsive">
             <table class="table table-bordered table-hover table-condensed">
               <thead>
                 <tr>
                   <th>Pinkie Title</th>
                   <th>Submitted By</th>
                   <th>Timestamp</th>
                   <th>Total</th>
                   <th>Status</th>
                   <th>Options</th>
                 </tr>
               </thead>
               <tbody>
                 <?php printSubmittedToYouTable(); ?>
               </tbody>
             </table>
           </div>

         </div>
       </div>

       <div class="container">
         <div class="well">
           <H2><u>Submitted by you</u></H2>
           <div class="table-responsive">
             <table class="table table-bordered table-hover table-condensed">
               <thead>
                 <tr>
                   <th>Pinkie Title</th>
                   <th>Submitted To</th>
                   <th>Timestamp</th>
                   <th>Total</th>
                   <th>Status</th>
                   <th>Options</th>
                 </tr>
               </thead>
               <tbody>
                 <?php printSubmittedByYouTable(); ?>
               </tbody>
             </table>
           </div>
         </div>
       </div>

       <?php if(isAdmin()) : ?>
       <div class="container">
         <div class="well">
           <H2><u>All Other Pinkies</u></H2>
           <div class="table-responsive">
             <table class="table table-bordered table-hover table-condensed">
               <thead>
                 <tr>
                   <th>Pinkie Title</th>
                   <th>Submitted By</th>
                   <th>Submitted To</th>
                   <th>Timestamp</th>
                   <th>Total</th>
                   <th>Status</th>
                   <th>Options</th>
                 </tr>
               </thead>
               <tbody>
                 <?php printAllOthersTable(); ?>
               </tbody>
             </table>
           </div>
         </div>
       </div>
     <?php endif; ?>

     </body>
</html>
