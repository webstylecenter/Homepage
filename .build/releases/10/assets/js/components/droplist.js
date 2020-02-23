var dropDomain = 'https://pvd.onl/';

$('.js-toggle').on('click', function (e) {
    e.preventDefault();

    var targetElement = $($(this).data('toggle-element'));

    targetElement.load(targetElement.data('target'), function () {
        showDropList();
        activateDropListEvents();
    });
});

$('.js-more-droplist').on('click', function () {
    window.location.href = '/droplist/all';
});

$('.content-overlay, .feed-list').on('click', function () {
    hideDropList();
    $('.profileMenu').slideUp();
});

$(function () {
    activateDropListEvents();
});

function showDropList() {
    $('.js-screenshot-list').slideDown();
    $('.content-overlay').fadeIn();
}

function hideDropList() {
    $('.js-screenshot-list').slideUp();
    $('.content-overlay').fadeOut();
}

function activateDropListEvents() {
    $('.dropOpen').on('click', function () {
        var image = $(this).data('image');
        var tab = window.open(dropDomain + image, '_blank');
        tab.focus();
        hideDropList();
    });

    $('.dropCopy').on('click', function () {
        $(this).css('color', 'red');
    });

    $('.dropHide').on('click', function () {
        var image = $(this).data('image');
        dropAction(this, image, 'hide');
    });

    $('.dropRemove').on('click', function () {
        var image = $(this).data('image');
        dropAction(this, image, 'delete');
    });
}

function dropAction(el, file, action) {
    $.ajax({
        method: "GET",
        url: dropDomain + "pages/" + action + ".php",
        data: {file: file}
    })
        .done(function (data) {
            $(el).parents(':eq(2)').html(data).css('background', 'none')
                .css('background-color', 'rgba(107, 193, 103, 0.7)')
                .css('text-align', 'center')
                .css('height', 'auto')
                .css('padding', '20px 0');
        });
}
