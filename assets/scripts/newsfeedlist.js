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

    $(document)
        .on('click', '.js-reload-page', function (event) {
            event.preventDefault();
            requestNewFeedItems();
        })
        .on('click', '.js-open-url', function () {
            openPage($(this).data('url') !== '' ? $(this).data('url') : '/nourl/', $(this).data('share-id'));
            $('.header--bar').css('backgroundColor', '#337dff');
        })
        .on('click', '.js-action-feed-list-click', function() {
            $(this).addClass('animated pulse feed-list-item--state-selected');
            $('.feed-list-item').removeClass('feed-list-item--state-selected');
            $('.header--bar').css('backgroundColor', $(this).css('borderLeftColor'));
            openPage($(this).data('url') !== '' ? $(this).data('url') : '/nourl/', $(this).data('share-id'));
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
            var that = this;
            $.post("/feed/pin/" + $(this).data('pin-id'), function() {
                $(that).parent().addClass('animated shake');
                $(that).parent().toggleClass('feed-list-item--state-pinned');
            }, 'json');
        })
        .on('click', '.js-modal-trigger', function() {
            $($(this).data('modal-target')).modal({fadeDuration:100});
        })
        .on('click', '.js-form-feed button', function() {
            $.post('/feed/add-item/', $('.js-form-feed').serialize(), function(data) {
                data.status === 'success'
                    ? $.modal.close()
                    : alert('Failed to add item due to a server error.');
            }, 'json');
        })
        .on('click', '.js-open-new-window', function() {
            window.open($('.urlbar a').attr('href'));
        })
        .on('click', '.js-visbility-toggle', function() {
            $($(this).data('target')).toggle();
        })
    ;

    $('.js-action-feed-list-swipe').hammer().on("swiperight", function() {
        $(this).find('.pin').trigger('click');
    });

    (new Clipboard('.js-copy-to-clipboard')).on('success', function(e) {
        e.clearSelection();
    });
});

global.requestNewFeedItems = function() {
    $.getJSON('/feed/refresh/' + encodeURI($('body').data('refresh-date')), function(data) {
        var html = data.html;
        $('.feed-list').prepend(html);
        $('body').data('refresh-date', data.refreshDate);
        $('.js-form-feed').find("input[type=text], textarea").val("");
    });
};

function openPage(url, shareId) {
    $('.content').show();
    $('.js-reload-page').addClass('hide-if-mobile hide-if-tablet');
    $('.js-return').addClass('show-if-mobile show-if-tablet');
    $('.header--bar').addClass('show-if-mobile');
    $('iframe').attr('src', parseYoutubeUrl(url));
    $('.urlbar a').text('https://' + window.location.hostname + '/share/' + shareId).attr('href', url);
    $('.js-copy-to-clipboard').attr('data-clipboard-text', 'https://' + window.location.hostname + '/share/' + shareId).addClass('show-if-mobile show-if-tablet');
    $('.js-open-new-window').addClass('show-if-mobile show-if-tablet');
}

function parseYoutubeUrl(url) {

    var videoId = url.replace('https://www.youtube.com/watch?v=', '');
    if (url !== videoId) {
        $('.feed-list').switchClass('', 'darkTheme', 2000, 'easeInOutQuad');
        return 'https://www.youtube.com/embed/' + videoId + '?autoplay=true';
    }
    $('.feed-list').switchClass('darkTheme', '', 2000, 'easeInOutQuad');
    return url;
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
