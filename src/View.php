<?php

namespace Blast\View;

use Zend\View\Model\ModelInterface as Model;
use Zend\View\View as ZendView;

class View
{
    /**
     * @var ZendView
     */
    private $zendView;

    /**
     * View constructor.
     * @param ZendView $zendView
     */
    public function __construct(ZendView $zendView)
    {
        $this->zendView = $zendView;
    }

    /**
     * Render the provided model.
     *
     * @param Model $model
     * @return string
     */
    public function render(Model $model)
    {
        // hack, force ZendView to return its output instead of triggering an event
        // see: http://mateusztymek.pl/blog/using-standalone-zend-view
        $model->setOption('has_parent', true);
        return $this->zendView->render($model);
    }
}
