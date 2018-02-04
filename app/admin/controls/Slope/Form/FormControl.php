<?php


namespace App\Admin\Controls\Slope\Form;


use App\Admin\Controls\Base\BaseControl;
use App\Repository\Destination\DestinationRepository;
use App\Repository\Slope\SlopeRepository;
use App\Repository\Tow\TowRepository;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

/**
 * Class FormControl
 * @package App\Admin\Controls\Slope\Form
 * @author Josef Banya
 */
class FormControl extends BaseControl
{
    /** @var string  */
    public $templateFile = 'form';

    /** @var bool  */
    public $isEdit = false;

    /** @var null  */
    public $editId = null;

    /** @var null  */
    public $destinationId = null;

    /** @var  SlopeRepository */
    protected $slopeRepository;

    /** @var  TowRepository */
    protected $towRepository;

    /** @var  DestinationRepository */
    protected $destinationRepository;

    /**
     * GridControl constructor.
     * @param SlopeRepository $slopeRepository
     * @param \App\Repository\Tow\TowRepository $towRepository
     * @param \App\Repository\Destination\DestinationRepository $destinationRepository
     */
    public function __construct(SlopeRepository $slopeRepository, TowRepository $towRepository, DestinationRepository $destinationRepository)
    {
        parent::__construct();
        $this->setSlopeRepository($slopeRepository)
            ->setTowRepository($towRepository)
            ->setDestinationRepository($destinationRepository);
    }

    /**
     * Render template
     */
    public function render()
    {
        parent::render();

        if ($this->isEdit()) {
            $slope = $this->getSlopeRepository()->getItem($this->getEditId());
            $this['form']->setDefaults($slope->toArray());
        }

        if ($this->getDestinationId() !== null) {
            $this['form']->setDefaults(['destination_id' => $this->getDestinationId()]);
        }

        $this->getTemplate()->render();
    }

    /**
     * @return \Nette\Application\UI\Form
     */
    public function createComponentForm()
    {
        $form = new Form();

        $form->addText('name', 'Jméno sjezdovky')->setRequired();
        $form->addText('lenght', 'Délka sjezdovky')->setRequired();
        $form->addText('camber', 'Převýšení sjezdovky')->setRequired();

        $slopeTypes = $this->getSlopeRepository()->getDatabase()->table('slope_type')->fetchPairs('id', 'name');
        $towTypes = $this->getTowRepository()->getDatabase()->table('tow_type')->fetchPairs('id', 'name');

        $destinations = $this->getDestinationRepository()->getList()->fetchPairs('id', 'title');

        $form->addRadioList('slope_type_id', 'Obtížnost', $slopeTypes);
        $form->addSelect('tow_type_id', 'Typ vleku', $towTypes);
        $form->addSelect('destination_id', 'Destinace', $destinations);

        $form->addSubmit('submit', 'Uložit');
        $form->onSuccess[] = [$this, 'process'];

        return $form;
    }

    /**
     * @param \Nette\Application\UI\Form $form
     */
    public function process(Form $form)
    {
        $values = $form->getValues();

        if ($this->isEdit()) {
            $this->edit($values);
        } else {
            $this->save($values);
        }
    }

    /**
     * @param \Nette\Utils\ArrayHash $values
     */
    public function edit(ArrayHash $values)
    {
        try {
            $this->getSlopeRepository()->update((array) $values, $this->getEditId());
            $this->getPresenter()->flashMessage('Uloženo', 'success');
        } catch (\Exception $e) {
            $this->getPresenter()->flashMessage('Nepodařilo se uložit záznam', 'error');
        }
        $this->getPresenter()->redirect('Slope:default', $values->destination_id);
    }

    /**
     * @param \Nette\Utils\ArrayHash $values
     */
    public function save(ArrayHash $values)
    {
        try {
            $this->getSlopeRepository()->insert((array) $values);
            $this->getPresenter()->flashMessage('Uloženo', 'success');
        } catch (\Exception $e) {
            $this->getPresenter()->flashMessage('Nepodařilo se uložit záznam', 'error');
        }
        $this->getPresenter()->redirect('Slope:default', $values->destination_id);
    }

    /**
     * @return SlopeRepository
     */
    public function getSlopeRepository()
    {
        return $this->slopeRepository;
    }

    /**
     * @param SlopeRepository $slopeRepository
     * @return self Provides Fluent Interface
     */
    public function setSlopeRepository($slopeRepository)
    {
        $this->slopeRepository = $slopeRepository;
        return $this;
    }

    /**
     * @return \App\Repository\Tow\TowRepository
     */
    public function getTowRepository()
    {
        return $this->towRepository;
    }

    /**
     * @param \App\Repository\Tow\TowRepository $towRepository
     * @return self Provides Fluent Interface
     */
    public function setTowRepository($towRepository)
    {
        $this->towRepository = $towRepository;
        return $this;
    }

    /**
     * @return \App\Repository\Destination\DestinationRepository
     */
    public function getDestinationRepository()
    {
        return $this->destinationRepository;
    }

    /**
     * @param \App\Repository\Destination\DestinationRepository $destinationRepository
     * @return self Provides Fluent Interface
     */
    public function setDestinationRepository($destinationRepository)
    {
        $this->destinationRepository = $destinationRepository;
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
     * @return null
     */
    public function getEditId()
    {
        return $this->editId;
    }

    /**
     * @param null $editId
     * @return self Provides Fluent Interface
     */
    public function setEditId($editId)
    {
        $this->editId = $editId;
        return $this;
    }

    /**
     * @return null|int
     */
    public function getDestinationId()
    {
        return $this->destinationId;
    }

    /**
     * @param null $destinationId
     * @return self Provides Fluent Interface
     */
    public function setDestinationId($destinationId)
    {
        $this->destinationId = $destinationId;
        return $this;
    }




}