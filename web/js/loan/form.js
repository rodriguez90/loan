
var payments = [];

var generateFee = function () {
    // alert('generateFee');
    var flag = true;
    var error = '';

    var fee = 0;
    fee = parseFloat($('#loan-fee_payment').prop('value'));

    var interes = 0;
    interes = parseInt($('#loan-porcent_interest option:selected').val());

    var amount = 0;
    amount = parseFloat($('#loan-amount').prop('value'));

    var start_date = moment($('#w1-start').prop('value'), 'DD-MM-YYYY'); //.format('DD-MM-YYYY');
    var end_date = moment($('#w1-end').prop('value'), 'DD-MM-YYYY');//.format('DD-MM-YYYY');

    var frequency = parseInt($('#loan-frequency_payment').prop('value'));

    var diff = 0;
    var fee_count = 0;
    var total = 0;

    if(isNaN(interes) || interes == 0)
    {
        flag = false;
        error = 'Debe definir el interés del préstamo.';
    }

    if(flag && isNaN(amount) || amount == 0)
    {
        flag = false;
        error = 'Debe definir la cantidad del préstamo.';
    }
    else
    {
        // amount = round(amount,2);
    }

    if(flag && (!start_date.isValid()
        || !end_date.isValid()))
    {
        flag = false;
        error = 'Debe definir el plazo del préstamo.';
    }
    else {
        // diff = moment.duration(end_date.subtract(start_date, 'days'), 'days', true);
        diff = end_date.diff(start_date, 'days', true);
    }

    if(flag && isNaN(frequency) || frequency == 0)
    {
        flag = false;
        error = 'Debe definir la frecuencia de pago.';
    }

    if(flag && frequency >= diff )
    {
        flag = false;
        error = 'La frequencia de pago debe estar comprendida en el plazo del préstamo.';
    }


    if(!flag)
    {
        $.alert(
            {
                title:'Advertencia!',
                content:error,
                buttons: {
                    confirm: {
                        text:'Aceptar',
                    }
                }
            }
        );

        return;
    }

    if(flag)
    {
        var params = $('#w0').serializeObject();
        console.log(params);

        var jsConfirm = null;

        $.ajax({
            // async:false,
            url: homeUrl + "loan/compute-loan",
            type: "POST",
            dataType: "json",
            data:  params,
//                            contentType: "application/json; charset=utf-8",
            beforeSend:function () {

            },
            success: function (response) {

                if(response.success)
                {
                    var data = response.data;

                    $('#loan-fee_payment').val(data.fee);
                    document.getElementById('countFee').innerHTML = "Cantidad de Coutas: " + data.paymentCount;
                    document.getElementById('total').innerHTML = "Total a cancelar: " + data.total.toFixed(2);
                    document.getElementById('total').innerHTML = "Total a cancelar: " + data.total.toFixed(2);

                    var table = $('#data-table').DataTable();
                    table
                        .clear()
                        .draw();
                    table.rows.add(data.payments).draw();

                    // for (var i=0; i < data.payments.length; i++)
                    // {
                    //     payments.push(data.payments[i].payment_date);
                    // }

                    payments= data.payments;

                    $('#payments').val(JSON.stringify(payments));

                }
                else
                {
                    $.alert(
                        {
                            title:'Advertencia!',
                            content:response.msg,
                            buttons: {
                                confirm: {
                                    text:'Aceptar',
                                }
                            }
                        }
                    );
                }

            },
            error: function(data) {
                $.alert(
                    {
                        title:'Advertencia!',
                        content:'Ah ocurrido un error al calcular las cuotas.',
                        buttons: {
                            confirm: {
                                text:'Aceptar',
                            }
                        }
                    }
                );
            },
        });
    }

    // fee_count = Math.round(diff / frequency);
    //
    // // if(flag && (isNaN(fee) || fee == 0))
    // if(flag)
    // {
    //     var partial = (amount * interes) / 100;
    //     // partial = round(partial,2);
    //     total = amount + partial;
    //     // total = round(total,2);
    //     fee =  total / fee_count;
    //     fee = round(fee,2);
    //     $('#loan-fee_payment').val(fee);
    //     // $('#loan-fee_payment-disp').val(fee);
    //     document.getElementById('countFee').innerHTML = "Cantidad de Coutas: " + fee_count;
    //     document.getElementById('total').innerHTML = "Total a cancelar: " + total.toFixed(2);
    //
    //     payments = [];
    //     var table = $('#data-table').DataTable();
    //     table
    //         .clear()
    //         .draw();
    //     for (var i=0; i < fee_count; i++)
    //     {
    //         var pay_date = {'payment_date':start_date.add(frequency, 'days').format('DD-MM-YYYY')};
    //         table.row.add(
    //             pay_date
    //         ).draw();
    //         payments.push(pay_date);
    //     }
    //     // console.log(payments);
    //     $('#payments').val(JSON.stringify(payments));
    // }

    // console.log('Interes: ' + interes);
    // console.log('Cantidad: ' + amount);
    // console.log('Start Date: ' + start_date);
    // console.log('End Date: ' + end_date);
    // console.log('Frecuencia: ' + frequency);
    // console.log('Diff: ' + diff);
    // console.log('Cantidad de Cuotas: ' + fee_count);
    // console.log('Total: ' + total);
    // console.log('Cuota: ' + fee);

};

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

        if(scenario == 'update')
        {
            ajax =  {
                "url": homeUrl + "payment/list",
                "type": "GET",
                'data':{'loanId':loanId}
            };

            var columnFee = {
                "title": "Cuota",
                "data":"amount"
            };

            var columnStatus = {
                "title": "Estado",
                "data":"status"
            };

            columns.push(columnFee);
            columns.push(columnStatus);

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

            columnDefs.push(columnsDefStatus);
        }

        var  table = $('#data-table').DataTable({
            // dom: '<"top"iflp<"clear">>rt',
            data:payments,
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

        // table
        //     .clear()
        //     .draw();
        //
        // table.data()
    }
};

