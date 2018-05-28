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
                    alert(data);
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

        let autoPin = $(this).parent().find("[name='autoPin']").prop('checked');

        $.post("/settings/feeds/add/", {
            url: url,
            color: color,
            icon: icon,
            autoPin: !!autoPin
        })
            .done(function (data) {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert(data);
                }
            })
            .fail(function (data) {
                alert(data);
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
                alert(data);
                $(that).show()
            });
    });
});
