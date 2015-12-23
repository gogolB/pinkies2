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

      if(we dont have a result!)
      {
          onError("Fund::fromDatabase()","Failed to find a fund with the given fund id of: ".$this->i_FundID);
      }
      // We have a result, lets bind the result to the variables.
      
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
  }

}

?>
