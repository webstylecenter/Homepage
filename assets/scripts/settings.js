$(function() {
   $('.js-settings-remove-feed').on('click', function() {

       let feedId = $(this).parent().parent().data('feed-id');
       let feedName = $(this).parent().parent().data('feed-name');
       let check = confirm('Are you sure you want to remove ' + feedName + '?');
       let button = $(this);

       if (check) {
           $.post( "/settings/feeds/remove/", { feedId: feedId })
               .done(function() {
                   console.log("Removed");
                   $(button).parent().parent().addClass('removed');
               })
               .fail(function(data) {
                   alert(data);
               });
       }
   });
});
