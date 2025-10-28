(function($) {
    "use strict";
    const oneTimeUseCheck = document.getElementById('defaultCheck2');
    const dayInput = document.getElementById('valid');

    oneTimeUseCheck.addEventListener('click', event => {
        if (dayInput.getAttribute('disabled') == 'disabled') {
            dayInput.disabled = false;
        } else {
            dayInput.setAttribute('disabled', 'disabled');
        }
    })
})(jQuery);


