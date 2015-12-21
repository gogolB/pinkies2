<?php
  include 'includes/functions.php';
  $s_errorTitle = $_GET['title'];
  $s_errorReason = $_GET['reason'];
?>
<!DOCTYPE html>
<html>
  <head>
    <?php printHeaderInfo(); ?>
    <title>Oops!</title>
    <style>
      .jumbotron {
        background-color: #FFBABA;
        color: #ffffff;
      }
    </style>
  </head>
  <body>
    <div class="jumbotron text-center">
      <h1>Oh Snaps!</h1>
      <h3>There seems to have been an error.</h3>
    </div>
    <div class="container">
      <div class="well">
        <H2><?php echo($s_errorTitle);?></H2>
        <p><?php echo($s_errorReason);?></p>
      </div>
    </div>
  </body>
</html>
