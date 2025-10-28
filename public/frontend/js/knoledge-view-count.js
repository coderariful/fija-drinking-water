(function($) {
    "use strict";
    $('.knoledgeTitle').on('click', countView);

    function countView(e){
        e.preventDefault();
        const href = $(this).attr('href');
        const id = $(this).attr('data-id')
        $.ajax({
            type: 'get',
            url: '/get-knowledge-view/'+id,
            success:function (data){
                window.location.href = href;
            }
        })
    }
})(jQuery);


