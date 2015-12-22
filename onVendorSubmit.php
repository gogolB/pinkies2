<?php
include_once 'includes/functions.php';
include_once 'includes/sessionFunctions.php';
include_once 'includes/vendor_object.php';
secureSessionStart();

if(!isset($_POST['vendorname']))
{
  onError("Vendor Submission Error!", "No Vendor name set! Can not update in database without vendor name");
}

$_vendor = new Vendor();
if(isset($_POST['vendorID']))
{
  $_vendor->i_VendorID = $_POST['vendorID'];
}

$_vendor->s_VendorName = $_POST['vendorname'];

$_vendor->s_Address = $_POST['address'];
$_vendor->s_City = $_POST['city'];
$_vendor->s_State = $_POST['state'];
$_vendor->s_Zip = $_POST['zip'];
$_vendor->s_Country = $_POST['country'];

$_vendor->s_UCRAccountID = $_POST['ucrAccountID'];

$_vendor->s_POC = $_POST['poc'];
$_vendor->s_PhoneNumber = $_POST['phoneNumber'];
$_vendor->s_FaxNumber = $_POST['faxNumber'];
$_vendor->s_Internet = $_POST['internet'];

$_vendor->toDatabase();

// All done, redirect to the vendor list page.
header("Location: ./vendor.php?reason=list");

?>
