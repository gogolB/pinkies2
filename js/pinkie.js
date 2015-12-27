//------------------------------------------------------------------------------
// Here are all the supporting functions for a pinkie object.
//------------------------------------------------------------------------------
function onNewSubmit(form)
{
    if(checkform(form))
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

function checkForm(form)
{
  var subTotal = calculateSubTotal();
}

// Calculates and sets the subtotal.
function calculateSubTotal(form)
{
  var subTotal = 0.0;
  for(var i; i < form['quantity[]'].length; i++)
  {
      subtotal += calculateObjectTotal(form, i);
  }
  form['subtotal'].value = subtotal;
  alert("subtotal is " + subtotal);
  return subTotal;
}

function calculateTax(form)
{

}

// This calculates the total per object, basically multiplies the quantity and
// the unit amount. Then sets the appropriate value.
function calculateObjectTotal(form, i)
{
  var objectTotal = form['quantity[]'][i].value * form['unitPrice[]'][i].value;
  form['totalPrice[]'][i].value = objectTotal;
  return objectTotal;
}

function calculateTotal(form)
{

}

function calculateTotalExpenses()
{

}

function updateForm()
{

}
