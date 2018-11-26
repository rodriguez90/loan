
var handleDataTable  = function (){

    if ($('#data-table').length !== 0)
    {
        var table = $('#data-table').DataTable({
            // dom: '<"top"i>flBpt<"bottom"Bp><"clear">',
            // dom: '<"top">flBpt<"bottom"Bp>',
            'dom': "<'row'<'col-sm-4'><'col-sm-4'f><'col-sm-4'p>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-4'><'col-sm-4'><'col-sm-4'p>>",
            // dom: '<"top"ip<"clear">>t',
            // "dom": 'lrtip',
            "ajax": {
                "url": homeUrl + "loan/list",
                "type": "GET",
                'data':{}
            },
            'pagingType': "full_numbers",
            'paging': true,
            'lengthMenu': [5, 10, 15],
            'pageLength': 10,
            'language': lan,
            responsive: true,
            'columnDefs': [
                {
                    orderable: true,
                    searchable: true,
                    targets:   [0,1,2,3,4,5,6]
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
                        return '-';
                    }
                },
                {
                    targets: 5,
                    data:null,
                    render: function ( data, type, full, meta )
                    {
                        var period = moment(full.start_date).format('DD-MM-YYYY') + ' - ' + moment(full.end_date).format('DD-MM-YYYY');
                        return period;
                    }
                },
                {
                    targets: 7,
                    title:"Estado",
                    data:'status',
                    render: function ( data, type, full, meta )
                    {
                        if(type == 'display')
                        {
                            var customHtml;
                            if(data == 1)
                            {
                                customHtml = '<span class="label label-danger pull-left f-s-12">Pendiente</span>';
                            }
                            else if(data == 2)
                            {
                                customHtml = '<span class="label label-success f-s-12">Cobrado</span>'
                            }
                            else if(data == 0 && full.refinancing_id > 0)
                            {
                                customHtml = '<span class="label label-warning f-s-12">Refinanciado</span>'
                            }

                            return customHtml;
                        }
                        return data == 1 ? 'Cobrado':'Pendiente';
                    }
                },
                {
                    targets: 8,
                    data:null,
                    render: function ( data, type, full, meta ) {
                        var elementId =  String(full.id);
                        if(type == 'display')
                        {
                            // var ticketClass = full.countContainer == full.countTicket ? 'btn-default':'btn-success';

                            var selectHtml = "<div class=\"row row-fluid\">";
                            selectHtml += "<div class=\"col col-xs-12\">" ;
                            selectHtml += "<a " + "href=\"" + homeUrl + "loan/view?id=" + elementId + "\" class=\"btn btn-info btn-icon btn-circle btn-xs\" title=\"Ver\"><i class=\"fa fa-eye\"></i></a>";
                            selectHtml += "<a " + "href=\"" + homeUrl + "loan/update?id=" + elementId + "\" class=\"btn btn-primary btn-icon btn-circle btn-xs\" title=\"Editar\"><i class=\"fa fa-edit\"></i></a>";

                            if(full.status == 1)
                            {
                                selectHtml += "<a " + "href=\"" + homeUrl + "loan/refinance?id=" + elementId + "\" class=\"btn btn-success btn-icon btn-circle btn-xs\" title=\"Refinanciar\"><i class=\"fa fa-refresh\"></i></a>";
                            }

                            // if(data.status == 0)
                            // selectHtml += "<a data-confirm=\"¿Está seguro que desea registrar el pago?\" data-method=\"post\"" + " href=\"" + homeUrl + "payment/pay?id=" + elementId +  "\" class=\"btn btn-primary btn-icon btn-circle btn-xs\" title=\"Pagar\"><i class=\"fa fa-credit-card\"></i></a>";
                            selectHtml += "<button data-row=\"" + meta.row +"\" + data-name=\"" + elementId +  "\" class=\"btn btn-danger btn-icon btn-circle btn-xs\" title=\"Eliminar\"><i class=\"fa fa-trash\"></i></button>";
                            selectHtml += "</div>";
                            selectHtml += "</div>";

                            return selectHtml;
                        }
                        return "-";
                    }
                }
            ],
            'columns': [
                { 'data':'id' },
                { 'data':'customerName' },
                { 'data':"porcent_interest" },
                { 'data':"amount" },
                { 'data':"fee_payment" },
                { 'data':null },
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
                content: 'Esta seguro que desea eliminar el préstamo?',
                buttons: {
                    confirm: {
                        text:'Confirmar',
                        btnClass: 'btn-danger',
                        action: function () {
                            $.ajax({
                                async:false,
                                url: homeUrl + "loan/delete?id="+id,
                                type: "POST",
                                // data:{'id':id},
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
                                    $.alert('Ha ocurrido un error al intenar eliminar el préstamo!');
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