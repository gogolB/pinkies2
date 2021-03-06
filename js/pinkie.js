//------------------------------------------------------------------------------
// Here are all the supporting functions for a pinkie object.
//------------------------------------------------------------------------------
function onNewSubmit()
{
    var form = document.getElementsByTagName("form")[0];
    if(checkForm(form))
    {
      attachStatus(form, "Waiting for Supervisor Approval");
      form.submit();
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
  // Check all the fields.
  // Check all the objects.
  // Check the stuff at the bottom.


  // Check to make sure all the money value lines up.
  var subT = calculateSubTotal(form);
  var t = calculateTax(form, subT);
  var tot = calculateTotal(form);
  var exp = calculateTotalExpenses(form);
  if(tot == 'NaN' || exp == 'Nan')
  {
    return false;
  }
  else
  {
    return parseFloat(tot) == parseFloat(exp);
  }
}

// Calculates and sets the subtotal.
function calculateSubTotal(form)
{
  var sub_total = 0.0;
  if(!form.elements['quantity[]'].length)
  {
    if(form.elements['quantity[]'].value == '' || form.elements['unitPrice[]'].value == '')
    {
      form.elements['totalPrice[]'].value = '--';
      sub_total =  0.0;
      return;
    }
    var objectTotal = parseInt(form.elements['quantity[]'].value) * parseFloat(form.elements['unitPrice[]'].value);
    objectTotal = parseFloat(objectTotal).toFixed(2);
    form.elements['totalPrice[]'].value = objectTotal;
    sub_total = objectTotal;
  }
  else
  {
    for(var i = 0; i < form.elements['quantity[]'].length; i++)
    {
      sub_total += parseFloat(calculateObjectTotal(form, i));
    }
  }
  form.elements['subtotal'].value = parseFloat(sub_total).toFixed(2);
  return sub_total;
}

// Calculates and sets the tax, set at 8%.
function calculateTax(form, subTotal)
{
    var checkedValue = $('.includeTax:checked').val();
    if(checkedValue)
    {
      var salesTax = 0.08; // Sales tax in riverside.
      var tax = parseFloat((subTotal * salesTax)).toFixed(2)
      form.elements['tax'].value = tax;
      return tax;
    }
    else
    {
      return 0.00;
    }

}

// This calculates the total per object, basically multiplies the quantity and
// the unit amount. Then sets the appropriate value.
function calculateObjectTotal(form, i)
{
  if(form.elements['quantity[]'][i].value == '' || form.elements['unitPrice[]'][i].value == '')
  {
    form.elements['totalPrice[]'][i].value = '--';
    return 0.0;
  }
  var objectTotal = parseInt(form.elements['quantity[]'][i].value) * parseFloat(form.elements['unitPrice[]'][i].value);
  form.elements['totalPrice[]'][i].value = parseFloat(objectTotal).toFixed(2);
  return objectTotal;
}

function calculateTotal(form)
{
    var SubTotal = parseFloat($( "#subtotal" ).val());
    var Tax = parseFloat($( "#tax" ).val());
    var Shipping = 0.0;
    if($( "#shipping" ).val() != '')
    {
      Shipping = parseFloat($( "#shipping" ).val());
    }
    else {
      $( "#shipping" ).val("0.00");
    }
    var Total = parseFloat((SubTotal + Tax + Shipping)).toFixed(2);
    $( "#total" ).val(Total);
    return Total;
}

function calculateTotalExpenses(form)
{
    var Expense = 0.0;
    if(!form.elements['amount[]'].length)
    {
        Expense = parseFloat(form.elements['amount[]'].value).toFixed(2);
    }
    else
    {
        for(var i = 0; i < form.elements['amount[]'].length; i++)
        {
          Expense += parseFloat(form.elements['amount[]'][i].value);
        }
    }
    form.elements['totalExpense'].value = parseFloat(Expense).toFixed(2);
    return Expense;
}

function onObjectChange()
{
  var form = document.getElementsByTagName("form")[0];
  var subT = calculateSubTotal(form);
  var t = calculateTax(form, subT);
  var tot = calculateTotal(form);
}

function onShippingChange()
{
  var form = document.getElementsByTagName("form")[0];
  var tot = calculateTotal(form);
}

function onExpenseChange()
{
    var form = document.getElementsByTagName("form")[0];
    var exp = calculateTotalExpenses(form);
}

function attachPinkieID(form, pinkieID)
{
  var input = document.createElement("input");
  input.setAttribute("type", "hidden");
  input.setAttribute("name", "pinkieID");
  input.setAttribute("value", pinkieID);
  form.appendChild(input);
}

function onCancel(pinkieID)
{
  var form = document.getElementsByTagName("form")[0];
  attachPinkieID(form, pinkieID);
  attachStatus(form, "Cancelled");
  form.submit();
}

function onEdit(pinkieID)
{
  var form = document.getElementsByTagName("form")[0];
  attachPinkieID(form, pinkieID);
  form.submit();
}

function onArchive(pinkieID)
{
  var form = document.getElementsByTagName("form")[0];
  attachPinkieID(form, pinkieID);
  attachStatus(form, "Archived");
  form.submit();
}

// Input for tax calculation
$(function(){
   $( "#includeTax:checkbox" ).on("change",function()
   {
      if($('#includeTax').is(':checked'))
      {
         subTotal = parseFloat($("#subtotal").val());
         tax = subTotal * 0.08;// Sales tax in california.
         $("#tax").val(tax.toFixed(2));
      }
      else
      {
         $("#tax").val("0.00");
      }
      calculateTotal();
   });
});
