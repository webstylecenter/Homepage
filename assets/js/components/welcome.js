$(function () {
    $(document).on('click', '.widget-custom-item', function () {
        var url = $(this).data('url');
        if (url === '') {
            url = '/nourl/';
        }

        /** global: parent */
        parent.$('.content-frame').attr('src', url);
        parent.$('.urlbar a').text(url).attr('href', url);
        parent.$('.js-copy-to-clipboard').attr('data-clipboard-text', url).addClass('show-if-mobile');
    });

    $('.weather--content').load('/weather/detail/');
});
