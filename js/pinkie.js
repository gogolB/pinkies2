//------------------------------------------------------------------------------
// Here are all the supporting functions for a pinkie object.
//------------------------------------------------------------------------------
function onNewSubmit(form)
{
    if(checkform())
    {
      attachStatus(form, "PendingSuperApproval");
    }
}

function attachStatus(form, status)
{
  var input = document.createElement("input");
  input.setAttribute("type", "hidden");
  input.setAttribute("name", "status");
  input.setAttribute("value", status);
  form.appendChild(input);
}

function checkForm()
{

}

function calculateSubTotal()
{

}

function calculateTax()
{

}

function calculateObjectTotal()
{

}

function calculateTotal()
{

}

function calculateTotalExpenses()
{

}

function updateForm()
{

}
