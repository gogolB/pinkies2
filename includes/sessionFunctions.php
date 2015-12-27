<?php
// -----------------------------------------------------------------------------
// Here are all the helper functions to be used to access variables in used by
// the session.
// -----------------------------------------------------------------------------

// Returns true if this current session is at User level.
function isUser()
{
  if(isset($_SESSION['Access']))
  {
    if($_SESSION["Access"] == "USER")
    {
      return true;
    }
  }
  return false;
}

// Returns true if this current session is at Transactor level.
function isTrans()
{
  if(isset($_SESSION['Access']))
  {
    if($_SESSION["Access"] == "TRANS")
    {
      return true;
    }
  }
  return false;
}

// Returns true if this current session is at Supervisor level.
function isSuper()
{
  if(isset($_SESSION['Access']))
  {
    if($_SESSION["Access"] == "SUPER")
    {
      return true;
    }
  }
  return false;
}

// Returns true if this current session is at Admin level.
function isAdmin()
{
  if(isset($_SESSION['Access']))
  {
    if($_SESSION["Access"] == "ADMIN")
    {
      return true;
    }
  }
  return false;
}

// Gets this current sessions user's name.
function getName()
{
  return $_SESSION['Name'];
}

// Gets the array of people you can submit to.
function getSubmitTo()
{
    return $_SESSION['SubmitTo'];
}

// -----------------------------------------------------------------------------
// Permissions table: Shows who has access to what.
// -----------------------------------------------------------------------------

// Returns true if this current session can view the list of vendors.
function canViewVendors()
{
    return isUser() || isSuper() || isTrans() || isAdmin();
}

// Returns true if this current session is allowed to edit the list of vendors,
// including adding and updating a vendor.
function canEditVendors()
{
    return isAdmin();
}

// Returns true if this current session can view the list of funds.
function canViewFunds()
{
    return isUser() || isSuper() || isTrans() || isAdmin();
}
// Returns true if this current session is allowed to edit the list of funds
// including adding and updating a fund.
function canEditFunds()
{
    return isAdmin();
}


?>
