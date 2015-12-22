<?php
include 'functions.php';
// -----------------------------------------------------------------------------
// Vendor class is responsible for carrying and holding all the variables
// associated with the vendor object. This object can construct itself from a
// given vendorID number or put it self in the database (Update and add).
// -----------------------------------------------------------------------------
class Vendor
{
  // Vendor Identity stuff.
  public $s_VendorName = 'aMemberVar Member Variable';
  public $i_VendorID = 0;

  // Address Variables
  public $s_Address = 'aMemberFunc';
  public $s_City = 'aMemberFunc';
  public $s_State = 'aMemberFunc';
  public $s_Zip = 'aMemberFunc';
  public $s_Country = 'USA';

  // UCR specific Vendor Variables
  public $s_UCRAccountID = '';

  // Contact Info.
  public $s_POC = 'Person';
  public $s_PhoneNumber = '';
  public $s_FaxNumber = '';
  public $s_Internet = '';
}
?>
