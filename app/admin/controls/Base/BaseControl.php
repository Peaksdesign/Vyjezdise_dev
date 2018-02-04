<?php


namespace App\Admin\Controls\Base;


use Nette\Application\UI\Control;

/**
 * Class BaseControl
 * @package App\Controls\Base
 * @author  Josef Banya
 */
abstract class BaseControl extends Control
{
    /** @var null|string $templateFile */
    public $templateFile = NULL;

    /**
     * Set template file, use parent::render() in others controls.
     */
    public function render()
    {
        $this->getTemplate()->setFile(
            __DIR__ . '/../../templates/controls/' .

            str_replace(
                $this->getReflection()->getShortName(),
                '',
                str_replace(
                    '\\', // remove back slashes
                    '/', // replace with /
                    substr(
                        $this->getClassName(),
                        1)
                )
            ) . $this->getTemplateFile() . '.latte'
        );
    }

    /**
     * @return null|string
     */
    public function getTemplateFile()
    {
        return $this->templateFile;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        $path = explode('App\Admin\Controls', get_class($this));
        return array_pop($path);
    }
}