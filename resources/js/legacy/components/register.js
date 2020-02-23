$(function () {
    $('.registerBox input[type="submit"]').on('click', function (e) {
        if (!$('.registerBox input[type="checkbox"]').is(':checked')) {
            e.preventDefault();
            alert('Registration can\'t continue, you must agree if you want to use Feednews.');
        }
    });
});
