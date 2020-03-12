/**
 * IMPORTANT WARNING:
 * Requires JQuery 
 * */ 

function createListViewItem (id, title, model, image, ...tags)
{
    var item_container = $("<div class='col-md-4 p-3 list-view-item-container'></div>");
    var link = $(`<a href='unit.php?id=${id}&model=${model}'></a>`);
    var item = $("<div class='list-view-item border shadow'></div>");
    var item_image = $(`<img class='border-bottom shadow-sm' src='${image}' />`);
    var item_title = $(`<h4 class='text-first font-weight-bold px-3 pt-3 pb-0'>${title}</h4>`);
    var item_tags = $("<div class='card-tags px-3 pb-3 mt-3'></div>");

    let i = 0;
    for (var tag of tags)
        if (tag != null)
            item_tags.append ($(`<span class='${(tags.length / 2 > i++) ? 'text-second': 'text-secondary'}'>${tag}</span>`));

    item.append (item_image);
    item.append (item_title);
    item.append (item_tags);
    
    link.append (item);

    item_container.append (link);

    return item_container;
}