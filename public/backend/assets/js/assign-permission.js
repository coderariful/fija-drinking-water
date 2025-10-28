(function($)
{

    "use strict";
    if (document.readyState == "loading"){
        document.addEventListener('DOMContentLoaded', ready)
    }else {
        ready();
    }

    function ready(){
        // roleAssignPermission();
        getRoleHasPermission()
    }

    function getRoleHasPermission(){
        $('#role').on('change', function (){
            $.ajax({
                type: 'get',
                url: '/admin/get-role-has-permission',
                data: {
                    'role': $(this).val()
                },
                success:function (data){
                    // console.log(data);
                    let permissions = [];
                    for (let p = 0; p < data.length; p++){
                        permissions.push(data[p].name)
                    }
                    showPermissionOfRole(permissions);
                }
            })
        })
    }

    function showPermissionOfRole(permissions){
        // console.log(permissions);
        let checkboxs = document.getElementsByName('permission[]');
        for (let cb = 0; cb < checkboxs.length; cb++){
            let box = checkboxs[cb];
            if (permissions.includes(box.value)){
                box.checked = true;
            }else {
                box.checked = false;
            }
        }
    }
})(jQuery);
