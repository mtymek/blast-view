<?php

namespace Blast\View\Factory;

use Blast\View\View;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;
use Zend\View\View as ZendView;
use Zend\View\ViewEvent;

class ViewFactory implements FactoryInterface
{
    private function createZendView(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');

        if (isset($config['view_manager'], $config['view_manager']['template_path_stack'])) {
            $templatePaths = $config['view_manager']['template_path_stack'];
        } else {
            $templatePaths = [];
        }
        $resolver = new TemplatePathStack(
            [
                'script_paths' => $templatePaths,
            ]
        );
        $phpRenderer = new PhpRenderer();
        $phpRenderer->setResolver($resolver);
        $zendView = new ZendView;
        $zendView->getEventManager()
            ->attach(ViewEvent::EVENT_RENDERER, function () use ($phpRenderer) {
                return $phpRenderer;
            });

        // view helpers?
        if (isset($config['view_helpers'])) {
            $helperManagerConfig = new Config($config['view_helpers']);
            $phpRenderer->setHelperPluginManager(new HelperPluginManager($helperManagerConfig));
        }

        return $zendView;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return View
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');

        if (isset($config['view_manager']['layout_template'])) {
            $layout = new ViewModel();
            $layout->setTemplate($config['view_manager']['layout_template']);
        } else {
            $layout = null;
        }

        $view = new View($this->createZendView($serviceLocator), $layout);

        return $view;
    }
}
