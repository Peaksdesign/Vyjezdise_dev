<?php


namespace App\Admin\Controls\Destination\Form;

/**
 * Interface IFormControlFactory
 * @package App\Admin\Controls\Destination\Form
 * @author Josef Banya
 */
interface IFormControlFactory
{
    /**
     * @return FormControl
     */
    public function create();
}