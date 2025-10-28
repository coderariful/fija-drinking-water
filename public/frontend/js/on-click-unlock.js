(function($) {
    "use strict";
    function unlock() {
        document.getElementById('unlockBtn').removeAttribute('onclick');
        document.getElementById('unlockBtn').setAttribute("onclick", "copyFunction()");
        document.getElementById('unlockBtn').innerText = 'Copy';
        document.getElementById('shareLink').classList.remove('d-none');

    }
})(jQuery);
