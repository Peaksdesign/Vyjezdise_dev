<?php


namespace App\Admin\Presenters;


/**
 * Class BaseAuthPresenter
 * @package App\Admin\Presenters
 * @author Josef Banya
 */
class BaseAuthPresenter extends BasePresenter
{
    /**
     * Check if user have permissions
     */
    public function startup()
    {

      if (!$this->getUser()->isLoggedIn()) {
          $this->redirect(':User:Homepage:default');
      }
      if ($this->getUser()->isLoggedIn() && !$this->getUser()->isInRole(1)) {
          $this->flashMessage('Nemáte dostatečná práva pro vstup do administrace', 'error');
          $this->redirect('Homepage');
      }

      parent::startup();
    }

    /**
     * Logout action
     */
    public function handleLogout()
    {
        $this->getUser()->logout(true);
        $this->redirect('Homepage:default');
    }
}