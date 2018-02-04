<?php

namespace App\Repository\Base;

use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\Object;
use Nette\SmartObject;
use Tracy\Debugger;

/**
 * Class BaseRepository
 * @package App\Repository
 */
abstract class BaseRepository
{
    use SmartObject;

    /** @var  string | null */
    protected $tableName = NULL;

    /** @var Context */
    protected $db;

    /** @var array */
    public $onInsert = [];

    /** @var array */
    public $onUpdate = [];

    /** @var array */
    public $onDelete = [];

    /** @var array  */
    public $onTriggeredUpdate = [];

    const ACTION_INSERT = 1;
    const ACTION_UPDATE = 2;
    const ACTION_DELETE = 3;

    /**
     * BaseRepository constructor.
     * @param Context $db
     */
    public function __construct(Context $db)
    {
        $this->db = $db;
    }

    /**
     * @param string $columnName
     * @return bool
     */
    public function hasColumn($columnName)
    {
        $structure = $this->db->getStructure()->getColumns($this->getTableName());
        foreach ($structure as $column) {
            if ($column['name'] == $columnName) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $arg
     * @return Selection
     */
    public function getList($arg = [])
    {
        $result = $this->db->table($this->tableName);

        if ($arg) {
            $this->makeFilter($result, $arg);
        }

        return $result;
    }

    /**
     * @param $arg
     * @return bool|mixed|\Nette\Database\Table\IRow
     */
    public function getItem($arg)
    {
        if (is_array($arg)) {
            $result = $this->getList();
            $this->makeFilter($result, $arg);
            return $result->fetch();
        } else {
            return $this->getList()->get($arg);
        }
    }

    public function insert($data, $returnItem = false)
    {
        $item = $this->getList()->insert($data);
        if (is_int($item)) {
            $this->afterAction(self::ACTION_INSERT,(array)$data);
            $this->onInsert(['id' => $item], $this->getTableName());
        } else {
            $this->afterAction(self::ACTION_INSERT,(array)$data);
            $this->onInsert($item->toArray(), $this->getTableName());
        }

        if ($returnItem) {
            return $item;
        } else {
            if (isset($item->id)) {
                return $item->id;
            }
        }
    }

    public function update($data, $arg)
    {
        if (is_array($arg)) {
            $result = $this->getList();
            $this->makeFilter($result, $arg);
            $items = $result->fetchField('id');
            $result->update($data);
            if (is_array($items)) {
                foreach ($items as $id) {
                    $data['id'] = $id;
                    if (isset($id) && is_int($id)) {
                        $this->afterAction(self::ACTION_UPDATE,(array)$data);
                        $this->onUpdate((array)$data, $this->getTableName());
                    }
                }
            } else {
                $data['id'] = $items;
                if (isset($items) && is_int($items)) {
                    $this->afterAction(self::ACTION_UPDATE,(array)$data);
                    $this->onUpdate((array)$data, $this->getTableName());
                }
            }
        } else {
            $this->getList()->where('id', $arg)->update($data);
            $this->afterAction(self::ACTION_UPDATE,(array)$data);
            $data['id'] = $arg;
            $this->onUpdate((array)$data, $this->getTableName());
        }
    }

    public function delete($arg)
    {
        if (is_array($arg)) {
            $result = $this->getList();
            $this->makeFilter($result, $arg);
            $items = $result->fetchAll();
            $itemsArr = [];
            foreach ($items as $item) {
                $itemsArr[] = $item->toArray();
            }
            $result->delete();
            foreach ($itemsArr as $item) {
                if (isset($item['id'])) {
                    $this->afterAction(self::ACTION_DELETE,$item);
                    $this->onDelete($item['id'], $this->getTableName());
                }
            }
            return $items;
        } else {
            $item = $this->getList()->where('id', $arg);
            $data = $item->fetch()->toArray();
            $item->delete();
            $this->afterAction(self::ACTION_DELETE,$data);
            $this->onDelete($arg, $this->getTableName());
            return $item;
        }
    }

    /**
     * @param Selection $result
     * @param array $where
     */
    public function makeFilter(Selection $result, array $where)
    {
        foreach ($where as $key => $val) {
            if (is_numeric($key)) {
                $result->where($val);
            } else if (!$val) {
                $result->where($key);
            } else {
                $val = !is_array($val) || count($val) > 1 ? $val : $val[0];
                $result->where($key, $val);
            }
        }
    }

    /**
     * @param int $action
     * @param array|null $data
     */
    protected function afterAction($action, array $data = null)
    {
        //bezprostredni akce po akci insert, update, delete
    }

    /**
     * @return Selection
     */
    public function getTable()
    {
        return $this->db->table($this->getTableName());
    }

    /**
     * @return Context
     */
    public function getDatabase()
    {
        return $this->db;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }
}