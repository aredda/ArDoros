/**
 * IMPORTANT: 
 * - Requires JQuery
 * - Requires component.js
 */

/**
* A method to retrieve form's data
*/
function serialize(formClass) 
{
    // return a FormData Object
    return new FormData ($(formClass) [0]);
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
     * The overlay fading in 
     */
    $(".btn-close, .overlay").on('click', function (){
        // Hide some elements
        $(".overlay").fadeOut (500);
        $(".notification").fadeOut (500);
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
        // Send a request
        ajaxRequest ('src/medium.php', data, function (response) {
            // Confirmation pop-up
            var header = response.hasOwnProperty ('success') ? 'نجاح' : 'فشل';
            var type = response.hasOwnProperty ('success') ? 'success' : 'fail';
            var message = response.hasOwnProperty ('success') ? 'تمت العملية بنجاح' : response.error;

            popNotification(header, message, type);
        });
    });

    /**
     * Delete button event
     */
    $(".btn-delete").on ('click', function (){
        // Retrieve the parent row
        var parent = $(this).closest ('tr');
        // Retrieve data id
        var id = parent.attr ('data-id');
        // Construct a form data object
        var data = new FormData ();
        // Provide the required data to send
        data.append ('id', id);
        data.append ('type', 'delete');
        for (var key in getUrlParams ())
            data.append (key, getUrlParams()[key]);
        // Send a DELETE request
        ajaxRequest ('src/medium.php', data, function (response) {
            // Remove from view
            if (response.hasOwnProperty ('success'))
                parent.remove ();
            else
                popNotification ('عذرا', 'فشلت عملية الحذف', 'error');
        });
    });

    /**
     * Search button event
     */
    $(".btn-search").on ('click', () => {
        // If the current page is not index, then redirect to index
        if (window.location.href.includes ('unit.php'))
        {
            // Retrieve the title
            let title = $("*[name='title']").val ();
            // Redirect
            window.location.href = 'index.php' + ((title.length > 0) ? `?title=${title}` : '');
        }   
        // Criteria to send
        var keys = ['model', 'title', 'grade', 'subject', 'semester'];
        // Create an empty form data
        var data = new FormData ();
        // Specify the type of the request
        data.append ('type', 'search');
        // Gather the values of those keys
        for (var key of keys)
            data.append (key, $(`*[name=${key}]`).val ());
        // Send a SEARCH request
        ajaxRequest ('src/medium.php', data, (response) => {
            // Extract data
            var dataArr = response.success.array;
            // Retrieve container
            var container = $(".list-view");
            // Retrieve result counter
            var counter = $(".result-counter");
            // Remove all items
            dominoEffect ($(".list-view-item-container"), 400, 'out', 0, () => {
                // Update result counter
                counter.html (dataArr.length);
                // Display data
                for (var item of dataArr)
                {
                    // Retrieve data that they have in common
                    let { id, title } = item;
                    // Declare other information
                    let image = 'media/unavailable.jpg', semester, grade, subject, year;
                    // Adjust data depending on the class
                    switch (response.success.class)
                    {
                        case 'درس':
                            ({ image, semester, grade, subject } = item);
                            break;
                        
                        case 'تمرين':
                            ({ grade, subject, semester } = item.lesson);
                            break;

                        case 'امتحان':
                            ({ year } = item);
                            break;
                    }
                    // Instantiate an item view 
                    var itemView = createListViewItem (
                        id, 
                        title,
                        data.get('model'), 
                        image, 
                        (grade == null ? null : grade.title), 
                        (subject == null ? null : subject.title), 
                        (semester == null ? null : `الدورة ${semester}`), 
                        response.success.class, 
                        year
                    );
                    // hide it
                    itemView.css ({'display': 'none'});
                    // append it
                    container.append (itemView);
                }
                dominoEffect ($(".list-view-item-container"), 400, 'in', 0);
            });
        }, 'post', 'json', function (a, b, c){
            popNotification ('error', c, 'error');s
        });
    });

});
