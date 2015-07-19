<?php

namespace Blast\View;

use Zend\View\Model\ModelInterface as Model;
use Zend\View\Model\ViewModel;
use Zend\View\View as ZendView;

class View
{
    /**
     * @var ZendView
     */
    private $zendView;

    /**
     * @var ViewModel
     */
    private $layout;

    /**
     * View constructor.
     * @param ZendView $zendView
     * @param ViewModel $layout
     */
    public function __construct(ZendView $zendView, ViewModel $layout = null)
    {
        $this->zendView = $zendView;
        $this->layout = $layout;
    }

    /**
     * @return ViewModel
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param ViewModel $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Render the provided model.
     *
     * @param Model $model
     * @return string
     */
    public function render(Model $model)
    {
        if ($this->layout) {
            $this->layout->addChild($model);
            $model = $this->layout;
        }

        // hack, force ZendView to return its output instead of triggering an event
        // see: http://mateusztymek.pl/blog/using-standalone-zend-view
        $model->setOption('has_parent', true);
        return $this->zendView->render($model);
    }
}
