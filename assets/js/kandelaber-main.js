(function($) {
    $(window).on("load", function() {
        setTimeout(() => {
            $(".page-loader").fadeOut(function () {
                $("body").css({
                    "height": "auto"
                });
            });
        }, 100);
    });
})(jQuery);