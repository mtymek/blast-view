<?php

namespace Blast\View\Factory;

use Blast\View\View;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;
use Zend\View\ViewEvent;
use Zend\View\View as ZendView;

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
        $config = $serviceLocator->get('Configuration');

        if (isset($config['view_manager'], $config['view_manager']['template_path_stack'])) {
            $templatePaths = $config['view_manager']['template_path_stack'];
        } else {
            $templatePaths = [];
        }
        $resolver = new TemplatePathStack([
            'script_paths' => $templatePaths,
        ]);
        $phpRenderer = new PhpRenderer();
        $phpRenderer->setResolver($resolver);

        $zendView = new ZendView;
        $zendView->getEventManager()
            ->attach(ViewEvent::EVENT_RENDERER, function () use ($phpRenderer) {
                return $phpRenderer;
            });

        $view = new View($zendView);

        return $view;
    }
}
