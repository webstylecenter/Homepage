var dropDomain = 'https://pvd.onl/';
var stopProp = true;

$('.showDropList').on('click', function() {
    $('.dropListPanel').load('/droplist/', function() {
        showDropList();
        activateDropListEvens();
    });
});

$('.dropListPanel').click(function(event) {
    if (stopProp) {
        event.stopPropagation();
    }
});

$(window).click(function() {
    hideDropList();
});

function showDropList() {
    $('.dropListPanel').slideDown();
    $('.dropListBackground').fadeIn();
}

function hideDropList() {
    $('.dropListPanel').slideUp();
    $('.dropListBackground').fadeOut();
}

function activateDropListEvens() {

    /** global: Clipboard */
    new Clipboard('.dropCopy');

    $('.dropOpen').on('click', function() {
        stopProp = false;
        var image = $(this).data('image');
        var tab = window.open(dropDomain + image, '_blank');
        tab.focus();
        stopProp = true;
        hideDropList();
    });

    $('.dropCopy').on('click', function() {
        $(this).css('color', 'red');
    });

    $('.dropHide').on('click', function() {
        var image = $(this).data('image');
        dropAction(this, image, 'hide');
    });

    $('.dropRemove').on('click', function() {
        var image = $(this).data('image');
        dropAction(this, image, 'delete');
    });
}

function dropAction(el, file, action) {
    $.ajax({
        method: "GET",
        url: dropDomain + "pages/" + action + ".php",
        data: { file:file }
    })
        .done(function( data ) {
            $(el).parents(':eq(2)').html(data).css('background', 'none')
                .css('background-color', 'rgba(107, 193, 103, 0.7)')
                .css('text-align', 'center')
                .css('height', 'auto')
                .css('padding', '20px 0');
        });
}
