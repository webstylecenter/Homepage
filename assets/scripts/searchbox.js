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
        $('.searchResults').each(function() {
           if (!$(this).hasClass('doNotHide')) {
               $('.searchResults').slideUp();
           }
        });

    }).on('keydown', function(e) {
        if (e.which == 13) {
            searchAutoRun($(this));
        }
    });

    $('.searchResults').on('click', '.addToChecklist', function() {
        addToChecklistFromSearch(this);
    });
});

function searchFeeds(searchQuery) {
    $('.searchResults').html("").slideDown();

    if (searchQuery == '' || searchQuery.substring(0, 4) == 'http') {
        return;
    }

    $.ajax('/feed/search/' + searchQuery + '/0')
        .done(function(data) {
            var html = data.replace('<div class="list scroll">', '<div class="NewSearchlist">');
            $('.searchResults').html(html);
        });
}

function addToChecklist(value) {
    postToChecklist({item:value});
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
                        url: "/feed/add-item/",
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
