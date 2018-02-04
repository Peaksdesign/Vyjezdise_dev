<?php


namespace App\Admin\Presenters;


use App\Admin\Controls\Album\AlbumControl;
use App\Admin\Controls\Album\IAlbumControlFactory;
use App\Admin\Controls\Destination\DestinationControl;
use App\Admin\Controls\Destination\IDestinationControlFactory;
use App\Admin\Controls\Image\IImageControlFactory;
use App\Admin\Controls\Image\ImageControl;
use App\Admin\Controls\Rating\IRatingControlFactory;
use App\Admin\Controls\Rating\RatingControl;
use App\Admin\Controls\Slope\ISlopeControlFactory;
use App\Admin\Controls\Slope\SlopeControl;
use App\Admin\Controls\User\IUserControlFactory;
use App\Admin\Controls\User\UserControl;
use Nette\Application\UI\Presenter;
use Nette\Security\Passwords;

/**
 * Class BasePresenter
 * @package App\Admin\Presenters
 * @author Josef Banya
 */
abstract class BasePresenter extends Presenter
{
    /** @var  IUserControlFactory @inject */
    public $userControl;

    /** @var  IDestinationControlFactory @inject */
    public $destinationControl;

    /** @var  IAlbumControlFactory @inject */
    public $albumControl;

    /** @var  IImageControlFactory @inject */
    public $imageControl;

    /** @var  ISlopeControlFactory  @inject */
    public $slopeControl;

    /** @var  IRatingControlFactory @inject */
    public $ratingControl;

    /**
     * @return UserControl
     */
    public function createComponentUser()
    {
        $control = $this->getUserControl();
        return $control;
    }

    /**
     * @return DestinationControl
     */
    public function createComponentDestination()
    {
        $control = $this->getDestinationControl();
        return $control;
    }

    /**
     * @return AlbumControl
     */
    public function createComponentAlbum()
    {
        $control = $this->getAlbumControl();
        return $control;
    }

    /**
     * @return ImageControl
     */
    public function createComponentImage()
    {
        $control = $this->getImageControl();
        return $control;
    }

    /**
     * @return SlopeControl
     */
    public function createComponentSlope()
    {
        $control = $this->getSlopeControl();
        return $control;
    }

	/**
	 * @return \App\Admin\Controls\Rating\RatingControl
	 */
	public function createComponentRating(  ) {
    	return $this->getRatingControl();
    }

    /**
     * @return UserControl
     */
    public function getUserControl()
    {
        return $this->userControl->create();
    }

    /**
     * @return DestinationControl
     */
    public function getDestinationControl()
    {
        return $this->destinationControl->create();
    }

    /**
     * @return AlbumControl
     */
    public function getAlbumControl()
    {
        return $this->albumControl->create();
    }

    /**
     * @return ImageControl
     */
    public function getImageControl()
    {
        return $this->imageControl->create();
    }

    /**
     * @return SlopeControl
     */
    public function getSlopeControl()
    {
        return $this->slopeControl->create();
    }

	/**
	 * @return \App\Admin\Controls\Rating\RatingControl
	 */
	public function getRatingControl() {
		return $this->ratingControl->create();
	}










}