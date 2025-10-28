(function($) {
    "use strict";
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Write description...',
            minHeight: 150,
            maxHeight: 450,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview']]
            ],
        });
    });
})(jQuery);
