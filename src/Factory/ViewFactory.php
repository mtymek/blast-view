<?php

namespace Blast\Factory;

use Blast\View\View;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ViewFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return View
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new View();
    }
}
