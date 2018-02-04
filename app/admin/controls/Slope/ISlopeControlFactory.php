<?php


namespace App\Admin\Controls\Slope;

/**
 * Interface ISlopeControlFactory
 * @package App\Admin\Controls\Slope
 * @author Josef Banya
 */
interface ISlopeControlFactory
{
    /**
     * @return SlopeControl
     */
    public function create();
}