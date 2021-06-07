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
function updateCatalog(data, isCardView = true)
{
    ajaxRequest ('src/medium.php', data, (response) => {
        // Extract data
        let dataArr = response.success.data;
        // Update paginator
        updatePaginator(response.success);
        // Retrieve container
        let container = $(".list-view");
        // Retrieve result counter
        let counter = $(".result-counter");
        // Remove all items
        dominoEffect ($(".list-view-item-container"), 10, 'out', 0, () => {
            // Update result counter
            counter.html (response.success.total_count);
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
                    let itemView = createCardViewItem (
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
                    if (!isCardView)
                        itemView = createListViewItem(item, data.get('model'))
                    // hide it
                    itemView.css ({'display': 'none'});
                    // append it
                    container.append (itemView);
                }
                // Show results
                dominoEffect ($(".list-view-item-container"), 10, 'in', 0);
            };
            // If there's no data, then show banner
            if(dataArr.length == 0)
            {
                $('.pagination').hide();
                $(".banner-not-found").fadeIn(400, onComplete);
            }
            else
            {
                $(".banner-not-found").fadeOut(400, function (){
                    $('.pagination').show();
                    onComplete();
                });
            }
        });
    }, 'post', 'json', function (a, b, c){
        popNotification ('error', c, 'error');
    });
}

/**
 * A method to facilitate the refresh of paginator
 */
function updatePaginator(meta)
{
    // Remove current pagination links
    $('.pagination .page-number').remove();
    // Create new pagination links
    for (let i=0; i < meta.total_pages; i++)
    {
        // Create link
        let page_link = $(`<li class="page-item page-number" data-page=${i+1}><a class="page-link">${i+1}</a></li>`);
        // Set as active if
        if(meta.page == i + 1)
            page_link.addClass('active');
        // Insert link
        $('.pagination').append(page_link);
    }
    // Move the next button to the end
    $('.pagination').append($('.page-next'));
    // Disable next & prev buttons
    $('.page-prev, .page-next').removeClass('disabled')
    if(meta.page == 1)
        $('.page-prev').addClass('disabled')
    if(meta.page == meta.total_pages)
        $('.page-next').addClass('disabled')
}

$(document).ready(function () {

    /**
     * Required for pagination
     */
    let card_view = true;
    let current_request = {
        params: new FormData()
    };
    current_request.params.append('model', 'Lesson');
    current_request.params.append('type', 'search');
    current_request.params.append('paginate', 'true');
    for(let key in getUrlParams())
        current_request.params.append(key, getUrlParams()[key]);

    /**
     * Initial load
     */
    $('.list-view').ready(function (){
        updateCatalog(current_request.params, card_view);
    });

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
            // If this is grade cb, change breadcrumb
            if(select.attr('id') == 'cb-grade')
                $('.a-grade-title').html($('#cb-grade option:selected').text())
            // Prepare query params
            let data = new FormData();
            data.append('type', 'search');
            data.append('paginate', 'true');
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
            // Save current request params
            current_request.params = data;
            // Send filter request & update view
            updateCatalog(data, card_view);
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
        data.append ('paginate', 'true');
        // Gather the values of those keys
        for (let key of keys)
            data.append (key, $(`*[name=${key}]`).val ());
        // Save current request params
        current_request.params = data;
        // Send a SEARCH request
        updateCatalog(data, card_view);
    });

    // Filter Panel Checkbox Effect
    $(".switch input[type='checkbox']").change(function() {
        let html_h6 = $(this).parent().parent().next().find("h6");
        let html_select = $(this).parent().parent().parent().next();

        html_select.prop("disabled", !this.checked)
        html_h6.toggleClass("text-first text-secondary")
    });

    /**
     * Pagination Controls
     */
    $(document).on('click', '.pagination .page-number:not(.active)', function (){
        // Remove active state from all siblings
        $('.pagination .page-number').removeClass('active');
        // Add active state to this item
        $(this).addClass('active');
        // Get page number
        let page = $(this).attr('data-page');
        // Update the current request params
        current_request.params.set('page', page);
        // Update catalog
        updateCatalog(current_request.params, card_view);
    });
    // Next button
    $(document).on('click', '.pagination .page-next:not(.disabled)', function (){
        // Retrieve active, and next links
        let active_link = $('.pagination .page-number.active');
        let next_link = active_link.next();
        // Update active state
        active_link.removeClass('active');
        next_link.addClass('active');
        // Update the current request params
        current_request.params.set('page', parseInt(active_link.attr('data-page')) + 1);
        // Update catalog
        updateCatalog(current_request.params, card_view);
    });
    // Previous button
    $(document).on('click', '.pagination .page-prev:not(.disabled)', function (){
        // Retrieve active, and next links
        let active_link = $('.pagination .page-number.active');
        let prev_link = active_link.prev();
        // Update active state
        active_link.removeClass('active');
        prev_link.addClass('active');
        // Update the current request params
        current_request.params.set('page', parseInt(active_link.attr('data-page')) - 1);
        // Update catalog
        updateCatalog(current_request.params, card_view);
    });
    // Card view button
    $('.page-list-view, .page-card-view').click(function (){
        // Determine clicked and the other
        let target = $(this);
        let other = target.hasClass('page-list-view') ? $('.page-card-view') : $('.page-list-view');
        // Optimize
        if(target.hasClass('active'))
            return;
        // Change active state
        other.removeClass('active');
        target.addClass('active');
        // Change switch
        card_view = target.hasClass("page-card-view");
        // Update catalog
        updateCatalog(current_request.params, card_view);
    });

});
