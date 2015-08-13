<?php

namespace BlastTest\View;

use Zend\View\Helper\AbstractHelper;

class FooHelper extends AbstractHelper
{
    public function __invoke()
    {
        return 'FOOBAR';
    }
}
