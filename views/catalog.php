<!-- Filtering Panel -->
<div class="row px-3">
    <div class="col-md-3 p-1">
        <h6 class="text-first font-weight-bold">المستوى</h6>
        <select class="w-100 p-2">
            <option>Item</option>
            <option>Item</option>
            <option>Item</option>
        </select>
    </div>
    <div class="col-md-3 p-1">
        <h6 class="text-first font-weight-bold">المستوى</h6>
        <select class="w-100 p-2">
            <option>Item</option>
            <option>Item</option>
            <option>Item</option>
        </select>
    </div>
    <div class="col-md-3 p-1">
        <h6 class="text-first font-weight-bold">المستوى</h6>
        <select class="w-100 p-2">
            <option>Item</option>
            <option>Item</option>
            <option>Item</option>
        </select>
    </div>
    <div class="col-md-3 p-1">
        <h6 class="text-first font-weight-bold">المستوى</h6>
        <select class="w-100 p-2">
            <option>Item</option>
            <option>Item</option>
            <option>Item</option>
        </select>
    </div>
</div>

<!-- Result List Item -->
<div class="row px-3 py-4">
    <div class="col-md-12">
        <div class="row p-3 list-view content-holder">
            <div class="col-md-12  py-2">
                <h3 class="m-0 p-0 text-second-dark"><span class="float-left text-first">9</span>عدد النتائج</h3>
            </div>
            <script>
                var list_view = $(".list-view");
                
                for (var i=0; i<9; i++)
                    list_view.append (createListViewItem ("media/unavailable.jpg", "العنوان", "المستوى", "المادة", "النوع", "الدورة"));
            </script>
        </div>
    </div>
</div>