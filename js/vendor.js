//------------------------------------------------------------------------------
// The various function used by the vendor php page.
//------------------------------------------------------------------------------
function addVendorID(vendorID, form)
{
  var input = document.createElement("input");
  input.setAttribute("type", "hidden");
  input.setAttribute("name", "vendorID");
  input.setAttribute("value", vendorID);
  form.appendChild(input);
}

function submitNewVendor(form)
{
  if(form["vendorname"].value == "" ||form["vendorname"].value == null )
  {
    alert("You must have a name for this vendor!");
    return false;
  }
  form.submit();
}

function updateVendor(form, vendorID)
{
  addVendorID(vendorID, form);
  submitNewVendor(form);
}
