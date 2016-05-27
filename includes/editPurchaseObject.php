<?php
include_once 'functions.php';
secureSessionStart();

if(strcmp($_POST['mode'], "delete") == 0) // We are in delete mode.
{
  $_db = getMysqli();
  $_sql = "DELETE FROM Objects WHERE ObjectID=?";
  $_stmt = $_db->prepare((string)$_sql);

  $_stmt->bind_param('i', $_POST['objectID']);
  $_stmt->execute();

  if ($_stmt->errno)
  {
    onErrorInternal("editPinkiePurchaseObject::Delete()", $_stmt->error);
  }
  $_stmt->close();
  // Close up the database connection.
  $_db->close();
  echo "OKAY";
  return;
}
if(strcmp($_POST['mode'], "edit") == 0) // We are in edit mode.
{

  echo var_dump($_POST);
  return;
  if(strlen($_POST['objectID']) == 0)
  {
    // No fundID was set.
    return;
  }

  $_db = getMysqli();
  $_sql = "UPDATE Objects SET Quantity=?, StockNumber=?, Description=?, BC=?, AccountNumber=?, UnitPrice=? WHERE ObjectID=?";
  $_stmt = $_db->prepare((string)$_sql);

  $_stmt->bind_param('issssd', $_POST['quantity'], $_POST['stockNumber'], $_POST['description'], $_POST['bc'], $_POST['accountNumber'], $_POST['unitPrice'], $_POST['objectID']);
  $_stmt->execute();

  if ($_stmt->errno)
  {
    onErrorInternal("editPinkiePurchaseObject::edit()", $_stmt->error);
  }
  $_stmt->close();
  // Close up the database connection.
  $_db->close();
  echo "OKAY";
  return;

}
if(strcmp($_POST['mode'], "add") == 0) // We are in add mode.
{

  if(strlen($_POST['objectID']) == 0 || strlen($_POST['pinkieID']) == 0)
  {
    // No fundID was set.
    return;
  }

  $_db = getMysqli();
  $_sql = "INSERT INTO Expenses (PinkieID, Quantity, Description, UnitPrice) Values(?,?,?,?)";
  $_stmt = $_db->prepare((string)$_sql);

  $_stmt->bind_param('iisd', $_POST['pinkieID'], $_POST['quantity'], $_POST['description'], $_POST['unitPrice']);
  $_stmt->execute();

  if ($_stmt->errno)
  {
    onErrorInternal("editPinkiePurchaseObject::add()", $_stmt->error);
  }
  $_stmt->close();
  // Close up the database connection.
  $_db->close();
  echo "OKAY";
  return;
}

echo "ERROR. Invalid Mode!";
?>
