<?php


namespace App\Admin\Controls\Slope\Form;

/**
 * Interface IFormControlFactory
 * @package App\Admin\Controls\Slope\Form
 * @author JOsef Banya
 */
interface IFormControlFactory
{
    /**
     * @return FormControl
     */
    public function create();
}