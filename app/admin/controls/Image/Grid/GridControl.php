<?php


namespace App\Admin\Controls\Image\Grid;

use App\Admin\Controls\Base\BaseControl;
use App\Repository\Album\AlbumRepository;
use App\Repository\Image\ImageRepository;
use Nette\Utils\Html;
use Ublaboo\DataGrid\DataGrid;

/**
 * Class GridControl
 * @package App\Admin\Controls\Image\Grid
 * @author Josef Banya
 */
class GridControl extends BaseControl
{
    /** @var string  */
    public $templateFile = 'grid';

    /** @var null | int */
    public $albumId = null;

    /** @var  AlbumRepository */
    public $albumRepository;

    /** @var  ImageRepository */
    public $imageRepository;

    /**
     * GridControl constructor.
     * @param AlbumRepository $albumRepository
     * @param \App\Repository\Image\ImageRepository $imageRepository
     */
    public function __construct(AlbumRepository $albumRepository, ImageRepository $imageRepository)
    {
        parent::__construct();
        $this->setAlbumRepository($albumRepository)
            ->setImageRepository($imageRepository);
    }

    /**
     * @return \Ublaboo\DataGrid\DataGrid
     */
    public function createComponentGrid()
    {
        $grid = new DataGrid();
        $grid->setDataSource(
            $this->getAlbumRepository()
                ->getDatabase()
                ->table('album_images')
                ->where(['album_id' => $this->getAlbumId()])
        );
        $grid->addColumnNumber('id', 'ID')->setSortable();

        $grid->addColumnText('image_file', 'Obrázek')
            ->setRenderer(function ($item) {
                    $image = $this->getImageRepository()->getItem($item->image_id);
                    $url = '/upload/' .  $image->image_file;
                    return Html::el('img')->src($url)->width('130 px')->height('130 px');
            })->setFitContent();

        $grid->addColumnText('is_primary', 'Hlavní fotka')
            ->setRenderer(function ($item) {
               return ($item->is_primary === 0) ? 'NE' : 'ANO';
            });

        $grid->addAction('primaryActive', 'Nastavit jako hlavní obrázek')
            ->setClass('btn btn-xs btn-success')
            ->setIcon('picture-o');

        $grid->addAction('delete', 'Smazat obrázek')
            ->setClass('btn btn-xs btn-warning')
            ->setIcon('trash');

        return $grid;
    }

    public function handlePrimaryActive($id)
    {
        try {
            $db = $this->getImageRepository()->getDatabase();
            $db->table('album_images')->where(['album_id' => $this->getAlbumId()])
                ->update(['is_primary' => 0]);
            $db->table('album_images')->where(['id' => $id])->update(['is_primary' => 1]);
            $this->getPresenter()->flashMessage('Nastaveno', 'success');
        } catch (\Exception $e) {
            $this->getPresenter()->flashMessage('Nastavení se nepodařilo', 'error');
        }
    }

    /**
     * @param $id
     */
    public function handleDelete($id)
    {
        try {
            $db = $this->getImageRepository()->getDatabase();
            $albumImage = $db->table('album_images')->where(['id' => $id])->fetch();
            $image = $this->getImageRepository()->getItem($albumImage->image_id);
            $imagePath = __DIR__ . '/../../../../../www/upload' . $image->image_file;
            unset($imagePath);
            $db->table('album_images')->where(['id' => $id])->delete();
            $this->getPresenter()->flashMessage('Smazáno', 'success');
        } catch (\Exception $e) {
            $this->getPresenter()->flashMessage('Smazání se nepodařilo', 'error');
        }
    }


    /**
     * @return int|null
     */
    public function getAlbumId()
    {
        return $this->albumId;
    }

    /**
     * @param int|null $albumId
     * @return self Provides Fluent Interface
     */
    public function setAlbumId($albumId)
    {
        $this->albumId = $albumId;
        return $this;
    }

    /**
     * @return AlbumRepository
     */
    public function getAlbumRepository()
    {
        return $this->albumRepository;
    }

    /**
     * @param AlbumRepository $albumRepository
     * @return self Provides Fluent Interface
     */
    public function setAlbumRepository($albumRepository)
    {
        $this->albumRepository = $albumRepository;
        return $this;
    }

    /**
     * @return \App\Repository\Image\ImageRepository
     */
    public function getImageRepository()
    {
        return $this->imageRepository;
    }

    /**
     * @param \App\Repository\Image\ImageRepository $imageRepository
     * @return self Provides Fluent Interface
     */
    public function setImageRepository($imageRepository)
    {
        $this->imageRepository = $imageRepository;
        return $this;
    }


}