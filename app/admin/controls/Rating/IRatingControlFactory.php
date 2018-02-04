<?php


namespace App\Admin\Controls\Rating;

/**
 * Interface IRatingControlFactory
 * @package App\Admin\Controls\Rating
 * @author Josef Banya
 */
interface IRatingControlFactory
{
	/**
	 * @return RatingControl
	 */
	public function create();
}