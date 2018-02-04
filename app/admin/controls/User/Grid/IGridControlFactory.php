<?php


namespace App\Admin\Controls\User\Grid;

/**
 * Interface IGridControlFactory
 * @package App\Admin\Controls\Grid
 * @author Josef Banya
 */
interface IGridControlFactory
{
    /**
     * @return GridControl
     */
    public function create();
}