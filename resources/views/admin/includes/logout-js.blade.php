<script>
    (function ($){
        "use script";
        $('#logoutBtn').on('click', function (e){
            e.preventDefault();
            document.getElementById('logout-form').submit();
        });
    })(jQuery)
</script>
