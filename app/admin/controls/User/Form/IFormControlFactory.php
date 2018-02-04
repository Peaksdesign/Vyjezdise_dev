<?php


namespace App\Admin\Controls\User\Form;


interface IFormControlFactory
{
    /**
     * @return FormControl
     */
    public function create();
}