$(function() {
   $('.registerBox input[type="submit"]').on('click', function(e) {
      if (!$('.registerBox input[type="checkbox"]').is(':checked')) {
          e.preventDefault();
          alert('You must agree if you want to use our services');
      }
   });
});