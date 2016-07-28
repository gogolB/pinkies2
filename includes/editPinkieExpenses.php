<?php
include_once 'functions.php';
include_once 'sessionFunctions.php';
include_once 'logger.php';
secureSessionStart();

if(strcmp($_POST['mode'], "delete") == 0) // We are in delete mode.
{
  if(strlen($_POST['fundID']) == 0)
  {
    // No fundID was set.
    return;
  }
  $_db = getMysqli();
  $_sql = "DELETE FROM Expenses WHERE ExpenseID=?";
  $_stmt = $_db->prepare((string)$_sql);

  $_stmt->bind_param('i', $_POST['expenseID']);
  $_stmt->execute();

  if ($_stmt->errno)
  {
    onErrorInternal("editPinkieExpenses::editDelete()", $_stmt->error);
  }
  $_stmt->close();
  // Close up the database connection.
  $_db->close();
  echo "OKAY";
  logGeneral($_POST['pinkieID'], $_SESSION['Username'], "Expense was deleted by: ".getName());
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
  logGeneral($_POST['pinkieID'], $_SESSION['Username'], "Expense was edited by: ".getName());
  return;

}
if(strcmp($_POST['mode'], "add") == 0) // We are in add mode.
{

  if(strlen($_POST['pinkieId']) == 0)
  {
    // No pinkieId was set.
    return;
  }

  $_db = getMysqli();
  $_sql = "INSERT INTO Expenses (PinkieID, Amount, FundID) Values(?,?,?)";
  $_stmt = $_db->prepare((string)$_sql);

  $_stmt->bind_param('idi', $_POST['pinkieID'], $_POST['fundAmt'], $_POST['fundID']);
  $_stmt->execute();

  if ($_stmt->errno)
  {
    onErrorInternal("editPinkieExpenses::editAdd()", $_stmt->error);
  }
  $_stmt->close();
  // Close up the database connection.
  $_db->close();
  echo "OKAY";
  logGeneral($_POST['pinkieID'], $_SESSION['Username'], "Expense was added by: ".getName());
  return;
}

echo "ERROR. Invalid Mode!";
?>
