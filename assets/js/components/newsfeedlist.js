$(function () {
    // Endless scroll
    $('.feed-list').jscroll({
        padding: 150,
        nextSelector: 'a.jscroll-next:last',
        contentSelector: '.feed-list-item',
        callback: function () {
            $('.jscroll-added:last-of-type .js-action-feed-list-swipe').hammer().on("swiperight", function () {
                $(this).find('.pin').trigger('click');
            });
        }
    });

    $('.header--bar').each(function () {
        var mc = new Hammer(this);
        mc.on("swiperight", function () {
            $('.js-return').trigger('click');
        })
    })

    $(document)
        .on('click', '.js-reload-page', function (event) {
            event.preventDefault();
        })
        .on('click', '.js-open-url', function () {
            $('.header--bar').css('backgroundColor', '#337dff');
            openPage($(this).data('url') !== '' ? $(this).data('url') : '/nourl/', $(this).data('share-id'));
        })
        .on('click', '.js-action-feed-list-click', function () {
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
        .on('click', '.js-reload-page', function () {
            $('.content-frame').attr('src', '/welcome/');
            $('.urlbar a').text('').attr('data-clipboard-text', '');
            $('.header--bar').css('backgroundColor', '#337dff');
            requestNewFeedItems();
        })
        .on('click', '.pin', function (e) {
            e.stopImmediatePropagation();
            var that = this;
            $.post("/feed/pin/" + $(this).data('pin-id'), function () {
                $(that).parent().addClass('animated shake');
                $(that).parent().toggleClass('feed-list-item--state-pinned');
            }, 'json');
        })
        .on('click', '.pip', function (e) {
            e.stopImmediatePropagation();
            openInPictureInPicture(parseYoutubeUrl($(this).parent().data('url'), false));
        })
        .on('click', '.js-close-pip', function () {
            $('.content-pictureInPictureFrame').remove();
            $('.content-close-pip').hide();
            $('.content-maximize-pip').hide();
        })
        .on('click', '.js-modal-trigger', function () {
            $($(this).data('modal-target')).modal({fadeDuration: 100});
        })
        .on('click', '.js-form-feed button', function () {
            $.post('/feed/add-item/', $('.js-form-feed').serialize(), function (data) {
                data.status === 'success'
                    ? $.modal.close()
                    : alert('Failed to add item due to a server error.');
            }, 'json');
        })
        .on('click', '.js-open-new-window', function () {
            window.open($('.urlbar a').attr('href'));
        })
        .on('click', '.js-visbility-toggle', function () {
            $($(this).data('target')).toggle();
        })
        .on('click', '.js-send-to-pip', function () {
            switchToPicutreInPicture();
        })
        .on('click', '.js-send-from-pip', function () {
            switchFromPictureInPicture();
        })
    ;

    $('.js-action-feed-list-swipe').each(function () {
        var mc = new Hammer(this);
        mc.on("swiperight", function () {
            $(this).find('.pin').trigger('click');
        })
    });

    /** global: ClipboardJS */
    (new ClipboardJS('.js-copy-to-clipboard')).on('success', function (e) {
        e.clearSelection();
    });
});

global.requestNewFeedItems = function () {
    $.getJSON('/feed/refresh/', function (data) {
        $('.feed-list').prepend(data.html);
        $('.js-form-feed').find("input[type=text], textarea").val("");
    });
};

function openPage(url, shareId) {
    let isMobile = $('.feed-list--type-sidebar').attr('data-is-mobile');
    let disableXcheck = $('.feed-list--type-sidebar').attr('data-hideXframe');

    if (isMobile === "1") {
        hasXFrameHeader(url, shareId);
    } else {
        if (disableXcheck === "1") {
            openInFrame(url, shareId);
        } else {
            hasXFrameHeader(url, shareId);
        }
    }
}

function hasXFrameHeader(url, shareId) {
    $.post('/feed/check-header/', {url: url}).then(function (data) {
        if (data.found === true) {
            openInNewWindow(url);
        } else {
            openInFrame(url, shareId)
        }
    });
}

function openInFrame(url, shareId) {
    $('.content').show();
    $('.js-reload-page').addClass('hide-if-mobile hide-if-tablet');
    $('.js-return').addClass('show-if-mobile show-if-tablet');
    $('.header--bar').addClass('show-if-mobile');
    $('.content-frame').attr('src', parseYoutubeUrl(url, true));
    $('.urlbar a').text('https://' + window.location.hostname + '/share/' + shareId).attr('href', url);
    $('.js-copy-to-clipboard').attr('data-clipboard-text', 'https://' + window.location.hostname + '/share/' + shareId).addClass('show-if-mobile show-if-tablet');
    $('.js-open-new-window').addClass('show-if-mobile show-if-tablet');
}

function openInNewWindow(url) {
    window.open(url);
    $('.content-frame').attr('src', '/feed/opened-in-popup/');
}

function switchToPicutreInPicture() {
    $('.content-pictureInPictureFrame').remove();
    $('.content-frame').addClass('content-pictureInPictureFrame').removeClass('content-frame');
    createIframe('content-frame', '');
}

function openInPictureInPicture(url) {
    $('.content-pictureInPictureFrame').remove();
    createIframe('content-pictureInPictureFrame', url);
}

function switchFromPictureInPicture() {
    $('.content-frame').remove();
    $('.content-pictureInPictureFrame').addClass('content-frame').removeClass('content-pictureInPictureFrame');
    $('.content-close-pip').hide();
    $('.content-maximize-pip').hide();
}

function createIframe(className, url) {
    var newFrame = document.createElement('iframe');
    newFrame.className = className;
    newFrame.src = url;
    $(newFrame).attr('sandbox', 'allow-scripts allow-same-origin allow-forms allow-popups allow-pointer-lock allow-modals');
    $(newFrame).attr('allowfullscreen', 'allowfullscreen');
    $('.iFramesContainer').append(newFrame);
    $('.content-close-pip').show();
    $('.content-maximize-pip').show();
}

function parseYoutubeUrl(url, changeColors) {

    var videoId = url.replace('https://www.youtube.com/watch?v=', '');
    if (url !== videoId) {
        if (changeColors) {
            $('.feed-list').addClass('darkTheme', 2000, 'easeInOutQuad');
        }
        $('.header--bar').css('backgroundColor', '#1a1a1a');
        return 'https://www.youtube.com/embed/' + videoId + '?autoplay=true';
    }

    if (changeColors) {
        $('.feed-list').removeClass('darkTheme', '', 2000, 'easeInOutQuad');
    }

    return url;
}

function hexToRgb(hex) {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function (m, r, g, b) {
        return r + r + g + g + b + b;
    });

    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}
