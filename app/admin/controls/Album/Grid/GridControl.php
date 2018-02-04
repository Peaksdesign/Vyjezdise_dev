<?php


namespace App\Admin\Controls\Album\Grid;


use App\Admin\Controls\Base\BaseControl;
use App\Repository\Album\AlbumRepository;
use Ublaboo\DataGrid\DataGrid;

/**
 * Class GridControl
 * @package App\Admin\Controls\Album\Grid
 * @author Josef Banya
 */
class GridControl extends BaseControl
{
    /** @var string  */
    public $templateFile = 'grid';

    /** @var  AlbumRepository */
    public $albumRepository;

    /**
     * GridControl constructor.
     * @param AlbumRepository $albumRepository
     */
    public function __construct(AlbumRepository $albumRepository)
    {
        parent::__construct();
        $this->setAlbumRepository($albumRepository);
    }


    /**
     * Render template
     */
    public function render()
    {
        parent::render();
        $this->getTemplate()->render();
    }

    /**
     * @return \Ublaboo\DataGrid\DataGrid
     */
    public function createComponentGrid()
    {
        $grid = new DataGrid();
        $grid->setDataSource($this->getAlbumRepository()->getList());
        $grid->addColumnNumber('id', 'ID')->setSortable()->setFitContent();
        $grid->addColumnText('title', 'Název')->setSortable();
        $grid->addColumnDateTime('create_date', 'Datum vytvoření')->setSortable();

        $grid->addAction('detail', 'Detail', 'detail!')
            ->setClass('btn btn-xs btn-primary')
            ->setIcon('info');

        $grid->addAction('delete', 'Smazat')
            ->setClass('btn btn-xs btn-warning')
            ->setIcon('trash');

        return $grid;
    }

    /**
     * @param $id
     */
    public function handleDetail($id)
    {
        $this->getPresenter()->redirect('Album:detail', $id);
    }

    /**
     * @param $id
     */
    public function handleDelete($id)
    {
        try {
            $this->getAlbumRepository()->delete($id);
            $this->getPresenter()->flashMessage('Smazáno', 'success');
        } catch (\Exception $e) {
            $this->getPresenter()->flashMessage('Album se nepodařilo smazat', 'error');
        }

        $this->getPresenter()->redirect('this');
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


}