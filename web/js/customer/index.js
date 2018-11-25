
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
                "url": homeUrl + "customer/list",
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
                    targets:   [0,1,2,3,5]
                },
                {
                    targets: 4,
                    data:'lcoation',
                    render: function ( data, type, full, meta )
                    {
                        if(type == 'display')
                        {
                            var selectHtml = "<a " + "href=\"" + data +  "\" class=\"btn btn-info btn-icon btn-circle btn-xs\" title=\"Ver Ubicación\"><i class=\"fa fa-map-marker\"></i></a>";
                            return selectHtml;
                        }
                        return '-';
                    }
                },
                {
                    targets: 5,
                    title:"Estado",
                    data:'active',
                    render: function ( data, type, full, meta )
                    {
                        if(type == 'display')
                        {
                            var customHtml;
                            if(data == 0)
                            {
                                customHtml = '<span class="label label-danger pull-left f-s-12">Inactivo</span>';
                            }
                            else if(data == 1)
                            {
                                customHtml = '<span class="label label-success f-s-12">Activo</span>'
                            }

                            return customHtml;
                        }
                        return data == 1 ? 'Activo':'Inactivo';
                    }
                },
                {
                    targets: 6,
                    data:null,
                    render: function ( data, type, full, meta ) {
                        var elementId =  String(full.id);
                        if(type == 'display')
                        {
                            // var ticketClass = full.countContainer == full.countTicket ? 'btn-default':'btn-success';

                            var selectHtml = "<div class=\"row row-fluid\">";
                            selectHtml += "<div class=\"col col-xs-12\">" ;
                            selectHtml += "<a " + "href=\"" + homeUrl + "customer/view?id=" + elementId + "\" class=\"btn btn-info btn-icon btn-circle btn-xs\" title=\"Ver\"><i class=\"fa fa-eye\"></i></a>";
                            selectHtml += "<a " + "href=\"" + homeUrl + "customer/update?id=" + elementId + "\" class=\"btn btn-primary btn-icon btn-circle btn-xs\" title=\"Modificar\"><i class=\"fa fa-edit\"></i></a>";
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
                { 'data':'customerName' },
                { 'data':"dni" },
                { 'data':"phone_number" },
                { 'data':"email" },
                { 'data':"location" },
                { 'data':"active" },
                { 'data':null }
            ],
            // 'select': {
            //     'style': 'multi',
            //     'selector': 'td .dt-checkboxes'
            //     // 'selector': 'td:not(:first-child)'
            //     // 'selector': 'td:not(.control)'
            //     // 'selector': 'td:eq(7)'
            //     // 'selector': 'tr.td:eq(6)'
            // },
            "order": [[ 0, 'asc']]
        });

        $('#data-table').on('click', 'button', function()
        {
            var id = $(this).data('name');
            var row = $(this).data('row');

            $.confirm({
                title: 'Advertencia!',
                content: 'Esta seguro que desea eliminar el cliente?',
                buttons: {
                    confirm: {
                        text:'Confirmar',
                        btnClass: 'btn-danger',
                        action: function () {
                            $.ajax({
                                async:false,
                                url: homeUrl + "customer/delete?id="+id,
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
                                    $.alert('Ha ocurrido un error al intenar eliminar el cliente!');
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