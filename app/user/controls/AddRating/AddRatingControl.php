<?php


namespace App\User\Controls\AddRating;


use App\Repository\Rating\RatingRepository;
use App\User\Controls\Base\BaseControl;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;

/**
 * Class AddRatingControl
 * @package App\User\Controls\AddRating
 * @author Josef Banya
 */
class AddRatingControl extends BaseControl
{
    /** @var string  */
    public $templateFile = 'addRating';

    /** @var null  */
    public $destinationId = null;

    /** @var  RatingRepository  */
    public $ratingRepository;

	/**
	 * AddRatingControl constructor.
	 *
	 * @param RatingRepository $ratingRepository
	 */
	public function __construct( RatingRepository $ratingRepository ) {
		$this->setRatingRepository($ratingRepository);
	}


	/**
     * Render
     */
    public function render()
    {
        parent::render();
        $this->getTemplate()->render();
    }

	public function createComponentForm() {
		$form = new Form();
		$ratingValues = [
			5=> 'Skvělé',
			4=> 'Dobré',
			3=> 'Průměr',
			2=> 'Podprůměr',
			1 =>'Špatné',
		];

		$form->addText('firstName', 'Vaše jméno')->setRequired('Prosím zvolte vaše jméno');
		$form->addText('lastName', 'Vaše příjmení');
		$form->addTextArea('description')->setRequired('Prosím napište komentář k vašemu hodnocení');
		$form->addRadioList('parkingRating', 'Parkování', $ratingValues)->setRequired('Prosím zvolte hodnocení parkování');
		$form->addRadioList('foodRating', 'Občerstvení', $ratingValues)->setRequired('Prosím zvolte hodnocení občerstvení.');
		$form->addRadioList('linesRating', 'Fronty', $ratingValues)->setRequired('Prosím zvolte hodnocení front');
		$form->addRadioList('totalRating', 'Celkové hodnocení', $ratingValues)->setRequired('Prosím zvolte celkové hodnocení');
		$form->addSubmit('sbmt');
		$form->onSuccess[] = [$this, 'handleProcessForm'];
		return $form;
    }

	public function handleProcessForm( $form ) {
		$values = $form->getValues();
		try {
			$author = $values->firstName;

			if (!empty($values->lastName) && $values->lastName !== '') {
				$author = $author . ' ' . $values->lastName;
			}

			$this->getRatingRepository()->insert([
				'destination_id' => $this->getDestinationId(),
				'author' => $author,
				'description' => $values->description,
				'parking' => $values->parkingRating,
				'food' => $values->foodRating,
				'queue' => $values->linesRating,
				'final' => $values->totalRating
			]);
			$this->getPresenter()->flashMessage('Děkujeme za hodnocení', 'success');
			$this->getPresenter()->redirect('this#js-add-rating-form');
		} catch (\Exception $e) {
			$this->getPresenter()->flashMessage('Hodnocení se nepodařilo uložit', 'error');
			$this->getPresenter()->redirect('this#js-add-rating-form');
		}



    }

	/**
	 * @return null|int
	 */
	public function getDestinationId() {
		return $this->destinationId;
	}

	/**
	 * @param null|int $destinationId
	 *
	 * @return self Provides Fluent Interface
	 */
	public function setDestinationId( $destinationId ) {
		$this->destinationId = $destinationId;

		return $this;
	}

	/**
	 * @return RatingRepository
	 */
	public function getRatingRepository() {
		return $this->ratingRepository;
	}

	/**
	 * @param RatingRepository $ratingRepository
	 *
	 * @return self Provides Fluent Interface
	 */
	public function setRatingRepository( $ratingRepository ) {
		$this->ratingRepository = $ratingRepository;

		return $this;
	}


}