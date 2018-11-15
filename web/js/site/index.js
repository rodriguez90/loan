var selectedPayments = [];
var selectAll = false;

$(document).ready(function () {

    // var keys = $('.grid-view').yiiGridView('getSelectedRows');
    // console.log(keys);
    $('#select_all').click(function (e, parameters) {
        // selectedPayments
        var nonUI = false;
        try {
            nonUI = parameters.nonUI;
        }catch (e) {
        }
        var checked = nonUI ? !this.checked: this.checked;
        alert('select all: ' + checked);
    })
});