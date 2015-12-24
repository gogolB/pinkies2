<?php
include_once 'functions.php';
include_once 'fund_object.php';
include_once 'vendor_object.php';
include_once 'PurchaseObject.php';
include_once 'attachement.php';
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
    public $s_SubmissionTimeStamp = '';
    public $s_ReferenceNumber = '';

    // Objects
    // An array of PurchaseObject.
    public $a_Objects = array();

    public $d_ShippingFreight = 0.0;

    public $s_dateNeeded = '';
    public $s_DelieveryLocation = '';

    public $v_Vendor = null;

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

    public $s_EquipmentType = '';

    public $s_Priority = '';

    public $s_status = '';

    // attachements
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
        array_push($a_Expenses, $_exp);
    }

    // Updates all the expenses with the current PinkieID
    public function updateExpenses()
    {
        foreach ($this->$a_Expenses as $_e)
        {
            $_e->i_PinkieID = $this->i_PinkieID;
        }
    }

    // Attaches a single file to the pinkie.
    public function addAttachment($s_filePath)
    {
        $_att = new Attachement($this->i_PinkieID);
        $_att->s_FilePath = $s_filePath;

        array_push($a_Attachments, $_att);
    }

    // Updates all the attachments with the current PinkieID.
    public function updateAttachements()
    {
        foreach ($this->$a_Attachments as $_f)
        {
            $_f->i_PinkieID = $this->i_PinkieID;
        }
    }

}

//------------------------------------------------------------------------------
// This object keeps track of the pinkie expense. How much money is drawn from
// what fund.
//------------------------------------------------------------------------------
class PinkieExpense
{
    public $f_FundID = - 1;
    public $d_Amount = 0.0;
    public $i_PinkieID = -1;
    public $i_ExpenseID = -1;

    // Default constructor.
    public function __construct($i_PID, $f_F, $d_amt)
    {
        $this->i_PinkieID = $i_PID;
        $this->$d_Amount = $d_amt;
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
        $statement->bind_result($throwaway, $this->i_PinkieID, $this->d_Amount, $this->i_FundID);
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
      $_stmt = $_db->prepare($_sql);

      $_stmt->bind_param('idii', $this->i_PinkieID, $this->$d_Amount, $this->i_FundID, $this->i_ExpenseID);
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
      $_stmt = $_db->prepare($_sql);

      $_stmt->bind_param('idi', $this->i_PinkieID, $this->d_Amount, $this->i_FundID);
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
