<?php

$app->post('/note/save/', function() use($app) {
    /** @var \Service\NoteService $noteService */
    $noteService = $app['noteService'];
    $noteService->saveNote(
    isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : null,
    isset($_POST['note']) && !empty($_POST['note']) ? $_POST['note'] : '',
    isset($_POST['position']) && !empty($_POST['position']) ? $_POST['position'] : null
    );

    return 'Done';
});
