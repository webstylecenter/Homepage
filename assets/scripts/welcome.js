$(function() {
    $(document).on('click', '.widget-custom-item', function() {
        var url = $(this).data('url');
        if (url === '') { url = '/nourl/'; }

        /** global: parent */
        parent.$('iframe').attr('src', url);
        parent.$('.header--bar-navigation a').text(url).attr('href', url);
        parent.$('.js-copy-to-clipboard').attr('data-clipboard-text', url).addClass('show-if-mobile');
    });

    $('.weather--content').load('/weather/detail/');
});
