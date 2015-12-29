<?php
include_once 'functions.php';
include_once 'fund_object.php';
include_once 'vendor_object.php';
include_once 'PurchaseObject.php';
include_once 'attachment.php';
secureSessionStart();

// -----------------------------------------------------------------------------
// This is the actual pinkie purchase order object.
// This object insert's itself in to a series of tables in a specific order.
// First the submitted_by table, then the Objects, then the Expenses, then
// attachements. A failure at any level will create a failure on all levels.
// Only if it is successful in loading in all parts will the changes be commited
// to the database, this prevents corruption among the purchase orders.
// -----------------------------------------------------------------------------
class Pinkie
{
    // The ID of the pinkie in the database.
    public $i_PinkieID = -1;

    // Header.
    public $s_Title = '';
    public $s_SubmissionTimeStamp = '';
    public $s_ReferenceNumber = '';
    public $s_Submitter = '';
    public $s_SubmittedFor = '';
    public $s_Status = '';
    public $s_Priority = '';
    public $s_Action = '';

    // Objects
    // An array of PurchaseObject.
    public $a_Objects = array();
    public $d_Total = 0.0;

    public $d_ShippingFreight = 0.0;

    public $s_dateNeeded = '';
    public $s_DelieveryLocation = '';

    public $v_Vendor = null;

    public $s_EquipmentType = '';

    // An array of pinkie expenses.
    public $a_Expenses = array();

    // Justification
    public $s_Justification = '';
    public $s_JustificationText = '';
    public $s_classInstructed = '';
    public $s_Quote = '';

    // UCR Specific Tracking info.
    public $s_EquipmentLocation = '';
    public $s_UCRPropertyTag = '';

    // Attachements
    // An array of Attachement
    public $a_Attachments = array();

    //**************************************************************************
    // FUNCTIONS.
    //**************************************************************************

    // Adds a single object to the pinkie.
    public function addObject($i_Quantity, $s_StockNumber, $s_Descripton, $s_BC, $s_AccountNumber, $d_UnitPrice)
    {
        $_ob = new PurchaseObject($this->i_PinkieID);
        $_ob->i_Quantity = $i_Quantity;
        $_ob->s_StockNumber = $s_StockNumber;
        $_ob->s_Description = $s_Descripton;
        $_ob->s_BC = $s_BC;
        $_ob->s_AccountNumber = $s_AccountNumber;
        $_ob->d_UnitPrice = $d_UnitPrice;
        array_push($this->a_Objects, $_ob);
    }

    // Updates all the objects with the current pinkieID.
    public function updateObjects()
    {
        foreach ($this->a_Objects as $_ob)
        {
          $_ob->i_PinkieID = $this->i_PinkieID;
        }
    }

    // Adds a single expense to the Pinkie.
    public function addExpense($d_amt, $f_fund)
    {
        $_exp = new PinkieExpense($this->i_PinkieID, $f_fund, $d_amt);
        array_push($this->a_Expenses, $_exp);
    }

    // Updates all the expenses with the current PinkieID
    public function updateExpenses()
    {
        foreach ($this->a_Expenses as $_e)
        {
            $_e->i_PinkieID = $this->i_PinkieID;
        }
    }

    // Attaches a single file to the pinkie.
    public function addAttachment($s_filePath)
    {
        $_att = new Attachement($this->i_PinkieID);
        $_att->s_FilePath = $s_filePath;

        array_push($this->a_Attachments, $_att);
    }

    // Updates all the attachments with the current PinkieID.
    public function updateAttachements()
    {
        foreach ($this->a_Attachments as $_f)
        {
            $_f->i_PinkieID = $this->i_PinkieID;
        }
    }

    //**************************************************************************
    // Database FUNCTIONS
    //**************************************************************************

    // Puts the pinki in the database.
    public function toDatabase()
    {

        if($this->i_PinkieID > 0)
        {
            $this->update();
        }
        else
        {
            $this->addNew();
        }
    }

