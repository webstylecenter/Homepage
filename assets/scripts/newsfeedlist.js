/**
 * Created by petervandam on 22/03/2017.
 */
$(function() {

    // Endless scroll
    $('.scroll').jscroll({
        padding: 150,
        nextSelector: 'a.jscroll-next:last',
        contentSelector: '.listItem',
        callback: function() {
            $('#mobile .Dumpert').each(function() {
                var title = $(this).find('.listTitle').text();
                var url = $(this).data('url');
                $(this).find('.listTitle').html('<a class="listTitle" href="' + url + '" target="_blank">' + title + '</a>');
            });
        }
    });

    // Make listItems clickable
    $(document).on('click', '.listItem', function() {
        $(this).addClass('animated pulse');
        $('.listItem').removeClass('selected');
        $(this).addClass('selected');
        $(this).addClass('used');

        var url = $(this).data('url');
        if (url === '') { url = 'nourl.php'; }

        $('iframe').attr('src', url);
        $('.pageLinkToUrl').text(url).attr('href', url);
    })
        .on ('click', '.title a', function() {
            $('iframe').attr('src', '/welcome/');
            $('.pageLinkToUrl').text('');
            $.requestNewFeedItems();
        })
        .on('click', '.pin', function(e) {
            e.stopImmediatePropagation();
            var pin = $(this);
            $.ajax("/pin/" +$(this).data('pin-id'))
                .done(function(response) {
                    if (response == '1') {
                        $(pin).parent().addClass('animated shake');
                        $(pin).parent().toggleClass('pinned');
                    }
                });
        })
        .on('click', '.createButton', function() {
            $('#createItem').modal({fadeDuration:100});
        })
        .on('click', '.submitFeedItem', function() {
            $.post('/add-item/', $('#createItem').serialize())
                .done(function(data) {
                    if (data === 'Done') {
                        $.modal.close();
                        $.requestNewFeedItems();
                    }
                    else {
                        alert(data);
                    }
                })
                .fail(function(data) {
                    alert(data);
                });
        });
});

$.requestNewFeedItems = function requestNewFeedItems() {
    $.getJSON('/refresh/' + encodeURI($('body').data('refresh-date')))
        .done(function(data) {
            var html = data.html.replace('<div class="list scroll">', '<div class="Newlist">');
            $('.list').prepend(html);
            $('body').data('refresh-date', data.refreshDate);
            clearCreateForm();
        });
};

