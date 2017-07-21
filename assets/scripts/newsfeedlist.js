$(function() {
    // Endless scroll
    $('.feed-list').jscroll({
        padding: 150,
        nextSelector: 'a.jscroll-next:last',
        contentSelector: '.feed-list-item',
        callback: function() {}
    });

    if (window.history && window.history.pushState) {
        $(window).on('popstate', function() {
            $('.js-return').trigger('click');
        });
    }

    $('.header--bar').hammer().on("swiperight", function() {
        $('.js-return').trigger('click');
    });

    $(document).on('click', '.js-reload-page', function (event) {
        event.preventDefault();
        requestNewFeedItems();
    });

    $(document).on('click', '.js-open-url', function () {
        openPage($(this).data('url') !== '' ? $(this).data('url') : '/nourl/');
    });

    $(document).on('click', '.js-action-feed-list-click', function() {
        $(this).addClass('animated pulse feed-list-item--state-selected');
        $('.feed-list-item').removeClass('feed-list-item--state-selected');

        openPage($(this).data('url') !== '' ? $(this).data('url') : '/nourl/');
        window.history.pushState('forward', null, './#' + $.now());

    })
        .on('click', '.js-return', function (e) {
            e.preventDefault();

            $('.content iframe').prop('src', 'about:blank');
            $('.content').hide();
            $('.js-reload-page').removeClass('hide-if-mobile hide-if-tablet');
            $('.js-return').removeClass('show-if-mobile show-if-tablet');
            $('.js-copy-to-clipboard').removeClass('show-if-mobile');
            window.history.pushState('backward', null, '/#' + $.now());

        })
        .on ('click', '.js-reload-page', function() {
            $('iframe').attr('src', '/welcome/');
            $('.header--bar-navigation a').text('').attr('data-clipboard-text', '');
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
                        requestNewFeedItems();
                    }
                    else {
                        alert(data);
                    }
                })
                .fail(function(data) {
                    alert(data);
                });
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

    $('iframe').attr('src', url);
    $('.header--bar-navigation a').text(url).attr('href', url);
    $('.js-copy-to-clipboard').attr('data-clipboard-text', url).addClass('show-if-mobile');
}

