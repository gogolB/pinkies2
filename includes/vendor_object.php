<?php
include_once 'functions.php';
// -----------------------------------------------------------------------------
// Vendor class is responsible for carrying and holding all the variables
// associated with the vendor object. This object can construct itself from a
// given vendorID number or put it self in the database (Update and add).
// -----------------------------------------------------------------------------
class Vendor
{
  // Vendor Identity stuff.
  public $s_VendorName = '';
  public $i_VendorID = -1;

  // Address Variables
  public $s_Address = '';
  public $s_City = '';
  public $s_State = '';
  public $s_Zip = '';
  public $s_Country = '';

  // UCR specific Vendor Variables
  public $s_UCRAccountID = '';

  // Contact Info.
  public $s_POC = '';
  public $s_PhoneNumber = '';
  public $s_FaxNumber = '';
  public $s_Internet = '';

  public function toDatabase()
  {
    if($i_VendorID > 0)
    {
        // This vendor already exists!
        // We just need to update it.
        updateInDatabase();
    }
    else
    {
      // It doesn't have a vendor ID. Doesn't mean it doesn't exist in the
      // database. Need to do a search by name.

      // Connect to the database.
      $_db = getMysqli();
      // SQL query to run.
      $_sql = "SELECT 1 FROM Vendors WHERE VendorName=".$s_VendorName;

      if(!$_result = $_db->query($_sql))
      {
        $_tmp = $_db->error;
        $_db->close();
        onError("Database Error in Vendor",'There was an error running the query [' . $_tmp . ']');
      }
      else
      {

        if($_result->num_rows > 0)
        {
          // It exists in the database, we just need to update it.
          $i_VendorID = $_result['VendorID'];
          updateInDatabase();
        }
        else
        {
          // It doesn't exist in the database, we need to add it.
          addNewVendor();
        }
      }
      $_result->free();
      $_db->close();
    }
  }

  public function fromDatabase()
  {
    if($i_VendorID < 0)
    {
      onError("Vendor, no VendorID set", "Could not run a search in the database because no vendorID was set.");
    }
    else
    {
      // Connect to the database.
      $_db = getMysqli();
      // SQL query to run.
      $_sql = "SELECT 1 FROM Vendors WHERE VendorID=".$i_VendorID;

      if(!$_result = $_db->query($_sql))
      {
        $_db->close();
        onError("Database Error in Vendor",'There was an error running the query [' . $_db->error . ']. Could not load vendor from database.');
      }
      else
      {

        if($_result->num_rows > 0)
        {
          // It exists in the database! Populate all the variables.
          $s_VendorName= $_result['VendorName'];

          $s_Address= $_result['Address'];
          $s_City= $_result['City'];
          $s_State= $_result['State'];
          $s_Zip= $_result['Zip'];
          $s_Country= $_result['Country'];

          $s_UCRAccountID= $_result['UCRAccountID'];

          $s_POC= $_result['POC'];
          $s_PhoneNumber= $_result['PhoneNumber'];
          $s_FaxNumber= $_result['FaxNumber'];
          $s_Internet= $_result['Internet'];

          $_result->free();
        }
        else
        {
          $_db->close();
          onError("Vendor not Found",'Could not find the vendor with the given vendorid of '.$i_VendorID);
        }
      }

      $_db->close();
    }
  }

  // Update this object in the database, requied that the VendorID be set.
  private function updateInDatabase()
  {
    $_db = getMysqli();
    $_sql = "UPDATE Vendors SET VendorName=?, Address=?, City=?, State=?, Zip=?, Country=?, UCRAccountID=?, POC=?, PhoneNumber=?, FaxNumber=?, Internet=? WHERE VendorID=?";
    $_stmt = $_db->prepare($_sql);

    $_stmt->bind_param('sssssssssssd', $s_VendorName, $s_Address, $s_City, $s_State, $s_Zip, $s_Country, $s_UCRAccountID, $s_POC, $s_POC, $s_PhoneNumber, $s_FaxNumber, $s_Internet, $i_VendorID);
    $_stmt->execute();

    if ($_stmt->errno)
    {
      onError("Error in Vendor::updateInDatabase()", $_stmt->error);
    }

    $_stmt->close();
    // Close up the database connection.
    $_db->close();

  }

  // We are adding a new Vendor.
  private function addNewVendor()
  {
    $_db = getMysqli();
    $_sql = "INSERT INTO Vendors (VendorName, Address, City, State, Zip, Country, UCRAccountID, POC, PhoneNumber, FaxNumber, Internet) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    $_stmt = $_db->prepare($_sql);

    $_stmt->bind_param('sssssssssss', $s_VendorName, $s_Address, $s_City, $s_State, $s_Zip, $s_Country, $s_UCRAccountID, $s_POC, $s_POC, $s_PhoneNumber, $s_FaxNumber, $s_Internet);
    $_stmt->execute();

    if ($_stmt->errno)
    {
      onError("Error in Vendor::addNewVendor()", $_stmt->error);
    }

    $_stmt->close();
    // Close up the database connection.
    $_db->close();
  }
}
?>
