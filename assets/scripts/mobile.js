/**
 * Created by petervandam on 07/11/2016.
 */

$(function() {
    $('#mobile').on('click', '.listItem', function() {
        mobileSwitchToWebView();
    })
        .on('click', '.backButton', function() {
            mobileSwitchToListItemView();
        });

    $('#mobile .listItem').hammer().on("swiperight", function() {
        var pin = $(this).find('.pin');
        $.ajax("/pin/" +$(pin).data('pin-id'))
            .done(function(response) {
                if (response == '1') {
                    $(pin).parent().toggleClass('pinned');
                }
            });
    });

    $('#mobile .topbar').hammer().on("swiperight", function() {
        $('.list').show();
        $('.title').show();
        $('.contentContainer').hide();

        $('.backButton').hide();
        $('.pageLinkToUrl').hide();
        $('.navbar').hide();

        $('iframe').attr('src', 'about:blank');
    });

    if (window.history && window.history.pushState) {
        $(window).on('popstate', function() {
            mobileSwitchToListItemView();
        });
    }

    $('.searchButton').on('click', function() {
       mobileSwitchToListItemView();
       if ($(this).hasClass("searchNotActive")) {
           $(this).removeClass('searchNotActive');
           $('.listview').hide();
           $('.searchview').show();
           $(this).css('color', 'red');
       } else {
           $(this).addClass('searchNotActive');
           $('.listview').show();
           $('.searchview').hide();
           $(this).css('color', 'black');
       }
    });

});

function mobileSwitchToWebView() {
    $('.list').hide();
    $('.title').hide();
    $('.contentContainer').show();

    $('.backButton').css('display', 'inline-block');
    $('.pageLinkToUrl').show();
    $('.navbar').css('display', 'inline-block');

    window.history.pushState('forward', null, './#' + $.now());
}

function mobileSwitchToListItemView() {
    $('.list').show();
    $('.title').show();
    $('.contentContainer').hide();

    $('.backButton').hide();
    $('.pageLinkToUrl').hide();
    $('.navbar').hide();

    $('iframe').attr('src', 'about:blank');
    window.history.pushState('forward', null, './#' + $.now());
}
