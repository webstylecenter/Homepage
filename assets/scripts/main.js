/**
 * Created by petervandam on 26/10/2016.
 */

$(function() {
    $(document).on('click', '.js-reload-page', function (event) {
        event.preventDefault();
        $('iframe').attr('src', '/welcome/');
    });

    $('.js-button-parse-url').on('click', function() {
        getUrlMetaData();
    });

    $('.js-form-feed [name="url"]').on('blur', function() {
        getUrlMetaData();
    });

    $('.widget-note textarea').on('blur', function() {
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
});

function getUrlMetaData() {
    var Url = $('.js-form-feed [name="url"]').val();

    if (Url.length > 0) {
        $.ajax({
            method: "POST",
            url: "/meta/",
            data: { url: Url}
        })
            .done(function( data ) {
                var json = $.parseJSON(data);
                $('.js-form-feed [name="title"]').val(json.title);
                $('.js-form-feed [name="description"]').val(json.description);
            });
    }
}

function saveNote($el) {
    var id = $el.attr('data-id');
    var position = $el.attr('data-position');
    var note = $el.val();

    $.ajax({
        method: "POST",
        url: "/note/save/",
        data: {
            id: id,
            position: position,
            note: note
        },
        beforeSend: function() {
            $el.css('color', '#303030');
        }
    })
        .done(function() {
            $el.css('color', 'black');
        })
        .fail(function() {
            $el.css('color', 'red');
        });
}

/** global: WOW */
new WOW({
    scrollContainer: '.scroll',
    mobile: false
}).init();
