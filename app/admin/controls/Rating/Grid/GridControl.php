<?php


namespace App\Admin\Controls\Rating\Grid;


use App\Admin\Controls\Base\BaseControl;
use App\Repository\Rating\RatingRepository;
use Ublaboo\DataGrid\DataGrid;

/**
 * Class GridControl
 * @package App\Admin\Controls\Rating\Grid
 * @author Josef Banya
 */
class GridControl extends BaseControl
{
	/** @var string  */
	public $templateFile = 'grid';

	/** @var  int */
	public $destinationId;

	/** @var  RatingRepository */
	public $ratingRepository;

	/**
	 * GridControl constructor.
	 *
	 * @param \App\Repository\Rating\RatingRepository $ratingRepository
	 */
	public function __construct( RatingRepository $ratingRepository ) {
		parent::__construct();
		$this->setRatingRepository($ratingRepository);
	}

	/**
	 *
	 */
	public function createComponentGrid() {
		$grid = new DataGrid();
		$grid->setDataSource($this->getRatingRepository()->getList()->where(['destination_id' => $this->getDestinationId()]));
		$grid->addColumnNumber('id', '#')->setSortable();
		$grid->addColumnText('author', 'Autor');
		$grid->addColumnText('description', 'Komentar');
		$grid->addColumnNumber('queue', 'Fronty')->setSortable();
		$grid->addColumnNumber('food', 'Občerstvení')->setSortable();
		$grid->addColumnNumber('parking', 'Parkování')->setSortable();
		$grid->addColumnNumber('final', 'Totální')->setSortable();
		$grid->addAction('delete', 'Smazat', 'delete!')
		     ->setClass('btn btn-xs btn-danger')
		     ->setIcon('trash');
		return $grid;
	}

	/**
	 * @param $id
	 */
	public function handleDelete($id)
	{
		try {
			$this->getRatingRepository()->delete($id);
			$this->getPresenter()->flashMessage('Hodnocení bylo smazáno', 'success');
		} catch (\Exception $e) {
			$this->getPresenter()->flashMessage('Hodnocení nemohlo být smazáno', 'error');
		}
		$this->getPresenter()->redirect('this');
	}

	/**
	 * @return int
	 */
	public function getDestinationId() {
		return $this->destinationId;
	}

	/**
	 * @param int $destinationId
	 *
	 * @return self Provides Fluent Interface
	 */
	public function setDestinationId( $destinationId ) {
		$this->destinationId = $destinationId;

		return $this;
	}

	/**
	 * @return \App\Repository\Rating\RatingRepository
	 */
	public function getRatingRepository() {
		return $this->ratingRepository;
	}

	/**
	 * @param \App\Repository\Rating\RatingRepository $ratingRepository
	 *
	 * @return self Provides Fluent Interface
	 */
	public function setRatingRepository( $ratingRepository ) {
		$this->ratingRepository = $ratingRepository;

		return $this;
	}

}