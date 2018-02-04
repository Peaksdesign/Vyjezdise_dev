<?php


namespace App\Admin\Controls\Rating;

use App\Admin\Controls\Base\BaseControl;
use App\Admin\Controls\Rating\Form\IFormControlFactory;
use App\Admin\Controls\Rating\Grid\IGridControlFactory;

/**
 * Class RatingControl
 * @package App\Admin\Controls\Rating
 * @author Josef Banya
 */
class RatingControl  extends BaseControl
{
	public $templateFile = null;

	/** @var  Grid\IGridControlFactory */
	public $gridControl;

	/** @var  Form\IFormControlFactory */
	public $formControl;

	/**
	 * RatingControl constructor.
	 *
	 * @param IGridControlFactory $gridControl
	 * @param IFormControlFactory $formControl
	 */
	public function __construct( IGridControlFactory $gridControl, IFormControlFactory $formControl ) {
		parent::__construct();
		$this->setFormControl($formControl)
			->setGridControl($gridControl);
	}

	/**
	 * @return \App\Admin\Controls\Rating\Grid\GridControl
	 */
	public function createComponentGrid() {
		$grid = $this->getGridControl();
		return $grid;
	}

	/**
	 * @return \App\Admin\Controls\Rating\Form\FormControl
	 */
	public function createComponentForm() {
		$form = $this->getFormControl();
		return $form;
	}



	/**
	 * @return Grid\GridControl
	 */
	public function getGridControl() {
		return $this->gridControl->create();
	}

	/**
	 * @param Grid\IGridControlFactory $gridControl
	 *
	 * @return self Provides Fluent Interface
	 */
	public function setGridControl( $gridControl ) {
		$this->gridControl = $gridControl;

		return $this;
	}

	/**
	 * @return Form\FormControl
	 */
	public function getFormControl() {
		return $this->formControl->create();
	}

	/**
	 * @param Form\IFormControlFactory $formControl
	 *
	 * @return self Provides Fluent Interface
	 */
	public function setFormControl( $formControl ) {
		$this->formControl = $formControl;

		return $this;
	}




}