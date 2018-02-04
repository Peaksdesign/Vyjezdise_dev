<?php


namespace App\Admin\Presenters;


/**
 * Class UserPresenter
 * @package App\Admin\Presenters
 * @author Josef Banya
 */
class UserPresenter extends BaseAuthPresenter
{
    /**
     * @param $id
     */
    public function actionEdit($id)
    {
        /** @var \App\Admin\Controls\User\UserControl $userControl */
        $userControl = $this->getComponent('user');
        /** @var \App\Admin\Controls\User\Form\FormControl $form */
        $form = $userControl->getComponent('form');
        $form->setIsEdit(true)->setEditId($id);
    }
}