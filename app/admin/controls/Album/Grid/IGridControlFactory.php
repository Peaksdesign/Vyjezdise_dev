<?php


namespace App\Admin\Controls\Album\Grid;

/**
 * Interface IGridControlFactory
 * @package App\Admin\Controls\Album\Grid
 * @author Josef Banya
 */
interface IGridControlFactory
{
    /**
     * @return GridControl
     */
    public function create();
}