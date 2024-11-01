jQuery( document ).ready( function( $ ) {
    if($(".wp-has-submenu").length > 0){
        $(".wp-has-submenu").not(".wp-has-current-submenu").on("mouseenter", function(){
            $(this).addClass("opensub");
            const $container = $(this);
            const container = this;
            const windowheight = $( window ).height();
            // console.log(container.getBoundingClientRect().top + $(this).find(".wp-submenu").outerHeight());
            // console.log(windowheight);
            if(container.getBoundingClientRect().top + $(this).find(".wp-submenu").outerHeight() > windowheight){
                const margin = (container.getBoundingClientRect().top + $(this).find(".wp-submenu").outerHeight() - windowheight) + $(this).find(".wp-submenu").height() / 2;
                $(this).find(".wp-submenu").css({"top" : -margin });
            }
        });
        $(".wp-has-submenu").not(".wp-has-current-submenu").on("mouseleave", function(){
            $(this).removeClass("opensub");
            $(this).find(".wp-submenu").css({"top" : "" });
        })
    }
});