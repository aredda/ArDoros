
$(document).ready(function (){
    $('.banner-not-found').hide();
    // Configure on load query
    let data = new FormData();
    data.append('model', 'Lesson');
    data.append('type', 'search');
    data.append('paginate', 'true');
    current_request = { params: data };
    // Update view
    updateCatalog(data);
});
