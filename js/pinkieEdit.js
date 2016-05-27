function addFundModal(key, options)
{
    $('#addFundModal').modal('show');
}

function editFundModal(key, options)
{
    $('#editFundModal').modal('show');
    $('#currentFund').val($(this).attr("fname"));
    $('#editTotal').val($(this).attr("amt"));
    $('#editExpenseID').val($(this).attr("id"));
}

function deleteFundModal(key, options)
{
    $('#deleteFundModal').modal('show');
    $('#deleteFundName').val($(this).attr("fname"));
    $('#deleteFundTotal').val($(this).attr("amt"));
    $('#deleteExpenseID').val($(this).attr("id"));
}

function onDeleteFund()
{
    $.ajax({
       type: "POST",
       url: "./includes/editPinkieExpenses.php",
       data: "mode=delete" + "&expenseID=" + $('#deleteExpenseID').val(),
       success : function(text)
       {
           if(text.localeCompare("OKAY") == 0)
           {
             $('#deleteFundModal').modal('hide');
             // Need to refresh the table somehow...
             return;
           }
           alert(text);
       }
   });
}

function onAddFund()
{
   $.ajax({
      type: "POST",
      url: "./includes/editPinkieExpenses.php",
      data: "mode=add" + "&fundID=" + $('#newFund').val() + "&fundAmt=" + $('#newFundTotal').val() + "&pinkieID=" + $('#pinkieID').val(),
      success : function(text)
      {
         if(text.localeCompare("OKAY") == 0)
         {
            $('#editFundModal').modal('hide');
            // Need to refresh the table somehow...
            return;
         }
         alert(text);
      }
 });
}

function onEditFund()
{
   $.ajax({
      type: "POST",
      url: "./includes/editPinkieExpenses.php",
      data: "mode=edit" + "&fundID=" + $('#editFund').val() + "&fundAmt=" + $('#editTotal').val() + "&expenseID=" + $('#editExpenseID').val(),
      success : function(text)
      {
         if(text.localeCompare("OKAY") == 0)
         {
            $('#addFundModal').modal('hide');
            // Need to refresh the table somehow...
            return;
         }
         alert(text);
      }
 });
}

$(function() {
    $('#fundTable').contextMenu({
      selector: 'tr',
      callback:function(key, options) {
        var m = "clicked: " + key + " on " + $(this).text();
        window.console && console.log(m) || alert(m);
    },
    items: {
        "edit": {name: "Edit", icon: "edit" , callback: editFundModal},
        "delete": {name: "Remove", icon: "delete" , callback: deleteFundModal},
        'add':{name: "Add", icon:"add", callback: addFundModal}
    }
});
});



$(function() {
    $('#objectTable').contextMenu({
      selector: 'tr',
      callback:function(key, options) {
        var m = "clicked: " + key + " on " + $(this).text();
        window.console && console.log(m) || alert(m);
    },
    items: {
        "edit": {name: "Edit", icon: "edit" , callback: editObjectModal},
        "delete": {name: "Remove", icon: "delete" , callback: deleteObjectModal},
        'add':{name: "Add", icon:"add", callback: addObjectModal}
    }
});
});


function addObjectModal(key, options)
{
    $('#addObjectModal').modal('show');
}

function editObjectModal(key, options)
{
    $('#editObjectModal').modal('show');

    $.ajax({
      type: "POST",
      url: "./includes/getObjectData.php",
      data: "pinkieID=" + $('#pinkieID').val() + "&objectID=" + $(this).attr("id"),
      success : function(text)
      {
         var test = $.parseJSON(text);

         // Set the values of the variables
         $('#editPurchaseObjectDescription').val(test.s_Description);
         $('#editPurchaseObjectUnitPrice').val(test.d_UnitPrice);
         $('#editPurchaseObjectQuantity').val(test.i_Quantity);
         var total = parseFloat(test.i_Quantity) * parseFloat(test.d_UnitPrice);
         $('#editPurchaseObjectTotalPrice').val(total.toFixed(2));
         $('#editPurchaseObjectID').val(test.i_ObjectID);
         $('#editPurchaseObjectAccountNumber').val(test.s_AccountNumber);
         $('#editPurchaseObjectStockNumber').val(test.s_StockNumber);
         $('#editPurchaseObjectBC').val(test.s_BC);


      }
 });

}

function deleteObjectModal(key, options)
{
    $('#deleteObjectModal').modal('show');

    // Fetch all the data from the server.
    $.ajax({
      type: "POST",
      url: "./includes/getObjectData.php",
      data: "pinkieID=" + $('#pinkieID').val() + "&objectID=" + $(this).attr("id"),
      success : function(text)
      {
         var test = $.parseJSON(text);

         // Set the values of the variables
         $('#deletePurchaseObjectDescription').val(test.s_Description);
         $('#deletePurchaseObjectUnitPrice').val(test.d_UnitPrice);
         $('#deletePurchaseObjectQuantity').val(test.i_Quantity);
         var total = parseFloat(test.i_Quantity) * parseFloat(test.d_UnitPrice);
         $('#deletePurchaseObjectTotalPrice').val(total.toFixed(2));
         $('#deletePurchaseObjectID').val(test.i_ObjectID);


      }
 });

}

function onAddObject()
{
   $.ajax({
     type: "POST",
     url: "./includes/editPurchaseObject.php",
     data: "mode=add&pinkieID="+$('#pinkieID').val()+"&description="+$('#newPurchaseObjectDescription').val()+"&unitPrice="+$('#newPurchaseObjectUnitPrice').val()+"&quantity="+$('#newPurchaseObjectQuantity').val(),
     success : function(text)
     {
        alert(text);
     }
     });
}

function onEditObject()
{
   $.ajax({
     type: "POST",
     url: "./includes/editPurchaseObject.php",
     data: "mode=edit&objectID="+$('#editPurchaseObjectID').val()+"&quantity="+$('#editPurchaseObjectQuantity').val()+"&stockNumber="+$('#editPurchaseObjectStockNumber').val()+"&description="+$('#editPurchaseObjectDescription').val()+"&bc="+
     $('#editPurchaseObjectBC').val()+"&accountNumber="+$('#editPurchaseObjectAccountNumber').val()+"&unitPrice="+$('#editPurchaseObjectUnitPrice').val(),
     success : function(text)
     {
        alert(text);
     }
     });
}

function onDeleteObject()
{
   $.ajax({
     type: "POST",
     url: "./includes/editPurchaseObject.php",
     data: "mode=delete&objectID="+$('#deletePurchaseObjectID').val(),
     success : function(text)
     {
        alert(text);
     }
     });

}

// Input for tax calculation
$( "#includeTax" ).change(function()
{
   alert($(this).val());
});
