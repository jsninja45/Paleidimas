// 2 decimal places
function round(number, digits) {
    if (!digits) {
        digits = 0;
    }

    var rounded = Math.round(number * Math.pow(10, digits)) / Math.pow(10, digits);

    return rounded;
}

// 00:00:00
function secondsToHumanTime(total_seconds) {
    hours = 0;
    if (total_seconds > 3600) {
        hours = Math.floor(total_seconds / 3600);
        //$output .= hours . ' h ';
    }

    minutes = 0;
    if (total_seconds > 60) {
        minutes = Math.floor((total_seconds - hours * 3600) / 60);
        //$output .= minutes . ' min ';
    }

    seconds = total_seconds  - hours * 3600 - minutes * 60;
    //$output .= seconds . ' s';



    return leftPad(round(hours), 2) + ':' + leftPad(round(minutes), 2) + ':' + leftPad(round(seconds), 2);
}

function leftPad(number, targetLength) {
    var output = number + '';
    while (output.length < targetLength) {
        output = '0' + output;
    }
    return output;
}

function isEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function isInIframe () {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
}


$(function() {
    $('.feedback-rating').on('click', '.rating-star', function() {
        var $parent = $(this).parent();
        var rating = $(this).index();

        $parent.children('input').val(rating).trigger('change');

        $parent.children('.rating-star').each(function(a){
            $(this).removeClass('active');
            var b = a + 1;
            if(b <= rating){
                $(this).addClass('active');
            }
        })
    });

    $('#menuToggle').on('click', function() {
       $("#menuCollapse").toggleClass('collapsed');
    });

    // Tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Popovers
    $('[data-toggle="popover"]').each(function() {
        var classes = $(this).data('class');
        var placement = $(this).data('placement');

        $(this).popover({
            container: 'body',
            template: '<div class="tooltip popover '+classes+'"><div class="tooltip-arrow"></div><div class="tooltip-inner popover-content"></div></div>',
            placement: (placement != '') ? placement : 'bottom',
            html: true,
            content: $($(this).data('target')).html()

        });
    });
});