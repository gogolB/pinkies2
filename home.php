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
          </div>
        </div>
    <?php endif; ?>

    <!-- Review Pinkies -->
    <?php if(isUser() || isTrans() || isSuper() || isAdmin()): ?>
      <div class="container">
        <div class="well">
          <H3>Review Pinkie</H3>
        </div>
      </div>
  <?php endif; ?>

  <!-- Manage Vendors -->
  <?php if(isAdmin()): ?>
    <div class="container">
      <div class="well">
        <H3>Manage Vendors</H3>
      </div>
    </div>
<?php endif; ?>

<!-- Manage Funds -->
<?php if(isAdmin()): ?>
  <div class="container">
    <div class="well">
      <H3>Manage Funds</H3>
    </div>
  </div>
<?php endif; ?>



    </body>
 </html>
