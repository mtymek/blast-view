Blast\View
==========

Standalone template renderer using templating engine from Zend Framework 2 - `Zend\View`.

You can use it to render PHTML files outside the context of ZF2 MVC, but with all `Zend\View` features: nested 
templates, view helpers, etc.

Example use cases:

- generating HTML from command line applications
- rendering HTML e-mails
- using `Zend\View` without other Zend Framework components

[![Build Status](https://travis-ci.org/mtymek/blast-view.svg?branch=master)](https://travis-ci.org/mtymek/blast-view)
[![Coverage Status](https://coveralls.io/repos/mtymek/blast-view/badge.svg?branch=master&service=github)](https://coveralls.io/github/mtymek/blast-view?branch=master)


Installation
------------

1. Install package using composer:

    ```bash
    $ composer require mtymek/blast-view ~0.5
    ```

2. Add `Blast\View` to module list in your system configuration (`config/application.config.php` file).
  
Configuration
-------------

`Blast\View` uses configuration keys similar to regular Zend application. Currently you can configure two
 options:

* location to view templates (`template_path_stack` key)
* default layout (`layout_template` key)

```php
return [
    'view_manager' => [
        'layout_template' => 'layout/layout.phtml',
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
```
  
Usage
-----

Once installed, `Blast\View` module will register new service: `Blast\View\View` in application's main
`ServiceManager`. It can be easily pulled from there to render templates outside of ZF2 MVC:  

```php
use Zend\View\Model\ViewModel;
use Blast\View\View;

$viewModel = new ViewModel(
    [
        'name' => 'Mat'
    ]
);
$viewModel->setTemplate('index.phtml');

$view = $serviceManager->get(View::class);
echo $view->render($viewModel);

```

### Layouts

Layout is just a parent `ViewModel` wrapping your main view script. It should be injected using `setLayout()` method
before rendering main template:

```php
$layout = new ViewModel();
$layout->setTemplate('layout/layout.phtml');
$view->setLayout($layout);

$viewModel = new ViewModel();
$viewModel->setTemplate('index.phtml');
echo $view->render($viewModel);
```

Layout template has the same structure as in ZF2:

```html
<html>
<head>
</head>
<body>
    <?php echo $this->content ?>
</body>
</html>
```

### View Helpers

`Blast\View` supports custom view helpers via `view_helpers` configuration key, in the same
manner as ZF2 does. Here's an example config that registers `foo` view helper:  

```php
return [
    'view_helpers' => [
        'invokables' => [
            'foo' => FooHelper::class,
        ]
    ],
];

```
