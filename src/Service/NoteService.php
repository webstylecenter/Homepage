<?php

namespace Service;

use Entity\Note;
use Doctrine\DBAL\Connection;

class NoteService
{
    /**
     * @var Connection
     */
    protected $database;

    /**
     * @param Connection $database
     */
    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    /**
     * @param integer|null $id
     * @return Note
     */
    public function loadNote($id = null)
    {
        $id = ($id === null ? 1 : $id);
        $noteInfo = $this->database->fetchAll('SELECT * FROM notes WHERE id = ' . (int) $id);
        return isset($noteInfo[0]) ? $this->toEntity($noteInfo[0]) : new Note;
    }

    /**
     * @param null|integer $id
     * @param string $note
     * @param integer|null $position
     */
    public function saveNote($id, $note, $position = null)
    {
        $persist = $id === null ? 'insert' : 'update';
        $identifier = $id !== null ? ['id' => $id] : [];

        $this->database->$persist('notes', [
            'note' => $note,
            'position' => $position,
        ], $identifier);
    }

    /**
     * @param array $data
     * @return Note
     */
    protected function toEntity(array $data)
    {
        return new Note($data['id'], $data['note'], $data['position']);
    }
}
