/*===================================================================================================

 - PROJECT NAME : BDC-TIKIT
 - DESCRIPTION : MODERN BOOTSTRAP 4 ADMIN TEMPLATE - FULLY RESPONSIVE
 - AUTHOR : bdCoder
 - VERSION : 1.0
 - FILE : ACCOUNT SINGUP JS

 ===================================================================================================*/
(function($)
{

    "use strict";
    //---------------------------------------------------------------------------------------------
    // - INTIALISATION ----------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------

    $("#terms-conditions-accepted").on("click", function () {
        $("#terms-conditions").prop('checked', true);
    });
    $("#terms-conditions-refused").on("click", function () {
        $("#terms-conditions").prop('checked', false);
    });
})(jQuery);
