<?php


namespace App\Admin\Presenters;

/**
 * Class AlbumPresenter
 * @package App\Admin\Presenters
 * @author Josef Banya
 */
class AlbumPresenter extends BaseAuthPresenter
{
    /**
     * @param $id
     */
    public function actionDetail($id)
    {
        /** @var \App\Admin\Controls\Image\ImageControl $imageControl */
        $imageControl = $this->getComponent('image');
        /** @var \App\Admin\Controls\Image\Grid\GridControl $gridControl */
        $gridControl = $imageControl->getComponent('grid');
        $gridControl->setAlbumId($id);
        /** @var \App\Admin\Controls\Image\Form\FormControl $imageForm */
        $imageForm = $imageControl->getComponent('form');
        $imageForm->setAlbumId($id);
    }
}