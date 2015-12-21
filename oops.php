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
        background-color: #D8000C;
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

    <div class="container">
      <div class="well">
        <H2>Please take a screenshot of this error report or explain your problem to <a href="mailto:systems@ece.ucr.edu?Subject=ePinkies2%20Error" target="_top">ECE Systems</a></H2>
        <p>Click here to return to <a href"./home.php">home</a>.</p>
      </div>
    </div>
  </body>
</html>
