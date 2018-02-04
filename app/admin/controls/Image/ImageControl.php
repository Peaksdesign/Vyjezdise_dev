<?php


namespace App\Admin\Controls\Image;


use App\Admin\Controls\Base\BaseControl;
use App\Admin\Controls\Image\Form\IFormControlFactory;
use App\Admin\Controls\Image\Grid\IGridControlFactory;

/**
 * Class ImageControl
 * @package App\Admin\Controls\Image
 * @author Josef Banya
 */
class ImageControl extends BaseControl
{
    public $templateFile = NULL; // wrapped component

    /** @var  Grid\IGridControlFactory */
    public $gridControl;
    /** @var  Form\IFormControlFactory */
    public $formControl;

    /**
     * ImageControl constructor.
     * @param IGridControlFactory $gridControl
     * @param IFormControlFactory $formControl
     */
    public function __construct(IGridControlFactory $gridControl, IFormControlFactory $formControl)
    {
        parent::__construct();
        $this->setGridControl($gridControl)
            ->setFormControl($formControl);
    }

    /**
     * @return Grid\GridControl
     */
    public function createComponentGrid()
    {
        $control = $this->getGridControl();
        return $control;
    }

    /**
     * @return Form\FormControl
     */
    public function createComponentForm()
    {
        $control = $this->getFormControl();
        return $control;
    }

    /**
     * @return Grid\GridControl
     */
    public function getGridControl()
    {
        return $this->gridControl->create();
    }

    /**
     * @param IGridControlFactory $gridControl
     * @return self Provides Fluent Interface
     */
    public function setGridControl($gridControl)
    {
        $this->gridControl = $gridControl;
        return $this;
    }

    /**
     * @return Form\FormControl
     */
    public function getFormControl()
    {
        return $this->formControl->create();
    }

    /**
     * @param Form\IFormControlFactory $formControl
     * @return self Provides Fluent Interface
     */
    public function setFormControl($formControl)
    {
        $this->formControl = $formControl;
        return $this;
    }



}