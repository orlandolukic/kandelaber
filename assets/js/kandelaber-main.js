(function($) {
    $(window).on("load", function() {
        let aboutStarsInterval;

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


        let aboutStarsInitiator = (offsetX, offsetY) => {
            var confettiContainer = $(".confetti-star-container");
            if (confettiContainer.length === 0) {
                return null;
            }
            var myConfetti = confetti.create(confettiContainer[0], {resize: true, useWorker: true});
            var offset = {
                x: offsetX / confettiContainer[0].width,
                y: offsetY / confettiContainer[0].height
            }
            var defaults = {
                spread: 360,
                ticks: 50,
                gravity: 0,
                decay: 0.94,
                startVelocity: 30,
                shapes: ['star'],
                colors: ['FFE400', 'FFBD00', 'E89400', 'FFCA6C', 'FDFFB8']
            };
            if (offsetX !== -1 && offsetY !== -1) {
                defaults.origin = offset;
            }

            function shoot() {
                myConfetti({
                    ...defaults,
                    particleCount: 40,
                    scalar: 1.2,
                    shapes: ['star']
                });

                myConfetti({
                    ...defaults,
                    particleCount: 10,
                    scalar: 0.75,
                    shapes: ['circle']
                });
            }

            setTimeout(shoot, 0);
            setTimeout(shoot, 100);
            setTimeout(shoot, 200);
            return true;
        };

        let retAboutStars = aboutStarsInitiator(-1,-1);
        if (retAboutStars) {
            aboutStarsInterval = setInterval(() => {
                aboutStarsInitiator(-1,-1);
            }, 3000);
        }

        let aboutStarsAppender = () => {
            var confettiContainer = $(".confetti-star-container");
            confettiContainer.on("click", function(e) {
                clearInterval(aboutStarsInterval);
                aboutStarsInitiator(e.originalEvent.offsetX, e.originalEvent.offsetY);
                aboutStarsInterval = setInterval(() => {
                    aboutStarsInitiator(-1,-1);
                }, 3000);
                console.log(e.originalEvent.offsetX);
            });
        };
        aboutStarsAppender();

        let timeout1 = null;
        let timeout2 = null;

        const transitionOverlayLoader = () => {
            clearTimeout(timeout1);
            clearTimeout(timeout2);
            jQuery(".overlay-loader-container").css('display', 'flex');
            timeout1 = setTimeout(() => {
                jQuery("#heading-section").parent().fadeOut(function() {
                    timeout2 = setTimeout(() => {
                        jQuery(".overlay-loader-container").hide();
                    }, 500);
                });
            }, 500);
        };

        const onPopStateHandler=  (e) => {
            if (e.state === null) {
                window.location.reload();
            } else if (e.state === "products-page") {
                if (jQuery("#heading-section").parent().length === 0) {
                    jQuery(".overlay-loader-container").css('display', 'flex');
                    window.location.reload();
                } else {
                    jQuery("#heading-section").parent().fadeIn();
                }
            } else if (e.state === "opened-category") {
                transitionOverlayLoader();
            }
        };
        window.addEventListener('popstate', onPopStateHandler);

        window.openCategory = (showSubcategoriesOverlay, categoryName, categorySlug) => {
            if (showSubcategoriesOverlay) {
                return;
            }

            transitionOverlayLoader();

            // Push a new state to the history
            const newState = "opened-category";
            const newTitle = categoryName + " — Kandelaber";
            const newUrl = '/proizvodi/' + categorySlug + "/";

            history.pushState(newState, null, newUrl);
            document.title = newTitle;
        };

        window.openSubcategory = (parentCategory, subcategory) => {
            transitionOverlayLoader();

            // Push a new state to the history
            const newState = "opened-category";
            const newTitle = subcategory.name + " — Kandelaber";
            const newUrl = '/proizvodi/' + parentCategory.slug + '/' + subcategory.slug + "/";

            history.pushState(newState, null, newUrl);
            document.title = newTitle;
        };

    });
})(jQuery);