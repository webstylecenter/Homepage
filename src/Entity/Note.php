<?php

namespace Entity;

/**
 * Class Note
 * @package Entity
 */
class Note
{
    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $note
     */
    protected $note;

    /**
     * @var int $position
     */
    protected $position;

    /**
     * @param $id
     * @param $name
     * @param $note
     * @param $position
     */
    public function __construct($id = null, $name = null, $note = null, $position = null) {
        $this->id = $id;
        $this->name = $name;
        $this->note = $note;
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getNote() {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note) {
        $this->note = $note;
    }

    /**
     * @return int
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position) {
        $this->position = $position;
    }
}
