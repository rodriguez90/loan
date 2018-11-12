/*   
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.5
Version: 1.9.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin-v1.9/admin/
*/

var handleBootstrapWizardsValidation = function() {
	"use strict";
	$("#wizard").bwizard(
	    {  activeIndexChanged:  function (e, ui)
            {
                if(ui.index == 0)
                {
                    $('ul.bwizard-buttons li.next a').text('Siguiente');
                }
                else if(ui.index == 1)
                {
                    $('ul.bwizard-buttons li.next a').text('Siguiente');
                }
                else if(ui.index == 2)
                {
                    $('ul.bwizard-buttons li.next a').text('Finalizar');
                }
            },
            validating: function (e, ui) {
            var result = true;
            var index = parseInt(ui.index);
            var nextIndex = parseInt(ui.nextIndex);
            if(index >= nextIndex)
            {
                return result;
            }

	        if (ui.index == 0) {
	            // step-1 validation
                if (false === $('form[name="form-wizard"]').parsley().validate('wizard-step-1')) {
                    return false;
                }
	        } else if (ui.index == 1) {
	            // step-2 validation
                if (false === $('form[name="form-wizard"]').parsley().validate('wizard-step-2')) {
                    return false;
                }
	        } else if (ui.index == 2) {
	            // step-3 validation
                if (false === $('form[name="form-wizard"]').parsley().validate('wizard-step-3')) {
                    return false;
                }

                $('#user-form').submit();
	        }
	    },
        backBtnText:'Anterior',
        nextBtnText: 'Siguiente'
	});
};

var FormWizardValidation = function () {
	"use strict";
    return {
        //main function
        init: function () {
            handleBootstrapWizardsValidation();
        }
    };
}();

FormWizardValidation.init();