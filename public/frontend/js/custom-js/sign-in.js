/*-----------------------------------------------------------------------------------
    Template Name: bdcZone
    Template URI: https://bdcoder.com
    Author: bdCoder
    Author URI: https://bdcoder.com/
    Version: 1.0

    Note: This is Main Custom js File.
-----------------------------------------------------------------------------------


/*===== LOGIN SHOW and HIDDEN =====*/
(function($) {
    "use strict";
// Author code here
    const signUp = document.getElementById('sign-up'),
        signIn = document.getElementById('sign-in'),
        loginIn = document.getElementById('login-in'),
        loginUp = document.getElementById('login-up'),
        loginButton = document.getElementById('login-button')


    signUp.addEventListener('click', () => {
        // Remove classes first if they exist
        loginIn.classList.remove('block')
        loginUp.classList.remove('none')

        // Add classes
        loginIn.classList.toggle('none')
        loginUp.classList.toggle('block')
    })

    signIn.addEventListener('click', () => {
        // Remove classes first if they exist
        loginIn.classList.remove('none')
        loginUp.classList.remove('block')

        // Add classes
        loginIn.classList.toggle('block')
        loginUp.classList.toggle('none')
    })
})(jQuery);



