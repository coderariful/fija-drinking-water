(function($)
{
    "use strict";
    $('#pageIcon').imageUploader({
        imagesInputName: 'page_icon',
        maxFiles: 1,
    });

    $('.admin-image').imageUploader({
        imagesInputName: 'profile_photo_path',
        maxFiles: 1,
    });
    $('.user-image').imageUploader({
        imagesInputName: 'profile_photo_path',
        maxFiles: 1,
    });
    $('#deletePageBtn').on('click',function (){
        deletePage();
    })

    $('#copyLinkBtn').on('click',function (){
        copyFunction();
    })

    function deletePage() {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this page and it\'s related files!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((value) => {
            if (value) {
                document.getElementById('deletePageForm').submit();
            } else {
                swal("Your employee and his  related files are safe!",
                    {
                        buttons: {
                            cancel: false,
                            confirm: true
                        }
                    });
            }
        });
    }

    function copyFunction() {
        /* Get the text field */
        var copyText = document.getElementById("copyUrl");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        // alert("Copied the text: " + copyText.value);
    }
})(jQuery);

