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

}

function onAddFund()
{

}

function onEditFund()
{

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
