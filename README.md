Symfony ReloadableFields Bundle
==========================

[![Latest Stable Version](http://poser.pugx.org/eltharin/reloadablefield/v)](https://packagist.org/packages/eltharin/reloadablefield) 
[![Total Downloads](http://poser.pugx.org/eltharin/reloadablefield/downloads)](https://packagist.org/packages/eltharin/reloadablefield) 
[![Latest Unstable Version](http://poser.pugx.org/eltharin/reloadablefield/v/unstable)](https://packagist.org/packages/eltharin/reloadablefield) 
[![License](http://poser.pugx.org/eltharin/reloadablefield/license)](https://packagist.org/packages/eltharin/reloadablefield)

Installation
------------

* Require the bundle with composer:

``` bash
composer require eltharin/reloadablefield
```

* Copy the route file :

``` bash
php bin/console make:eltharin_reloadfields:route
```

What is ReloadableFields Bundle?
---------------------------
This bundle will help you to work with reloadbale fields, it will make the ajax query and the backend for reload a form select.

The select will have a new event "reload" witch call an ajax query.

When you modify in other page the data linked to the field you can easiest reload them.


``` yaml
# config/packages/eltharin_reloadable_field.yaml

eltharin_reloadable_field:
  endpoint: 'eltharin_reloadablefields_endpoint'
  showbtn: \Eltharin\ReloadableFieldBundle\Service\ButtonPrinter::class . '::showButton'
```

endpoint parameter is the route name for update the field, it take 3 arguments:
* the FormType classname where the call is make
* the Entity Class name
* the field to reload

by default, the route is 'eltharin_reloadablefields_endpoint' and point to Eltharin\ReloadableFieldBundle\Controller\ReloadFieldController::reload (with the route file copied in config/routes)

showbtn parameter is a static function called by ReloadableEnityType to add a button for call the reload event associate to the field.

by default, it adds a markup in params/after, you need to update your form theme to view it by add : 

``` php
# form theme twig template

{%- for after in params.after -%}
    {{- after.content ?? '' -}}
{%- endfor -%}
```