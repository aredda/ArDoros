/**
 * IMPORTANT: 
 * Requires JQuery
 */

$(document).ready(function (){

    /**
     * This is the navigation item's functionality
     */
    $(".nav-item-body").hide ();
    $(".nav-item-header span").html ("&rtrif;");
    $(".nav-item-header").click (function ()
    {
        // Hide all nav items' bodies
        $(".nav-item-body").slideUp ();
        // Change the sign also
        $(".nav-item-header span").html ("&rtrif;");
        // Don't show it because it's already shown
        if ($(this).parent().hasClass ("active"))
        {
            // Remove the active status
            $(".nav-item").removeClass ("active");
            
            return;
        }
        // Remove the active status
        $(".nav-item").removeClass ("active");
        // Show the body of this clicked item
        $(this).parent().find(".nav-item-body").slideDown ();
        // Change the sign
        $(this).find("span").html ("&dtrif;");
        // Change this nav item's status to active
        $(this).parent().addClass ("active");
    });

});
