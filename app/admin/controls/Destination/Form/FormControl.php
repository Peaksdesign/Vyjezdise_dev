<?php


namespace App\Admin\Controls\Destination\Form;


use App\Admin\Controls\Base\BaseControl;
use App\Repository\Album\AlbumRepository;
use App\Repository\Destination\DestinationRepository;
use App\Repository\Opening\OpeningRepository;
use App\Repository\Slope\SlopeRepository;
use App\Repository\Tow\TowRepository;
use Nette\Application\UI\Form;
use Nette\Database\DriverException;
use Nette\Forms\Container;
use Nette\Forms\Controls\SubmitButton;
use Nette\Utils\ArrayHash;
use Nette\Utils\Strings;

/**
 * Class FormControl
 * @package App\Admin\Controls\Destination\Form
 * @author Josef Banya
 */
class FormControl extends BaseControl
{
    /** @var string  */
    public $templateFile = 'form';

    /** @var bool  */
    protected $isEdit = false;

    /** @var null |int */
    protected $editId = null;

    /** @var  DestinationRepository */
    protected $destinationRepository;

    /** @var  TowRepository */
    protected $towRepository;

    /** @var  SlopeRepository */
    protected $slopeRepository;

    /** @var  AlbumRepository */
    protected $albumRepository;

    /** @var  OpeningRepository */
    protected $openingRepository;

    const WEEK_DAYS = [
       1 => 'monday',
       2 => 'tuesday',
       3 => 'wednesday',
       4 => 'thursday',
       5 => 'friday',
       6 => 'saturday',
       7 => 'sunday'
    ];

    const WEEK_DAYS_CZ = [
        1 => 'Pondělí',
        2 => 'Uterý',
        3 => 'Středa',
        4 => 'Čtvrtek',
        5 => 'Pátek',
        6 => 'Sobota',
        7 => 'Neděle'
    ];

    /**
     * FormControl constructor.
     * @param DestinationRepository $destinationRepository
     * @param \App\Repository\Tow\TowRepository $towRepository
     * @param \App\Repository\Slope\SlopeRepository $slopeRepository
     * @param \App\Repository\Album\AlbumRepository $albumRepository
     * @param \App\Repository\Opening\OpeningRepository $openingRepository
     */
    public function __construct(DestinationRepository $destinationRepository, TowRepository $towRepository, SlopeRepository $slopeRepository, AlbumRepository $albumRepository, OpeningRepository $openingRepository)
    {
        parent::__construct();
        $this->setDestinationRepository($destinationRepository)
            ->setSlopeRepository($slopeRepository)
            ->setTowRepository($towRepository)
            ->setAlbumRepository($albumRepository)
            ->setOpeningRepository($openingRepository);
    }

    /**
     *  Render template
     */
    public function render()
    {
        parent::render();
        if ($this->isEdit()) {
            /** @var Form $form */
            $form = $this->getComponent('form');
            $destination = $this->getDestinationRepository()->getItem($this->getEditId());
            $slopes = $this->getSlopeRepository()->getList()->select('*')->where(['destination_id' => $this->getEditId()]);
            $form['slopes']->setDefaults($slopes);
            $openingDefaults = $this->getOpeningRepository()->getList()->where(['destination_id' => $destination->id]);

            foreach ($openingDefaults as $openingDefault) {
                $form['opening'][$openingDefault->day]->setDefaults(['from_to' => $openingDefault->body]);
            }

            $form['destination']->setDefaults([
                'title' => $destination->title,
                'description' => $destination->description,
                'address' => $destination->address,
                'tel' => $destination->tel,
                'website' => $destination->website,
                'area' => $destination->area,
                'night' => $destination->night,
                'price' => $destination->price,
                'lat' => $destination->lat,
                'lng' => $destination->lng,
                'album_id' => $destination->album_id
            ]);

        }
        $this->getTemplate()->render();
    }

    /**
     * @return \Nette\Application\UI\Form
     */
    public function createComponentForm()
    {
        $form = new Form();
        $destination = $form->addContainer('destination');
        $destination->addText('title', 'Název')->setRequired();
        $destination->addTextArea('description', 'Popis')->setRequired();
        $destination->addText('address', 'Adresa')->setRequired();
        $destination->addText('tel', 'Telefon')->setRequired();
        $destination->addText('website', 'Webové stránky')->setRequired();
        $destination->addText('area', 'Oblast')->setRequired();
        $areas  = $this->getAlbumRepository()->getDatabase()
            ->table('area')->fetchPairs('id', 'name');

        $destination->addSelect('area_id', 'OBLAST - NOVÁ', $areas)->setRequired();

        $albums = $this->getAlbumRepository()->getTable()->fetchPairs('id', 'title');
        $destination->addSelect('album_id', 'Album obrázků', $albums)->setPrompt('Vyberte album');

        $destination->addCheckbox('night', 'Noční provoz');
        $opening = $form->addContainer('opening');
        foreach (self::WEEK_DAYS as $k => $day) {
            $openingHours = $opening->addContainer($day);
            $openingHours->addText('from_to', 'Rozmezí');
        }
        $destination->addText('price', 'Cena')->setRequired();
        $destination->addText('lat', 'GPS LATITUDE')->setRequired();
        $destination->addText('lng', 'GPS LONGITUDE')->setRequired();



       $slopes =  $form->addDynamic('slopes', function (Container $slope) {
            $slope->addText('name', 'Jméno sjezdovky')->setRequired();
            $slope->addText('lenght', 'Délka sjezdovky')->setRequired();
            $slope->addText('camber', 'Převýšení sjezdovky')->setRequired();

            $slopeTypes = $this->getSlopeRepository()->getDatabase()->table('slope_type')->fetchPairs('id', 'name');
            $towTypes = $this->getTowRepository()->getDatabase()->table('tow_type')->fetchPairs('id', 'name');


            $slope->addSelect('slope_type_id', 'Obtížnost', $slopeTypes)->setRequired();
            $slope->addSelect('tow_type_id', 'Typ vleku', $towTypes)->setRequired();
            $slope->addHidden('id');
            $slope->addSubmit('remove', 'Odstranit')
                ->setValidationScope(FALSE)
                ->onClick[] = [$this, 'removeSlope'];
        }, 0);

        $slopes->addSubmit('add', 'Přidat sjezdovku')
            ->setValidationScope(FALSE)
            ->onClick[] = [$this, 'addSlope'];


        $form->addSubmit('submit', 'Uložit')->onClick[] = [$this, 'process'];;
        return $form;
    }

