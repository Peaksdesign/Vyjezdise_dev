<?php


namespace App\Admin\Controls\Album\Form;

/**
 * Interface IFormControlFactory
 * @package App\Admin\Controls\Album\Form
 * @author Josef Banya
 */
interface IFormControlFactory
{
    /**
     * @return FormControl
     */
    public function create();
}