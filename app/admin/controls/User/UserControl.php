<?php


namespace App\Admin\Controls\User;


use App\Admin\Controls\Base\BaseControl;

/**
 * Class UserControl
 * @package App\Admin\Controls\User
 * @author Josef Banya
 */
class UserControl extends BaseControl
{
    /** @var null  */ // this is wrapper component for user components
    public $templateFile = NULL;

    /** @var  Form\IFormControlFactory */
    protected $formControl;
    /** @var  Grid\IGridControlFactory */
    protected $gridControl;
    /** @var  Login\ILoginControlFactory */
    protected $loginControl;

    /**
     * UserControl constructor.
     * @param Form\IFormControlFactory $formControl
     * @param Grid\IGridControlFactory $gridControl
     * @param Login\ILoginControlFactory $loginControl
     */
    public function __construct(Form\IFormControlFactory $formControl, Grid\IGridControlFactory $gridControl, Login\ILoginControlFactory $loginControl)
    {
        parent::__construct();
        $this->setFormControl($formControl)
            ->setGridControl($gridControl)
            ->setLoginControl($loginControl);
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
     * @return Login\LoginControl
     */
    public function createComponentLogin()
    {
        $control = $this->getLoginControl();
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

    /**
     * @return Login\LoginControl
     */
    public function getLoginControl()
    {
        return $this->loginControl->create();
    }

    /**
     * @param Login\ILoginControlFactory $loginControl
     * @return self Provides Fluent Interface
     */
    public function setLoginControl($loginControl)
    {
        $this->loginControl = $loginControl;
        return $this;
    }

}