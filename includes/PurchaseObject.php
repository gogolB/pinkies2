<?php
include_once 'functions.php';
secureSessionStart();
//------------------------------------------------------------------------------
// This represents the object that is being purchased through the pinkie.
//------------------------------------------------------------------------------
class PurchaseObject
{
    public $i_ObjectID = -1;

    public $i_Quantity = 0;
    public $s_StockNumber = '';
    public $s_Description = '';
    public $s_BC = '';
    public $s_AccountNumber = '';
    public $d_UnitPrice = 0.0;

    // Pinkie Parent Object.
    public $i_PinkieID = -1;

    // Default constructor.
    public function __construct($i_PID)
    {
        $this->i_PinkieID = $i_PID;
    }

    public function getTotalCost()
    {
        return $this->i_Quantity * $this->d_UnitPrice;
    }

    public function toDatabase()
    {
        // Checks to make sure everything is okay before uploading.
        if($this->i_PinkieID < 0)
        {
            onError("PurchaseObject::toDatabase()", "Failed to add to the database because no PinkieID was set.");
        }

        if(strlen($this->s_Description) == 0)
        {
            onError("PurchaseObject::toDatabase()", "Failed to add to the database because there was no Description set.");
        }

        if($this->i_Quantity < 0)
        {
            onError("PurchaseObject::toDatabase()", "Failed to add to the database because the quantity was invalid. Can not be less than 0!");
        }

        if($this->d_UnitPrice < 0)
        {
            onError("PurchaseObject::toDatabase()", "Failed to add to the database because the Unit Price was invalid. Can not be less than 0!");
        }

        // Everything checks out, add to the database.
        if($this->i_ObjectID > 0)
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

    }

    function update()
    {

    }

    function addNew()
    {

    }
}
?>
