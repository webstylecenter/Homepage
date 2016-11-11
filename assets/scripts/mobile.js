/**
 * Created by petervandam on 07/11/2016.
 */
$( document ).ready(function() {
    $('.listItem').click(function() {
        $('.list').hide();
        $('.title').hide();
        $('.contentContainer').show();

        $('.backButton').css('display', 'inline-block');
        $('.pageLinkToUrl').show();
    });

    $('.backButton').click(function() {
        $('.list').show();
        $('.title').show();
        $('.contentContainer').hide();

        $('.backButton').hide();
        $('.pageLinkToUrl').hide();
    });

    $('.mobilePin').click(function() {

    });


    $('.listItem').hammer().bind("press", function() {
        var pin = $(this).find('.pin');
        $.ajax("/pin/" +$(pin).data('pin-id'))
            .done(function(response) {
                if (response == '1') {
                    $(pin).parent().toggleClass('pinned');
                    console.log("pinned");
                }
            });
    });

});
