<?php


namespace App\Admin\Controls\Destination;

/**
 * Interface IDestinationControlFactory
 * @package App\Admin\Controls\Destination
 * @author Josef Banya
 */
interface IDestinationControlFactory
{
    /**
     * @return DestinationControl
     */
    public function create();
}