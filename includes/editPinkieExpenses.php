<?php
include_once 'functions.php';
secureSessionStart();

if(strcmp($_POST['mode'], "delete") == 0) // We are in delete mode.
{
  $_db = getMysqli();
  $_sql = "DELETE FROM Expenses WHERE ExpenseID=?";
  $_stmt = $_db->prepare((string)$_sql);

  $_stmt->bind_param('i', $_POST['expenseID']);
  $_stmt->execute();

  if ($_stmt->errno)
  {
    onErrorInternal("editPinkieExpenses::Delete()", $_stmt->error);
  }
  $_stmt->close();
  // Close up the database connection.
  $_db->close();
  echo "OKAY";
  return;
}
if(strcmp($_POST['mode'], "edit") == 0) // We are in edit mode.
{
  if(strlen($_POST['fundID']) == 0)
  {
    // No fundID was set.
    return;
  }

  $_db = getMysqli();
  $_sql = "UPDATE Expenses SET Amount=?, FundID=? WHERE ExpenseID=?";
  $_stmt = $_db->prepare((string)$_sql);

  $_stmt->bind_param('dii', $_POST['fundAmt'], $_POST['fundID'], $_POST['expenseID']);
  $_stmt->execute();

  if ($_stmt->errno)
  {
    onErrorInternal("editPinkieExpenses::editUpdate()", $_stmt->error);
  }
  $_stmt->close();
  // Close up the database connection.
  $_db->close();
  echo "OKAY";
  return;

}
if(strcmp($_POST['mode'], "add") == 0) // We are in add mode.
{

  if(strlen($_POST['fundID']) == 0)
  {
    // No fundID was set.
    return;
  }
  
  $_db = getMysqli();
  $_sql = "INSERT INTO Expenses (PinkieID, Amount, FundID) Values(?,?,?)";
  $_stmt = $_db->prepare((string)$_sql);

  $_stmt->bind_param('idi', $_POST['pinkieID'], $_POST['fundAmt'], $_POST['fundID']);
  $_stmt->execute();

  if ($_stmt->errno)
  {
    onErrorInternal("editPinkieExpenses::editUpdate()", $_stmt->error);
  }
  $_stmt->close();
  // Close up the database connection.
  $_db->close();
  echo "OKAY";
  return;
}

echo "ERROR. Invalid Mode!";
?>
