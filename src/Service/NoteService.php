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
                echo 'CreateNote';
                $this->database->insert('notes', [
                    'id'=> null,
                    'note' => $note,
                    'position' => null,
                ]);
            } else {
                $this->database->update('notes', [
                    'note' => $note,
                    'position' => $position
                ], [
                    'id' => $id
                ]);
            }

            return true;
        } catch (PDOException $e) {
            throw new \Exception($e);
        }
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
