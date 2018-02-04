<?php


namespace App\Admin\Controls\Image\Form;

/**
 * Interface IFormControlFactory
 * @package App\Admin\Controls\Image\Form
 * @author Josef Banya
 */
interface IFormControlFactory
{
    /**
     * @return FormControl
     */
    public function create();
}