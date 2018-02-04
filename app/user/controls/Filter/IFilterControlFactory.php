<?php


namespace App\User\Controls\Filter;

/**
 * Interface IFilterControlFactory
 * @package App\User\Controls\Filter
 * @author Josef Banya
 */
interface IFilterControlFactory
{
    /**
     * @return FilterControl
     */
    public function create();
}