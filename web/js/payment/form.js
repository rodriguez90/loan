$(document).ready(function () {
    if(scenario == 'update')
    {
        $('#payment-loan_id').prop("disabled",true);

        $('#payment-payment_date-container .kv-drp-dropdown').prop("disabled",true);
        $('#payment-payment_date-container .range-value').prop("disabled",true);
    }
});