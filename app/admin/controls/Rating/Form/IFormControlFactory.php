<?php


namespace App\Admin\Controls\Rating\Form;

/**
 * Interface IFormControlFactory
 * @package App\Admin\Controls\Rating\Form
 * @author Josef Banya
 */
interface IFormControlFactory
{
	/**
	 * @return FormControl
	 */
	public function create();

}