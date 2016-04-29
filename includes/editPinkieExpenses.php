<?php
include_once 'functions.php';
secureSessionStart();

if(strcmp($_POST['mode'], "delete") == 0) // We are in delete mode.
{
  // TESTING Only
  print_r($_POST);
  return;

  $_db = getMysqli();
  $_sql = "DELETE FROM Expenses WHERE ExpenseID=?";
  $_stmt = $_db->prepare((string)$_sql);

  $_stmt->bind_param('i', $_POST['expenseID']);
  $_stmt->execute();

  if ($_stmt->errno)
  {
    onError("editPinkieExpenses::Delete()", $_stmt->error);
  }
  $_stmt->close();
  // Close up the database connection.
  $_db->close();
  echo "OKAY";
  return;
}
if(strcmp($_POST['mode'], "edit") == 0) // We are in edit mode.
{
  print_r($_POST);
  return;
  echo "edit mode!";
  return;

}
if(strcmp($_POST['mode'], "add") == 0) // We are in add mode.
{
  print_r($_POST);
  return;
  echo "add mode!";
  return;
}

echo "ERROR. Invalid Mode!";
?>
