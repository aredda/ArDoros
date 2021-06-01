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
    let query = window.location.search.substring(1);
    let params = query.split ('&');

    let object = new Object ();
    for (let param of params)
    {
        let pair = param.split ('=');

        object[pair[0]] = pair[1];
    }

    return object;
}

/**
 * A method to facilitate refreshing catalog items view
 */
function updateCatalog(data)
{
    ajaxRequest ('src/medium.php', data, (response) => {
        // Extract data
        let dataArr = response.success.array;
        // Retrieve container
        let container = $(".list-view");
        // Retrieve result counter
        let counter = $(".result-counter");
        // Remove all items
        dominoEffect ($(".list-view-item-container"), 50, 'out', 0, () => {
            // Update result counter
            counter.html (dataArr.length);
            // Continuation
            let onComplete = function (){
                // Display data
                for (let item of dataArr)
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
                    let itemView = createListViewItem (
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
                // Show results
                dominoEffect ($(".list-view-item-container"), 50, 'in', 0);
            };
            // If there's no data, then show banner
            if(dataArr.length == 0)
                $(".banner-not-found").fadeIn(400, onComplete);
            else
                $(".banner-not-found").fadeOut(400, onComplete);
        });
    }, 'post', 'json', function (a, b, c){
        popNotification ('error', c, 'error');
    });
}

$(document).ready(function () {

    /**
     * Filter Panel Setup
     */
    let filter_switches = []
    $('.switch input').each(function (index){
        // Retrieve select element
        filter_switches.push($(this).parent().parent().parent().next());
    });
    // Configure change event
    for (let select of filter_switches)
    {
        select.change(function (){
            let data = new FormData();
            data.append('type', 'search');
            // Loop through checkboxes and retrieve data if they are
            $('.switch input').each(function (index){
                if(this.checked)
                {
                    // Retrieve corresponding select element
                    let target_select = $('.filter-switch').eq(index);
                    // Store filter criteria
                    data.append(target_select.attr('name'), target_select.val());
                }
            });
            // If there's no model then add a default
            if(!data.has('model'))
                data.append('model', 'Lesson');
            // Send filter request & update view
            updateCatalog(data);
        });
    }

    /**
     * This is the navigation item's functionality
     */
    $(".nav-item-body").hide();
    $(".nav-item-header").on('click', function () {
        // Hide all nav items' bodies
        $(".nav-item-body").slideUp();
        // Change the sign also
        $(".nav-item-header i.chevrolet").prop("class", "fas fa-chevron-right chevrolet");
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
        $(this).find("i.chevrolet").prop("class", "fas fa-chevron-down chevrolet");
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
        let data = serialize ("#form");
        // Retrieve form type from the element id
        let btnId = $(this).attr ('id');
        // Extract mode from id
        let mode = btnId.split ('-') [1];
        // Append request's type
        data.append ('type', mode);
        // Append the params from URL
        for (let key in getUrlParams ())
            data.append (key, getUrlParams()[key]);
        // Send a request
        ajaxRequest ('src/medium.php', data, function (response) {
            // Confirmation pop-up
            let header = response.hasOwnProperty ('success') ? 'نجاح' : 'فشل';
            let type = response.hasOwnProperty ('success') ? 'success' : 'fail';
            let message = response.hasOwnProperty ('success') ? 'تمت العملية بنجاح' : response.error;

            popNotification(header, message, type);
        });
    });

    /**
     * Delete button event
     */
    $(".btn-delete").on ('click', function (){
        // Retrieve the parent row
        let parent = $(this).closest ('tr');
        // Retrieve data id
        let id = parent.attr ('data-id');
        // Construct a form data object
        let data = new FormData ();
        // Provide the required data to send
        data.append ('id', id);
        data.append ('type', 'delete');
        for (let key in getUrlParams ())
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
        let keys = ['model', 'title'];
        // Create an empty form data
        let data = new FormData ();
        // Specify the type of the request
        data.append ('type', 'search');
        // Gather the values of those keys
        for (let key of keys)
            data.append (key, $(`*[name=${key}]`).val ());
        // Send a SEARCH request
        updateCatalog(data);
    });

    // Filter Panel Checkbox Effect
    $(".switch input[type='checkbox']").change(function() {
        let html_h6 = $(this).parent().parent().next().find("h6");
        let html_select = $(this).parent().parent().parent().next();

        html_select.prop("disabled", !this.checked)
        html_h6.toggleClass("text-first text-secondary")
    });

    /**
     * GradeCategory Select
     */
    $('#cb-grade-category').change(function (){
        // Clear targeted combo
        $('#cb-grade').empty();
        // Query
        let data = new FormData();
        data.append('type', 'search');
        data.append('model', 'Grade');
        data.append('category', $(this).val());
        // Fill with new data
        ajaxRequest('src/medium.php', data, function (response){
            response.success.array.forEach(function (element){
               $('#cb-grade').append(`<option value=${element.id}>${element.title}</option>`);
            });
        });
    });

});
