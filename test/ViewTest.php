<?php

namespace BlastTest\View;

use Blast\View\View;
use PHPUnit_Framework_TestCase;
use Prophecy\Argument;
use Zend\View\Model\ViewModel;
use Zend\View\View as ZendView;

class ViewTest extends PHPUnit_Framework_TestCase
{
    public function testRenderPassesViewModelToUnderlyingZendView()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('index.phtml');
        $zendView = $this->prophesize(ZendView::class);
        $zendView->render(Argument::that(function ($viewModel) {
            $this->assertEquals('index.phtml', $viewModel->getTemplate());
            return true;
        }))->willReturn('OUTPUT');
        $view = new View($zendView->reveal());

        $output = $view->render($viewModel);
        $this->assertEquals('OUTPUT', $output);
    }

    public function testViewCanRenderLayout()
    {
        $layout = new ViewModel();
        $layout->setTemplate('layout.phtml');

        $viewModel = new ViewModel();
        $viewModel->setTemplate('index.phtml');
        $zendView = $this->prophesize(ZendView::class);
        $zendView->render(Argument::that(function ($viewModel) {
            $this->assertEquals('layout.phtml', $viewModel->getTemplate());
            return true;
        }))->willReturn('OUTPUT');
        $view = new View($zendView->reveal(), $layout);

        $output = $view->render($viewModel);
        $this->assertEquals('OUTPUT', $output);
    }

    public function testLayoutCanBeInjectedWithSetter()
    {
        $layout = new ViewModel();
        $layout->setTemplate('layout.phtml');

        $viewModel = new ViewModel();
        $viewModel->setTemplate('index.phtml');
        $zendView = $this->prophesize(ZendView::class);
        $zendView->render(Argument::that(function ($viewModel) {
            $this->assertEquals('layout.phtml', $viewModel->getTemplate());
            return true;
        }))->willReturn('OUTPUT');
        $view = new View($zendView->reveal());
        $view->setLayout($layout);

        $output = $view->render($viewModel);
        $this->assertEquals('OUTPUT', $output);
    }

    public function testLayoutCanBeAccessedUsingGetter()
    {
        $zendView = $this->prophesize(ZendView::class);
        $view = new View($zendView->reveal());
        $layout = new ViewModel();
        $view->setLayout($layout);
        $this->assertSame($layout, $view->getLayout());
    }
}
