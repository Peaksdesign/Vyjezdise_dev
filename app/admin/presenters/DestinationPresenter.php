<?php


namespace App\Admin\Presenters;
use App\Admin\Controls\Destination\DestinationControl;
use App\Admin\Controls\Destination\Form\FormControl;


/**
 * Class DestinationPresenter
 * @package App\Admin\Presenters
 * @author Josef Banya
 */
class DestinationPresenter extends BaseAuthPresenter
{
    /**
     * @param $id
     */
    public function actionEdit($id)
    {
        /** @var DestinationControl $destinationControl */
        $destinationControl = $this->getComponent('destination');
        /** @var FormControl $formControl */
        $formControl = $destinationControl->getComponent('form');
        $formControl->setEditId($id)->setIsEdit(true);
    }
}