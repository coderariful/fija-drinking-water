(function ($){
    "use script";
    $(document).on('click', '#logoutBtn', function (e){
        e.preventDefault();
        this.closest('form').submit();
    })
})(jQuery)
