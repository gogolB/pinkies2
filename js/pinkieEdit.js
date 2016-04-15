function clicky()
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
        "delete": {name: "Delete", icon: "delete", callback: clicky},
        'add':{name: "Add", icon:"add"}
    }
});
});
