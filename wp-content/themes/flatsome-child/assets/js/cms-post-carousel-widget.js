( function( $ ) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetCMSPostCarouselHandler = function( $scope, $ ) {
        var breakpoints = elementorFrontend.config.breakpoints;
        var carousel = $scope.find(".cms-slick-carousel");
        var data = carousel.data();
        var slickOptions = {
            slidesToShow: data.slidestoshow,
            slidesToScroll: data.slidestoscroll,
            autoplay: true === data.autoplay,
            centerMode: true === data.centermode,
            autoplaySpeed: data.autoplayspeed,
            infinite: true === data.infinite,
            pauseOnHover: true === data.pauseonhover,
            speed: data.speed,
            arrows: true === data.arrows,
            dots: true === data.dots,
            rtl: 'rtl' === data.dir,
            nextArrow: '<span class="slick-next">Next</span>',
            prevArrow: '<span class="slick-prev">Prev</span>',

            responsive: [{
                breakpoint: breakpoints.lg,
                settings: {
                    slidesToShow: data.slidestoshowtablet,
                    slidesToScroll: data.slidestoscrolltablet,
                }
            }, {
                breakpoint: breakpoints.md,
                settings: {
                    slidesToShow: data.slidestoshowmobile,
                    slidesToScroll: data.slidestoscrollmobile,
                }
            }]
        };
        if(typeof data.appendarrows != 'undefined'){
            slickOptions.appendArrows = data.appendarrows;
        }

        if(typeof data.appenddots != 'undefined'){
            slickOptions.appendDots = data.appenddots;
        }
        
        $scope.find('.filter-item').on('click', function(){
            var data_filter = $(this).data('filter');
            $scope.find('.filter-item').removeClass('active');
            if (typeof data_filter !== "undefined") {
                carousel.slick('slickUnfilter');
                carousel.slick('slickFilter', data_filter);
                $(this).addClass('active');
            } else {
                carousel.slick('slickUnfilter');
            }
        });
        var nav_for = $scope.find(".cms-slick-nav");
        if(nav_for.length > 0){
            slickOptions.asNavFor = nav_for;
        }

        // On init slide
        carousel.on('init', function(event, slick){
            var slickMetaData = $scope.find('.cms-slick-meta-data');
            if(slickMetaData.length){
                slickMeta = slickMetaData.find('.cms-slick-meta');
                $(slickMeta[slick.slideOffset]).show();
            }
        });

        // On after slide change
        carousel.on('afterChange', function(event, slick, currentSlide){
            var slickMetaData = $scope.find('.cms-slick-meta-data');
            if(slickMetaData.length){
                slickMeta = slickMetaData.find('.cms-slick-meta');
                slickMeta.hide();
                $(slickMeta[currentSlide]).show();
            }
        });

        carousel.slick(slickOptions);

        $('.cms-nav-carousel').parents('.elementor-widget').addClass('hide-nav');
        $('.cms-nav-carousel .nav-prev').on('click', function () {
            $(this).parents('.elementor-widget').find('.slick-prev').trigger('click');
        });
        $('.cms-nav-carousel .nav-next').on('click', function () {
            $(this).parents('.elementor-widget').find('.slick-next').trigger('click');
        });

        $scope.find('.cms-slick-slider').each(function () {
            var slider_main = $(this).find('.cms-slick-carousel');
            var slider_nav = $(this).find('.cms-slick-nav');
            
            $(slider_nav).slick({
                slidesToShow: parseInt(slider_nav.attr('data-nav')),
                slidesToScroll: 1,
                asNavFor: slider_main,
                dots: false,
                arrows: false,
                centerMode: false,
                focusOnSelect: true,
                autoplay: true === data.autoplay,
                autoplaySpeed: 8000,
                speed: 800,
                infinite: false,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });
        });
    };

    $(document).ready(function() {
        $('.cms-slick-carousel').slick({
            slidesToShow: parseInt(slider_nav.attr('data-nav')),
            slidesToScroll: 1,
            asNavFor: slider_main,
            dots: false,
            arrows: false,
            centerMode: false,
            focusOnSelect: true,
            autoplay: true === data.autoplay,
            autoplaySpeed: 8000,
            speed: 800,
            infinite: false,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
    // // Make sure you run this code under Elementor.
    $( window ).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/cms_post_carousel.default', WidgetCMSPostCarouselHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/cms_gallery_carousel.default', WidgetCMSPostCarouselHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/cms_images_gallery.default', WidgetCMSPostCarouselHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/cms_team_carousel.default', WidgetCMSPostCarouselHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/cms_testimonial_carousel.default', WidgetCMSPostCarouselHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/cms_client_carousel.default', WidgetCMSPostCarouselHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/cms_fancybox_carousel.default', WidgetCMSPostCarouselHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/cms_service_carousel.default', WidgetCMSPostCarouselHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/cms_counter_carousel.default', WidgetCMSPostCarouselHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/cms_pricing_carousel.default', WidgetCMSPostCarouselHandler );
    } );
} )( jQuery );