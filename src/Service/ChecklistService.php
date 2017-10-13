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

        return isset($checklistItemInfo[0]) ? $this->toEntity($checklistItemInfo[0]) : new ChecklistItem;
    }

    /**
     * @param integer|null $id
     * @param $checklistItem
     * @param boolean $checked
     */
    public function saveChecklistItem($id, $checklistItem, $checked = false)
    {
        $persist = $id === null ? 'insert' : 'update';
        $identifier = $persist === 'update' ? ['id' => $id] : [];

        $data = ['checked' => $checked, 'lastUpdated' => date('Y-m-d H:i:s')];
        if ($persist === 'insert') {
            $data['item'] = $checklistItem;
        }

        $this->database->$persist('checklist', $data, $identifier);
    }

    /**
     * @param array $data
     *
     * @return ChecklistItem
     */
    protected function toEntity(array $data)
    {
        return new ChecklistItem($data['id'], $data['item'], $data['checked']);
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
            'SELECT * FROM checklist WHERE checked = ? ORDER BY lastUpdated DESC LIMIT 0, 25',
            [$checked], [\PDO::PARAM_BOOL]
        );

        $checklist = array_map(function($checklistItem) {
            return $this->toEntity($checklistItem);
        }, $checklistItems);

        return $checklist;
    }
}
