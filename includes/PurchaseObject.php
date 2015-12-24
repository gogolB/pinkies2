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
      // Check if a file ID has been set.
      if($this->i_FileID < 0)
      {
          onError("PurchaseObject::fromDatabase()", "Failed to load attachment from database because no ObjectID was set.");
      }

      // Everything is all good, load it from the database.
      // Connect to the database.
      $_db = getMysqli();
      // SQL query to run.
      $statement = $_db->prepare("SELECT * FROM Objects WHERE ObjectID=?");
      $statement->bind_param('i', $this->i_ObjectID);
      $statement->execute();

      // Error running the statment.
      if($statement->errno != 0)
      {
        $_tmp = $statement->error;
        $statement->close();
        $_db->close();
        onError("PurchaseObject::fromDatabase()",'There was an error running the query [' . $_tmp . '] Could not fetch Object.');
      }


      $statement->store_result();
      if($statement->num_rows <= 0)
      {
          $statement->free_result();
          $statement->close();
          $_db->close();
          onError("PurchaseObject::fromDatabase()","Failed to find a Object with the given ObjectID of: ".$this->i_ObjectID);
      }
      // We have a result, lets bind the result to the variables.
      $statement->bind_result($throwaway, $this->i_PinkieID, $this->i_Quantity, $this->s_StockNumber, $this->s_Description, $this->s_BC, $this->s_AccountNumber, $this->d_UnitPrice);
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
      $_sql = "UPDATE Objects SET PinkieID=?, Quantity=?, StockNumber=?, Description=?, BC=?, AccountNumber=?, UnitPrice=? WHERE ObjectID=?";
      $_stmt = $_db->prepare($_sql);

      $_stmt->bind_param('iissssdi', $this->i_PinkieID, $this->i_Quantity, $this->s_StockNumber, $this->s_Description, $this->s_BC, $this->s_AccountNumber, $this->d_UnitPrice, $this->i_ObjectID);
      $_stmt->execute();

      if ($_stmt->errno)
      {
        onError("PurchaseObject::update()", $_stmt->error);
      }

      $_stmt->close();
      // Close up the database connection.
      $_db->close();
    }

    function addNew()
    {
      // Everything all good, lets insert in to the table.
      $_db = getMysqli();
      $_sql = "INSERT INTO Objects (PinkieID, Quantity, StockNumber, Description, BC, AccountNumber, UnitPrice) VALUES (?,?,?,?,?,?,?)";
      $_stmt = $_db->prepare($_sql);

      $_stmt->bind_param('iissssd', $this->i_PinkieID, $this->i_Quantity, $this->s_StockNumber, $this->s_Description, $this->s_BC, $this->s_AccountNumber, $this->d_UnitPrice);
      $_stmt->execute();

      if ($_stmt->errno)
      {
        onError("PurchaseObject::addNew()", $_stmt->error);
      }

      $_stmt->close();
      // Close up the database connection.
      $_db->close();
    }
}
?>
