(function($)
{
    "use strict";
    var drivePath = window.location.pathname.replace('/','');
    drivePath = drivePath.split('/');
    var drivePathMainPath = '/'+drivePath[0];

    $('#knowledge_category').on('change', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var id = $(this).val();

        $.ajax({
            type:'get',
            url: drivePathMainPath + '/help-center/knowledge-subcategory/'+id,
            success:function (data) {
                $('#knowledge_subcategories_id').html(data);
            }
        });
    });
})(jQuery);
