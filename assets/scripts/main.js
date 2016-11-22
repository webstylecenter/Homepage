/**
 * Created by petervandam on 26/10/2016.
 */
$( document ).ready(function() {

    $('.createButton').click(function() {
        $('#createItem').modal({fadeDuration:100});
    });

    $('.submitFeedItem').click(function() {
        $.post('/add-item/', $('#createItem').serialize())
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
            })
    });

    $('.weatherContent').load('/weather/detail/');

   addListEventHandlers('list');

});

function addListEventHandlers(container) {
    $('.' + container + ' .listItem').click(function() {
        $('.listItem').removeClass('selected');
        $(this).addClass('selected');
        $(this).addClass('used');

        var url = $(this).data('url');
        if (url == '') { url = 'nourl.php'; }

        $('iframe').attr('src', url);
        $('.pageLinkToUrl').text(url);
        $('.pageLinkToUrl').attr('href', url);
    });

    $('.' + container + ' .title a').click(function() {
        $('iframe').attr('src', '/welcome/');
        $('.pageLinkToUrl').text('');
        requestNewFeedItems();
    });

    $('.' + container + ' .pin').click(function(e) {
        e.stopImmediatePropagation();
        var pin = $(this);
        $.ajax("/pin/" +$(this).data('pin-id'))
            .done(function(response) {
                if (response == '1') {
                    $(pin).parent().toggleClass('pinned');
                }
            });
    });
}

var refreshDate;
function setRefreshDate(refreshDateValue) {
    refreshDate = refreshDateValue;
}

function getRefreshDate() {
    return refreshDate;
}

function requestNewFeedItems() {
    $.getJSON('/refresh/' + getRefreshDate())
        .done(function(data) {
            var html = data.html.replace('<div class="list">', '<div class="Newlist">');
            $('.list').prepend(html);
            addListEventHandlers('Newlist');
            setRefreshDate(data.refreshDate);
            clearCreateForm();
    });
}

function clearCreateForm() {
    $('#createItem').find("input[type=text], textarea").val("");
}
