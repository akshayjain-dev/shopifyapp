//Simple Search
$(document).on('submit', '#admin_search_form', function (e) {
    e.preventDefault();
    const search_data = $('#search_keyword').val();
    const csrf_token = $('meta[name="csrf-token"]').attr('content');
    const pagination = $('#pagination').val();
    let order_by_asc_desc = "false";
    if ($('th.order-sorting.active').find('span').hasClass('order_action')) {
        order_by_asc_desc = "true";
    }
    const order_by_field = $('th.order-sorting.active').find('span').attr('data-sort');
    var formData = {
        "_token": csrf_token,
        "pagination": pagination,
        "order_by_field": order_by_field,
        "order_by_asc_desc": order_by_asc_desc,
        "search_data": search_data,
    };
    pagnationAction(formData);
});
//pagination page
$(document).on('click', '.custom-pagination li.page-item.page-item-btn a', function (event) {
    event.preventDefault();
    const number = $(this).attr('data-href');
    const csrf_token = $('meta[name="csrf-token"]').attr('content');
    const pagination = $('select#pagination').val();
    const search_data = $('#search_keyword').val();
    let order_by_asc_desc = "false";
    if ($('th.order-sorting.active').find('span').hasClass('order_action')) {
        order_by_asc_desc = "true";
    }
    const order_by_field = $('th.order-sorting.active').find('span').attr('data-sort');
    const formData = {
        "pagination": pagination,
        "_token": csrf_token,
        "page": number,
        "order_by_field": order_by_field,
        "order_by_asc_desc": order_by_asc_desc,
        "search_data": search_data,
    };
    pagnationAction(formData);
});    
//pagination common file
function pagnationAction(formData) {
    const admin_url = $('#baseurl').data('admin-url');
    $(".overlay").show();        
    $('.alert-danger').hide();
    $.ajax({
        url: "/" + admin_url,
        type: "post",
        data: formData,
        dataType: 'html',
        complete: function () {
            $(".overlay").hide();
        },
        success: function (response) {
            const data = $(response).find('.card-body table tbody').html();
            const total_count = $(response).find('.records-count').html();
            const change_number = $(response).find('.custom-pagination').html();
            const active_filters = $(response).find('.active-filter-datas').html();
            $('.records-count').html(total_count);
            $('.card-body table tbody').html(data);
            $('.active-filters').hide();
            if(parseInt(total_count) > 0){
                if(!$('.table.table-responsive-sm').hasClass('table-overflow')){
                    $('.table.table-responsive-sm').addClass('table-overflow');
                }
            }else{
                $('.table.table-responsive-sm').removeClass('table-overflow');
            }

            if ($.trim(active_filters) !== '') {
                $('.active-filters').show();
                $('.active-filter-datas').html(active_filters);
            }
            if (change_number == undefined) {
                $('.card-body .custom-pagination').html(' ');
            } else {
                $('.card-body .custom-pagination').html(change_number);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            $('.alert-danger').show();
            $('#show-error').html(errorThrown);
        }   
    });
}