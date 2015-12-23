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
        $_ob->s_Descripton = $s_Descripton;
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
    public $f_Fund;
    public $d_amount;
    public $i_PinkieID;
    public $i_ExpenseID;

    // Default constructor.
    public function __construct($i_PID, $f_F, $d_amt)
    {
        $this->i_PinkieID = $i_PID;
        $this->d_amount = $d_amt;
        $this->f_Fund = $f_F;
    }
}
?>
