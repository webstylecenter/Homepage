<?php

namespace Service;

use Entity\Note;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOException;

/**
 * Class NoteService
 * @package Service
 */
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
     * @param null $id
     *
     * @return Note
     */
    public function loadNote($id = null)
    {
        $id = ($id === null ? 1 : $id);
        $noteInfo = $this->database->fetchAll(
            'SELECT * FROM notes WHERE id = ?',
            [$id]
        );

        $note = array_map(function($noteItem) {
            return $this->toEntity($noteItem);
        }, $noteInfo);

        if (isset($note[0])) {
            return $note[0];
        }

        return new Note();
    }

    /**
     * @param null $id
     * @param $note
     * @param null $position
     *
     * @return bool
     * @throws \Exception
     */
    public function saveNote($id = null, $note, $position = null)
    {
        try {
            if ($id == null) {
                $this->insertNoteToDatabase($id, $note, $position);
            } else {
                $this->updateNoteInDatabase($id, $note, $position);
            }

            return true;
        } catch (PDOException $e) {
            throw new \Exception($e);
        }
    }

    /**
     * @param int $id
     * @param $note
     * @param int $position
     */
    public function insertNoteToDatabase($id, $note, $position)
    {
        $this->database->insert('notes', [
            'id'=> null,
            'note' => $note,
            'position' => null,
        ]);
    }

    /**
     * @param int $id
     * @param $note
     * @param int $position
     */
    public function updateNoteInDatabase($id, $note, $position)
    {
        $this->database->update('notes', [
            'note' => $note,
            'position' => $position
        ], [
            'id' => $id
        ]);
    }

    /**
     * @param array $data
     *
     * @return Note
     */
    protected function toEntity(array $data)
    {
        $note = new Note(
            $data['id'],
            $data['note'],
            $data['position']
        );

        return $note;
    }
}
