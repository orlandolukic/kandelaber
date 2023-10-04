
(function($) {

    $(document).on("ready", function () {
        $("#qodef-page-header").css('height', 'auto');
        $("#qodef-page-inner").css('padding', '0');
    });

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

        window.openCategory = (showSubcategoriesOverlay, category, subcategories, callback) => {
            if (showSubcategoriesOverlay) {
                return;
            }

            window.reactMain.transitionOverlayLoader(function() {
                if (typeof callback === 'function') {
                    callback();
                }
            });

            // Push a new state to the history
            console.log("SUBCATEGORIES BEFORE PUSH", subcategories);
            const newState = {
                page: "opened-category",
                category: category,
                subcategory: null,
                subcategories: subcategories
            };
            const newTitle = category.name + " — Kandelaber";
            const newUrl = '/proizvodi/' + category.slug + "/";

            // Push initial state if it's already pushed
            //window.reactMain.pushStartHistoryState();

            history.pushState(newState, null, newUrl);
            document.title = newTitle;
        };

        window.openSubcategory = (props, callback) => {
            window.reactMain.transitionOverlayLoader(() => {
                if (typeof callback === 'function') {
                    callback();
                }
            });

            // Push a new state to the history
            const newState = {
                ...props,
                page: "opened-category"
            };
            console.log("new state", newState);
            const newTitle = props.subcategory.name + " — Kandelaber";
            const newUrl = '/proizvodi/' + props.category.slug + '/' + props.subcategory.slug + "/";

            history.pushState(newState, null, newUrl);
            document.title = newTitle;
            console.log("PUSHED");
        };

        window.showLoader = function () {
            jQuery(".overlay-loader-container").css('display', 'flex');
        };

        window.hideLoader = function () {
            jQuery(".overlay-loader-container").hide();
        };

    });
})(jQuery);