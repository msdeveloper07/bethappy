$(document).ready(function() {

    $(document).on('click', '.adjust-limit', function(e) {
        var $_this = $(this);
        var key = $_this.data('key');
        var type = $_this.data('type');
        if (typeof $_this.data('user') != 'undefined') {
            var user = $_this.data('user');
        }
        
        $_this.html('Waiting ...');
        
        var ajaxdata = {
            limit: {
                user_id: "",
                id: key,
                min_bet: $('input[name = "data['+type+']['+key+'][min_bet]"]').val(),
                max_bet: $('input[name = "data['+type+']['+key +'][max_bet]"]').val(),
                min_multi_bet: $('input[name = "data['+type+']['+key+'][min_multi_bet]"]').val(),
                max_multi_bet: $('input[name = "data['+type+']['+key+'][max_multi_bet]"]').val()
            }
        }
        
        if (typeof user != 'undefined') {
            ajaxdata.limit.user_id = user;
        }

        $.ajax({
            url: window.location.pathname,
            type: "POST",
            data: ajaxdata,
            success: function(data) {
                $_this.html('<i class="icon-check" style="margin-right:5px;"></i>Done');
                $_this.removeClass('btn-primary');
                $_this.addClass('btn-success');
                
                setTimeout(function(){
                    $_this.addClass('btn-primary');
                    $_this.removeClass('btn-success');
                    $_this.html('<i class="icon-save" style="margin-right:5px;"></i>Save');
                }, 3000);
            }
        });
    });

});