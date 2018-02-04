<?php


namespace App\User\Controls\Filter;


use App\User\Controls\Base\BaseControl;

/**
 * Class FilterControl
 * @package App\User\Controls\Filter
 * @author Josef Banya
 */
class FilterControl extends BaseControl
{
    /** @var string  */
    public $templateFile = 'filter';

    /**
     * Render
     */
    public function render()
    {
        parent::render();
        $this->getTemplate()->render();
    }
}