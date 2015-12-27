<?php
include_once 'defines.php';
// -----------------------------------------------------------------------------
// Here are all the helper generic functions used throughout ePinkies 2.
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
  echo '
      <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
      <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

      <!-- Optional theme -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

      <!-- Latest compiled and minified JavaScript -->
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

      <meta charset="utf-8">

      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" type="text/css" href="styles.css">';
}

// Prints all the vendors as <option value='vendorID'>VendorName</option>
function printVendors()
{
    $_db = getMysqli();
    $_stmt = $_db->prepare("SELECT VendorName, VendorID FROM Vendors");
    $_stmt->execute();
    $_stmt->bind_result($s_VendorName, $i_VendorID);
    while($_stmt->fetch())
    {
        echo '<option value="'.$i_VendorID.'">'.$s_VendorName.'</option>';
    }
    $_stmt->free_result();
    $_db->close();
}

// Prints all the Funds as <option value='FundID'>FundName</option>
function printFunds()
{
  $_db = getMysqli();
  $_stmt = $_db->prepare("SELECT FundName, FundID FROM Funds WHERE Active=1");
  $_stmt->execute();
  $_stmt->bind_result($s_FundName, $i_FundID);
  while($_stmt->fetch())
  {
      echo '<option value="'.$i_FundID.'">'.$s_FundName.'</option>';
  }
  $_stmt->free_result();
  $_db->close();

}

// Prints all the submitto array as <option value='UserName'>Proper Name of User</option>
function printSubmitTo()
{
    $a_SubmitToArray = getSubmitTo();
    for ($i = 0; $i < $count; $i++)
    {
      $var = $a_SubmitToArray[$i];
      $parts = explode("|", $var);
      echo "<option value='".$parts[1]."'>".$parts[0]."</option>";
    }
}

?>
