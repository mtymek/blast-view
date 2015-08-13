<?php

namespace BlastTest\View;

use Blast\View\Module;
use Blast\View\View;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Model\ViewModel;

class ModuleTest extends PHPUnit_Framework_TestCase
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
        $serviceManager->setService('Configuration', $this->getSmConfig());

        $this->serviceManager = $serviceManager;
    }

    /**
     * Load all available services from module configuration
     * @return array
     */
    public function provideServicesToCheck()
    {
        $config = $this->getSmConfig();
        $services = [];
        $list = array_merge(
            isset($config['invokables'])?$config['invokables']:[],
            isset($config['factories'])?$config['factories']:[]
        );

        foreach ($list as $service => $factory) {
            $services[] = [$service];
        }
        return $services;
    }

    /**
     * Check if all configured services can be created
     *
     * @param $serviceName
     * @dataProvider provideServicesToCheck
     */
    public function testServiceAvailability($serviceName)
    {
        $this->assertTrue($this->serviceManager->has($serviceName));
        $this->assertInstanceOf($serviceName, $this->serviceManager->get($serviceName));
    }
}
