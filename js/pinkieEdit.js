function addFundModal(key, options)
{
    $('#addFundModal').modal('show');
}

function editFundModal(key, options)
{
    $('#editFundModal').modal('show');
    $('#editFund').val($(this).attr("fname"));
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
       success : function(text){
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
      success : function(text){
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
      success : function(text){
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
