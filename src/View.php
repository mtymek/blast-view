<?php

namespace Blast\View;

use Zend\View\Model\ModelInterface as Model;
use Zend\View\View as ZendView;

class View extends ZendView
{
    /**
     * Render the provided model.
     *
     * @return string
     */
    public function render(Model $model)
    {
        // hack, force ZendView to return its output instead of triggering an event
        $model->setOption('has_parent', true);
        return parent::render($model);
    }
}
