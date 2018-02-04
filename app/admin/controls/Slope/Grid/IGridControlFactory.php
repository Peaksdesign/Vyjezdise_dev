<?php


namespace App\Admin\Controls\Slope\Grid;

/**
 * Interface IGridControlFactory
 * @package App\Admin\Controls\Slope\Grid
 * @author Josef Banya
 */
interface IGridControlFactory
{
    /**
     * @return GridControl
     */
    public function create();
}