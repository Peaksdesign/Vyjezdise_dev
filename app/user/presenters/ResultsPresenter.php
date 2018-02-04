<?php

namespace App\User\Presenters;

use App\User\Components\FilterQuery;
use Nette\Application\BadRequestException;


/**
 * Class ResultsPresenter
 * @package App\User\Presenters
 * @author Josef Banya
 */
class ResultsPresenter extends BasePresenter
{
    /**
     * Render
     * @param bool $fromSession
     */
    public function actionDefault($fromSession = false)
    {
        $session = $this->getSession()->getSection('filterForm');

        if ($fromSession === false) {
            $query = $this->getHttpRequest()->getPost();
        } else {
            $query = unserialize($session->filter);
        }
        $filter = new FilterQuery();
        $session->filter = serialize($query);
        $filter->hydrate($query);
        $destinations = $this->getDestinationRepository()->search($filter);
        $this->template->defaults = $query;
        $this->template->count = count($destinations);
        $this->template->results = $destinations;
    }

    /**
     * @throws \Nette\Application\BadRequestException
     */
    public function handleRedrawResults()
    {
        if (!$this->isAjax()) {
            throw new BadRequestException('This URL are not available');
        }

        $query = $this->getHttpRequest()->getPost();
        $filter = new FilterQuery();
        $filter->hydrate($query);
        $destinations = $this->getDestinationRepository()->search($filter);
        $this->template->defaults = $query;
        $this->template->count = count($destinations);
        $this->template->results = $destinations;
        $this->redrawControl();
    }

}