    // Loads the pinkie from the database.
    public function fromDatabase()
    {
      // Check if a file ID has been set.
      if($this->i_PinkieID < 0)
      {
          onError("Pinkie::fromDatabase()", "Failed to load Pinkie from database because no PinkieID was set.");
      }

      // Everything is all good, load it from the database.
      // Connect to the database.
      $_db = getMysqli();
      // SQL query to run.
      $statement = $_db->prepare("SELECT * FROM Submitted_By WHERE PinkieID=?");
      $statement->bind_param('i', $this->i_PinkieID);
      $statement->execute();

      // Error running the statment.
      if($statement->errno != 0)
      {
        $_tmp = $statement->error;
        $statement->close();
        $_db->close();
        onError("Pinkie::fromDatabase()",'There was an error running the query [' . $_tmp . '] Could not fetch Pinkie.');
      }

      $statement->store_result();
      if($statement->num_rows == 0)
      {
          $statement->free_result();
          $statement->close();
          $_db->close();
          onError("Pinkie::fromDatabase()","Failed to find a Pinkie with the given PinkieID of: ".$this->i_PinkieID);
      }
      // We have a result, lets bind the result to the variables.
      $statement->bind_result($throwaway, $this->s_SubmissionTimeStamp, $this->s_Submitter, $this->s_SubmittedFor, $this->s_Title, $this->s_Status, $this->d_Total);
      $statement->fetch();

      // Cleanup.
      $statement->free_result();
      $statement->close();
      $_db->close();

      // Now we need to load all the children that are attached to this pinkie.
      $this->getObjects();
      $this->getExpenses();
      $this->getAttachments();
      $this->getPinkieInformation();

    }

    // Updates this pinkie in the database.
    function update()
    {
      // Everything all good, lets update the table.
      $_db = getMysqli();
      $_sql = "UPDATE Submitted_By SET Submitter=?, SubmittedFor=?, Title=?, Status=?, TotalValue=? WHERE PinkieID=?";
      $_stmt = $_db->prepare((string)$_sql);

      $_stmt->bind_param('ssssdi', $this->s_Submitter, $this->s_SubmittedFor, $this->s_Title, $this->s_Status, $this->d_Total, $this->i_PinkieID);
      $_stmt->execute();

      if ($_stmt->errno)
      {
        onError("Pinkie::update()", $_stmt->error);
      }

      $_stmt->close();

      $_sql = "UPDATE PinkieInformation SET VendorID=?, Justification=?, JustificationText=?, EquipmentLocation=?, UCRPropertyNumber=?, ClassInstructed=?, Quote=?, Action=?, Priority=?, ReferenceNumber=?, EquipmentType=?, ShippingFreight=? WHERE PinkieID=?";
      $_stmt = $_db->prepare((string)$_sql);
      $_stmt->bind_param('issssssssssdi', $this->v_Vendor, $this->s_Justification, $this->s_JustificationText, $this->s_EquipmentLocation, $this->s_UCRPropertyTag, $this->s_classInstructed, $this->s_Quote, $this->s_Action, $this->s_Priority, $this->s_ReferenceNumber, $this->s_EquipmentType, $this->d_ShippingFreight, $this->i_PinkieID);

      $_stmt->execute();

      if ($_stmt->errno)
      {
        onError("Pinkie::update() Extra Info", $_stmt->error);
      }
      $_stmt->close();

      // Close up the database connection.
      $_db->close();

      // Update all the other objects, assumes that the pinkieID of each of
      // those has been set previously.
      foreach ($this->a_Objects as $_ob)
      {
        $_ob->toDatabase();
      }

      foreach ($this->a_Expenses as $_e)
      {
          $_e->toDatabase();
      }

      foreach ($this->a_Attachments as $_f)
      {
          $_f->toDatabase();
      }
    }

