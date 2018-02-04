<?php


namespace App\Admin\Controls\User\Login;

/**
 * Interface ILoginControlFactory
 * @package App\Admin\Controls\User\Login
 * @author Josef Banya
 */
interface ILoginControlFactory
{
    /**
     * @return LoginControl
     */
    public function create();
}