    /**
     * @param \Nette\Forms\Controls\SubmitButton $button
     */
    public function addSlope(SubmitButton $button)
    {
        $button->parent->createOne();
    }

    /**
     * @param \Nette\Forms\Controls\SubmitButton $button
     */
    public function removeSlope(SubmitButton $button)
    {
        $slopes = $button->parent->parent;

        $slopes->remove($button->parent, TRUE);

        if (!empty($button->parent->getComponent('id')->value)) {
            $this->getSlopeRepository()->delete($button->parent->getComponent('id')->value);
            $this->getPresenter()->flashMessage('Sjezdovka byla smazána z databáze', 'success');
        } else {
            $this->getPresenter()->flashMessage('Položka byla byla odebrána ze seznamu', 'success');
        }

    }

    /**
     * @param \Nette\Forms\Controls\SubmitButton $button
     */
    public function process(SubmitButton $button)
    {
        $values = $button->getForm()->getValues();

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
            $values->destination->seo_title = Strings::webalize($values->destination->title);
            $this->getDestinationRepository()->update($values->destination, $this->getEditId());
            $this->deleteOpening($this->getEditId());
            $this->createOpening($values->opening, $this->getEditId());
            $this->processSlopes($values->slopes, $this->getEditId());
            $this->getPresenter()->flashMessage('Uloženo', 'success');
            $this->getPresenter()->redirect('Destination:default');
        } catch (\Error $e) {
            $this->getPresenter()->flashMessage('Destinaci se nepodařilo uložit', 'error');
            $this->getPresenter()->redirect('Destination:default');
        }
    }

    /**
     * @param \Nette\Utils\ArrayHash $values
     */
    public function save(ArrayHash $values)
    {
        try {
            $values->destination->seo_title = Strings::webalize($values->destination->title);
            $destination = $this->getDestinationRepository()->insert($values->destination, true);
            $this->deleteOpening($destination->id);
            $this->createOpening($values->opening, $destination->id);
            $this->processSlopes($values->slopes, $destination->id);
            $this->getPresenter()->flashMessage('Uloženo', 'success');
            $this->getPresenter()->redirect('Destination:default');
        } catch (\Error $e) {
            $this->getPresenter()->flashMessage('Destinaci se nepodařilo uložit', 'error');
            $this->getPresenter()->redirect('Destination:default');
        }
    }

    /**
     * @param \Nette\Utils\ArrayHash $slopes
     * @param $destinationId
     */
    public function processSlopes(ArrayHash $slopes, $destinationId)
    {
        foreach ($slopes as $slope) {
            $slope->destination_id = $destinationId;
            if (!empty($slope->id)) {
                $this->getSlopeRepository()->update((array) $slope, $slope->id);
            } else {
                $this->getSlopeRepository()->insert((array)$slope);
            }
        }
    }

    /**
     * @param $destinationId
     * @return int
     */
    public function deleteOpening($destinationId)
    {
       return $this->getOpeningRepository()
           ->getList()->where(['destination_id' => $destinationId])->delete();
    }

    /**
     * @param \Nette\Utils\ArrayHash $opening
     * @param $destinationId
     */
    public function createOpening(ArrayHash $opening, $destinationId)
    {
        foreach ($opening as $day => $value) {
            $this->getOpeningRepository()->insert([
                'day' => trim($day),
                'body' => $value->from_to,
                'destination_id' => $destinationId]
            );
        }
    }

    /**
     * @return DestinationRepository
     */
    public function getDestinationRepository()
    {
        return $this->destinationRepository;
    }

    /**
     * @param DestinationRepository $destinationRepository
     * @return self Provides Fluent Interface
     */
    public function setDestinationRepository($destinationRepository)
    {
        $this->destinationRepository = $destinationRepository;
        return $this;
    }

    /**
     * @return TowRepository
     */
    public function getTowRepository()
    {
        return $this->towRepository;
    }

    /**
     * @param TowRepository $towRepository
     * @return self Provides Fluent Interface
     */
    public function setTowRepository($towRepository)
    {
        $this->towRepository = $towRepository;
        return $this;
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
     * @return \App\Repository\Album\AlbumRepository
     */
    public function getAlbumRepository()
    {
        return $this->albumRepository;
    }

    /**
     * @param \App\Repository\Album\AlbumRepository $albumRepository
     * @return self Provides Fluent Interface
     */
    public function setAlbumRepository($albumRepository)
    {
        $this->albumRepository = $albumRepository;
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
     * @return \App\Repository\Opening\OpeningRepository
     */
    public function getOpeningRepository()
    {
        return $this->openingRepository;
    }

    /**
     * @param \App\Repository\Opening\OpeningRepository $openingRepository
     * @return self Provides Fluent Interface
     */
    public function setOpeningRepository($openingRepository)
    {
        $this->openingRepository = $openingRepository;
        return $this;
    }


}