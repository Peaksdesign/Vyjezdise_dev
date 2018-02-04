<?php


namespace App\Admin\Controls\Image\Grid;

/**
 * Interface IGridControlFactory
 * @package App\Admin\Controls\Image\Grid
 * @author Josef Banya
 */
interface IGridControlFactory
{
    /**
     * @return GridControl
     */
    public function create();
}