(function($)
{
    "use strict";
    var drivePath = window.location.pathname.replace('/','');
    drivePath = drivePath.split('/');
    var drivePathMainPath = '/'+drivePath[0];
    $('.faqActivationBtn').on('click', function () {
        var selected = $(this).attr('id');
        $.ajax({
            type:'get',
            url: drivePathMainPath + '/faq-activation/'+selected,
            success:function (data) {
                // do nothing;
            }
        });
    });
})(jQuery);
