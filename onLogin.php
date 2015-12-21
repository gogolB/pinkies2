<?php
  //----------------------------------------------------------------------------
  // This script is responsible for checking if a given username and password is
  // correct. Then it will generate the session variables necesary for logging
  // in to and using the rest of ePinkies2.
  //----------------------------------------------------------------------------

  include 'includes/login_functions.php'
  include 'includes/functions.php'

  // Start the session.
  secureSessionStart();

  if(isset($_POST['username']) && isset($_POST['pwd']))
  {
    if(login($_POST['username'], $_POST['pwd']))
    {
      // Redirect to home page.
      header("Location: home.php");
    }
    else
    {
      header("Location: ../");
    }
  }
  else
  {
    header("Location: ../");
  }

 ?>
