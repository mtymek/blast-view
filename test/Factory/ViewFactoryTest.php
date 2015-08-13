<?php

namespace BlastTest\Factory;

use Blast\View\Module;
use Blast\View\View;
use BlastTest\View\FooHelper;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Model\ViewModel;

class ViewFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    private $serviceManager;

    private function getSmConfig()
    {
        $module = new Module();
        $config = $module->getConfig();
        return $config['service_manager'];
    }

    public function setUp()
    {
        $serviceManager = new ServiceManager(new Config($this->getSmConfig()));

        // dependencies - every external dependency should be mocked here
        $serviceManager->setService('Configuration', []);

        $this->serviceManager = $serviceManager;
    }

    public function testViewFactoryCreatesLayoutIfLayoutTemplateIsConfigured()
    {
        $config = [
            'view_manager' => [
                'layout_template' => 'layout-tpl.phtml',
            ],
        ];
        $this->serviceManager->setAllowOverride(true);
        $this->serviceManager->setService('Configuration', array_merge($this->getSmConfig(), $config));
        $view = $this->serviceManager->get(View::class);
        $this->assertInstanceOf(ViewModel::class, $view->getLayout());
        $this->assertEquals('layout-tpl.phtml', $view->getLayout()->getTemplate());
    }

    public function testViewFactoryAllowsConfiguringViewHelpers()
    {
        $config = [
            'view_manager' => [
                'template_path_stack' => [
                    __DIR__ . '/../view'
                ],
            ],
            'view_helpers' => [
                'invokables' => [
                    'foo' => FooHelper::class,
                ]
            ],
        ];
        $this->serviceManager->setAllowOverride(true);
        $this->serviceManager->setService('Configuration', array_merge($this->getSmConfig(), $config));
        $view = $this->serviceManager->get(View::class);
        $vm = new ViewModel();
        $vm->setTemplate('helper-test.phtml');
        $result = $view->render($vm);
        $this->assertEquals('FOOBAR', $result);
    }
}
