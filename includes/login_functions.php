<?php
  // ---------------------------------------------------------------------------
  // Here all the functions required to log in are stored. The Sesson variables
  // are set from here as well. These include User access level (User, Super,
  // Admin, Transactor).
  // ---------------------------------------------------------------------------

  // This is a temp function. It is supposed to be replaced with whatever your
  // login system is supposed to be.
  function login($s_username, $s_password)
  {
    $_SESSION['Username'] = $$s_username;
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

  function setAdminPermissions()
  {
    $_SESSION['Access'] = "ADMIN";
  }

  function setSupervisorPermissions()
  {
    $_SESSION['Access'] = "SUPER";
  }

  function setTransactorPermissions()
  {
    $_SESSION['Access'] = "TRANS";
  }

  function setUserPermissions()
  {
    $_SESSION['Access'] = "USER";
  }
 ?>
