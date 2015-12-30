<?php
  // ---------------------------------------------------------------------------
  // This is the main page from where the user will select what they want to do.
  // Their access will be granted based on their user level.
  // --- USER ---
  // + Make a new Pinkie.
  // + Submit a pinkie to a supervisor.
  // + Review past submitted pinkies.
  // + Change/Update a pinkie.
  // + Delete a submitted, but not approved pinkie.
  // --- SUPER ---
  // + Do all a USER can do.
  // + Approve submitted pinkies.
  // + Reject a pinkie.
  // --- ADMIN ---
  // + Do all a SUPER can do.
  // + Send a pinkie to a Transactor.
  // + Add/Update Vendors.
  // + Add/Update Funds.
  // --- TRANS ---
  // + Do all a user can do.
  // + Submit directly to the admin.
  // ---------------------------------------------------------------------------
  include_once 'includes/functions.php';
  include_once 'includes/sessionFunctions.php';
  secureSessionStart();


  // Shows all pinkies not arcived or Cancelled.
  function printSubmittedToYouTable()
  {
      $_db = getMysqli();
      $statement = $_db->prepare("SELECT * FROM Submitted_By WHERE SubmittedFor=? && Status!=? && Status!=? && Status!=?");
      $a = Archived;
      $c = Cancelled;
      $d = Done;
      $statement->bind_param('ssss', $_SESSION['Username'], $a, $c, $d);
      $statement->execute();

      // Error running the statement.
      if($statement->errno != 0)
      {
        $_tmp = $statement->error;
        $statement->close();
        $_db->close();
        onError("Home::printSubmittedToYouTable()",'There was an error running the query [' . $_tmp . '] Could not fetch Pinkies submitted to: '.$_SESSION['Username']);
      }

      $statement->store_result();
      if($statement->num_rows <= 0)
      {
          echo '<tr>
                  <td> No more Pinkies to process for you!</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>';
          return;
      }
      // We have a result, lets bind the result to the variables.
      $statement->bind_result($pinkieID, $timestamp, $submitterUser, $submittedFor, $title, $status, $totalvalue);
      while($statement->fetch())
      {
          printf('<tr>
                  <td>%s</td>
                  <td>%s</td>
                  <td>%s</td>
                  <td>%.2f</td>
                  <td><a href="./viewpinkie.php?pid=%d" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-search"></span> View</a>
                      <a href="./editpinkie.php?pid=%d" class="btn btn-info" role="button"><span class="glyphicon glyphicon-pencil"></span> Edit</a></td>
                </tr>', $title, $submitterUser, $timestamp, $totalvalue, $pinkieID, $pinkieID);
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
    $statement = $_db->prepare("SELECT * FROM Submitted_By WHERE Submitter=? && Status!=? && Status !=? && Status!=?");
    $a = Archived;
    $c = Cancelled;
    $d = Done;
    $statement->bind_param('ssss', $_SESSION['Username'], $a, $c, $d);
    $statement->execute();

    // Error running the statement.
    if($statement->errno != 0)
    {
      $_tmp = $statement->error;
      $statement->close();
      $_db->close();
      onError("Home::printSubmittedToYouTable()",'There was an error running the query [' . $_tmp . '] Could not fetch Pinkies submitted to: '.$_SESSION['Username']);
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
              </tr>';
        return;
    }
    // We have a result, lets bind the result to the variables.
    $statement->bind_result($pinkieID, $timestamp, $submitterUser, $submittedFor, $title, $status, $totalvalue);
    while($statement->fetch())
    {
        printf('<tr>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%.2f</td>
                <td><a href="./viewpinkie.php?pid=%d" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-search"></span> View</a></td>
              </tr>', $title, $submittedFor, $timestamp, $totalvalue, $pinkieID);
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
      <title>ePinkies2 Home</title>
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
        </div>
      </div>

      <!-- Start a new Pinkie -->
      <?php if(isUser() || isTrans() || isSuper() || isAdmin()): ?>
        <div class="container">
          <div class="well well-lg">
            <H3>Start a New Pinkie</H3>

            <!-- Form for starting a new pinkie. -->
            <form class="form-horizontal" role="form" action="newpinkie.php" method="POST">
              <div class="form-group form-group-lg">
                <label class="control-label col-sm-2" for="title">Title:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="title" name="title" placeholder="Title of the pinkie">
                </div>
              </div>
              <div class="form-group form-group-lg">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="button" class="btn btn-success" onclick="onStartNewPinkie(this.form)"><span class="glyphicon glyphicon-plus"></span> Start a New Pinkie</button>
                </div>
              </div>
            </form>
            <!-- End form -->
          </div>
        </div>
    <?php endif; ?>

    <!-- Review Pinkies -->
    <?php if(isUser() || isTrans() || isSuper() || isAdmin()): ?>
      <div class="container">
        <div class="well">
          <H3>Review Pinkies</H3>
          <!-- Everyone but a user can have a pinkie submitted to them. -->
          <?php if(isTrans() || isSuper() || isAdmin()): ?>
            <H4><u>Pinkies Submitted To You</u></H4>
            <div class="table-responsive">
              <table class="table table-bordered table-hover table-condensed">
                <thead>
                  <tr>
                    <th>Pinkie Title</th>
                    <th>Submitted By</th>
                    <th>Timestamp</th>
                    <th>Total</th>
                    <th>Options</th>
                  </tr>
                </thead>
                <tbody>
                  <?php printSubmittedToYouTable(); ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>

          <H4><u>Pinkies Submitted By You</u></H4>
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-condensed">
              <thead>
                <tr>
                  <th>Pinkie Title</th>
                  <th>Submitted To</th>
                  <th>Timestamp</th>
                  <th>Total</th>
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
    <?php endif; ?>

    <!-- Manage Vendors -->
    <?php if(canViewVendors()): ?>
      <div class="container">
        <div class="well">
          <H3>Manage Vendors</H3>
          <?php if(canEditVendors()): ?>
            <a href="./vendor.php?reason=add" class="btn btn-success" role="button"><span class="glyphicon glyphicon-plus"></span> Add a new Vendor</a>
          <?php endif; ?>
          <a href="./vendor.php?reason=list" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-list-alt"></span> View all Vendors</a>
        </div>
      </div>
    <?php endif; ?>

    <!-- Manage Funds -->
    <?php if(canViewFunds()): ?>
      <div class="container">
        <div class="well">
          <H3>Manage Funds</H3>
          <?php if(canEditFunds()): ?>
            <a href="./fund.php?reason=add" class="btn btn-success" role="button"><span class="glyphicon glyphicon-plus"></span> Add a new Fund</a>
          <?php endif; ?>
          <a href="./fund.php?reason=list" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-list-alt"></span> View all Funds</a>
        </div>
      </div>
    <?php endif; ?>
    </body>
 </html>
