<?php


namespace App\Admin\Controls\User\Login;


use App\Admin\Controls\Base\BaseControl;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;

/**
 * Class LoginControl
 * @package App\Admin\Controls\User\Login
 * @author Josef Banya
 */
class LoginControl extends BaseControl
{
    /** @var string  */
    public $templateFile = 'login';

    /**
     * Render template
     */
    public function render()
    {
        parent::render();
        $this->getTemplate()->render();
    }

    /**
     * @return \Nette\Application\UI\Form
     */
    public function createComponentForm()
    {
        $form = new Form();
        $form->addText('email', 'Zadejte prosím email')->setRequired();
        $form->addPassword('password', 'Zadejte prosím své heslo')->setRequired();

        $form->addSubmit('submit', 'Přihlásit');
        $form->onSuccess[] = [$this, 'processLogin'];
        return $form;
    }

    /**
     * @param \Nette\Application\UI\Form $form
     */
    public function processLogin(Form $form)
    {
        $values = $form->getValues();
        try {
            $this->getPresenter()->getUser()->login($values->email, $values->password);
            $this->getPresenter()->flashMessage('Přihlášení proběhlo v pořádku', 'success');
            $this->getPresenter()->redirect('Dashboard:default');
        } catch (AuthenticationException $e) {
            $this->getPresenter()->flashMessage($e->getMessage(), 'error');
            $this->getPresenter()->redirect('Homepage:default');
        }
    }
}