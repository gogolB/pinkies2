<?php
include_once 'functions.php';
secureSessionStart();

if(strcmp($_POST['mode'], "delete") == 0) // We are in delete mode.
{
  echo "delete mode!"
  return;
}
if(strcmp($_POST['mode'], "edit") == 0) // We are in edit mode.
{
  echo "edit mode!"
  return;

}
if(strcmp($_POST['mode'], "add") == 0) // We are in add mode.
{
  echo "add mode!"
  return;
}

echo "ERROR. Invalid Mode!";
?>
