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

        $.post( "/settings/feeds/add/", {
            name: name,
            url: url,
            color: color
        })
            .done(function(data) {
                if (data.replace('Error', '') !== data) {
                    alert(data);
                } else {
                    alert("RSS Feed added! It may take up to 20 minutes before your feed is displayed on your Homepage");
                }
            })
            .fail(function(data) {
                alert(data);
            });
    });
});
