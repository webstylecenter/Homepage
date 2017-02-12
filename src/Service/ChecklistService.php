<?php

namespace Service;

use Entity\ChecklistItem;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOException;

/**
 * Class ChecklistService
 * @package Service
 */
class ChecklistService
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
     * @return ChecklistItem
     */
    public function loadChecklistItem($id = null)
    {
        $id = ($id === null ? 1 : $id);
        $checklistItemInfo = $this->database->fetchAll(
            'SELECT * FROM checklist WHERE id = ?',
            [$id]
        );

        $checklistItem = array_map(function($checklistItem) {
            return $this->toEntity($checklistItem);
        }, $checklistItemInfo);

        if (isset($checklistItem[0])) {
            return $checklistItem[0];
        }

        return new ChecklistItem();
    }

    /**
     * @param null $id
     * @param $checklistItem
     * @param null $checked
     */
    public function saveChecklistItem($id = null, $checklistItem, $checked = null)
    {
        $persist = $id === null ? 'insert' : 'update';
        $identifier = $id !== null ? ['id' => $id] : [];

        $this->database->$persist('checklist', [
            'item' => $checklistItem,
            'checked' => $checked,
        ], $identifier);
    }

    /**
     * @param array $data
     *
     * @return ChecklistItem
     */
    protected function toEntity(array $data)
    {
        $checklistItem = new ChecklistItem(
            $data['id'],
            $data['item'],
            $data['checked']
        );

        return $checklistItem;
    }

    /**
     * @return array
     */
    public function getTodos()
    {
        return $this->getItems(false);
    }

    /**
     * @return array
     */
    public function getFinished()
    {
        return $this->getItems(true);
    }

    /**
     * @param $checked
     *
     * @return array
     */
    public function getItems($checked)
    {
        $checklistItems = $this->database->fetchAll(
            'SELECT * FROM checklist WHERE checked = ? LIMIT 0, 25',
            [$checked], [\PDO::PARAM_BOOL]
        );

        $checklist = array_map(function($checklistItem) {
            return $this->toEntity($checklistItem);
        }, $checklistItems);

        return $checklist;
    }
}
