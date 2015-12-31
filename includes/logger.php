<?php
include_once 'functions.php';
//
// This is the logger system used by ePinkies2. This allows for a history per
// pinkie to be generated. A pinkie can now be tracked from birth to when it is
// marked as done.
//

function l_log($pinkieID, $user, $msg, $lvl)
{
  $_db = getMysqli();

  $_sql = "INSERT INTO Log (PinkieID, User, Level, Message) VALUES (?,?,?,?)";
  $_stmt = $_db->prepare((string)$_sql);

  $_stmt->bind_param('isss', $pinkieID, $user, $lvl, $msg);
  $_stmt->execute();

  if ($_stmt->errno)
  {
    onError("Error in Logger.php::log()", $_stmt->error);
  }

  $_stmt->close();
  // Close up the database connection.
  $_db->close();
}

function logGeneral($pinkieID, $user, $msg)
{
  l_log($pinkieID, $user, $msg, "General");
}

function logWarning($pinkieID, $user, $msg)
{
  l_log($pinkieID, $user, $msg, "Warning");
}

function logDanger($pinkieID, $user, $msg)
{
  l_log($pinkieID, $user, $msg, "Danger");
}

function logError($pinkieID, $user, $msg)
{
  l_log($pinkieID, $user, $msg, "Error");
}
?>
