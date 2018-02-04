<?php


namespace App\Admin\Controls\Destination;


use App\Admin\Controls\Base\BaseControl;

/**
 * Class DestinationControl
 * @package App\Admin\Controls\Destination
 * @author Josef Banya
 */
class DestinationControl extends BaseControl
{
    public $templateFile = NULL; //wrapped component
    /** @var  Form\IFormControlFactory */
    public $formControl;
    /** @var  Grid\IGridControlFactory */
    public $gridControl;

    /**
     * DestinationControl constructor.
     * @param Form\IFormControlFactory $formControl
     * @param Grid\IGridControlFactory $gridControl
     */
    public function __construct(Form\IFormControlFactory $formControl, Grid\IGridControlFactory $gridControl)
    {
        parent::__construct();
        $this->setGridControl($gridControl)
            ->setFormControl($formControl);
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
    public function createComponentGrid()
    {
        $control = $this->getGridControl();
        return $control;
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

    /**
     * @return Grid\GridControl
     */
    public function getGridControl()
    {
        return $this->gridControl->create();
    }

    /**
     * @param Grid\IGridControlFactory $gridControl
     * @return self Provides Fluent Interface
     */
    public function setGridControl($gridControl)
    {
        $this->gridControl = $gridControl;
        return $this;
    }

}