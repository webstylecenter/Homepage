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
});
