var selectedPayments = [];
var selectAll = false;

var handleDataTable  = function (){

    if ($('#data-table').length !== 0)
    {
        var table = $('#data-table').DataTable({
            dom: '<"top"i>flpt<"bottom"p><"clear">',
            pagingType: "full_numbers",
            processing:true,
            lengthMenu: [5, 10, 15],
            "pageLength": 10,
            "language": lan,
            responsive: true,
            rowId: 'id',
            // "processing": true,
            // "serverSide": true,
            "ajax": homeUrl + "/payment/list",
            // deferRender:true,
            "columns": [
                {
                    "title": "Seleccionar",
                    "data":'checkbox',
                },
                {
                    "title": "Cliente",
                    "data":'customerName',
                },
                {   "title":'Cuota',
                    "data":"amount",
                },
                {   "title":'Fecha de Pago',
                    "data":"payment_date",
                },
                {   "title":'Cobrador',
                    "data":"collectorName",
                },
                { "title": "Estado",
                    "data":"status",
                },
                { "title": "Acciones",
                    "data":null
                },
            ],
            "order": [[ 3, 'des']],
            columnDefs: [
                {
                    orderable: false,
                    searchable: false,
                    className: 'select-checkbox',
                    targets:   [0],
                },
                {
                    orderable: true,
                    searchable: true,
                    targets:   [1,2,3,4,5]
                },
                {
                    targets: [3],
                    data:'payment_date',
                    render: function ( data, type, full, meta )
                    {

                        return moment(data).format('DD-MM-YYYY');
                    },
                },
                {
                    targets: [5],
                    title:"Estado",
                    data:'status',
                    render: function ( data, type, full, meta )
                    {
                        if(type == 'display')
                        {
                            var customHtml = data == 1? '<span class="label label-success pull-left f-s-12">Cobrado</span>' :
                                '<span class="label label-danger f-s-12">Pendiente</span>';

                            return customHtml;
                        }
                        return data == 1 ? 'Cobrado':'Pendiente';
                    },
                },
                {
                    targets: [6],
                    data:null,
                    render: function ( data, type, full, meta ) {
                        var elementId =  String(full.id);
                        if(type == 'display')
                        {
                            // var ticketClass = full.countContainer == full.countTicket ? 'btn-default':'btn-success';

                            var selectHtml = "<div class=\"row row-space-2\">";
                            selectHtml += "<div class=\"col col-md-12\">" ;
                            // selectHtml += "<a " + "href=\"" + homeUrl + "/rd/ticket/view?id=" + elementId + "\" class=\"btn btn-info btn-icon btn-circle btn-sm\" title=\"Ver\"><i class=\"fa fa-eye\"></i></a>";
                            // selectHtml += "<a " + "href=\"" + homeUrl + "/rd/ticket/update?id=" + elementId + "\" class=\"btn btn-success btn-icon btn-circle btn-sm\" title=\"Editar\"><i class=\"fa fa-edit\"></i></a>";
                            selectHtml += "<a data-confirm=\"¿Está seguro de eliminar este turno ?\" data-method=\"post\"" + " href=\"" + homeUrl + "/payment/delete?id=" + elementId + "&from=1"+ "\" class=\"btn btn-danger btn-icon btn-circle btn-sm\" title=\"Eliminar\"><i class=\"fa fa-trash\"></i></a>";
                            selectHtml += "</div>";
                            selectHtml += "</div>";

                            return selectHtml;
                        }
                        return "-";
                    },
                },
            ],
        });
    }
}

$(document).ready(function () {

    // handleDataTable();
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