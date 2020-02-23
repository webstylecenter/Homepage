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

    $(".spectrum").spectrum({
        color: $(this).val(),
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

    $('.js-update-feed-color').on('change', function() {
        let newColor = $(this).val();
        let userFeedId = $(this).parent().parent().parent().data('feed-id');

        $.post("/settings/feeds/update/", {
            id: userFeedId,
            color: newColor
        })
            .done(function () {
                // Do nothing
            })
            .fail(function (data) {
                showDialog('Failed to update color', 'Your color setting cannot be changed at the moment because of a server error. Please try again later.<br /><br /><small>' + data.toString().substr(0, 200) + '</small>');
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

    var IconToBeReplaced;
    $('.js-open-icon-selector').on('click', function() {
        $('.iconSelector input').val($(this).parent().parent().data('feed-id'));
        $('.iconSelector').modal({fadeDuration: 100});
        IconToBeReplaced = $(this);
    });

    $('.iconSelector .fa, .iconSelector button').on('click', function() {
        let name = $(this).attr('class').replace('fa fa-', '');
        let id = $(this).parent().parent().find('input').val();

        if (name === '') {
            name = 'plus emptyIcon';
        }

        if (parseInt(id) > 0) {
            $.post("/settings/feeds/update/", {
                id: id,
                icon: name
            })
                .done(function () {
                    $('.iconSelector input').val('');
                    $('.iconSelector').hide();
                    $('.jquery-modal').hide();

                    $(IconToBeReplaced).attr('class', 'fa fa-' + name);
                })
                .fail(function (data) {
                    showDialog('Failed to update setting', 'Your auto pin setting cannot be changed at the moment because of a server error. Please try again later.<br /><br /><small>' + data.toString().substr(0, 200) + '</small>');
                });
        }
    });

    $('.js-follow-feed').on('click', function() {

        let that = this;

        $.post("/settings/feeds/follow/", {
            feed_id: $(this).data('feed-id'),
        })
          .done(function (data) {
              if (data.status === 'success') {
                  $(that).html('Following').css('background-color', 'whitesmoke').css('color' , 'gray');
                  $('.refreshNotice').show();
              } else {
                  showDialog('Cannot add Feed', 'An error occured while adding the feed:<br /><br />' + data.message.substr(0, 300));
              }
          })
          .fail(function (data) {
              showDialog('Cannot add Feed', 'An error occured while adding the feed:<br /><br />' + data.toString().substr(0, 300));
          });
    })
});
