function addFundModal()
{
    $('#addFundModal').modal('show');
}

$(function() {
    $('#fundTable').contextMenu({
      selector: 'tr',
      callback:function(key, options) {
        var m = "clicked: " + key + " on " + $(this).text();
        window.console && console.log(m) || alert(m);
    },
    items: {
        "edit": {name: "Edit", icon: "edit"},
        "delete": {name: "Delete", icon: "delete"},
        'add':{name: "Add", icon:"add", callback: addFundModal}
    }
});
});
