<?php


namespace App\Admin\Controls\Slope\Grid;


use App\Admin\Controls\Base\BaseControl;
use App\Repository\Slope\SlopeRepository;
use Ublaboo\DataGrid\DataGrid;

/**
 * Class GridControl
 * @package App\Admin\Controls\Slope\Grid
 * @author Josef Banya
 */
class GridControl extends BaseControl
{
    /** @var string  */
    public $templateFile = 'grid';

    /** @var null  */
    public $destinationId = null;

    /** @var  SlopeRepository */
    protected $slopeRepository;

    /**
     * GridControl constructor.
     * @param SlopeRepository $slopeRepository
     */
    public function __construct(SlopeRepository $slopeRepository)
    {
        parent::__construct();
        $this->setSlopeRepository($slopeRepository);
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
        if ($this->getDestinationId() !== NULL) {
            $ds = $this->getSlopeRepository()->getList()->where(['destination_id' => $this->getDestinationId()]);
        } else {
            $ds = $this->getSlopeRepository()->getList();
        }
        $grid->setDataSource($ds);
        $grid->addColumnNumber('id', 'ID');
        $grid->addColumnText('name', 'Jmeno');
        $grid->addColumnNumber('camber', 'Převýšení')->setSortable();
        $grid->addColumnNumber('lenght', 'Délka')->setSortable();

        $grid->addAction('edit', 'Editace', 'edit!')
            ->setClass('btn btn-xs btn-success')
            ->setIcon('pencil');


        $grid->addAction('delete', 'Smazat', 'delete!')
            ->setClass('btn btn-xs btn-warning')
            ->setIcon('trash');


        return $grid;
    }

    public function handleEdit($id)
    {
        $this->getPresenter()->redirect('Slope:edit', $id);
    }

    public function handleDelete($id)
    {

        try {
            $this->getSlopeRepository()->delete($id);
            $this->getPresenter()->flashMessage('Smazáno', 'success');
        } catch (\Exception $e) {
            $this->getPresenter()->flashMessage('Nepodařilo se smazat záznam', 'error');
        }
        $this->getPresenter()->redirect('Slope:default', $this->getDestinationId());
    }


    /**
     * @return SlopeRepository
     */
    public function getSlopeRepository()
    {
        return $this->slopeRepository;
    }

    /**
     * @param SlopeRepository $slopeRepository
     * @return self Provides Fluent Interface
     */
    public function setSlopeRepository($slopeRepository)
    {
        $this->slopeRepository = $slopeRepository;
        return $this;
    }

    /**
     * @return null
     */
    public function getDestinationId()
    {
        return $this->destinationId;
    }

    /**
     * @param null $destinationId
     * @return self Provides Fluent Interface
     */
    public function setDestinationId($destinationId)
    {
        $this->destinationId = $destinationId;
        return $this;
    }



}