//------------------------------------------------------------------------------
// The various function used by the fund php page.
//------------------------------------------------------------------------------
function submitNewFund(form)
{
  if(form["fundName"].value == "" ||form["fundName"].value == null )
  {
    alert("You must have a name for this Fund!");
    return false;
  }
  form.submit();
}

function updateFund(form, fundID)
{
  var input = document.createElement("input");
  input.setAttribute("type", "hidden");
  input.setAttribute("name", "fundID");
  input.setAttribute("value", fundID);
  form.appendChild(input);

  submitNewVendor(form);
}
