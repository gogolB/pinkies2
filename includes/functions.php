<?php
include_once 'defines.php';
// -----------------------------------------------------------------------------
// Here are all the helper functions used throughout ePinkies 2.
// -----------------------------------------------------------------------------

// Starts a secure session
function secureSessionStart()
{
    $session_name = 'EPinkies2-Session';
    $secure = false;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        onError("Failed to start a Secure Session", "Could not init a safe session. Failed to force session to use cookies only.");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
                              $cookieParams["path"],
                              $cookieParams["domain"],
                              $secure,
                              $httponly);

    session_name($session_name);
    session_start();                // Start the PHP session
    session_regenerate_id(true);    // regenerated the session, delete the old one.
}

// Redirects the user to a nicer error page.
function onError($s_Title, $s_Reason)
{
  header("Location: ./oops.php?title=".$s_Title."&reason=".$s_Reason);
}

// Returns a new Mysqli object.
function getMysqli()
{
  $_db = new mysqli(HOST, DB_USER, DB_PASS, DB);
  if($_db->connect_errno > 0)
  {
    onError("Unable to connect to database.", $_db->connect_error);
  }
  return $_db;
}

// prints out all the nice header info.
function printHeaderInfo()
{
  echo '<!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

      <!-- Optional theme -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

      <!-- Latest compiled and minified JavaScript -->
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

      <meta charset="utf-8">

      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" type="text/css" href="styles.css">';
}

?>
