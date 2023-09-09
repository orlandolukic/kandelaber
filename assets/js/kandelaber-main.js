(function($) {
    $(window).on("load", function() {
        setTimeout(() => {
            $(".page-loader").fadeOut(function () {
                $("body").css({
                    "height": "auto"
                });
            });
        }, 100);

        var timeout;
        $('.container-tween').mousemove(function(e){
            if(timeout) clearTimeout(timeout);
            setTimeout(callParallax.bind(null, e), 200);

        });

        function callParallax(e){
            parallaxIt(e, '.animate-tween-1', -10);
            parallaxIt(e, '.animate-tween-2', -30);
            parallaxIt(e, '.animate-tween-3', -20);
        }

        function parallaxIt(e, target, movement){
            var $this = $('.container-tween');
            var relX = e.pageX - $this.offset().left;
            var relY = e.pageY - $this.offset().top;

            TweenMax.to(target, 1, {
                x: (relX - $this.width()/2) / $this.width() * movement,
                y: (relY - $this.height()/2) / $this.height() * movement,
                ease: Power2.easeOut
            })
        }
    });
})(jQuery);