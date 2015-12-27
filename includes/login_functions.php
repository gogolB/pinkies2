<?php
  // ---------------------------------------------------------------------------
  // Here all the functions required to log in are stored. The Sesson variables
  // are set from here as well. These include User access level (User, Super,
  // Admin, Transactor).
  // ---------------------------------------------------------------------------

  // This is a temp function. It is supposed to be replaced with whatever your
  // login system is supposed to be.
  // @returns: True if valid username and password combo, false if otherwise.
  function login($s_username, $s_password)
  {
    $_SESSION['Username'] = $s_username;
    if($s_username == "PinkiesAdmin")
    {
        setAdminPermissions();
        return true;
    }
    elseif ($s_username == "PinkiesSuper")
    {
      setSupervisorPermissions();
      return true;
    }
    elseif ($s_username == "PinkiesTrans")
    {
      setTransactorPermissions();
      return true;
    }
    elseif ($s_username == "PinkiesUser")
    {
      setUserPermissions();
      return true;
    }
    return false;
  }

  // Sets the admin permissions.
  function setAdminPermissions()
  {
    $_SESSION['Access'] = "ADMIN";
    $_SESSION['Name'] = "Pinkies Devlopment Admin";
    $_SESSION['SubmitTo'] = array("Pinkies Devlopment Transactor|PinkiesTrans");
  }

  // Sets the Supervisor permissions.
  function setSupervisorPermissions()
  {
    $_SESSION['Access'] = "SUPER";
    $_SESSION['Name'] = "Pinkies Devlopment Supervisor";
    $_SESSION['SubmitTo'] = array("Pinkies Devlopment Admin|PinkiesAdmin");
  }

  // Sets the Transactor permissions.
  function setTransactorPermissions()
  {
    $_SESSION['Access'] = "TRANS";
    $_SESSION['Name'] = "Pinkies Devlopment Transactor";
    $_SESSION['SubmitTo'] = array("Pinkies Devlopment Admin|PinkiesAdmin");
  }

  // Sets the User permissions.
  function setUserPermissions()
  {
    $_SESSION['Access'] = "USER";
    $_SESSION['Name'] = "Pinkies Devlopment User";
    $_SESSION['SubmitTo'] = array("Pinkies Devlopment Admin|PinkiesAdmin", "Pinkies Devlopment Supervisor|PinkiesSuper");
  }
 ?>
