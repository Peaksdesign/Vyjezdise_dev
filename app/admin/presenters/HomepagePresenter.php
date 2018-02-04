<?php

namespace App\Admin\Presenters;


/**
 * Class HomepagePresenter
 * @package App\Admin\Presenters
 * @author Josef Banya
 */
class HomepagePresenter extends BasePresenter
{

    /**
     * If user is already login-in redirect to dashboard
     */
    public function beforeRender()
    {
        if ($this->getUser()->isLoggedIn() && $this->getUser()->isInRole(1)){
            $this->redirect(301, 'Dashboard:default');
        }
    }

}
