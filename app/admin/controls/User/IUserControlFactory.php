<?php


namespace App\Admin\Controls\User;

/**
 * Interface IUserControlFactory
 * @package App\Admin\Controls\User
 * @author Josef Banya
 */
interface IUserControlFactory
{
    /**
     * @return UserControl
     */
    public function create();
}