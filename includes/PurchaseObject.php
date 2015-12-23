<?php
include_once 'functions.php';
secureSessionStart();
//------------------------------------------------------------------------------
// This represents the object that is being purchased through the pinkie.
//------------------------------------------------------------------------------
class PurchaseObject
{
    public $i_objectID = -1;

    public $i_Quantity = 0;
    public $s_StockNumber = '';
    public $s_Descripton = '';
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
}
?>
