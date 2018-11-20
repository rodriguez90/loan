var table = null;

var handleDataTable = function(columns, columnsDef, params){

    console.log(columns);
    console.log(columnsDef);

    if ($('#data-table').length !== 0)
    {
        if(table != null)
        {
            table = $('#data-table').DataTable();
            table.destroy();
            $('#data-table').empty();
        }

        table = $('#data-table').DataTable({
            'dom': "<'row'<'col-sm-4'B><'col-sm-4'f><'col-sm-4'p>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-4'B><'col-sm-6'p>>",
            // dom: '<"top"ip<"clear">>t',
            // "dom": 'lrtip',
            "ajax": {
                "url": homeUrl + "site/report",
                "type": "GET",
                'data':params
            },
            'pagingType': "full_numbers",
            'paging': true,
            'lengthMenu': [5, 10, 15],
            'pageLength': 10,
            'language': lan,
            responsive: true,
            'buttons': [
                {
                    'extend':'pdf',
                    'text':'Exportar PDF',
                    'className':'btn btn-success btn-circle btn-xs'
                }
            ],
            // 'columnDefs': columnsDef,
            'columns': columns,
            "order": [[ 0, 'asc']],
            // fixedHeader: {
            //     header:true,
            //     footer:true
            // },
            // "footerCallback": function ( row, data, start, end, display ) {
            //     var api = this.api(), data;
            //
            //     // converting to interger to find total
            //     var intVal = function ( i ) {
            //         return typeof i === 'string' ?
            //             i.replace(/[\$,]/g, '')*1 :
            //             typeof i === 'number' ?
            //                 i : 0;
            //     };
            //
            //     // computing column Total the complete result
            //     var amountTotal = api
            //         .column( 3 )
            //         .data()
            //         .reduce( function (a, b) {
            //             return intVal(a) + intVal(b);
            //         }, 0 );
            //
            //
            //     // Update footer by showing the total with the reference of the column index
            //     $( api.column( 0 ).footer() ).html('Total');
            //     $( api.column( 2 ).footer() ).html(amountTotal);
            // }
        });
    }

};

var builderColumns= function(params)
{
    var columns = [];
    // if(params.customerUnPaid != undefined || params.loanAmount != undefined )
    // {
    //     columns.push( {
    //         'title':'Cliente',
    //         'data':'customerName'
    //     });
    //
    //     columns.push( {
    //         'title':'Cédula',
    //         'data':'dni'
    //     });
    // }

    if(params.option != undefined && params.option == 'customerUnPaid')
    {
        columns.push( {
            'title':'Cliente',
            'data':'customerName'
        });

        columns.push( {
            'title':'Cédula',
            'data':'dni'
        });

        columns.push( {
            'title':'Cantidad por Pagar',
            'data':'amount'
        });

        columns.push( {
            'title':'Fecha de pago',
            'data':'payment_date'
        });
    }

    // if(params.totalCustomer != undefined)
    // {
    //     columns.push( {
    //         'title':'Total de Clientes',
    //         'data':'totalCustomer'
    //     });
    // }

    if(params.option != undefined && params.option == 'loanAmount')
    {
        columns.push( {
            'title':'Cantidad Préstada',
            'data':'loanAmount'
        });
    }

    if(params.option != undefined && params.option == 'amountPaid')
    {
        columns.push( {
            'title':'Cantidad por Cobrar',
            'data':'amountPaid'
        });
    }

    if(params.option != undefined && params.option == 'earnings')
    {
        // el reporte debe ser en base al préstamo
        columns.push( {
            'title':'Ganancias',
            'data':'earnings'
        });
    }

    return columns;
};

var builderColumnsDef= function(params)
{
    var columns = [];
    // if(params.customerUnPaid != undefined)
    // {
    //     columns.push( {
    //         'titl':'Cliente',
    //         'data':'customerName'
    //     });
    //
    //     columns.push( {
    //         'titl':'Cantidad por Pagar',
    //         'data':'amount'
    //     });
    // }

    // if(params.totalCustomer != undefined)
    // {
    //     columns.push( {
    //         'titl':'Total de Clientes',
    //         'data':'totalCustomer'
    //     });
    // }

    // if(params.loanAmount != undefined)
    // {
    //     columns.push( {
    //         'title':'Cantidad Préstada',
    //         'data':'loanAmount'
    //     });
    // }

    // if(params.amountPaid != undefined)
    // {
    //     columns.push( {
    //         'title':'Cantidad por Pagar',
    //         'data':'amountPaid'
    //     });
    // }

    // if(params.earnings != undefined)
    // {
    //     columns.push( {
    //         'title':'Ganancias',
    //         'data':'earnings'
    //     });
    // }

    return columns;
};

var builderFooter = function(params){

    var footer = '';

    if(params.customerUnPaid != undefined)
    {

    }

    return footer;
};

$(document).ready(function () {

    $('#reset').click(function (e) {
        $('#w1').val('');
        $('#w1-start').val('');
        $('#w1-end').val('');
    });

    $('#searchBtn').click(function (e) {

        var params = $('#w0').serializeObject();//JSON.stringify();
        console.log(params);

        if(params.option === undefined)
        {
            $.alert({
                title:'Advertencia',
                content:'Debe seleccionar alguna de las opciones',
                buttons: {
                    confirm: {
                        text:'Aceptar'
                    }
                }
            });
        }
        else {
            handleDataTable(builderColumns(params),
                            builderColumnsDef(params),
                            params);

            // $.ajax({
            //     async:false,
            //     url: homeUrl + "site/report",
            //     type: "GET",
            //     data:params,
            //     success: function (response) {
            //         if(!response.success)
            //         {
            //             $.alert(
            //                 {
            //                     title:'Error',
            //                     content:response.msg,
            //                     buttons: {
            //                         confirm: {
            //                             text:'Aceptar',
            //                         }
            //                     }
            //                 });
            //         }
            //         else {
            //
            //         }
            //     },
            //     error: function(data) {
            //         $.alert('Ha ocurrido un error al registrar la cuota !');
            //     }
            // });
        }
    });
});