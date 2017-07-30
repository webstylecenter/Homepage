<?php

$app->post('/note/save/', function() use($app) {
    /** @var \Service\NoteService $noteService */
    $noteService = $app['noteService'];
    $noteService->saveNote(
    isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : null,
    isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : null,
    isset($_POST['note']) && !empty($_POST['note']) ? $_POST['note'] : '',
    isset($_POST['position']) && !empty($_POST['position']) ? $_POST['position'] : null
    );

    $id = (strlen($_POST['id']) > 0 ? $_POST['id'] : $noteService->getLastNoteId());

    header('Content-Type: application/json');
    return json_encode(['id' => $id]);
});

$app->post('/note/remove/', function() use($app) {
    /** @var \Service\NoteService $noteService */
    $noteService = $app['noteService'];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        if ($noteService->removeNote($_POST['id'])) {
            return 'Done';
        } else {
            return 'Unknown error';
        }
    }

   return 'Error: No ID set';
});
