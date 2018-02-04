<?php


namespace App\User\Controls\Grid;


use App\User\Controls\Base\BaseControl;
use Nette\Database\Row;

/**
 * Class GridControl
 * @package App\User\Controls\Grid
 * @author Josef Banya
 */
class GridControl extends BaseControl
{
    /** @var string  */
    public $templateFile = 'grid';

    /** @var Row[] */
    public $items = [];

    /**
     * Render Template
     */
    public function render()
    {
        parent::render();
        $this->getTemplate()->items = $this->items;
        $this->getTemplate()->render();
    }

    /**
     * @return \Nette\Database\Row[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param \Nette\Database\Row[] $items
     * @return self Provides Fluent Interface
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

}