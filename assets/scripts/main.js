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

    $('.scroll').jscroll({
        padding: 150,
        nextSelector: 'a.jscroll-next:last',
        contentSelector: '.listItem',
        callback: function() {
            addListEventHandlers('list');
            if (typeof addMobileListeners == 'function') {
                /** global: addMobileListeners */
                addMobileListeners();
            }
        }
    });

    $('.parseUrlButton').click(function() {
        getUrlMetaData();
    });

    $('#inputUrl').on('blur', function() {
        getUrlMetaData();
    })

   addListEventHandlers('list');

    $('.searchBox').on('keyup', function() {
       searchFeeds($(this).val());
       addListEventHandlers('searchResults');
    });

    $('.searchBox').on('click', function() {
        if ($(this).val() !== '') {
            $('.searchResults').slideDown();
        }
    });

    $('.searchBox').on('blur', function() {
        $('.searchResults').slideUp();
    })



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
            var html = data.html.replace('<div class="list scroll">', '<div class="Newlist">');
            $('.list').prepend(html);
            addListEventHandlers('Newlist');
            setRefreshDate(data.refreshDate);
            clearCreateForm();
    });
}

function clearCreateForm() {
    $('#createItem').find("input[type=text], textarea").val("");
}

function getUrlMetaData() {
    var Url = $('#inputUrl').val();

    if (Url.length > 0) {
        $.ajax({
            method: "POST",
            url: "/meta/",
            data: { url: Url}
        })
            .done(function( data ) {
                var json = $.parseJSON(data);
                $('#addTitle').val(json.title);
                $('#addDescription').val(json.description);
            });
    }
}

function searchFeeds(searchQuery) {
    $('.searchResults').html("");
    $('.searchResults').slideDown();
    $.ajax('/search/' + searchQuery)
        .done(function(data) {
            var html = data.replace('<div class="list scroll">', '<div class="NewSearchlist">');
            html = html.replace('<a href="/page/2" class="listItem jscroll-next">Next page</a>', '');
            $('.searchResults').prepend(html);
            addListEventHandlers('NewSearchlist');
        });
}
