$(function() {
    $('.addedLinkItem').on('click', function() {
        var url = $(this).data('url');
        if (url === '') { url = '/nourl/'; }

        /** global: parent */
        parent.$('iframe').attr('src', url);
        parent.$('.pageLinkToUrl').text(url).attr('href', url);
    });

    $('.weatherContent').load('/weather/detail/');

});
