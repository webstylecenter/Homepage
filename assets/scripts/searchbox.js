/**
 * Created by petervandam on 22/03/2017.
 */
$(function() {
    $('.searchBox').on('keyup', function() {
        searchFeeds($(this).val());
    }).on('click', function() {
        if ($(this).val() !== '') {
            $('.searchResults').slideDown();
        }
    }).on('blur', function() {
        $('.searchResults').slideUp();
    }).on('keydown', function(e) {
        if (e.which == 13) {
            searchAutoRun($(this));
        }
    });
});

function searchFeeds(searchQuery) {
    $('.searchResults').html("").slideDown();

    if (searchQuery == '' || searchQuery.substring(0, 4) == 'http') {
        return;
    }

    $.ajax('/search/' + searchQuery)
        .done(function(data) {
            var html = data.replace('<div class="list scroll">', '<div class="NewSearchlist">');
            html = html.replace('<a href="/page/2" class="listItem jscroll-next">Next page</a>', '');
            $('.searchResults').prepend(html);
        });
}


function addToChecklistFromSearch(el) {
    var value = $(el).find('b').html();
    addToChecklist(value);
    $(el).html('<b>' + value + ' added to checklist!');
    $('.searchBox').val('');
}

function searchAutoRun(el) {
    let value = $(el).val();
    $('.searchResults').hide();

    if ((value.substring(0, 4) !== 'http')) {
        addToChecklist(value);
    } else {
        $(el).val("Fetching meta data...");
        $('.searchResults').hide();

        $.ajax({
            method: "POST",
            url: "/meta/",
            data: { url: value}
        })
            .done(function( data ) {

                $(el).val("Adding data to feed...");
                $('.searchResults').hide();

                let json = $.parseJSON(data);

                if (json.title.length == 0) {
                    $('#createItem').modal({fadeDuration:100});
                    $('#inputUrl').val(value);
                    $('#addDescription').val(json.description);
                } else {
                    $.ajax({
                        method: "POST",
                        url: "/add-item/",
                        data: {
                            url: value,
                            title: json.title,
                            description: json.description
                        }
                    }).done(function(data) {
                        if (data === 'Done') {
                            $(el).val("");
                            $.requestNewFeedItems();
                            $.modal.close();
                        } else {
                            $(el).val(value);
                            alert(data);
                        }

                        $('.searchResults').hide();
                    });
                }
            });
    }
}