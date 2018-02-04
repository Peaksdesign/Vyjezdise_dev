<?php


namespace App\Admin\Controls\Album;

/**
 * Interface IAlbumControlFactory
 * @package App\Admin\Controls\Album
 * @author Josef Banya
 */
interface IAlbumControlFactory
{
    /**
     * @return AlbumControl
     */
    public function create();
}