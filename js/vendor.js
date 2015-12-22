//------------------------------------------------------------------------------
// The various function used by the vendor php page.
//------------------------------------------------------------------------------
function submitNewVendor(form)
{
  if(form["vendorName"].value == "" ||form["vendorName"].value == null )
  {
    alert("You must have a name for this vendor!");
    return false;
  }
  form.submit();
}

function updateVendor(form, vendorID)
{
  var input = document.createElement("input");
  input.setAttribute("type", "hidden");
  input.setAttribute("name", "vendorID");
  input.setAttribute("value", vendorID);
  form.appendChild(input);

  submitNewVendor(form);
}
