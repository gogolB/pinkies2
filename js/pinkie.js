//------------------------------------------------------------------------------
// Here are all the supporting functions for a pinkie object.
//------------------------------------------------------------------------------
function addAObject()
{
  if(currentObject < maxObject)
  {
    var div = document.createElement("div");
        div.id = 'PurchaseObject'+ currentObject;
        div.innerHTML = addObjectInput;
        document.getElementById('moreObjects').appendChild(div);
        currentObject++; 
        return false;
  }
  else
  {
    alert("Too many objects with this Pinkie.");
  }
}

function addAFund()
{
  if(currentFund < maxFunds)
  {
    var div = document.createElement("div");
        div.id = 'Expense'+ currentFund;
        div.innerHTML = addFundInput;
        document.getElementById('moreFunds').appendChild(div);
        currentFund++;
        return false;
  }
  else
  {
    alert("Too many objects with this Pinkie.");
  }
}
