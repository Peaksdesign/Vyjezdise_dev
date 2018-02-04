<?php


namespace App\Admin\Presenters;


/**
 * Class RatingPresenter
 * @package App\Admin\Presenters
 * @author Josef Banya
 */
class RatingPresenter extends BaseAuthPresenter {
	public function actionDefault( $destinationId ) {
		$grid = $this->getComponent('rating-grid');
		$grid->setDestinationId($destinationId);
	}
}