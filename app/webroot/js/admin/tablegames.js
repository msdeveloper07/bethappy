$(document).ready(function () {
    $(".showimage").hover(function () {
        var _this = $(this);

        if (_this.hasClass('hoveredimage')) {
            _this.removeClass('hoveredimage');
        } else {
            _this.addClass('hoveredimage');
        }
    });

    $(".showimage").click(function () {
        var gameid = $(this).parent('tr').attr('id');
        var source = $(this).parent('tr').attr('data-source');

        $("form.uploadimage").attr('data-id', gameid);
        $("form.uploadimage").attr('data-source', source);
        $(".uploadimage").dialog({
            title: "Upload Game Image",
            modal: true,
            buttons: {
                Close: {
                    click: function () {
                        $(this).dialog("close");
                    },
                    text: 'Close',
                    class: 'btn btn-danger'
                }

//                Close: function () {
//                    $(this).dialog("close");
//                }
            }
        });
        $(".uploadimage").show();
    });

    $(".switch-button-button").on('click', function () {
        var btnbg = $(this).parent();
        if (btnbg.hasClass('checked')) {
            btnbg.removeClass('checked');
        } else {
            btnbg.addClass('checked');
        }

        var inpbg = $(this).parent().parent().children('.checkswitch');
        var backbg = $(this).parent().parent().parent();
        var action = $(this).parent().parent().parent().attr('data-type');
        var gameid = $(this).parent().parent().parent().parent().attr('id');
        checkCheckBox(backbg, action, gameid, inpbg);
    });

    $("input[name='checkall']").on('click', function () {
        var catid = $(this).attr('cat-id');
        if ($(this).is(":checked")) {
            $("tbody#bid-" + catid).children().children().find('input[name="checkgame"]').prop('checked', true);
        } else {
            $("tbody#bid-" + catid).children().children().find('input[name="checkgame"]').prop('checked', false);
        }
    });

    function checkCheckBox(backbg, action, gameid, inpbg) {
        $.ajax({
            url: '/admin/int_games/int_games/set_bulk/?action=' + action + '&games=' + gameid + '&value=' + inpbg.val(),
            method: "GET",
            success: function (data) {
                if (data.status == 'success') {
                    backbg.css('background', '#d4ba86');
                    setTimeout(function () {

                        if (inpbg.val() == 1) {
                            inpbg.val(0);
                        } else {
                            inpbg.val(1);
                        }
                        backbg.css('background', '#f5f5f5');
                    }, 1500);
                } else {
                    backbg.css('background', '#da4f49');
                    setTimeout(function () {
                        backbg.css('background', '#f5f5f5');
                        console.log(data);
                    }, 1500);
                }
            }
        });
    }
    ;

    // DataTable
    var catid = $(".showdata").attr('id');
    var table = $("table#" + catid).DataTable({});


//    $(".showorder").hover(function () {
//
//        var _this = $(this);
//
//        if (_this.hasClass('hoveredorder')) {
//            _this.removeClass('hoveredorder');
//        } else {
//            _this.addClass('hoveredorder');
//        }
//    });


    $(document).on('click', '.showorder', function () {
//        var gameid = $('.game-id').data("gameid");

        var gameid = $(this).parent('td').parent('tr').attr('id');
        console.log(gameid);
        var order = $('#order').attr('data-order');
        $("form.updateorder").attr('data-id', gameid);
        $("form.updateorder").attr('data-order', order);
        $(".updateorder").dialog({
            title: "Update Game Order",
            modal: true,
            buttons: {
                Close: {
                    click: function () {
                        $(this).dialog("close");
                    },
                    text: 'Close',
                    class: 'btn btn-danger'
                }
            }
        });
        $(".updateorder").show();
        gameid = '';
    });


//    $(".showorder").click(function () {
//        console.log($(this));
//        var gameid = $(this).parent('tr').attr('id');
//        var order = $('#order').attr('data-order');
//        $("form.updateorder").attr('data-id', gameid);
//        $("form.updateorder").attr('data-order', order);
//        $(".updateorder").dialog({
//            title: "Update Game Order",
//            modal: true,
//            buttons: {
//                Close: {
//                    click: function () {
//                        $(this).dialog("close");
//                    },
//                    text: 'Close',
//                    class: 'btn btn-danger'
//                }
//            }
//        });
//        $(".updateorder").show();
//    });


});