/**
 * Created by petervandam on 26/10/2016.
 */

$(function() {
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
            });
    });

    $('.weatherContent').load('/weather/detail/').on('click', function() {
        $('.note').slideToggle('slow');
        $('.feedCounter').slideToggle('slow');
    });

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
    });

   addListEventHandlers('list');

    $('.searchBox').on('keyup', function() {
       searchFeeds($(this).val());
       addListEventHandlers('searchResults');
    }).on('click', function() {
        if ($(this).val() !== '') {
            $('.searchResults').slideDown();
        }
    }).on('blur', function() {
        $('.searchResults').slideUp();
    });

    $('.note').on('blur', function() {
       saveNote($(this));
    });

    $('.specialTxt').each(function() {
        var p1 = 'peter';
        var p3 = 'vdam';
        var p2 = '.nl';
        var p4 = 'mail';
        var p5 = '@';
        var p6 = 'to';
        $(this).html('<a href="' + p4 + p6 + ':' + p1 + p5 + p1 + p3 + p2 + '">' + p1 + p5 + p1 + p3 + p2 + '</a>');
    });

    $('.checklistAdder input[type="button"]').on('click', function() {
        addToChecklist($('.checklistAdder input[type="text"]').val());
    });

    $('.checklistItem').on('click', function() {
       checkItem(this);
    });

});

function addListEventHandlers(container) {
    $('.' + container + ' .listItem').click(function() {

        $(this).addClass('animated pulse');
        $('.listItem').removeClass('selected');
        $(this).addClass('selected');
        $(this).addClass('used');

        var url = $(this).data('url');
        if (url === '') { url = 'nourl.php'; }

        $('iframe').attr('src', url);
        $('.pageLinkToUrl').text(url).attr('href', url);
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
                    $(pin).parent().addClass('animated shake');
                    $(pin).parent().toggleClass('pinned');
                }
            });
    });
}

function requestNewFeedItems() {
    $.getJSON('/refresh/' + encodeURI($('body').data('refresh-date')))
        .done(function(data) {
            var html = data.html.replace('<div class="list scroll">', '<div class="Newlist">');
            $('.list').prepend(html);
            addListEventHandlers('Newlist');
            $('body').data('refresh-date', data.refreshDate);
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
    $('.searchResults').html("").slideDown();
    $.ajax('/search/' + searchQuery)
        .done(function(data) {
            var html = data.replace('<div class="list scroll">', '<div class="NewSearchlist">');
            html = html.replace('<a href="/page/2" class="listItem jscroll-next">Next page</a>', '');
            $('.searchResults').prepend(html);
            addListEventHandlers('NewSearchlist');
        });
}

function saveNote(el) {
    var id = $(el).attr('data-id');
    var position = $(el).attr('data-position');
    var note = $(el).val();

    $.ajax({
        method: "POST",
        url: "/note/save/",
        data: {
            id: id,
            position: position,
            note: note
        },
        beforeSend: function() {
            $(el).css('color', '#303030');
        }
    })
        .done(function() {
            $(el).css('color', 'black');
        })
        .fail(function() {
            $(el).css('color', 'red');
        });
}

function openWelcomePage() {
    $('iframe').attr('src', '/welcome/');
}

function addToChecklistFromSearch(el) {
    var value = $(el).find('b').html();
    addToChecklist(value);
    $(el).html('<b>' + value + ' added to checklist!');
    $('.searchBox').val('');
}

function addToChecklist(value) {
    postToChecklist({item:value});
}

function checkItem(el) {
    var id = $(el).data('database-id');
    var newCheckedState = $(el).is(':checked');

    postToChecklist({
        id: id,
        checked: newCheckedState
    });

}

function postToChecklist(data) {
    $.post("/checklist/add/", data).then(function(data) {
        $('.checklists').html(data);
        $('.checklistAdder input[type="text"]').val('');

        $('.checklistItem').on('click', function() {
            checkItem(this);
        });
    }).catch(function() {
        alert('Updating checklist failed!');
        return false;
    });
}

/** global: WOW */
new WOW({
    scrollContainer: '.scroll',
    mobile: false
}).init();