<?php

namespace Entity;

/**
 * Class Note
 * @package Entity
 */
class ChecklistItem
{
    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var string $note
     */
    protected $item;

    /**
     * @var bool $checked
     */
    protected $checked;

    /**
     * @param integer|null $id
     * @param string|null $item
     * @param boolean $checked
     */
    public function __construct($id = null, $item = null, $checked = false)
    {
        $this->id = $id;
        $this->item = $item;
        $this->checked = $checked;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param string $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }

    /**
     * @param $checked
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
    }

    /**
     * @return bool
     */
    public function isChecked()
    {
        return $this->checked;
    }
}
