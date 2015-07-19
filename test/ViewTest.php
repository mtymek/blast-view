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
        $zendView = $this->prophesize(ZendView::class);
        $zendView->render($viewModel)->willReturn('OUTPUT');
        $view = new View($zendView->reveal());

        $output = $view->render($viewModel);
        $this->assertEquals('OUTPUT', $output);
    }
}
