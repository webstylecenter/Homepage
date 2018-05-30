$(function () {
    $('.js-settings-remove-feed').on('click', function () {

        let feedId = $(this).parent().parent().data('feed-id');
        let feedName = $(this).parent().parent().data('feed-name');
        let check = confirm('Are you sure you want to remove ' + feedName + '?');
        let button = $(this);

        if (check) {
            $.post("/settings/feeds/remove/", {feedId: feedId})
                .done(function () {
                    $(button).parent().parent().addClass('removed');
                })
                .fail(function (data) {
                    showDialog('Error', 'Cannot remove feed. Please try again later.<br /><br />' + data.toString().substr(0, 200))
                });
        }
    });

    $("#spectrum").spectrum({
        color: $("#spectrum").val(),
        allowEmpty: false,
        preferredFormat: "hex"
    });

    $('.js-settings-add-feed').on('click', function () {
        let url = $(this).parent().find("[name='url']").val();
        let color = $(this).parent().find("[name='color']").val();
        let icon = $(this).parent().find("[name='icon']").val();
        let website = $(this).parent().find("[name='website']").val();

        let autoPin = $(this).parent().find("[name='autoPin']").prop('checked');

        if (url.length === 0 && website.length === 0) {
            showDialog('Invalid input', 'Please enter a website url or RSS feed-url');
            return;
        }

        $.post("/settings/feeds/add/", {
            url: url,
            website: website,
            color: color,
            icon: icon,
            autoPin: !!autoPin
        })
            .done(function (data) {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    showDialog('Cannot add Feed', 'An error occured while adding the feed:<br /><br />' + data.message.substr(0, 300));
                }
            })
            .fail(function (data) {
                showDialog('Cannot add Feed', 'An error occured while adding the feed:<br /><br />' + data.toString().substr(0, 300));
            });
    });

    $('.js-update-auto-pin').on('click', function () {

        let feedId = $(this).parent().parent().data('feed-id');
        var autoPin = 'on';
        let that = $(this);

        if (!$(this).prop('checked')) {
            autoPin = 'off';
        }
        $(that).hide();

        $.post("/settings/feeds/update/", {
            id: feedId,
            autoPin: autoPin
        })
            .done(function () {
                $(that).show()
            })
            .fail(function (data) {
                showDialog('Failed to update setting', 'Your auto pin setting cannot be changed at the moment because of a server error. Please try again later.<br /><br /><small>' + data.toString().substr(0, 200) + '</small>')
                $(that).show()
            });
    });
});
