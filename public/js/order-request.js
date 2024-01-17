$(document).ready(function () {
    (function () {
        const $captureForm = $('#capture-form');
        const csrf_token = $('meta[name="csrf-token"]').attr('content');
        $captureForm.on('submit', function (e) {
            e.preventDefault();
            const order_id = $('#order_id').val();
            const admin_url = $('#admin_url').val();
            if (order_id < 1) {
                alert("Error: order id is null!");
                return;
            }
            var formData = {
                "_token": csrf_token,
                "order_id": order_id
            };
            $(this).find('button').html("Processing.. please wait..");
            $.ajax({
                url: "/" + admin_url + "/orders/capture",
                type: "post",
                data: formData,
                dataType: 'json',
                success: function (response) {
                    $('.capture-button').html('Capture');
                    if (response.status == 'success') {
                        window.location.href = '/' + admin_url + '/orders/' + order_id + '';
                    }
                    else {
                        alert('Error : ' + response.message);
                    }
                }
            });
        });
    })();

    //cancel order

    (function () {
        const $captureForm = $('#cancel-form');
        const csrf_token = $('meta[name="csrf-token"]').attr('content');
        $captureForm.on('submit', function (e) {
            e.preventDefault();
            const order_id = $('#order_id').val();
            const admin_url = $('#admin_url').val();
            if (order_id < 1) {
                alert("Error: order id is null!");
                return;
            }
            var formData = {
                "_token": csrf_token,
                "order_id": order_id
            };
            $(this).find('button').html("Processing.. please wait..");
            $.ajax({
                url: "/" + admin_url + "/orders/cancel",
                type: "post",
                data: formData,
                dataType: 'json',
                success: function (response) {
                    $('.cancel-button').html('Cancel');
                    if (response.status == 'success') {
                        window.location.href = '/' + admin_url + '/orders/' + order_id + '';
                    }
                    else {
                        alert('Error : ' + response.message);
                    }
                }
            });
        });
    })();

    //refund order

    (function () {
        const $captureForm = $('#refund-form');
        const csrf_token = $('meta[name="csrf-token"]').attr('content');
        $captureForm.on('submit', function (e) {
            e.preventDefault();
            const order_id = $('#order_id').val();
            const admin_url = $('#admin_url').val();
            if (order_id < 1) {
                alert("Error: order id is null!");
                return;
            }
            var formData = {
                "_token": csrf_token,
                "order_id": order_id
            };
            $(this).find('button').html("Processing.. please wait..");
            $.ajax({
                url: "/" + admin_url + "/orders/refund",
                type: "post",
                data: formData,
                dataType: 'json',
                success: function (response) {
                    $('.refund-button').html('Refund');
                    if (response.status == 'success') {
                        window.location.href = '/' + admin_url + '/orders/' + order_id + '';
                    }
                    else {
                        alert('Error : ' + response.message);
                    }
                }
            });
        });
    })();

    //pagination show selected item
    $(document).on('change', '.per-page-filter', function (event) {
        const csrf_token = $('meta[name="csrf-token"]').attr('content');
        const pagination = $(this).val();
        $('.per-page-filter').val(pagination);
        const date_from = $('#date_from').val();
        const date_to = $('#date_to').val();
        const status = $('#status').val();
        let order_by_asc_desc = "false";
        if ($('th.order-sorting.active').find('span').hasClass('order_action')) {
            order_by_asc_desc = "true";
        }
        const order_by_field = $('th.order-sorting.active').find('span').attr('data-sort');
        const search_data = $('#search_keyword').val();
        const merchant_entity_reference = $('#merchant_entity_reference').val();
        let merchant_name = '';
        if ($('#merchant_name').length) {
            merchant_name = $('#merchant_name').val();
        }
        const formData = {
            "_token": csrf_token,
            "date_from": date_from,
            "date_to": date_to,
            "status": status,
            "pagination": pagination,
            "order_by_field": order_by_field,
            "order_by_asc_desc": order_by_asc_desc,
            "search_data": search_data,
            "merchant_entity_reference": merchant_entity_reference,
            "merchant_name": merchant_name
        }
        pagnationAction(formData);
    });
    //pagination common file
    function pagnationAction(formData) {
        const admin_url = $('#OrderDataJs').data('admin-url');
        $(".overlay").show();        
        $('.alert-danger').hide();
        $.ajax({
            url: "/" + admin_url + "/orders/search",
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

                console.log(active_filters);
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
    //pagination page
    $(document).on('click', '.custom-pagination li.page-item.page-item-btn a', function (event) {
        event.preventDefault();
        const number = $(this).attr('data-href');
        const csrf_token = $('meta[name="csrf-token"]').attr('content');
        const pagination = $('select#pagination').val();
        const date_from = $('#date_from').val();
        const date_to = $('#date_to').val();
        const status = $('#status').val();
        const search_data = $('#search_keyword').val();
        let order_by_asc_desc = "false";
        if ($('th.order-sorting.active').find('span').hasClass('order_action')) {
            order_by_asc_desc = "true";
        }
        const order_by_field = $('th.order-sorting.active').find('span').attr('data-sort');
        const merchant_entity_reference = $('#merchant_entity_reference').val();
        let merchant_name = '';
        if ($('#merchant_name').length) {
            merchant_name = $('#merchant_name').val();
        }
        const formData = {
            "pagination": pagination,
            "date_from": date_from,
            "date_to": date_to,
            "status": status,
            "_token": csrf_token,
            "page": number,
            "order_by_field": order_by_field,
            "order_by_asc_desc": order_by_asc_desc,
            "search_data": search_data,
            "merchant_entity_reference": merchant_entity_reference,
            "merchant_name": merchant_name
        };
        pagnationAction(formData);
    });
    //sorting order price,id,name etc
    $(document).on('click', 'th.order-sorting', function (event) {
        event.preventDefault();
        $('th.order-sorting').removeClass('active');
        $(this).addClass('active');
        $(this).find('span').toggleClass('order_action');
        let order_by_asc_desc = "false";
        const number = $('.custom-pagination li#pageitem a').attr('data-gethref');
        const csrf_token = $('meta[name="csrf-token"]').attr('content');
        const pagination = $('select#pagination').val();
        const date_from = $('#date_from').val();
        const date_to = $('#date_to').val();
        const status = $('#status').val();
        const search_data = $('#search_keyword').val();
        const order_by_field = $(this).find('span').attr('data-sort');
        if ($(this).find('span').hasClass('order_action')) {
            order_by_asc_desc = "true";
        }
        const merchant_entity_reference = $('#merchant_entity_reference').val();
        let merchant_name = '';
        if ($('#merchant_name').length) {
            merchant_name = $('#merchant_name').val();
        }
        const formData = {
            "pagination": pagination,
            "date_from": date_from,
            "date_to": date_to,
            "status": status,
            "_token": csrf_token,
            "page": number,
            "order_by_field": order_by_field,
            "order_by_asc_desc": order_by_asc_desc,
            "search_data": search_data,
            "merchant_entity_reference": merchant_entity_reference,
            "merchant_name": merchant_name
        };
        pagnationAction(formData);
    });
    //pagination change according to the number
    $(document).on('change', '.custom-pagination .currentpage', function (event) {
        const val = $(this).val();
        const lastnumber = $('li#lastpage label').attr('data-lastpage');
        let page_num = val;
        let order_by_asc_desc = "false";
        if (val > parseInt(lastnumber)) {
            $(this).val(lastnumber);
            page_num = lastnumber;
        }
        const csrf_token = $('meta[name="csrf-token"]').attr('content');
        const pagination = $('#pagination').val();
        const date_from = $('#date_from').val();
        const date_to = $('#date_to').val();
        const status = $('#status').val();
        const search_data = $('#search_keyword').val();
        const order_by_field = $('th.order-sorting.active').find('span').attr('data-sort');
        if ($('th.order-sorting.active').find('span').hasClass('order_action')) {
            order_by_asc_desc = "true";
        }
        const merchant_entity_reference = $('#merchant_entity_reference').val();
        let merchant_name = '';
        if ($('#merchant_name').length) {
            merchant_name = $('#merchant_name').val();
        }
        const formData = {
            "_token": csrf_token,
            "pagination": pagination,
            "order_by_field": order_by_field,
            "page": page_num,
            "date_from": date_from,
            "date_to": date_to,
            "status": status,
            "order_by_asc_desc": order_by_asc_desc,
            "search_data": search_data,
            "merchant_entity_reference": merchant_entity_reference,
            "merchant_name": merchant_name
        };
        pagnationAction(formData);
    });
    // Filters
    $('#filter_form').on('submit', function (e) {
        e.preventDefault();
        const date_from = $('#date_from').val();
        const date_to = $('#date_to').val();
        const status = $('#status').val();
        const pagination = $('#pagination').val();
        const csrf_token = $('meta[name="csrf-token"]').attr('content');
        const search_data = $('#search_keyword').val();
        const merchant_entity_reference = $('#merchant_entity_reference').val();
        const order_id = $('#order_id').val();
        let merchant_name = '';
        if ($('#merchant_name').length) {
            merchant_name = $('#merchant_name').val();
        }
        let order_by_asc_desc = "false";
        if ($('th.order-sorting.active').find('span').hasClass('order_action')) {
            order_by_asc_desc = "true";
        }
        const order_by_field = $('th.order-sorting.active').find('span').attr('data-sort');
        var formData = {
            "date_from": date_from,
            "date_to": date_to,
            "status": status,
            "_token": csrf_token,
            "pagination": pagination,
            "order_by_field": order_by_field,
            "order_by_asc_desc": order_by_asc_desc,
            "search_data": search_data,
            "merchant_entity_reference": merchant_entity_reference,
            "merchant_name": merchant_name,
            "order_id": order_id,
        };
        pagnationAction(formData);
    });
    //Simple Search
    $(document).on('submit', '#admin_search_form', function (e) {
        e.preventDefault();
        const search_data = $('#search_keyword').val();
        if (search_data === '') {
            alert('Please enter the Keyword');
            $('#search_keyword').focus();
            return false;
        }
        const date_from = $('#date_from').val();
        const date_to = $('#date_to').val();
        const status = $('#status').val();
        const csrf_token = $('meta[name="csrf-token"]').attr('content');
        const pagination = $('#pagination').val();
        let order_by_asc_desc = "false";
        if ($('th.order-sorting.active').find('span').hasClass('order_action')) {
            order_by_asc_desc = "true";
        }
        const order_by_field = $('th.order-sorting.active').find('span').attr('data-sort');
        const merchant_entity_reference = $('#merchant_entity_reference').val();
        let merchant_name = '';
        if ($('#merchant_name').length) {
            merchant_name = $('#merchant_name').val();
        }
        var formData = {
            "_token": csrf_token,
            "date_from": date_from,
            "date_to": date_to,
            "status": status,
            "pagination": pagination,
            "order_by_field": order_by_field,
            "order_by_asc_desc": order_by_asc_desc,
            "search_data": search_data,
            "merchant_entity_reference": merchant_entity_reference,
            "merchant_name": merchant_name
        };
        pagnationAction(formData);
    });
    // Active Filters Remove
    $(document).on('click', '.filter-remove', function (e) {
        e.preventDefault();
        const filter_type = $(this).data('type');
        const search_type = $(this).data('search');

        if (filter_type === 'merchant_entity_reference') {
            $('#merchant_entity_reference').val(null).trigger('change');
            $("#merchant_entity_reference").val('');

        } else if (filter_type === 'merchant_name') {
            $('#merchant_name').val(null).trigger('change');
            $("#merchant_name").val('');

        } else if (filter_type === 'purchase_date') {
            $('#date_from').val('');
            $('#date_to').val('');
        }
        else if (filter_type === 'status') {
            $('#status').val('');
        }
        else if (filter_type === 'keyword') {
            $('#search_keyword').val('');
        }
        else if (filter_type === 'order_id') {
            $('#order_id').val('');
        }
        else {
            $('#date_from').val('');
            $('#date_to').val('');
            $('#status').val('');
            $('#search_keyword').val('');
            $('#order_id').val('');
            $("#merchant_entity_reference").val();
            $('#merchant_entity_reference').val(null).trigger('change');
            if ($('#merchant_name').length) {
                $('#merchant_name').val(null).trigger('change');
                $("#merchant_name").val('');
            }
        }
        $("#filter_form").submit();
    });
    $(function () {
        $(".datepicker").datepicker({
            showButtonPanel: true,
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            showOn: 'button',
            buttonImageOnly: true,
            buttonImage: "/icons/date-icon.png",
            //comment the beforeShow handler if you want to see the ugly overlay
            beforeShow: function () {
                setTimeout(function () {
                    $('.ui-datepicker').css('z-index', 99999999999999);
                }, 0);
            }
        });
    });
});

$(function () {
    const admin_url = $('#OrderDataJs').data('admin-url');
    $('#merchant_entity_reference').select2({
        ajax: {
            url: "/" + admin_url + "/ajaxMerchantorders",
            dataType: 'json',
            data: function (params) {
                $( ".select2-search__field" ).attr('placeholder','Search here...');
                let users_id = '';
                if ($('#merchant_name').length) {
                    users_id = $('#merchant_name').val();
                }
                var query = {
                    search: params.term,
                    type: 'merchant_entity_reference',
                    users_id: users_id
                }
                return query;
            }
        },
        //minimumInputLength: 1,
        allowClear: true,
        placeholder: "Select a Merchant Entity Reference",
        width: 'resolve'
    });
    $('#merchant_name').select2({
        ajax: {
            url: "/" + admin_url + "/ajaxMerchantorders",
            //url: "ajaxMerchantorders",
            dataType: 'json',
            data: function (params) {
                $( ".select2-search__field" ).attr('placeholder','Search here...');
                var query = {
                    search: params.term,
                    type: 'merchant_name'
                }
                return query;
            }
        },
        //minimumInputLength: 1,
        allowClear: true,
        placeholder: "Select a Merchant Name",
        width: 'resolve'
    });    

    $('#merchant_name').on("select2:selecting", function (e) {
        $('#merchant_entity_reference').val(null).trigger('change');
    });
});
