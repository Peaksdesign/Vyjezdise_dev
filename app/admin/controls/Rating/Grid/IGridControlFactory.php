<?php


namespace App\Admin\Controls\Rating\Grid;


/**
 * Interface IGridControlFactory
 * @package App\Admin\Controls\Rating\Grid
 * @author Josef Banya
 */
interface IGridControlFactory
{
	/**
	 * @return GridControl
	 */
	public function create();
}