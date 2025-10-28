(function($) {
    "use strict";
    // Author code here
    const loginIn = document.getElementById('login-in'),
        loginUp = document.getElementById('login-up')
    // login recapthca code
    let isLoginRecaptchaVerified = false;
    loginIn.addEventListener('submit', e => {
        e.preventDefault();
        handleLoginRecaptchaCallback();
        if (isLoginRecaptchaVerified) {
            loginIn.submit();
        } else {
            alert('Please Complete reCapthca');
        }
    })
    function handleLoginRecaptchaCallback(token) {
        isLoginRecaptchaVerified = true;
    }

    // registration recapthca code
    let isRegistrationRecaptchaVerified = false;
    loginUp.addEventListener('submit', e => {
        e.preventDefault();
        handleRegistrationRecaptchaCallback
        if (isRegistrationRecaptchaVerified) {
            loginUp.submit();
        } else {
            alert('Please Complete Capthca');
        }
    })
    function handleRegistrationRecaptchaCallback(token) {
        isRegistrationRecaptchaVerified = true;
    }
})(jQuery);
