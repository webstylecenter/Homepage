$(function() {
    $('.js-search-feed').on('keyup', function() {
        searchFeeds($(this).val());
    }).on('click', function() {
        if ($(this).val() !== '') {
            $('.js-search-list').slideDown();
        }
    }).on('blur', function() {
        $('.js-search-list:not(.doNotHide)').slideUp()
    }).on('keydown', function(e) {
        if (e.which === 13) {
            searchAutoRun($(this));
        }
    });

    $('.js-search-list').on('click', '.feed-list-item--state-button', function() {
        addToChecklistFromSearch(this);
    });
});

function searchFeeds(searchQuery) {
    $('.js-search-list').html('').slideDown();

    if (searchQuery == '' || searchQuery.substring(0, 4) === 'http') {
        return;
    }

    $.getJSON('/feed/search/0?query=' + encodeURIComponent(searchQuery), function (data) {
        if (data.status !== 'success') {
            return;
        }

        var source = document.getElementById('js-search-result').innerHTML;
        /** global: Handlebars */
        var template = Handlebars.compile(source);

        $('.js-search-list').html(template({
            results: data.data,
            query: searchQuery
        }));
    });
}

function addToChecklist(value) {
    postToChecklist({item:value});
}

function postToChecklist(data) {
    $.post("/checklist/add/", data).then(function(data) {
        $('.checklist--list').html(data);
        $('.checklist--form input[type="text"]').val('');

        $('.js-checklist-item').on('click', function() {
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
    $('.s-search-feed').val('');
}

function searchAutoRun(el) {
    let value = $(el).val();
    $('.js-search-list').hide();

    if ((value.substring(0, 4) !== 'http')) {
        addToChecklist(value);
    } else {
        $(el).val("Fetching meta data...");

        $.ajax({
            method: "POST",
            url: "/meta/",
            data: { url: value}
        })
            .done(function( data ) {
                $(el).val("Adding data to feed...");
                $('.js-search-list').hide();

                let json = $.parseJSON(data);
                if (json.title.length === 0) {
                    $('.js-form-feed modal').modal({fadeDuration:100});
                    $('.js-form-feed [name="title"]').val(value);
                    $('.js-form-feed [name="description"]').val(json.description);
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
                            requestNewFeedItems();
                            $.modal.close();
                        } else {
                            $(el).val(value);
                            alert(data);
                        }

                        $('.js-search-list').hide();
                    });
                }
            });
    }
}
