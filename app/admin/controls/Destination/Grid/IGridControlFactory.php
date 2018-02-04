<?php


namespace App\Admin\Controls\Destination\Grid;

/**
 * Interface IGridControlFactory
 * @package App\Admin\Controls\Destination\Grid
 * @author Josef Banya
 */
interface IGridControlFactory
{
    /**
     * @return GridControl
     */
    public function create();
}