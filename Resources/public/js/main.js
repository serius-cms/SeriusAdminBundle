

$(function(){
    var enlarged = false;

    $(".content #nav a").on('click',function(e){
        if(!enlarged){
            if ($(this).parent().hasClass("has_sub")) {
                e.preventDefault();
            }

            if(!$(this).hasClass("subdrop")) {
                // hide any open menus and remove all other classes
                $("ul",$(this).parents("ul:first")).slideUp(350);
                $("a",$(this).parents("ul:first")).removeClass("subdrop");
                $("#nav .pull-right i").removeClass("fa-chevron-down").addClass("fa-chevron-left");

                // open our new menu and add the open class
                $(this).next("ul").slideDown(350);
                $(this).addClass("subdrop");
                $(".pull-right i",$(this).parents(".has_sub:last")).removeClass("fa-chevron-left").addClass("fa-chevron-down");
                $(".pull-right i",$(this).siblings("ul")).removeClass("fa-chevron-down").addClass("fa-chevron-left");
            } else if($(this).hasClass("subdrop")) {
                $(this).removeClass("subdrop");
                $(this).next("ul").slideUp(350);
                $(".pull-right i",$(this).parent()).removeClass("fa-chevron-down").addClass("fa-chevron-left");
                //$(".pull-right i",$(this).parents("ul:eq(1)")).removeClass("fa-chevron-down").addClass("fa-chevron-left");
            }
        }
    });

    $("#nav").find("> li.has_sub > a.open").addClass("subdrop").next("ul").show();

    $(".menu-button").click(function(){
        if(!enlarged){
            $("#nav .has_sub ul").removeAttr("style");
            $("#nav .has_sub .pull-right i").removeClass("fa-chevron-left").addClass("fa-chevron-down");
            $("#nav ul .has_sub .pull-right i").removeClass("fa-chevron-down").addClass("fa-chevron-right");
            $(".content").addClass("enlarged");
            enlarged = true;
        } else {
            $("#nav .has_sub .pull-right i").addClass("fa-chevron-left").removeClass("fa-chevron-down").removeClass("fa-chevron-right");
            $(".content").removeClass("enlarged");
            enlarged = false;
        }

        return false;
    });

    $(".sidebar-dropdown a").on('click',function(e){
        e.preventDefault();

        if(!$(this).hasClass("open")) {
            // hide any open menus and remove all other classes
            $(".sidebar #nav").slideUp(350);
            $(".sidebar-dropdown a").removeClass("open");

            // open our new menu and add the open class
            $(".sidebar #nav").slideDown(350);
            $(this).addClass("open");
        } else if($(this).hasClass("open")) {
            $(this).removeClass("open");
            $(".sidebar #nav").slideUp(350);
        }
    });

    $('.sscroll').slimScroll({wheelStep: 1,opacity:0.3});
    $(".slimScrollBar").hide();
});


/* Scroll to Top */
$(".to-top").hide();

$(function(){
    $(window).scroll(function(){
        if ($(this).scrollTop() > 300) {
            $('.to-top').slideDown();
        } else {
            $('.to-top').slideUp();
        }
    });

    $('.to-top a').click(function (e) {
        e.preventDefault();
        $('body, html').animate({ scrollTop: 0 }, 500);
    });
});

// Show last tabs
var lastTabs;
try {
    lastTabs = JSON.parse(localStorage.getItem('lastTabs') || "[]");
} catch (err) {
    lastTabs = [];
}

if (lastTabs && Array.isArray(lastTabs)) {
    $.each(lastTabs, function(index, value) {
        $('[href="' + value + '"]').tab('show');
    });
} else {
    lastTabs = [];
}

$('.remember-tab a').on('shown.bs.tab', function (e) {
    lastTabs.push($(e.target).attr('href'));
    localStorage.setItem('lastTabs', JSON.stringify(lastTabs));
});