//------------------------------------------------------------------------------
// Here are all the supporting functions for a pinkie object.
//------------------------------------------------------------------------------
function onNewSubmit()
{
    var form = document.getElementsByTagName("form")[0];
    if(checkForm(form))
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
  var subTotal = calculateSubTotal(form);
}

// Calculates and sets the subtotal.
function calculateSubTotal(form)
{
  var subTotal = 0.0;
  alert("Total number of objects: " + form.elements['quantity[]'].length);
  for(var i; i < form.elements['quantity[]'].length; i++)
  {
      subtotal += calculateObjectTotal(form, i);
  }
  //form.elements['subtotal'].value = subtotal;
  return subTotal;
}

function calculateTax(form)
{

}

// This calculates the total per object, basically multiplies the quantity and
// the unit amount. Then sets the appropriate value.
function calculateObjectTotal(form, i)
{
  var objectTotal = parseInt(form.elements['quantity[]'][i].value) * parseFloat(form.elements['unitPrice[]'][i].value);
  alert(objectTotal);
  form.elements['totalPrice[]'][i].value = objectTotal;
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
