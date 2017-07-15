$(function() {
    // Endless scroll
    $('.scroll').jscroll({
        padding: 150,
        nextSelector: 'a.jscroll-next:last',
        contentSelector: '.feed-list-item',
        callback: function() {}
    });

    $(document).on('click', '.js-reload-page', function (event) {
        event.preventDefault();
        requestNewFeedItems();
    });

    $(document).on('click', '.feed-list-item', function() {
        $(this).addClass('animated pulse feed-list-item--state-selected');
        $('.feed-list-item').removeClass('feed-list-item--state-selected');

        var url = $(this).data('url') !== '' ? $(this).data('url') : '/nourl/';

        $('iframe').attr('src', url);
        $('.pageLinkToUrl').text(url).attr('href', url);
    })
        .on ('click', '.title a', function() {
            $('iframe').attr('src', '/welcome/');
            $('.pageLinkToUrl').text('');
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
        .on('click', '.js-form-feed button', function(e) {
            $.post('/feed/add-item/', $('.js-form-feed').serialize())
                .done(function(data) {
                    if (data === 'Done') {
                        $.modal.close();
                        requestNewFeedItems();
                    }
                    else {
                        console.log('done');
                        console.log(data);
                        alert(data);
                    }
                })
                .fail(function(data) {
                    console.log('fail');
                    console.log(data);
                    alert(data);
                });
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

