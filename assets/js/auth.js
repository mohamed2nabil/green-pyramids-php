(function () {
    'use strict';

    // Logout → يروح لملف PHP
    function logout() {
        window.location.href = 'api/logout.php';
    }

    // expose functions
    window.greenLightAuth = {
        logout
    };

})();
