$(function() {
    // Endless scroll
    $('.feed-list').jscroll({
        padding: 150,
        nextSelector: 'a.jscroll-next:last',
        contentSelector: '.feed-list-item',
        callback: function() {
            $('.jscroll-added:last-of-type .js-action-feed-list-swipe').hammer().on("swiperight", function() {
                $(this).find('.pin').trigger('click');
            });
        }
    });

    $('.header--bar').hammer().on("swiperight", function() {
        $('.js-return').trigger('click');
    });

    $(document).on('click', '.js-reload-page', function (event) {
        event.preventDefault();
        requestNewFeedItems();
    })
        .on('click', '.js-open-url', function () {
        openPage($(this).data('url') !== '' ? $(this).data('url') : '/nourl/');
        $('.header--bar').css('backgroundColor', '#337dff');
    })
        .on('click', '.js-action-feed-list-click', function() {
        $(this).addClass('animated pulse feed-list-item--state-selected');
        $('.feed-list-item').removeClass('feed-list-item--state-selected');
        $('.header--bar').css('backgroundColor', $(this).css('borderLeftColor').replace(')', ', 0.35)').replace('rgb', 'rgba'));

        openPage($(this).data('url') !== '' ? $(this).data('url') : '/nourl/');

    })
        .on('click', '.js-return', function (e) {
            e.preventDefault();

            $('.content iframe').prop('src', '/welcome/');
            $('.content').hide();
            $('.js-reload-page').removeClass('hide-if-mobile hide-if-tablet');
            $('.js-return').removeClass('show-if-mobile show-if-tablet');
            $('.js-copy-to-clipboard').removeClass('show-if-mobile show-if-tablet');
            $('.js-open-new-window').removeClass('show-if-mobile show-if-tablet');
            $('.header--bar').removeClass('show-if-mobile');

        })
        .on ('click', '.js-reload-page', function() {
            $('iframe').attr('src', '/welcome/');
            $('.urlbar a').text('').attr('data-clipboard-text', '');
            $('.header--bar').css('backgroundColor', '#337dff');
            requestNewFeedItems();
        })
        .on('click', '.pin', function(e) {
            e.stopImmediatePropagation();
            var pin = $(this);
            $.ajax("/feed/pin/" +$(this).data('pin-id'))
                .done(function(response) {
                    if (response == 1) {
                        $(pin).parent().addClass('animated shake');
                        $(pin).parent().toggleClass('feed-list-item--state-pinned');
                    }
                });
        })
        .on('click', '.js-modal-trigger', function() {
            $($(this).data('modal-target')).modal({fadeDuration:100});
        })
        .on('click', '.js-form-feed button', function() {
            $.post('/feed/add-item/', $('.js-form-feed').serialize())
                .done(function(data) {
                    if (data === 'Done') {
                        $.modal.close();
                    }
                    else {
                        alert(data);
                    }
                })
                .fail(function(data) {
                    alert(data);
                });
        })
        .on('click', '.js-open-new-window', function() {
            window.open($('.urlbar a').attr('href'));
        })
        .on('click', '.js-visbility-toggle', function() {
        $($(this).data('target')).toggle();
    });

    $('.js-action-feed-list-swipe').hammer().on("swiperight", function() {
        $(this).find('.pin').trigger('click');
    });


    /** global: Clipboard */
    var clipboard = new Clipboard('.js-copy-to-clipboard');

    clipboard.on('success', function(e) {
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);

        e.clearSelection();
    });

    clipboard.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });

});

function requestNewFeedItems() {
    $.getJSON('/feed/refresh/' + encodeURI($('body').data('refresh-date')))
        .done(function(data) {
            var html = data.html.replace('<div class="list scroll">', '<div class="Newlist">');
            $('.list').prepend(html);
            $('body').data('refresh-date', data.refreshDate);
            $('.js-form-feed').find("input[type=text], textarea").val("");
        });
};

function openPage(url) {
    $('.content').show();
    $('.js-reload-page').addClass('hide-if-mobile hide-if-tablet');
    $('.js-return').addClass('show-if-mobile show-if-tablet');
    $('.header--bar').addClass('show-if-mobile');

    $('iframe').attr('src', url);
    $('.urlbar a').text(url).attr('href', url);
    $('.js-copy-to-clipboard').attr('data-clipboard-text', url).addClass('show-if-mobile show-if-tablet');
    $('.js-open-new-window').addClass('show-if-mobile show-if-tablet');
}

function hexToRgb(hex) {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function(m, r, g, b) {
        return r + r + g + g + b + b;
    });

    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}
