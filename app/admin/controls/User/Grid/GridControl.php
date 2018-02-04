<?php


namespace App\Admin\Controls\User\Grid;


use App\Admin\Controls\Base\BaseControl;
use App\Repository\User\UserRepository;
use Ublaboo\DataGrid\DataGrid;

/**
 * Class GridControl
 * @package App\Admin\Controls\Grid
 * @author Josef Banya
 */
class GridControl extends BaseControl
{
    /** @var string  */
    public $templateFile = 'grid';

    /** @var  UserRepository */
    private $userRepository;

    /**
     * GridControl constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->setUserRepository($userRepository);
    }

    /**
     * Render template
     */
    public function render()
    {
        parent::render();
        $this->getTemplate()->render();
    }

    public function createComponentGrid()
    {
        $grid = new DataGrid();
        $grid->setDataSource($this->getUserRepository()->getList());

        $grid->addColumnNumber('id', 'ID', 'id')
            ->setSortable()
            ->setFitContent();

        $grid->addColumnText('email', 'Email', 'email')
            ->setSortable()
            ->setFilterText(['email']);

        $grid->addColumnDateTime('create_date', 'Datum vytvoření', 'create_date')
            ->setSortable()
            ->setFitContent();

        $grid->addAction('delete', 'Smazat', 'delete!')
            ->setClass('btn btn-xs btn-warning')
            ->setIcon('trash');

        $grid->addAction('edit', 'Upravit', 'edit!')
            ->setClass('btn btn-xs btn-primary')
            ->setIcon('pencil');

        return $grid;
    }

    /**
     * @param $id
     */
    public function handleDelete($id)
    {
        if ($this->getUserRepository()->delete($id)) {
            $this->getPresenter()->flashMessage('Uživatel byl smazán', 'success');
        } else {
            $this->getPresenter()->flashMessage('Uživatel nebyl smazán', 'error');
        }
        $this->getPresenter()->redirect('default');

    }

    /**
     * @param $id
     */
    public function handleEdit($id)
    {
        $this->getPresenter()->redirect('User:edit', $id);
    }

    /**
     * @return UserRepository
     */
    public function getUserRepository()
    {
        return $this->userRepository;
    }

    /**
     * @param UserRepository $userRepository
     * @return self Provides Fluent Interface
     */
    public function setUserRepository($userRepository)
    {
        $this->userRepository = $userRepository;
        return $this;
    }


}