$(function() {
   $('.js-settings-remove-feed').on('click', function() {

       let feedId = $(this).parent().parent().data('feed-id');
       let feedName = $(this).parent().parent().data('feed-name');
       let check = confirm('Are you sure you want to remove ' + feedName + '?');
       let button = $(this);

       if (check) {
           $.post( "/settings/feeds/remove/", { feedId: feedId })
               .done(function() {
                   $(button).parent().parent().addClass('removed');
               })
               .fail(function(data) {
                   alert(data);
               });
       }
   });

    $("#spectrum").spectrum({
        color: "#000",
        allowEmpty: false,
        preferredFormat: "hex"
    });

    $('.js-settings-add-feed').on('click', function() {
       let name = $(this).parent().find("[name='name']").val();
       let url = $(this).parent().find("[name='url']").val();
       let color = $(this).parent().find("[name='color']").val();
       let icon = $(this).parent().find("[name='icon']").val();
       let autoPin = 'off';

        if ($(this).parent().find("[name='autoPin']").prop('checked')) {
            autoPin = 'on';
        }

        $.post( "/settings/feeds/update/", {
            name: name,
            url: url,
            color: color,
            icon: icon,
            autoPin: autoPin
        })
            .done(function(data) {
                if (data.replace('Error', '') !== data) {
                    alert(data);
                } else {
                    alert("RSS Feed added! It may take up to 20 minutes before your feed is displayed on your Homepage");
                    location.reload();
                }
            })
            .fail(function(data) {
                alert(data);
            });
    });

    $('.js-update-auto-pin').on('click', function() {

        let feedId = $(this).parent().parent().data('feed-id');
        var autoPin = 'on';
        let that = $(this);

        if (!$(this).prop('checked')) {
            autoPin = 'off';
        }
        $(that).hide();

        $.post( "/settings/feeds/update/", {
            id: feedId,
            autoPin: autoPin
        })
            .done(function() {
                $(that).show()
            })
            .fail(function(data) {
                alert(data);
                $(that).show()
            });
    });
});
