$(function () {
    $('.js-search-feed').on('keyup', function () {
        searchFeeds($(this).val());
    }).on('click', function () {
        if ($(this).val() !== '') {
            $('.js-search-list').slideDown();
        }
    }).on('blur', function () {
        $('.js-search-list:not(.doNotHide)').slideUp()
    }).on('keydown', function (e) {
        if (e.which === 13) {
            searchAutoRun($(this));
        }
    });

    $('.js-search-list').on('click', '.feed-list-item--state-button', function () {
        addToChecklistFromSearch(this);
    });
});

var searchFeeds = function (searchQuery) {
    $('.js-search-list').html('').slideDown();

    if (searchQuery === '' || searchQuery.substring(0, 4) === 'http') {
        return;
    }

    $.getJSON('/feed/search/?query=' + encodeURIComponent(searchQuery), function (data) {
        if (data.status !== 'success') {
            return;
        }

        var source = $('#js-search-result').html();
        /** global: Handlebars */
        var template = Handlebars.compile(source);

        $('.js-search-list').html(template({
            results: data.data,
            query: searchQuery
        }));
    });
}

var addToChecklist = function (value) {
    postToChecklist({item: value});
}

var postToChecklist = function (data) {
    $.post("/checklist/add/", data).then(function (data) {
        $('.checklist--list').html(data);
        $('.checklist--form input[type="text"]').val('');

        $('.js-checklist-item').on('click', function () {
            checkItem(this);
        });
    }).catch(function () {
        alert('Updating checklist failed!');
        return false;
    });
}

var addToChecklistFromSearch = function (el) {
    var value = $(el).find('b').html();
    addToChecklist(value);
    $(el).html('<b>' + value + ' added to checklist!');
    $('.s-search-feed').val('');
}

var searchAutoRun = function (el) {
    let value = $(el).val();
    $('.js-search-list').hide();

    if ((value.substring(0, 4) !== 'http')) {
        addToChecklist(value);
        return;
    }

    $(el).val("Fetching meta data...");

    $.post('/meta/', {url: value}, function (result) {
        $(el).val("Adding data to feed...");
        $('.js-search-list').hide();
        if (!result.data.title) {
            $('.js-form-feed modal').modal({fadeDuration: 100});
            $('.js-form-feed [name="title"]').val(value);
            $('.js-form-feed [name="description"]').val(result.data.description);
            return;
        }

        $.post('/feed/add-item/', {
            url: value,
            title: result.data.title,
            description: result.data.description
        }, function (data) {
            if (data.status !== 'success') {
                $(el).val(value);
                alert('Failed to add item due to a server error.');
            } else {
                $(el).val('');
                requestNewFeedItems();
                $.modal.close();
            }
            $('.js-search-list').hide();
        }, 'json');
    }, 'json');
};
