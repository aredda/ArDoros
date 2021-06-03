/**
 * IMPORTANT WARNING:
 * Requires JQuery 
 * */ 

function createCardViewItem (id, title, model, image, ...tags)
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

function createListViewItem (record, model)
{
    return $(`
    <div class="col-md-12 list-view-item-container pb-3">
        <a href="unit.php?id=${record.id}&model=${model}">
            <div class='list-view-item bg-zero px-4 py-3 d-flex flex-row flex-row-reverse align-items-center rounded border shadow'>
                <i class="${record.subject.icon} h2 m-0 ml-4 text-dark"></i>
                <div class='d-flex flex-column'>
                    <h4 class='text-first m-0 font-weight-bold mb-2'>${record.title}</h4>
                    <div class='h6 m-0'>
                        <span class="badge text-white py-1 px-2 bg-grd-second">${record.grade.title}</span>
                        <span class="badge text-white py-1 px-2 bg-grd-second">${record.subject.title}</span>
                        <span class="badge text-white py-1 px-2 bg-grd-second">الدورة ${record.semester}</span>
                    </div>
                </div>
            </div>
        </a>
    </div>`);
}