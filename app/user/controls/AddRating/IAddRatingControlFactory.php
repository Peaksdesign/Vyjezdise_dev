<?php


namespace App\User\Controls\AddRating;

/**
 * Interface IAddRatingControlFactory
 * @package App\User\Controls\AddRating
 * @author Josef Banya
 */
interface IAddRatingControlFactory
{
    /**
     * @return AddRatingControl
     */
    public function create();
}