/**
 * Created by petervandam on 26/10/2016.
 */
$( document ).ready(function() {

    $('.listItem').click(function() {

        $('.listItem').removeClass('selected');
        $(this).addClass('selected');
        $(this).addClass('used');

        var url = $(this).data('url');
        if (url == '') { url = 'nourl.php'; }

        console.log('Going to: ' + url);
        $('iframe').attr('src', url);
    });



});
