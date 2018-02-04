<?php


namespace App\Admin\Controls\Image;

/**
 * Interface IImageControlFactory
 * @package App\Admin\Controls\Image
 * @author Josef Banya
 */
interface IImageControlFactory
{
    /**
     * @return ImageControl
     */
    public function create();
}