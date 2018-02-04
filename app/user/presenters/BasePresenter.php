<?php


namespace App\User\Presenters;

use App\Repository\Album\AlbumRepository;
use App\Repository\Destination\DestinationRepository;
use App\Repository\Image\ImageRepository;
use App\Repository\Opening\OpeningRepository;
use App\Repository\Rating\RatingRepository;
use App\Repository\Slope\SlopeRepository;
use App\User\Controls\Filter\FilterControl;
use App\User\Controls\Filter\IFilterControlFactory;
use App\User\Controls\AddRating\AddRatingControl;
use App\User\Controls\AddRating\IAddRatingControlFactory;
use Nette\Application\UI\Presenter;

/**
 * Class BasePresenter
 * @package App\User\Presenters
 * @author Josef Banya
 */
abstract class BasePresenter extends Presenter
{

    /** @var  IFilterControlFactory @inject */
    public $filterControl;

    /** @var IAddRatingControlFactory @inject */
    public $addRatingControl;

    /** @var  DestinationRepository @inject */
    public $destinationRepository;

    /** @var  SlopeRepository @inject */
    public $slopeRepository;

    /** @var  AlbumRepository @inject */
    public $albumRepository;

    /** @var  OpeningRepository  @inject */
    public $openingRepository;

    /** @var  RatingRepository @inject */
    public $ratingRepository;

    /** @var  ImageRepository @inject */
    public $imageRepository;

    /**
     * @return AddRatingControl
     */
    public function createComponentAddRating()
    {
        $addRating = $this->getAddRatingControl();
        return $addRating;
    }

    /**
     * @return AddRatingControl
     */
    public function getAddRatingControl()
    {
        return $this->addRatingControl->create();
    }

    /**
     * @return FilterControl
     */
    public function createComponentFilter()
    {
        $filter = $this->getFilterControl();
        return $filter;
    }

    /**
     * @return FilterControl
     */
    public function getFilterControl()
    {
        return $this->filterControl->create();
    }

    /**
     * @return \App\Repository\Destination\DestinationRepository
     */
    public function getDestinationRepository()
    {
        return $this->destinationRepository;
    }

    /**
     * @return \App\Repository\Slope\SlopeRepository
     */
    public function getSlopeRepository()
    {
        return $this->slopeRepository;
    }

    /**
     * @return \App\Repository\Album\AlbumRepository
     */
    public function getAlbumRepository()
    {
        return $this->albumRepository;
    }

    /**
     * @return \App\Repository\Opening\OpeningRepository
     */
    public function getOpeningRepository()
    {
        return $this->openingRepository;
    }

	/**
	 * @return \App\Repository\Rating\RatingRepository
	 */
	public function getRatingRepository() {
		return $this->ratingRepository;
	}

	/**
	 * @return \App\Repository\Image\ImageRepository
	 */
	public function getImageRepository() {
		return $this->imageRepository;
	}


}