    // Adds a brand new pinkie to the database.
    function addNew()
    {
      // Everything all good, lets insert in to the table.
      $_db = getMysqli();
      $_sql = "INSERT INTO Submitted_By (Submitter, SubmittedFor, Title, Status, TotalValue) VALUES (?,?,?,?,?)";
      $_stmt = $_db->prepare((string)$_sql);

      $_stmt->bind_param('ssssd', $this->s_Submitter, $this->s_SubmittedFor, $this->s_Title, $this->s_Status, $this->d_Total);
      $_stmt->execute();

      if ($_stmt->errno)
      {
        onError("Pinkie::addNew()", $_stmt->error);
      }

      // Get the pinkie id of the thing we just inserted.
      $this->i_PinkieID = $_db->insert_id;
      $_stmt->close();
      $_sql = "INSERT INTO PinkieInformation (PinkieID, VendorID, Justification, JustificationText, EquipmentLocation, UCRPropertyNumber, ClassInstructed, Quote, Action, Priority, ReferenceNumber, EquipmentType, ShippingFreight) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $_stmt = $_db->prepare((string)$_sql);
      $_stmt->bind_param('iissssssssssd', $this->i_PinkieID, $this->v_Vendor, $this->s_Justification, $this->s_JustificationText, $this->s_EquipmentLocation, $this->s_UCRPropertyTag, $this->s_classInstructed, $this->s_Quote, $this->s_Action, $this->s_Priority, $this->s_ReferenceNumber, $this->s_EquipmentType, $this->d_ShippingFreight);

      $_stmt->execute();

      if ($_stmt->errno)
      {
        onError("Pinkie::addNew() Extra Info", $_stmt->error);
      }
      $_stmt->close();
      // Close up the database connection.
      $_db->close();

      // Now we need to submit all the other stuff.
      // Probably not the most efficient way to do it, runs O(2n), could be O(n)
      $this->updateObjects();
      $this->updateExpenses();
      $this->updateAttachements();

      foreach ($this->a_Objects as $_ob)
      {
        $_ob->toDatabase();
      }

      foreach ($this->a_Expenses as $_e)
      {
          $_e->toDatabase();
      }

      foreach ($this->a_Attachments as $_f)
      {
          $_f->toDatabase();
      }

    }

    // Gets all the objects associated with this pinkie.
    function getObjects()
    {
      // Everything is all good, load it from the database.
      // Connect to the database.
      $_db = getMysqli();
      // SQL query to run.
      $statement = $_db->prepare("SELECT ObjectID FROM Objects WHERE PinkieID=?");
      $statement->bind_param('i', $this->i_PinkieID);
      $statement->execute();

      // Error running the statment.
      if($statement->errno != 0)
      {
        $_tmp = $statement->error;
        $statement->close();
        $_db->close();
        onError("Pinkie::getObjects()",'There was an error running the query [' . $_tmp . '] Could not fetch Objects.');
      }

      $statement->store_result();
      if($statement->num_rows == 0)
      {
          $statement->free_result();
          $statement->close();
          $_db->close();
          if(TRUE)
          {
              onError("Pinkie::getObjects()",'Could not find any objects associated with the PID of: '.$this->i_PinkieID);
          }
      }

      // We have a results.
      $statement->bind_result($_tempObjectID);
      while($statement->fetch())
      {
          $_o = new PurchaseObject($this->i_PinkieID);
          $_o->i_ObjectID = (int)$_tempObjectID;
          $_o->fromDatabase();
          array_push($this->a_Objects, $_o);
          onError("Pinkie::getObjects()",'FOUND a Object associated with the PID of: '.$this->i_PinkieID.' And the object ID of '.$_tempObjectID);
      }

      // Cleanup.
      $statement->free_result();
      $statement->close();
      $_db->close();
    }

