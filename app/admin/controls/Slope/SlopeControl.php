<?php


namespace App\Admin\Controls\Slope;


use App\Admin\Controls\Base\BaseControl;

/**
 * Class SlopeControl
 * @package App\Admin\Controls\Slope
 * @author Josef Banya
 */
class SlopeControl extends BaseControl
{
    /** @var null  */
    public $templateFile = null; // wrapped component
    /** @var  Grid\IGridControlFactory */
    protected $gridControl;
    /** @var  Form\IFormControlFactory */
    protected $formControl;

    /**
     * SlopeControl constructor.
     * @param Grid\IGridControlFactory $gridControl
     * @param Form\IFormControlFactory $formControl
     */
    public function __construct(Grid\IGridControlFactory $gridControl, Form\IFormControlFactory $formControl)
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