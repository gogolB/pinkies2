<?php
  //----------------------------------------------------------------------------
  // This script is responsible for checking if a given username and password is
  // correct. Then it will generate the session variables necesary for logging
  // in to and using the rest of ePinkies2.
  //----------------------------------------------------------------------------

  include_once 'includes/login_functions.php';
  include_once 'includes/functions.php';

  //ini_set('display_errors', 1);
  //ini_set('display_startup_errors', 1);
  //error_reporting(E_ALL);

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
      //echo "wrong login.";
      header("Location: ./");
    }
  }
  else
  {
    //echo "not set.";
    header("Location: ./");
  }

 ?>
