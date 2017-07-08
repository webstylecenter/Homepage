$(function() {
    $('.checklistAdder input[type="button"]').on('click', function() {
        addToChecklist($('.checklistAdder input[type="text"]').val());
    });

    $('.checklistAdder input[type="text"]').keypress(function(e) {
        if (e.which == 13) {
            $('.checklistAdder input[type="button"]').click();
        }
    });

    $('.checklistItem').on('click', function() {
        checkItem(this);
    });
});

function checkItem(el) {
    var id = $(el).data('database-id');
    var newCheckedState = $(el).is(':checked');

    postToChecklist({
        id: id,
        checked: newCheckedState
    });

}

function addToChecklist(value) {
    postToChecklist({item:value});
}

function postToChecklist(data) {
    $.post("/checklist/add/", data).then(function(data) {
        $('.checklists').html(data);
        $('.checklistAdder input[type="text"]').val('');

        $('.checklistItem').on('click', function() {
            checkItem(this);
        });
    }).catch(function() {
        alert('Updating checklist failed!');
        return false;
    });
}