var init = function(){
  //   var amount = $('#loan-amount').val();
  //   amount = parseFloat(amount).toFixed(2);
  //   // alert(amount);
  // $('#loan-amount').val(amount);

    handleDataTable();

    $('#generate').click( function() {

        if($('#feeBox').hasClass('collapsed-box'))
        {
            document.getElementById('collapsedBtn').click();
        }

        if(scenario == 'create')
        {
            generateFee();
        }

        return false;
    });

    if(scenario == 'update')
    {
        $('#loan-porcent_interest').prop('readonly', 'readonly').prop('disabled','disabled');
        $('#loan-amount').prop('readonly', 'readonly').prop('disabled','disabled');
        $('#w1-container').attr('disabled','disabled');
        $('#w1-container .kv-drp-dropdown').attr('disabled','disabled');
        $('#w1-container .range-value').attr('disabled','disabled');
        // $('#w1-container .kv-drp-dropdown').prop('disabled','disabled');
        $('#loan-frequency_payment').prop('readonly', 'readonly').prop('disabled','disabled');

        document.getElementById('countFee').innerHTML = "Cantidad de Coutas: " + loan.feeCount;
        document.getElementById('total').innerHTML = "Total a cancelar: " + loan.totalPay;
    }
};

$(document).ready(function () {
    console.log(loan);
    console.log(scenario);
    init();

    // $('#loan-fee_payment').change(function () {
    //     // hasChanged = true;
    //     // alert('loan-fee_payment: ' + this.value);
    //     // console.log(this.value);
    //     // generateFee();
    // });
    //
    // $('#loan-amount-disp').change(function () {
    //     hasChanged = true;
    //     // alert('loan-amount-disp: ' + this.value);
    //     // console.log(this.value);
    //     // generateFee();
    // });
    //
    // $('#loan-porcent_interest').change(function () {
    //     hasChanged = true;
    //     // alert('loan-porcent_interest: ' + this.value);
    //     // generateFee();
    // });
    //
    // $('#loan-frequency_payment').change(function () {
    //     hasChanged = true;
    //     // alert('loan-porcent_interest: ' + this.value);
    //     // generateFee();
    // });

    // $('#w1-start').change(function () {
    //     // alert('w1-start: ' + this.value);
    //     // console.log(this.value);
    //     generateFee();
    // });

    // $('#w1-end').change(function () {
    //     hasChanged = true;
    //     // alert('w1-end: ' + this.value);
    //     // console.log(this.value);
    //     // generateFee();
    // });

    if(loanId > 0 && scenario == 'update' ) // update loan
    {
        if($('#feeBox').hasClass('collapsed-box'))
        {
            document.getElementById('collapsedBtn').click();
        }
    }

    // form submit
    $('#aceptBtn').on('click', function(){
        // var formData = $('#w0').serialize();
        // console.log(formData);
        $('#w0').submit();

        $("#modal-default").modal("hide");

        // $.ajax({
        //     url: homeUrl + "/loan/create",
        //     type: "POST",
        //     dataType: "json",
        //     data:  {
        //
        //     },
        //     beforeSend:function () {
        //         $("#modal-select-bussy").modal("show");
        //     },
        //     success: function (response) {
        //         $("#modal-select-bussy").modal("hide");
        //         // you will get response from your php page (what you echo or print)
        //         console.log(response);
        //         // var obj = response;
        //         // console.log(obj);
        //
        //         if(response.success)
        //         {
        //             valid = true;
        //             window.location.href = response.url;
        //         }
        //         else
        //         {
        //             valid  = false;
        //             alert(response.msg);
        //         }
        //         return valid;
        //     },
        //     error: function(data) {
        //         $("#modal-select-bussy").modal("hide");
        //         console.log(data);
        //         alert(data['msg']);
        //         valid = false;
        //         return valid;
        //     }
        // });
        // return;
    });
});