
$(document).ready(function () {

    // $('#w0').parsley();

    if(scenario == 'update')
    {
        $('#payment-loan_id').prop("disabled",true);

        $('#payment-payment_date-container .kv-drp-dropdown').prop("disabled",true);
        $('#payment-payment_date-container .range-value').prop("disabled",true);

        $('#payment-loan_id').on('select2:select', function (e) {
            loan = e.params.data;
            console.log(loan);
            // $('#payment-payment_date')
            //     .attr('data-parsley-min', moment(data.start_date, 'YYYY-MM-DD'))
            //     .attr('data-parsley-max', moment(data.end_date, 'YYYY-MM-DD'));


        });

        $('#payment-amount').on('change',function () {

            console.log('payment-amoun change');
            var value = this.value;
            var self = this;
            if(value <= 0 || value > loan.amountUnPaid)
            {
                $.alert(
                    {
                        title:'Advertencia',
                        content:'El valor de la cuota debe ser mayo que 0 y menor que ' + loan.amountUnPaid,
                        buttons: {
                            confirm: {
                                text:'Aceptar',
                                action:function () {
                                    // $('#payment-amount-disp').val(loan.amount);
                                    // self.value = loan.amount;
                                }
                            }
                        }
                    }
                );
            }
        });
    }

    //
    // $('#payment-amount')
    //     .attr('data-parsley-min', Number(1))
    //     .attr('data-parsley-max', Number(loan.amount));

});