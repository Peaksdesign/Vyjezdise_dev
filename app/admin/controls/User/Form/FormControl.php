<?php


namespace App\Admin\Controls\User\Form;


use App\Admin\Controls\Base\BaseControl;
use App\Repository\User\UserRepository;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Security\Passwords;

/**
 * Class FormControl
 * @package App\Admin\Controls\User\Form
 * @author Josef Banya
 */
class FormControl extends BaseControl
{
    /** @var string  */
    public $templateFile = 'form';

    /** @var null | int */
    public $editId = null;

    /** @var bool  */
    public $isEdit = false;

    /** @var  UserRepository */
    protected $userRepository;

    /**
     * FormControl constructor.
     * @param \App\Repository\User\UserRepository $userRepository
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
        if ($this->isEdit()) {
            /** @var Form $form */
            $form = $this->getComponent('form');
            $defaults = $this->getUserRepository()->getItem($this->getEditId());
            $form->setDefaults($defaults->toArray());
        }
        $this->getTemplate()->render();
    }

    /**
     * @return Form
     */
    public function createComponentForm()
    {
        $form = new Form();

        $form->addEmail('email', 'Email')->setRequired();

        $form->addSelect('role', 'Role', UserRepository::ROLES);
        $form->addPassword('password', 'Heslo')->setRequired();

        $form->addSubmit('submit', 'Odeslat');
        $form->onSuccess[] = [$this, 'processForm'];
        return $form;
    }

    /**
     * @param Form $form
     */
    public function processForm(Form $form)
    {
        $values = $form->getValues();
        try {
            if ($this->isEdit() || isset($this->editId)) {
                $this->getUserRepository()
                    ->update([
                        'email' => $values->email,
                        'role' => $values->role,
                        'password' => Passwords::hash($values->password)
                    ], $this->getEditId());
            } else {
                $this->getUserRepository()->insert([
                    'email' => $values->email,
                    'role' => $values->role,
                    'password' => Passwords::hash($values->password),
                    'create_date' => new \DateTime()
                ]);
            }
            $this->getPresenter()->flashMessage('Akce provedena úspěšně', 'success');

        } catch (UniqueConstraintViolationException $e) {
            $this->getPresenter()->flashMessage('Akce se nezdařila, prosím kontaktujte webmastera', 'error');
        }
        $this->getPresenter()->redirect('default');

    }

    /**
     * @return int|null
     */
    public function getEditId()
    {
        return $this->editId;
    }

    /**
     * @param int|null $editId
     * @return self Provides Fluent Interface
     */
    public function setEditId($editId)
    {
        $this->editId = $editId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEdit()
    {
        return $this->isEdit;
    }

    /**
     * @param bool $isEdit
     * @return self Provides Fluent Interface
     */
    public function setIsEdit($isEdit)
    {
        $this->isEdit = $isEdit;
        return $this;
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