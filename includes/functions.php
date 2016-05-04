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

function onErrorInternal($s_Title, $s_Reason)
{
  header("Location: ../oops.php?title=".$s_Title."&reason=".$s_Reason);
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
      <link rel="stylesheet" type="text/css" href="./css/styles.css">

      <link rel="apple-touch-icon" sizes="57x57" href="./img/apple-touch-icon-57x57.png">
      <link rel="apple-touch-icon" sizes="60x60" href="./img/apple-touch-icon-60x60.png">
      <link rel="apple-touch-icon" sizes="72x72" href="./img/apple-touch-icon-72x72.png">
      <link rel="apple-touch-icon" sizes="76x76" href="./img/apple-touch-icon-76x76.png">
      <link rel="apple-touch-icon" sizes="114x114" href="./img/apple-touch-icon-114x114.png">
      <link rel="apple-touch-icon" sizes="120x120" href="./img/apple-touch-icon-120x120.png">
      <link rel="apple-touch-icon" sizes="144x144" href="./img/apple-touch-icon-144x144.png">
      <link rel="apple-touch-icon" sizes="152x152" href="./img/apple-touch-icon-152x152.png">
      <link rel="apple-touch-icon" sizes="180x180" href="./img/apple-touch-icon-180x180.png">
      <link rel="icon" type="image/png" href="./img/favicon-32x32.png" sizes="32x32">
      <link rel="icon" type="image/png" href="./img/android-chrome-192x192.png" sizes="192x192">
      <link rel="icon" type="image/png" href="./img/favicon-96x96.png" sizes="96x96">
      <link rel="icon" type="image/png" href="./img/favicon-16x16.png" sizes="16x16">
      <link rel="manifest" href="./img/manifest.json">
      <link rel="mask-icon" href="./img/safari-pinned-tab.svg" color="#5bbad5">
      <meta name="msapplication-TileColor" content="#da532c">
      <meta name="msapplication-TileImage" content="./img/mstile-144x144.png">
      <meta name="theme-color" content="#ffffff">

      ';
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
    $count = count($a_SubmitToArray);
    for ($i = 0; $i < $count; $i++)
    {
      $var = $a_SubmitToArray[$i];
      $parts = explode("|", $var);
      echo "<option value='".$parts[1]."'>".$parts[0]."</option>";
    }
}

// If the value == the Selection Text, then selected is printed. Used for multi
// option selection windows, to select the correct one. Only works for strings.
// Disables all other inputs.
function isSelected($value, $selectionText)
{
  if(strcmp($value, $selectionText) == 0)
  {
    echo 'selected="selected"';
  }
  else
  {
    echo 'disabled="disabled"';
  }
}

?>
