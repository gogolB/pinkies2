function addFundModal()
{
    $('#addFundModal').modal('show');
}

function editFundModal()
{
    $('#editFundModal').modal('show');
}

function deleteFundModal()
{
    $('#deleteFundModal').modal('show');
    var m = "clicked: " + key + " on " + $(this).text();
    window.console && console.log(m) || alert(m);
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
        "delete": {name: "Delete", icon: "delete" , callback: deleteFundModal},
        'add':{name: "Add", icon:"add", callback: addFundModal}
    }
});
});
