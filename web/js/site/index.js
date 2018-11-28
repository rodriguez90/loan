
var handleDataTable  = function (){

    if ($('#data-table').length !== 0)
    {
        var table = $('#data-table').DataTable({
            // dom: '<"top"i>flBpt<"bottom"Bp><"clear">',
            // dom: '<"top">flBpt<"bottom"Bp>',
            'dom': "<'row'<'col-sm-4'B><'col-sm-4'f><'col-sm-4'p>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-4'B><'col-sm-4'p>>",
            // dom: '<"top"ip<"clear">>t',
            // "dom": 'lrtip',
            "ajax": {
                "url": homeUrl + "payment/list",
                "type": "GET",
                'data':{
                    'status':0,
                    'loan_status':1,
                    'paymentDate':moment().format('YYYY-MM-DD')
                }
            },
            'pagingType': "full_numbers",
            'paging': true,
            'lengthMenu': [5, 10, 15],
            'pageLength': 10,
            'language': lan,
            responsive: true,
            // responsive: {
            //     details: false
            // },
            // 'responsive': {
            //     'details': {
            //         'type': 'column',
            //         'target': 0
            //     }
            // },
            // 'rowId': 'id',

            // "processing": true,
            // "serverSide": true,
            // deferRender:true,
            'buttons': [
                {
                    text: 'Pagar Seleccionados',
                    action: function ( e, dt, node, config )
                    {
                        // this.disable(); // disable button
                        // var count = table.rows( { selected: true } ).count();
                        var selectedPayments = [];

                        table.rows( { selected: true }).data().each( function ( value, index ) {

                            selectedPayments.push(parseInt(value.id));
                        });
                        console.log(selectedPayments);

                        if(selectedPayments.length <= 0)
                            $.alert({
                                title: 'Advertencia!',
                                content: 'Debe seleccionar los pagos.',
                                buttons: {
                                    confirm: {
                                        text:'Aceptar'
                                    }
                                }
                            });
                        else
                        {
                            $.confirm({
                                title: 'Advertencia!',
                                content: 'Esta seguro que desea regitrar las cuotas?',
                                buttons: {
                                    confirm: {
                                        text: 'Confirmar',
                                        icon: 'fa fa-credit-card',
                                        // btnClass: 'btn-blue',
                                        keys: ['enter', 'shift'],
                                        action: function () {
                                            $.ajax({
                                                async:false,
                                                url: homeUrl + "payment/pay-bulk",
                                                type: "POST",
                                                dataType: "json",
                                                data: {
                                                    'payments': selectedPayments
                                                },
                                                success: function (response) {
                                                    if(response.success)
                                                    {
                                                        $.alert(
                                                            {
                                                                title:'Información',
                                                                content:response.msg,
                                                                buttons: {
                                                                    confirm: {
                                                                        text:'Aceptar',
                                                                        action:function () {
                                                                            // window.location.href = response.url;
                                                                            table
                                                                                .rows( '.selected' )
                                                                                .remove()
                                                                                .draw();
                                                                        }
                                                                    },
                                                                }
                                                            });
                                                    }
                                                    else {
                                                        $.alert(
                                                            {
                                                                title:'Error',
                                                                content:response.msg,
                                                                buttons: {
                                                                    confirm: {
                                                                        text:'Aceptar',
                                                                    }
                                                                }
                                                            });
                                                    }

                                                },
                                                error: function(data) {
                                                    $.alert('Ha ocurrido un error al registrar la cuota !');
                                                }
                                            });
                                        }
                                    },
                                    cancel: {
                                        text:'Cancelar'
                                    }
                                }
                            });
                        }
                    },
                    className: 'btn btn-primary btn-xs',
                    // name: 'payBtn'
                },
                // 'pdf'
            ],
            'columnDefs': [
                // {
                //     'data': null,
                //     'defaultContent': '',
                //     'className': 'control',
                //     'orderable': false,
                //     'targets': 0,
                //     'checkboxes': {
                //         'selectRow': true
                //     }
                // },
                // {
                //     'targets': 0,
                //     'orderable': false,
                //     // 'className': 'control',
                //     'checkboxes': {
                //         'selectRow': true
                //     }
                // },
                {
                    'data': null,
                    'orderable': false,
                    searchable: true,
                    'targets': 0,
                    'checkboxes': {
                        'selectRow': true
                    }
                },
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
                    targets:   [1,2,3,4,5,6]
                },
                {
                    targets: 1,
                    data:'customerName',
                    render: function ( data, type, full, meta )
                    {
                        if(type == 'display')
                        {
                            return "<a " + "href=\"" + homeUrl + "customer/view?id=" + full.customerId + "\" class=\"\" title=\"Ver\">" + data +"</a>";
                        }
                        return data;
                    }
                },
                {
                    targets: 2,
                    data:'payment_date',
                    render: function ( data, type, full, meta )
                    {
                        return moment(data).format('DD-MM-YYYY');
                    }
                },
                {
                    targets: 6,
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
                    }
                },
                // {
                //     'targets': 7,
                //     'searchable':false,
                //     'orderable':false,
                //     'checkboxes': {
                //         'selectRow': true
                //     },
                //     data:'id'
                // },
                {
                    targets: 7,
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
                            if(data.status == 0)
                                // selectHtml += "<a data-confirm=\"¿Está seguro que desea registrar el pago?\" data-method=\"post\"" + " href=\"" + homeUrl + "payment/pay?id=" + elementId +  "\" class=\"btn btn-primary btn-icon btn-circle btn-xs\" title=\"Pagar\"><i class=\"fa fa-credit-card\"></i></a>";
                                selectHtml += "<button data-row=\"" + meta.row +"\" + data-name=\"" + elementId +  "\" class=\"btn btn-primary btn-icon btn-circle btn-xs\" title=\"Pagar\"><i class=\"fa fa-credit-card\"></i></button>";
                            selectHtml += "</div>";
                            selectHtml += "</div>";

                            return selectHtml;
                        }
                        return "-";
                    }
                }
            ],
            'columns': [
                { 'data':null },
                { 'data':'customerName' },
                { 'data':"payment_date" },
                { 'data':"amount" },
                { 'data':"dni" },
                { 'data':"collectorName" },
                { 'data':"status" },
                { 'data':null }
            ],
            // select: {
            //     // items: 'cells',
            //     style:    'multi',
            //     // selector: 'td:first-child'
            // },
            'select': {
                'style': 'multi',
                'selector': 'td .dt-checkboxes'
                // 'selector': 'td:not(:first-child)'
                // 'selector': 'td:not(.control)'
                // 'selector': 'td:eq(7)'
                // 'selector': 'tr.td:eq(6)'
            },
            "order": [[ 1, 'asc']]
        });

        $('#data-table').on('click', 'button', function()
        {
            var id = $(this).data('name');
            var row = $(this).data('row');

            $.confirm({
                title: 'Advertencia!',
                content: 'Esta seguro que desea regitrar la cuota?',
                buttons: {
                    confirm: {
                        text:'Confirmar',
                        btnClass: 'btn-blue',
                        action: function () {
                            $.ajax({
                                async:false,
                                url: homeUrl + "payment/pay",
                                type: "POST",
                                data:{'id':id},
                                success: function (response) {
                                    if(response.success)
                                    {
                                        $.alert(
                                            {
                                                title:'Información',
                                                content:response.msg,
                                                buttons: {
                                                    confirm: {
                                                        text:'Aceptar',
                                                        action:function () {
                                                            // window.location.href = response.url;
                                                            table
                                                                .row(row)
                                                                .remove()
                                                                .draw();
                                                        }
                                                    }
                                                }
                                            }
                                        );
                                    }
                                    else {
                                        $.alert(
                                            {
                                                title:'Error',
                                                content:response.msg,
                                                buttons: {
                                                    confirm: {
                                                        text:'Aceptar',
                                                    }
                                                }
                                            });
                                    }
                                },
                                error: function(data) {
                                    $.alert('Ha ocurrido un error al registrar la cuota !');
                                }
                            });
                        }
                    },
                    cancel: {
                        text:'Cancelar'
                    }
                }
            });
        });

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