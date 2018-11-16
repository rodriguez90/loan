var selectedPayments = [];
var selectAll = false;

var handleDataTable  = function (){

    if ($('#data-table').length !== 0)
    {
        var table = $('#data-table').DataTable({
            // dom: '<"top"i>flBpt<"bottom"Bp><"clear">',
            dom: '<"top">flBpt<"bottom"Bp><"clear">',
            // dom: '<"top"ip<"clear">>t',
            buttons: [
                {
                    text: 'Pagar Seleccionados',
                    action: function ( e, dt, node, config )
                    {
                        // this.disable(); // disable button
                        // var count = table.rows( { selected: true } ).count();
                        selectedPayments = [];

                        table.rows( { selected: true }).data().each( function ( value, index ) {

                            selectedPayments.push(parseInt(value.id));
                        });
                        console.log(selectedPayments);

                        if(selectedPayments.length <= 0)
                            alert( 'Debe seleccionar los pagos.' );
                        else
                        {
                            var r = confirm('Esta seguro que desea regitrar las cuotas?');
                            if(r == true)
                            {
                                $.ajax({
                                    // async:false,
                                    url: homeUrl + "payment/pay-bulk",
                                    type: "POST",
                                    dataType: "json",
                                    data:  {
                                        'payments':selectedPayments
                                    },
//                            contentType: "application/json; charset=utf-8",
                                    beforeSend:function () {
                                        // $("#modal-select-bussy").modal("show");
                                    },
                                    success: function (response) {

                                        // $("#modal-select-bussy").modal("hide");
                                        // // you will get response from your php page (what you echo or print)
                                        // // console.log(response);
                                        //
                                        // if(response['success'])
                                        // {
                                        //     result = true;
                                        //     window.location.href = response['url'];
                                        // }
                                        // else
                                        // {
                                        //     alert(response['msg']);
                                        // }
                                        // result = false;
                                    },
                                    error: function(data) {
                                        // $("#modal-select-bussy").modal("hide");
                                        // // console.log(data);
                                        // console.log(data.responseText);
                                        // result = false;
                                        // return false;
                                    },
                                });
                            }
                        }
                    },
                    className: 'btn btn-primary btn-xs',
                    // name: 'payBtn'
                }
            ],
            pagingType: "full_numbers",
            paging: true,
            lengthMenu: [5, 10, 15],
            pageLength: 10,
            language: lan,
            responsive: true,
            // responsive: {
            //     details: false
            // },
            rowId: 'id',
            "ajax": homeUrl + "payment/list",
            "processing": true,
            // "serverSide": true,
            // deferRender:true,
            select: {
                // items: 'cells',
                style:    'multi',
                // selector: 'td:first-child'
            },
            "columns": [
                {
                    // "title": "Cliente",
                    "data":'customerName',
                },
                {
                    // "title":'Cuota',
                    "data":"amount",
                },
                {
                    "title":'Cédula',
                    "data":"dni",
                },
                {
                    // "title":'Fecha de Pago',
                    "data":"payment_date",
                },
                {
                    // "title":'Cobrador',
                    "data":"collectorName",
                },
                {
                    // "title": "Estado",
                    "data":"status",
                },
                {
                    // "title": "Selecionar",
                    "data":'id', // FIXME CHECK THIS
                },
                {
                    // "title": "Acciones",
                    "data":null
                },
            ],
            "order": [[ 2, 'asc']],
            columnDefs: [
                // {
                //     'targets': 5,
                //     'searchable':false,
                //     'orderable':false,
                //     'className': 'dt-body-center',
                //     'render': function (data, type, full, meta) {
                //         return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
                //     }
                // },
                {
                    orderable: true,
                    searchable: true,
                    targets:   [0,1,2,3,4,5]
                },
                {
                    targets: [3],
                    data:'dni',
                },
                {
                    targets: [4],
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
                    'targets': 6,
                    'searchable':false,
                    'orderable':false,
                    'checkboxes': {
                        'selectRow': true
                    },
                    data:'id'
                },
                {
                    targets: [7],
                    data:null,
                    render: function ( data, type, full, meta ) {
                        var elementId =  String(full.id);
                        if(type == 'display')
                        {
                            // var ticketClass = full.countContainer == full.countTicket ? 'btn-default':'btn-success';

                            var selectHtml = "<div class=\"row row-fluid\">";
                            selectHtml += "<div class=\"col col-xs-12\">" ;
                            selectHtml += "<a " + "href=\"" + homeUrl + "payment/view?id=" + elementId + "\" class=\"btn btn-info btn-icon btn-circle btn-xs\" title=\"Ver\"><i class=\"fa fa-eye\"></i></a>";
                            selectHtml += "<a " + "href=\"" + homeUrl + "payment/update?id=" + elementId + "\" class=\"btn btn-success btn-icon btn-circle btn-xs\" title=\"Editar\"><i class=\"fa fa-edit\"></i></a>";
                            selectHtml += "<a data-confirm=\"¿Está seguro que desea registrar el pago?\" data-method=\"post\"" + " href=\"" + homeUrl + "payment/pay?id=" + elementId + "&from=1"+ "\" class=\"btn btn-primary btn-icon btn-circle btn-xs\" title=\"Pagar\"><i class=\"fa fa-credit-card\"></i></a>";
                            selectHtml += "</div>";
                            selectHtml += "</div>";

                            return selectHtml;
                        }
                        return "-";
                    },
                },
            ],
        });

        table.on( 'user-select', function ( e, dt, type, cell, originalEvent ) {

            var index = cell.index();
            // alert('user-select: ' + index.column);

            if(index.column != 5)
            {
                e.preventDefault();
                return false;
            }
        } );

        // Handle click on "Select all" control
        // $('#select-all').on('click', function(){
        //     // Get all rows with search applied
        //     var rows = table.rows({ 'search': 'applied' }).nodes();
        //     // Check/uncheck checkboxes for all rows in the table
        //     $('input[type="checkbox"]', rows).prop('checked', this.checked);
        // });
        //
        // // Handle click on checkbox to set state of "Select all" control
        // $('#data-table tbody').on('change', 'input[type="checkbox"]', function(){
        //     // If checkbox is not checked
        //     if(!this.checked){
        //         var el = $('#select-all').get(0);
        //         // If "Select all" control is checked and has 'indeterminate' property
        //         if(el && el.checked && ('indeterminate' in el)){
        //             // Set visual state of "Select all" control
        //             // as 'indeterminate'
        //             el.indeterminate = true;
        //         }
        //     }
        // });
    }
}

$(document).ready(function () {
    handleDataTable();
});