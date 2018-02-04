<?php


namespace App\Admin\Presenters;


/**
 * Class SlopePresenter
 * @package App\Admin\Presenters
 * @author Josef Banya
 */
class SlopePresenter extends BaseAuthPresenter
{
    /**
     * @param null $id
     */
    public function actionAdd($id = NULL)
    {
        if ($id) {
            /** @var \App\Admin\Controls\Slope\Form\FormControl $grid */
            $grid = $this->getComponent('slope-form');
            $grid->setDestinationId($id);
        }
    }

    /**
     * @param null $id
     */
    public function actionDefault($id = NULL)
    {
        if ($id) {
            /** @var \App\Admin\Controls\Slope\Grid\GridControl $grid */
            $grid = $this->getComponent('slope-grid');
            $grid->setDestinationId($id);
            $this->getTemplate()->id = $id;
        }

    }

    /**
     * @param $id
     */
    public function actionEdit($id)
    {
        /** @var \App\Admin\Controls\Slope\Form\FormControl $grid */
        $grid = $this->getComponent('slope-form');
        $grid->setEditId($id)->setIsEdit(true);
    }
}