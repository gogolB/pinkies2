<?php
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
    </body>
 </html>
