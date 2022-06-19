var moveLeft = 0;
var moveDown = 0;
var timer;
function adjustPop(e, target, moveLeft, moveDown) {
    leftD = e.pageX + parseInt(moveLeft);
    maxRight = leftD + $(target).outerWidth();
    windowLeft = $(window).width() - 40;
    windowRight = 0;
    maxLeft = e.pageX - (parseInt(moveLeft) + $(target).outerWidth() + 20);

    if(maxRight > windowLeft && maxLeft > windowRight) leftD = maxLeft;

    topD = e.pageY - parseInt(moveDown);
    maxBottom = parseInt(e.pageY + parseInt(moveDown) + 20);
    windowBottom = parseInt(parseInt($(document).scrollTop()) + parseInt($(window).height()) - 200);
    maxTop = topD;
    windowTop = parseInt($(document).scrollTop());
    if(maxBottom > windowBottom) {
        topD = windowBottom - $(target).outerHeight() - 20;
    } else if(maxTop < windowTop){
        topD = windowTop + 20;
    }
    $(target).css('top', topD+15).css('left', leftD+15);
};

$(document).ready(function() {
    
    $('a.popper').hover(function(e) {
        var _this = this,
            target = '#' + ($(this).attr('data-popbox'));
            
        timer = setTimeout(function(e) {
            $.ajax({
                url: "/admin/users/ajax_view/" + $(_this).attr('data-id'),
                success: function(data) {
                    $( target ).html(data);
                    $( target).show();
                    moveLeft = $(_this).outerWidth();
                    moveDown = ($(target).outerHeight() / 2);
                }
            });
         }, 1000);
    }, function() {
        var target = '#' + ($(this).attr('data-popbox'));
        $(target).hide();
        clearTimeout(timer);
    });
 
    $('a.popper').mousemove(function(e) {
        var target = '#' + ($(this).attr('data-popbox'));
        adjustPop(e, target, moveLeft, moveDown);
    });
    
    $('.paymentinfo').hover(function(e) {
        var _this = this, target = '#' + ($(this).attr('data-popbox'));
        timer = setTimeout(function(e) {
            $(target).html($(_this).attr('data-content'));
            $(target).show();
            moveLeft = $(_this).outerWidth();
            moveDown = ($(target).outerHeight() / 2);
        }, 1000);
    }, function() {
        var target = '#' + ($(this).attr('data-popbox'));
        $(target).hide();
        clearTimeout(timer);
    });
    
    $('.paymentinfo').mousemove(function(e) {
        var target = '#' + ($(this).attr('data-popbox'));
        adjustPop(e, target, moveLeft, moveDown);
    });
});

