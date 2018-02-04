<?php


namespace App\User\Controls\Grid;

/**
 * Interface IGridControlFactory
 * @package App\User\Controls\Grid
 * @author Josef Banya
 */
interface IGridControlFactory
{
    /**
     * @return GridControl
     */
    public function create();
}