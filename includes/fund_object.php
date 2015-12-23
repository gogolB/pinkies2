<?php
include_once 'functions.php';
secureSessionStart();
//------------------------------------------------------------------------------
// This object is responsible for managing all information relating to a single
// fund.
//------------------------------------------------------------------------------

class Fund
{
  public $i_FundID=-1;
  public $s_FundName='';
  public $s_Activity='';
  public $s_Fund='';
  public $s_Function='';
  public $s_CostCenter='';
  public $s_ProjectCode='';
  public $s_Balance='';
  public $b_Active=TRUE;
  public $s_Timestamp='';

  // Send a fund object to the database.
  public function toDatabase()
  {
      if($this->i_FundID > 0)
      {
          // This fund object already exists. We just need to update it.
          $this->updateFund();
      }
      else
      {
          // We need to check for repeats.
          // Connect to the database.
          $_db = getMysqli();
          // SQL query to run.
          $statement = $_db->prepare("SELECT FundID FROM Funds WHERE FundName=?");
          $statement->bind_param('s', $this->s_FundName);
          $statement->execute();

          // There was an error executing the statement.
          if($statement->errno != 0)
          {
            $_tmp = $statement->error;
            $statement->close();
            $_db->close();
            onError("Fund::toDatabase()",'There was an error running the query [' . $_tmp . '] when we were searching for repeats.');
          }

          // Checking for repeats.
          if($statement->num_rows > 0)
          {
              // Its a repeat, we just need to update the fund.
              $statement->bind_result($this->i_FundID);
              $this->updateFund();
          }
          else
          {
              // Its new, we can add it to the database.
              $this->addFund();
          }

          // Cleanup.
          $statement->free_result();
          $_db->close();
      }
  }

  // Load a Fund object from the database.
  public function fromDatabase()
  {
      // Check if a fund ID has been set.
      if($this->i_FundID < 0)
      {
          // No fund ID has been set, abort search.
          onError("Fund::fromDatabase()","Failed to conduct search in database because no valid fundID was set. Fund id was: ".$this->i_FundID);
      }

      // We have a proper fund ID, conduct a search.
      // Connect to the database.
      $_db = getMysqli();
      // SQL query to run.
      $statement = $_db->prepare("SELECT * FROM Funds WHERE FundID=?");
      $statement->bind_param('i', $this->i_FundID);
      $statement->execute();

      // Error running the statment.
      if($statement->errno != 0)
      {
        $_tmp = $statement->error;
        $statement->close();
        $_db->close();
        onError("Fund::fromDatabase()",'There was an error running the query [' . $_tmp . '] Could not fetch Fund.');
      }


      $statement->store_result();
      if($statement->num_rows <= 0)
      {
          $statement->free_result();
          $statement->close();
          $_db->close();
          onError("Fund::fromDatabase()","Failed to find a fund with the given fund id of: ".$this->i_FundID);
      }
      // We have a result, lets bind the result to the variables.
      $statement->bind_result($throwaway, $this->s_FundName, $this->s_Activity, $this->s_Fund, $this->s_Function, $this->s_CostCenter, $this->s_ProjectCode,$this->s_Balance, $this->s_Active, $this->s_Timestamp);
      $statement->fetch();

      // Cleanup.
      $statement->free_result();
      $statement->close();
      $_db->close();

  }

  // Actual code to update a fund in the database.
  public function updateFund()
  {
      // Check to make sure we have a proper name.
      if(strlen($this->s_FundName) == 0)
      {
          // Invalid name.
          onError("Fund::updateFund()", "No fund name set!");
      }

      // Check to make sure we have a fund ID.
      if($this->i_FundID < 0)
      {
          onError("Fund::updateFund()", "Could not update fund, invalid fund id.");
      }

      // Everything all good, lets update the table.
      $_db = getMysqli();
      $_sql = "UPDATE Funds SET FundName=?, Activity=?, Fund=?, Function=?, CostCenter=?, ProjectCode=?, Balance=?, Active=? WHERE FundID=?";
      $_stmt = $_db->prepare($_sql);

      $_stmt->bind_param('ssssssssssii', $this->s_FundName, $this->s_Activity, $this->s_Fund, $this->s_Function, $this->s_CostCenter, $this->s_ProjectCode, $this->s_Balance, $this->b_Active, $this->i_FundID);
      $_stmt->execute();

      if ($_stmt->errno)
      {
        onError("Error in Fund::updateFund()", $_stmt->error);
      }

      $_stmt->close();
      // Close up the database connection.
      $_db->close();
  }

  // Actual code to add a fund to the database.
  public function addFund()
  {
      // Check to make sure we have a proper name.
      if(strlen($this->s_FundName) == 0)
      {
          // Invalid name.
          onError("Fund::addFund()", "No fund name set!");
      }

      // Everything all good, lets insert in to the table.
      $_db = getMysqli();
      $_sql = "INSERT INTO Funds (FundName, Activity, Fund, Function, CostCenter, ProjectCode, Balance, Active) VALUES (?,?,?,?,?,?,?,?)";
      $_stmt = $_db->prepare($_sql);

      $_stmt->bind_param('ssssssssssi', $this->s_FundName, $this->s_Activity, $this->s_Fund, $this->s_Function, $this->s_CostCenter, $this->s_ProjectCode, $this->s_Balance, $this->b_Active);
      $_stmt->execute();

      if ($_stmt->errno)
      {
        onError("Error in Fund::addFund()", $_stmt->error);
      }

      $_stmt->close();
      // Close up the database connection.
      $_db->close();
  }

}

?>
