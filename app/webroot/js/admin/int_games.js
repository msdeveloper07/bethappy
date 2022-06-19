$(document).ready(function () {
    $(".uploadimage").hide();
    $(".updateorder").hide();

    /** GAMES REPORT START **/
    $(".dialog-message").hide();
    $("label[for='set_category']").hide();
    $("label[for='set_brand']").hide();

    $("#force_gamelists").on('change', function () {
        var game_to_force = $(this).find(":selected").val();
        var url = "";
        if (game_to_force == 'all') {
            url = '/admin/int_games/int_games/force_gamelists';
        } else {
            url = '/admin/int_games/int_games/force_gamelists?type=' + game_to_force;
        }
        console.log(url);
        $.ajax({
            url: url,
            method: "GET",
            success: function (response) {
                if (response.status == 'success')
                    location.reload();
            }
        });
    });

    var category = 'category';
    var brand = 'brand';

    $("select#bulk_actions").change(function () {
        var action = $(this).val();
        if (action == category) {
            $("label[for='set_brand']").hide();
            $("label[for='set_category']").show();
        } else if (action == brand) {
            $("label[for='set_category']").hide();
            $("label[for='set_brand']").show();
        } else {
            $("label[for='set_category']").hide();
            $("label[for='set_brand']").hide();
        }
    });

//    var selected = {};
//    $('input[name="checkgame"]').on('click', function(){
//        var id = $(this).parent().parent().parent().parent().parent().attr('id');
//        if (!selected[id]) selected[id] = {};
//        
//        var val = $(this).val();
//        if ($('#'+id + ' input[name="checkgame"][value="'+val+'"]').is(":checked")) {
//            selected[id][val] = val;
//        } else {
//            selected[id][val] = null;
//        }
//        console.log(selected);
//        
//        var countsel = 0
//        for (var i in selected[id]) {
//            if (selected[id][i] != null) {
//                countsel++;
//            }
//        }
//        if (countsel == 1) {
//            if (!$(".setorder#"+id).length) {
//                $(".dataTables_wrapper#"+id).prepend('<div id="'+id+'" class="setorder btn ui-state-highlight">Set order</div>');
//            } else {
//                $(".setorder#"+id).css('display', 'initial');
//            }
//        } else if (countsel < 1){
//            $(".setorder#"+id).css('display', 'none');
//        }
//    });
//    
//    $(".setorder").on('click', function(){
//        var catid = $(this).attr('id');
//        
//        
//        
//        $(".dataTables_wrapper#"+catid).append()
//    });

    $("input#setbulk").on('click', function () {
        var setid;
        var action = $("select#bulk_actions option:selected").val();

        if (action == category) {
            setid = $("select[name='categories'] option:selected").val();
        } else if (action == brand) {
            setid = $("select[name='brands'] option:selected").val();
        }

        var games = [];
        $('input[name="checkgame"]:checked').each(function () {
            games.push(this.value);
        });

        $.ajax({
            url: '/admin/int_games/int_games/set_bulk/?action=' + action + '&games=' + games + '&setid=' + setid,
            method: "GET",
            success: function (data) {
                if (data.status == 'success')
                    location.reload();
            }
        });
    });


    // DataTable
    $(".namecap").on('click', function (event) {
        $("#groupnogames").hide();
        $("#loadertab").show();
        var category_id = $(this).attr('id');
        $(this).addClass('active');
        $(".namecap:not(#" + category_id + ")").removeClass('active');

        $.ajax({
            type: 'GET',
            url: '/admin/int_games/int_games/get_games/' + category_id,
            success: function (data) {//console.log(data);
                setTimeout(function () {
                    $("#grouptable").html('<div class="row-fluid" id="cat' + category_id + '"></div>');
                    $("#grouptable").show();
                    $("#cat" + category_id).html(data);
                    $("#loadertab").hide();
                }, 1000);
            }
        });
    });

    /** GAMES REPORT END **/
    $(".uploadimage").on('submit', function (event) {
        event.preventDefault();

        var action = 'image';
        var gameid = $(this).attr('data-id');
        var source = $(this).attr('data-source');
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '/admin/int_games/int_games/set_bulk/?action=' + action + '&games=' + gameid + '&source=' + source,
            data: formData,
            cache: false,
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.status == 'success') {
                    var showimage = $(".showimage#" + gameid + " img");
                    showimage.attr('src', data.path);

                    $(".uploadimage").dialog('close');
                } else {
                    console.log(data);
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });


    $(".updateorder").on('submit', function (event) {
        event.preventDefault();
        var action = 'order';
        var gameid = $(this).attr('data-id');
        var order = $("#intorder").val();
        var formData = new FormData(this);console.log(gameid, order);
        $.ajax({
            type: 'POST',
            url: '/admin/int_games/int_games/set_bulk/?action=' + action + '&games=' + gameid + '&order=' + order,
            data: formData,
            cache: false,
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.status == 'success') {
                    //var showorder = $(".showorder#" + gameid + " order");
                    //showimage.attr('src', data.path);

                    $(".updateorder").dialog('close');
                    location.reload();
                } else {
                    console.log(data);
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

});