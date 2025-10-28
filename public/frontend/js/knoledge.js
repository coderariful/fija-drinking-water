(function($) {
    "use strict";
// Author code here
    if (document.readyState == "loading"){
        document.addEventListener('DOMContentLoaded', ready)
    }else {
        ready();
    }

    function ready(){
        $('.help_search_input').on('keyup', function (){
            var query = $(this).val();
            if(query != ''){
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "/autocomplete",
                    method: "POST",
                    data: {query:query, _token:_token},
                    success:function(data){
                        $('#bdc_result').fadeIn();
                        $('#bdc_result').html(data);
                    }
                })
            }else{
                $('#bdc_result').fadeOut();
            }
        });

        $('.knowledge-like-btn').on("click", function (e) {
            e.preventDefault();
            var mainobj = $(this);
            var id = mainobj.data("kid");

            var url = "/help-center/knowledge-like/"+id;
            $.ajax({
                type:'get',
                url: url,
                success:function (data) {
                    // do nothing;
                    document.getElementById('knowledgeLike').innerHTML = parseInt(document.getElementById('knowledgeLike').innerHTML)  + 1
                    $('.desk-give-helpful').addClass('d-none');
                    $('.desk-show-helpful-feedback').removeClass('d-none');

                }
            });
        });

        $('.knowledge-dislike-btn').on("click", function (e) {
            e.preventDefault();
            var mainobj = $(this);
            var id = mainobj.data("kid");

            var url = "/help-center/knowledge-dislike/"+id;
            $.ajax({
                type:'get',
                url: url,
                success:function (data) {
                    // do nothing;
                    document.getElementById('knowledgeDislike').innerHTML = parseInt(document.getElementById('knowledgeDislike').innerHTML) + 1
                    $('.desk-give-helpful').addClass('d-none').fadeOut();

                    $('.desk-show-helpful-feedback').removeClass('d-none').fadeIn();

                }
            });
        });
    }
})(jQuery);