    // Gets all the expenses associated with this pinkie.
    function getExpenses()
    {
      // Everything is all good, load it from the database.
      // Connect to the database.
      $_db = getMysqli();
      // SQL query to run.
      $statement = $_db->prepare("SELECT ExpenseID FROM Expenses WHERE PinkieID=?");
      $statement->bind_param('i', $this->i_PinkieID);
      $statement->execute();

      // Error running the statment.
      if($statement->errno != 0)
      {
        $_tmp = $statement->error;
        $statement->close();
        $_db->close();
        onError("Pinkie::getExpenses()",'There was an error running the query [' . $_tmp . '] Could not fetch Expenses.');
      }

      $statement->store_result();
      if($statement->num_rows <= 0)
      {
          $statement->free_result();
          $statement->close();
          $_db->close();
          if(SQL_NO_RESULTS_BREAK)
          {
              onError("Pinkie::getExpenses()",'Could not find any expenses associated with the PID of: '.$this->i_PinkieID);
          }
          return;
      }

      // We have a results.
      $statement->bind_result($_tempExpenseID);
      while($statement->fetch())
      {
          $_e = new PinkieExpense($this->i_PinkieID, 0, 0);
          $_e->i_ExpenseID = (int)$_tempExpenseID;
          $_e->fromDatabase();
          array_push($this->a_Expenses, $_e);
      }

      // Cleanup.
      $statement->free_result();
      $statement->close();
      $_db->close();
    }

    // Gets all the attachments associated with this pinkie.
    function getAttachments()
    {
      // Everything is all good, load it from the database.
      // Connect to the database.
      $_db = getMysqli();
      // SQL query to run.
      $statement = $_db->prepare("SELECT AttachmentID FROM Attachments WHERE PinkieID=?");
      $statement->bind_param('i', $this->i_PinkieID);
      $statement->execute();

      // Error running the statment.
      if($statement->errno != 0)
      {
        $_tmp = $statement->error;
        $statement->close();
        $_db->close();
        onError("Pinkie::getAttachments()",'There was an error running the query [' . $_tmp . '] Could not fetch Attachments.');
      }

      $statement->store_result();
      if($statement->num_rows <= 0)
      {
          $statement->free_result();
          $statement->close();
          $_db->close();
          if(SQL_NO_RESULTS_BREAK)
          {
              onError("Pinkie::getAttachments()",'Could not find any attachments associated with the PID of: '.$this->i_PinkieID);
          }
          return;
      }

      // We have a results.
      $statement->bind_result($_tempAttachementID);
      while($statement->fetch())
      {
          $_f = new Attachement($this->i_PinkieID);
          $_f->i_FileID = (int)$_tempAttachementID;
          $_f->fromDatabase();
          array_push($this->a_Attachments, $_f);
      }

      // Cleanup.
      $statement->free_result();
      $statement->close();
      $_db->close();
    }

    // Gets all the other info associated with a pinkie.
    function getPinkieInformation()
    {
      // Check if a file ID has been set.
      if($this->i_PinkieID < 0)
      {
          onError("Pinkie::getPinkieInformation()", "Failed to load Pinkie from database because no PinkieID was set.");
      }

      // Everything is all good, load it from the database.
      // Connect to the database.
      $_db = getMysqli();
      // SQL query to run.
      $statement = $_db->prepare("SELECT * FROM PinkieInformation WHERE PinkieID=?");
      $statement->bind_param('i', $this->i_PinkieID);
      $statement->execute();

      // Error running the statment.
      if($statement->errno != 0)
      {
        $_tmp = $statement->error;
        $statement->close();
        $_db->close();
        onError("Pinkie::getPinkieInformation()",'There was an error running the query [' . $_tmp . '] Could not fetch Pinkie.');
      }

      $statement->store_result();
      if($statement->num_rows <= 0)
      {
          $statement->free_result();
          $statement->close();
          $_db->close();
          onError("Pinkie::getPinkieInformation()","Failed to find a Pinkie with the given PinkieID of: ".$this->i_PinkieID);
      }
      // We have a result, lets bind the result to the variables.
      $statement->bind_result($this->i_PinkieID, $this->v_Vendor, $this->s_Justification, $this->s_JustificationText, $this->s_EquipmentLocation, $this->s_UCRPropertyTag, $this->s_classInstructed, $this->s_Quote, $this->s_Action, $this->s_Priority, $this->s_ReferenceNumber, $this->s_EquipmentType, $this->d_ShippingFreight);
      $statement->fetch();

      // Cleanup.
      $statement->free_result();
      $statement->close();
      $_db->close();
    }

}

