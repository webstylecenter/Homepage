$(function () {
    $('.checklist--form input[type="button"]').on('click', function () {
        if ($('.checklist--form input[type="text"]').val()) {
            postToChecklist({item: $('.checklist--form input[type="text"]').val()});
        }
    });

    $('.checklist--form input[type="text"]').keypress(function (e) {
        if (e.which === 13) {
            $('.checklist--form input[type="button"]').click();
        }
    });

    $('.js-checklist-item').on('click', function () {
        checkItem(this);
    });
});

function checkItem(el) {
    postToChecklist({
        id: $(el).data('database-id'),
        checked: $(el).is(':checked')
    });
}

function postToChecklist(data) {
    $.post("/checklist/add/", data).then(function (data) {
        $('.checklist--list').html(data);
        $('.checklist--form input[type="text"]').val('');

        $('.js-checklist-item').on('click', function () {
            checkItem(this);
        });
    }).catch(function () {
        showDialog('Error', 'Updating checklist has failed! Please try again in a moment.');
        return false;
    });
}
