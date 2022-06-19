$(document).ready(function() {        
    $('#expand-all-btn').click(function(ev) {
        $('.box').each(function(i, el) {
            if($(el).find('.box-content').is(':visible')) return;
            else $(el).find('.btn-minimize').click();
        });
    });
    
    $('#collapse-all-btn').click(function(ev) {    
        $('.box').each(function(i, el) {
            if(!$(el).find('.box-content').is(':visible')) return;
            else $(el).find('.btn-minimize').click();
        });
    });
                
    $(document.body).on('keyup', '.box .box-content input', function(e) { 
        if(parseFloat($(this).val()) != parseFloat($(this).data('odd'))) {
            // dont place btn
            if($(this).parent().children('.btn').length  > 0) return;

            var $_btn = $('<button type="button" class="btn btn-warning btn-mini"><i class="icon-ok-sign icon-white"></i></button>');

            $_btn.click(function(e) {
                var $_this  = $(this),
                    _val    = $_this.parent().children('.betpart-odd-value').val(),
                    _id     = $_this.parents('.betpart').attr('id').replace('betpart-', '');

                $.ajax({
                    url:        '/admin/bets/changebetpart/' + _id + '/' + _val,
                    success:    function(data) {
                        if(data.error) alert(data.error); 
                        else {
                            $_this.removeClass('btn-warning');
                            $_this.children('span').removeClass('icon-save');
                            $_this.addClass('btn-success');
                            $_this.children('span').addClass('icon-ok');
                            $_this.unbind('click');
                            $_this.parents('tr').addClass('warning');

                            var pauseBtn = $_this.parents('.box').find('.bet-change-btn');

                            if(pauseBtn.length > 0) {
                                pauseBtn.replaceWith('<a class="btn bet-release-btn btn-success btn-round" title="Allow market to take odds from feeds">Release</a>');                            
                            }

                            setTimeout(function() {
                                $_this.remove();
                            }, 4 * 1000);
                        }
                    },
                    error:      function(data) {
                        $_this.removeClass('btn-warning');
                        $_this.children('span').removeClass('icon-save');
                        $_this.addClass('btn-danger');
                        $_this.children('span').addClass('icon-remove');

                        setTimeout(function() {
                            $_this.removeClass('btn-danger');
                            $_this.children('span').removeClass('icon-remove');
                            $_this.addClass('btn-warning');
                            $_this.children('span').addClass('icon-save');
                        }, 4 * 1000);
                    }
                });
            });

            $(this).parent().append($_btn);
        }
        else {
            $(this).parent().children('.btn').remove();
        }
    }); 

    $(document.body).on('click', '.bet-stop-btn', function(e) { 
        var $_this = $(this),
            id = $_this.parents('.box').attr('id').replace('bet-', '');
            
        $.ajax({
            url: '/admin/bets/disablebet/' + id ,
            success: function(data) {
                $_this.parents('.box').addClass('disabled');                
                $_this.replaceWith('<a class="btn bet-start-btn btn-danger btn-round" title="Activate market"><i class="icon-ban-circle icon-white"></i></a>');
            },
            error: function(data) {
                alert("Sorry an error has occured, please try again.");
            }
        });
    });
        
    $(document.body).on('click', '.bet-start-btn', function(e) { 
        var $_this = $(this),
            id = $_this.parents('.box').attr('id').replace('bet-', '');
            
        $.ajax({
            url: '/admin/bets/enablebet/' + id ,
            success: function(data) {
                $_this.parents('.box').removeClass('disabled');        
                $_this.replaceWith('<a class="btn bet-stop-btn btn-success btn-round" title="Deactivate market"><i class="icon-ban-circle icon-white"></i></a>');
            },
            error: function(data) {
                alert("Sorry an error has occured, please try again.");
            }
        });
    });
    
    $(document.body).on('click', '.bet-change-btn', function(e) { 
        var $_this = $(this),
            id = $_this.parents('.box').attr('id').replace('bet-', '');
            
        $.ajax({
            url: '/admin/bets/setbettomanual/' + id ,
            success: function(data) {       
                $_this.replaceWith('<a class="btn bet-success-btn btn-primary btn-round" title="Stop market from taking odds from feeds">Release</a>');                
            },
            error: function(data) {
                alert("Sorry an error has occured, please try again.");
            }
        });
    });
    
    $(document.body).on('click', '.bet-release-btn', function(e) { 
        var $_this = $(this),
            id = $_this.parents('.box').attr('id').replace('bet-', '');
            
        $.ajax({
            url: '/admin/bets/unsetbettomanual/' + id ,
            success: function(data) {     
                $_this.replaceWith('<a class="btn bet-change-btn btn-primary btn-round" title="Allow market to take odds from feeds">Change</a>');      
            },
            error: function(data) {
                alert("Sorry an error has occured, please try again.");
            }
        });
    });    
});