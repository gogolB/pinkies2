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
  var subT = calculateSubTotal(form);
}

// Calculates and sets the subtotal.
function calculateSubTotal(form)
{
  var subTotal = 0;
  if(!form.elements['quantity[]'].length)
  {
    var objectTotal = parseInt(form.elements['quantity[]'].value) * parseFloat(form.elements['unitPrice[]'].value);
    objectTotal = parseFloat(objectTotal).toFixed(2);
    form.elements['totalPrice[]'].value = objectTotal;
    subtotal = objectTotal;
  }
  else
  {
    for(var i = 0; i < form.elements['quantity[]'].length; i++)
    {
      subtotal += calculateObjectTotal(form, i);
    }
  }
  form.elements['subtotal'].value = parseFloat(subtotal).toFixed(2);
  return subTotal;
}

// Calculates and sets the tax, set at 8%.
function calculateTax(form, subTotal)
{
    var salesTax = 0.08; // Sales tax in riverside.
    var tax = parseFloat((subTotal * salesTax)).toFixed(2)
    form.elements['tax'].value = tax;
    return tax;

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
    var SubTotal = parseFloat(form.elements['subtotal'].value);
    var Tax = parseFloat(form.elements['tax'].value);
    var Shipping = 0;
    if(form.elements['shipping'].value != null || form.elements['shipping'].value != '')
    {
      Shipping = parseFloat(form.elements['shipping'].value);
    }
    var Total = parseFloat((SubTotal + Tax + Shipping)).toFixed(2);
    form.elements['total'].value = Total;
    return Total;
}

function calculateTotalExpenses()
{

}

function updateForm()
{

}