//------------------------------------------------------------------------------
// This object keeps track of the pinkie expense. How much money is drawn from
// what fund.
//------------------------------------------------------------------------------
class PinkieExpense
{
    public $f_FundID = -1;
    public $d_Amount = 0.0;
    public $i_PinkieID = -1;
    public $i_ExpenseID = -1;

    // Default constructor.
    public function __construct($i_PID, $f_F, $d_amt)
    {
        $this->i_PinkieID = $i_PID;
        $this->d_Amount = $d_amt;
        $this->f_FundID = $f_F;
    }

    public function toDatabase()
    {
        if($this->i_PinkieID < 0)
        {
            onError("PinkieExpense::toDatabase()", "Failed to add expense because no pinkieID was set.");
        }

        if($this->f_FundID < 0)
        {
            onError("PinkieExpense::toDatabase()", "Failed to add expense because no FundID was set.");
        }


        if($this->i_ExpenseID > 0)
        {
            $this->update();
        }
        else
        {
            $this->addNew();
        }
    }

    public function fromDatabase()
    {
        if($this->f_FundID < 0)
        {
            onError("PinkieExpense::fromDatabase()", "Failed to load expense because no ExpenseID was set.");
        }

        // Connect to the database.
        $_db = getMysqli();
        // SQL query to run.
        $statement = $_db->prepare("SELECT * FROM Expenses WHERE ExpenseID=?");
        $statement->bind_param('i', $this->i_ExpenseID);
        $statement->execute();

        // Error running the statment.
        if($statement->errno != 0)
        {
          $_tmp = $statement->error;
          $statement->close();
          $_db->close();
          onError("PinkieExpense::fromDatabase()",'There was an error running the query [' . $_tmp . '] Could not fetch Expense.');
        }


        $statement->store_result();
        if($statement->num_rows <= 0)
        {
            $statement->free_result();
            $statement->close();
            $_db->close();
            onError("PinkieExpense::fromDatabase()","Failed to find a Expense with the given ExpenseID of: ".$this->i_ExpenseID);
        }
        // We have a result, lets bind the result to the variables.
        $statement->bind_result($throwaway, $this->i_PinkieID, $this->d_Amount, $this->f_FundID);
        $statement->fetch();

        // Cleanup.
        $statement->free_result();
        $statement->close();
        $_db->close();
    }

    function update()
    {
      // Everything all good, lets update the table.
      $_db = getMysqli();
      $_sql = "UPDATE Expenses SET PinkieID=?, Amount=?, FundID=? WHERE ExpenseID=?";
      $_stmt = $_db->prepare((string)$_sql);

      $_stmt->bind_param('idii', $this->i_PinkieID, $this->d_Amount, $this->f_FundID, $this->i_ExpenseID);
      $_stmt->execute();

      if ($_stmt->errno)
      {
        onError("PinkieExpense::update()", $_stmt->error);
      }

      $_stmt->close();
      // Close up the database connection.
      $_db->close();
    }

    function addNew()
    {
      // Everything all good, lets insert in to the table.
      $_db = getMysqli();
      $_sql = "INSERT INTO Expenses (PinkieID, Amount, FundID) VALUES (?,?,?)";
      $_stmt = $_db->prepare((string)$_sql);

      $_stmt->bind_param('idi', $this->i_PinkieID, $this->d_Amount, $this->f_FundID);
      $_stmt->execute();

      if ($_stmt->errno)
      {
        onError("PinkieExpense::addNew()", $_stmt->error);
      }
      $_stmt->close();
      // Close up the database connection.
      $_db->close();
    }
}
?>
