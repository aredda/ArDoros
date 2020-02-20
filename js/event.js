/**
 * IMPORTANT: 
 * Requires JQuery
 */


/**
* A method to retrieve form's data
*/
function serialize(formCLass) 
{
    // return a FormData Object
    return new FormData ($(formCLass) [0]);
}

/**
 * A method to abstract ajax requests
 */
function ajaxRequest (url, data, onSuccess, method = 'POST', returnDataType = 'JSON', onError = null)
{
    $.ajax ({
        url: url,
        method: method,
        data: data,
        dataType: returnDataType,
        processData: false,
        contentType: false,
        success: onSuccess,
        error: onError
    });
}

/**
 * A method to get url parameters
 */
function getUrlParams ()
{
    var query = window.location.search.substring(1);
    var params = query.split ('&');

    var object = new Object ();
    for (var param of params)
    {
        var pair = param.split ('=');

        object[pair[0]] = pair[1];
    }

    return object;
}

$(document).ready(function () {

    /**
     * This is the navigation item's functionality
     */
    $(".nav-item-body").hide();
    $(".nav-item-header span").html("&rtrif;");
    $(".nav-item-header").on('click', function () {
        // Hide all nav items' bodies
        $(".nav-item-body").slideUp();
        // Change the sign also
        $(".nav-item-header span").html("&rtrif;");
        // Don't show it because it's already shown
        if ($(this).parent().hasClass("active")) {
            // Remove the active status
            $(".nav-item").removeClass("active");

            return;
        }
        // Remove the active status
        $(".nav-item").removeClass("active");
        // Show the body of this clicked item
        $(this).parent().find(".nav-item-body").slideDown();
        // Change the sign
        $(this).find("span").html("&dtrif;");
        // Change this nav item's status to active
        $(this).parent().addClass("active");
    });

    /**
     * Insert/Update button event
     */
    $("#btn-insert, #btn-update").on ('click', function () {
        // Retrieve data
        var data = serialize ("#form");
        // Retrieve form type from the element id
        var btnId = $(this).attr ('id');
        // Extract mode from id
        var mode = btnId.split ('-') [1];
        // Append request's type
        data.append ('type', mode);
        // Append the params from URL
        for (var key in getUrlParams ())
            data.append (key, getUrlParams()[key]);
        // Send an INSERT request
        ajaxRequest ('src/medium.php', data, function (response) {
            
        });
    });

});
