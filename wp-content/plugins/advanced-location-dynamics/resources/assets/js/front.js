jQuery(function ($) {
    "use strict";
    $(document).ready(function () {
        $(".ld-dropdown").hide();
        $(".ld-toggle").click(function (e) {
            e.preventDefault();
            $(this).next().slideToggle();
        });
    });
});