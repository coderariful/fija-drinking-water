<script>
    $(function () {
        $('.subject-thumbnail').imageUploader({
            imagesInputName: 'image',
            maxFiles: 1,
        });
    });
    $(document).ready(function () {

        //---------------------------------------------------------------------------------------------
        // - SUMMERNOTE INIT --------------------------------------------------------------------------
        //---------------------------------------------------------------------------------------------

        // $('#summernote').summernote({
        //     placeholder: 'Compose your new mail',
        //     minHeight: 350,
        //     maxHeight: 350
        // });

        $('textarea').summernote({
            placeholder: 'Wright your blog................',
            minHeight: 350,
            maxHeight: 400,
            styleTags: ['p', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            fontNames: ['Arial', 'Galada', 'Kalpurush', 'Roboto', 'Times New Roman', 'Verdana'],
            fontNamesIgnoreCheck: ['Roboto', 'Galada', 'Kalpurush'],
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });


    });
</script>
