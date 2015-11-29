$(function() {

    //Navigate to # URLs
    $('body').on('click','a[href*=#]:not([href=#])', function() {

        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {

            var target = $(this.hash);

            //target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            //target = target.length ? target : $('[name=' + this.hash.slice(1) +']');

            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }

        }

    });
});