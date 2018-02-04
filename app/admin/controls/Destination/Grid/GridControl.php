<?php


namespace App\Admin\Controls\Destination\Grid;


use App\Admin\Controls\Base\BaseControl;
use App\Repository\Destination\DestinationRepository;
use App\Repository\Slope\SlopeRepository;
use Nette\Database\DriverException;
use Nette\Database\Table\ActiveRow;
use Ublaboo\DataGrid\DataGrid;

/**
 * Class GridControl
 * @package App\Admin\Controls\Destination\Grid
 * @author Josef Banya
 */
class GridControl extends BaseControl
{
    /** @var string  */
    public $templateFile = 'grid';

    /** @var  DestinationRepository */
    protected $destinationRepository;

    /** @var  SlopeRepository */
    protected $slopeRepository;

    /**
     * FormControl constructor.
     * @param DestinationRepository $destinationRepository
     * @param \App\Repository\Slope\SlopeRepository $slopeRepository
     */
    public function __construct(DestinationRepository $destinationRepository, SlopeRepository $slopeRepository)
    {
        parent::__construct();
        $this->setDestinationRepository($destinationRepository)
            ->setSlopeRepository($slopeRepository);
    }

    /**
     *  Render template
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
        $grid->setDataSource($this->getDestinationRepository()->getList());

        $grid->addColumnNumber('id', 'ID')
            ->setSortable()->setFilterText(['id']);

        $grid->addColumnText('title', 'Název')->setSortable();
        $grid->addColumnText('area', 'Poloha')->setFilterText();
        $grid->addColumnText('address', 'Adresa')->setFilterText();
        $grid->addColumnNumber('slopes', 'Počet sjezdovek')
            ->setRenderer(function ($item) {
                $slopes = $this->getSlopeRepository()->getTable();
                return $slopes->where(['destination_id' => $item->id])->count('id');
            });

        $grid->addColumnText('status', 'Status')
            ->setRenderer(function ($item) {
                return ($item->status === 'allow') ? 'Aktivní' : 'Deaktivní';
            });

        $grid->addAction('active', 'Aktivovat', 'active!')
            ->setClass('btn btn-xs btn-success')
            ->setIcon('check-square');

	    $grid->addAction('rating', 'Hodnocení', 'ratings!')
	         ->setClass('btn btn-xs btn-primary')
	         ->setIcon('favorite');


	    $grid->addAction('deactive', 'Deaktivovat', 'deactive!')
            ->setClass('btn btn-xs btn-warning')
            ->setIcon('times');

        $grid->addAction('slopes', 'Sjezdovky', 'slopes!')
            ->setClass('btn btn-xs btn-success');

        $grid->addAction('edit', 'Editovat', 'edit!')
            ->setClass('btn btn-xs btn-info')
            ->setIcon('edit');

        $grid->addAction('delete', 'Smazat', 'delete!')
            ->setClass('btn btn-xs btn-danger')
            ->setIcon('trash');
        return $grid;
    }

	public function handleRatings( $id ) {
		$this->getPresenter()->redirect('Rating:default', $id);
    }

    /**
     * @param $id
     */
    public function handleDeactive($id)
    {
        $destination = $this->getDestinationRepository()->getItem($id);
        if ($destination instanceof ActiveRow) {
            if ($destination->status === 'deny') {
                $this->getPresenter()->flashMessage('Nemůžete deaktivovat již deaktivovanou destinaci', 'error');
                $this->getPresenter()->redirect('this');
            } else if ($destination->status === 'allow') {
                $this->getDestinationRepository()->update(['status' => 'deny'], $id);

                $this->getPresenter()->flashMessage('Destinace byla deaktivována', 'success');
                $this->getPresenter()->redirect('this');
            }
        } else {
            $this->getPresenter()->flashMessage('Nemůžete deaktivovat žádnou destinaci', 'error');
            $this->getPresenter()->redirect('this');
        }
    }

    /**
     * @param $id
     */
    public function handleActive($id)
    {
        $destination = $this->getDestinationRepository()->getItem($id);
        if ($destination instanceof ActiveRow) {
            if ($destination->status === 'allow') {
                $this->getPresenter()->flashMessage('Nemůžete aktivovat již aktivovanou destinaci', 'error');
                $this->getPresenter()->redirect('this');
            } else if ($destination->status === 'deny') {
                $this->getDestinationRepository()->update(['status' => 'allow'], $id);

                $this->getPresenter()->flashMessage('Destinace byla aktivována', 'success');
                $this->getPresenter()->redirect('this');
            }
        } else {
            $this->getPresenter()->flashMessage('Nemůžete aktivovat žádnou destinaci', 'error');
            $this->getPresenter()->redirect('this');
        }
    }

    /**
     * @param $id
     */
    public function handleEdit($id)
    {
        $this->getPresenter()->redirect('Destination:edit',$id);
    }

    /**
     * @param $id
     */
    public function handleSlopes($id)
    {
        $this->getPresenter()->redirect('Slope:default',$id);
    }

    /**
     * @param $id
     */
    public function handleDelete($id)
    {
        try {
            $this->getDestinationRepository()->delete($id);
            $this->getPresenter()->flashMessage('Destinace byla smazána', 'success');
        } catch (\Exception $e) {
            $this->getPresenter()->flashMessage('Destinace nemohla být smazána', 'error');
        }
        $this->getPresenter()->redirect('this');
    }

    /**
     * @return DestinationRepository
     */
    public function getDestinationRepository()
    {
        return $this->destinationRepository;
    }

    /**
     * @param DestinationRepository $destinationRepository
     * @return self Provides Fluent Interface
     */
    public function setDestinationRepository($destinationRepository)
    {
        $this->destinationRepository = $destinationRepository;
        return $this;
    }

    /**
     * @return \App\Repository\Slope\SlopeRepository
     */
    public function getSlopeRepository()
    {
        return $this->slopeRepository;
    }

    /**
     * @param \App\Repository\Slope\SlopeRepository $slopeRepository
     * @return self Provides Fluent Interface
     */
    public function setSlopeRepository($slopeRepository)
    {
        $this->slopeRepository = $slopeRepository;
        return $this;
    }


}