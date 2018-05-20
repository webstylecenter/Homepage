<?php

namespace App\Service;

use App\Entity\Note;
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
     * @return array
     */
    public function loadNotes()
    {
        $notesInfo = $this->database->fetchAll('SELECT * FROM notes ORDER BY `position` ASC');
        $notes = [];

        foreach ($notesInfo as $noteInfo) {
            $notes[] = $this->toEntity($noteInfo);
        }

        return $notes;
    }

    /**
     * @param null|integer $id
     * @param string $name
     * @param string $note
     * @param integer|null $position
     */
    public function saveNote($id, $name, $note, $position = null)
    {
        $persist = $id === null ? 'insert' : 'update';
        $identifier = $id !== null ? ['id' => $id] : [];

        $this->database->$persist('notes', [
            'name' => $name,
            'note' => $note,
            'position' => $position,
        ], $identifier);
    }

    /**
     * @return int
     */
    public function getLastNoteId()
    {
        $notesInfo = $this->database->fetchAll('SELECT id FROM notes ORDER BY id DESC LIMIT 1');
        return $notesInfo[0]['id'];
    }

    /**
     * @param $id
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function removeNote($id)
    {
        return $this->database->delete('notes', ['id'=>$id], [\PDO::PARAM_INT]);
    }

    /**
     * @param array $data
     * @return Note
     */
    protected function toEntity(array $data)
    {
        return new Note($data['id'], $data['name'], $data['note'], $data['position']);
    }
}
