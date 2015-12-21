<?php

// Start a secure session
function secureSessionStart() {
    $session_name = 'EPinkies2-Session';
    $secure = SECURE;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session
    session_regenerate_id(true);    // regenerated the session, delete the old one.
}

function isUser()
{
  if(isset($_SESSION))
  {
    if($_SESSION["Access"] == "USER")
    {
      return true;
    }
  }
  return false;
}

function isTrans()
{
  if(isset($_SESSION))
  {
    if($_SESSION["Access"] == "TRANS")
    {
      return true;
    }
  }
  return false;
}

function isSuper()
{
  if(isset($_SESSION))
  {
    if($_SESSION["Access"] == "SUPER")
    {
      return true;
    }
  }
  return false;
}

function isAdmin()
{
  if(isset($_SESSION))
  {
    if($_SESSION["Access"] == "ADMIN")
    {
      return true;
    }
  }
  return false;
}


 ?>
