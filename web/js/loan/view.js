var handleDataTable = function() {

    if ($('#data-table').length !== 0) {

        var ajax = null;

        var columns = [
            {
                "title": "No.",
                "data":null
            },
            {
                "title": "Fecha",
                "data":"payment_date"
            }
        ];

        var columnDefs = [
            {
                'targets':[0],
                'data':null,
                'render': function ( data, type, full, meta ) {
                    return meta.row + 1 ;
                }
            }
        ];

        ajax =  {
            "url": homeUrl + "payment/list",
            "type": "GET",
            'data':{'loanId':loan.id}
        };

        var columnFee = {
            "title": "Cuota",
            "data":"amount"
        };

        var columnStatus = {
            "title": "Estado",
            "data":"status"
        };

        var columnActions = {
            "title": "Acciones",
            "data":null
        };

        columns.push(columnFee);
        columns.push(columnStatus);
        columns.push(columnActions);

        var columnsDefStatus =  {
            targets: 3,
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
        };


        var columnDefActions =  {
            targets: 4,
            data:null,
            render: function ( data, type, full, meta ) {
                var elementId =  String(full.id);
                if(type == 'display')
                {
                    // var ticketClass = full.countContainer == full.countTicket ? 'btn-default':'btn-success';

                    var selectHtml = "<div class=\"row row-fluid\">";
                    selectHtml += "<div class=\"col col-xs-12\">" ;
                    selectHtml += "<a " + "href=\"" + homeUrl + "payment/view?id=" + elementId + "\" class=\"btn btn-primary btn-icon btn-circle btn-xs\" title=\"Ver\"><i class=\"fa fa-eye\"></i></a>";
                    selectHtml += "<a " + "href=\"" + homeUrl + "payment/update?id=" + elementId + "\" class=\"btn btn-success btn-icon btn-circle btn-xs\" title=\"Modificar\"><i class=\"fa fa-edit\"></i></a>";
                    if(data.status == 0)
                    // selectHtml += "<a data-confirm=\"¿Está seguro que desea registrar el pago?\" data-method=\"post\"" + " href=\"" + homeUrl + "payment/pay?id=" + elementId +  "\" class=\"btn btn-primary btn-icon btn-circle btn-xs\" title=\"Pagar\"><i class=\"fa fa-credit-card\"></i></a>";
                        selectHtml += "<button data-row=\"" + meta.row +"\" + data-name=\"" + elementId +  "\" class=\"btn btn-primary btn-icon btn-circle btn-xs\" title=\"Pagar\"><i class=\"fa fa-credit-card\"></i></button>";
                    selectHtml += "</div>";
                    selectHtml += "</div>";

                    return selectHtml;
                }
                return "-";
            }
        };

        columnDefs.push(columnsDefStatus);
        columnDefs.push(columnDefActions);

        var  table = $('#data-table').DataTable({
            // dom: '<"top"iflp<"clear">>rt',
            dom: '<"top"<"clear">>tp',
            // processing:true,
            lengthMenu: [5, 10, 15],
            pageLength: 3,
            language: lan,
            order: [[ 0, 'asc' ]],
            responsive: true,
            deferRender: false,
            "ajax": ajax,
            columns: columns,
            columnDefs:columnDefs
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
                                                            var data = table
                                                                .row(row)
                                                                .data();
                                                            data.status = 1;
                                                            table
                                                                .row(row)
                                                                .data(data);
                                                            table.draw();

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

    }
};


$(document).ready(function () {

    handleDataTable();

});