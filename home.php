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
  include 'includes/functions.php';
  include 'includes/sessionFunctions.php';
  secureSessionStart();
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
        <div class-"well">
          <p>
            Welcome <?php echo(getName());?> to ePinkies2.
          </p>
        </div>
      </div>
      <!-- Start a new Pinkie -->
      <?php if(isUser() || isTrans() || isSuper() || isAdmin()): ?>
        <div class="container">
          <div class="well">
            <H3>Start a New Pinkie</H3>

            <!-- Form for starting a new pinkie. -->
            <form class="form-horizontal" role="form" action="" method="POST">
              <div class="form-group">
                <label class="control-label col-sm-2" for="title">Title:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="title" name="title" placeholder="Title of the pinkie">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-success">Start a New Pinkie</button>
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
        </div>
      </div>
  <?php endif; ?>

  <!-- Manage Vendors -->
  <?php if(isAdmin()): ?>
    <div class="container">
      <div class="well">
        <H3>Manage Vendors</H3>
        <a href="#" class="btn btn-success" role="button">Add a new Vendor</a>
        <a href="#" class="btn btn-info" role="button">Update a Vendor</a>
        <a href="#" class="btn btn-primary" role="button">View all Vendors</a>
      </div>
    </div>
<?php endif; ?>

<!-- Manage Funds -->
<?php if(isAdmin()): ?>
  <div class="container">
    <div class="well">
      <H3>Manage Funds</H3>
      <a href="#" class="btn btn-success" role="button">Add a new Fund</a>
      <a href="#" class="btn btn-info" role="button">Update a Fund</a>
      <a href="#" class="btn btn-primary" role="button">View all Funds</a>
    </div>
  </div>
<?php endif; ?>
    </body>
 </html>
