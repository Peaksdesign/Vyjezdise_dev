<?php


namespace App\Admin\Controls\Album;


use App\Admin\Controls\Base\BaseControl;

/**
 * Class AlbumControl
 * @package App\Admin\Controls\Album
 * @author Josef Banya
 */
class AlbumControl extends BaseControl
{
    /** @var null  */
    public $templateFile = NULL; // wrapped component
    /** @var  Form\IFormControlFactory */
    protected $formControl;
    /** @var  Grid\IGridControlFactory */
    protected $gridControl;

    /**
     * AlbumControl constructor.
     * @param Form\IFormControlFactory $formControl
     * @param Grid\IGridControlFactory $gridControl
     */
    public function __construct(Form\IFormControlFactory $formControl, Grid\IGridControlFactory $gridControl)
    {
        parent::__construct();
        $this->setFormControl($formControl)
            ->setGridControl($gridControl);
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