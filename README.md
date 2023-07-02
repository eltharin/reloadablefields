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
php bin/console eltharinreloadablefield:copyroutefile
```

What is ReloadableFields Bundle?
---------------------------
This bundle will help you to work with reloadbale fields, it will make the ajax query and the backend for reload a form select.

The select will have a new event "reload" witch call an ajax query.

When you modify in other page the data linked to the field you can easiest reload them.


``` yaml
# config/packages/eltharin_reloadable_field.yaml

eltharin_reloadable_field:
  useOwnJsFile: false
```

if you want to use default JS for ajax query, leave useOwnJsFile parameters at false and import eltharin/twigfilesgetter for load unique Js : https://github.com/eltharin/TwigFilesGetter 

by default, it adds a markup in params/after, you need to update your form theme to view it by add : 

``` php
# form theme twig template

{%- for after in params.after -%}
    {{- include(template_from_string(before ?? '')) -}}
{%- endfor -%}
```

and to use template_form_string in twig put in services.yaml : 
``` yaml
services:
  twig.extension.stringloader:
    class: \Twig\Extension\StringLoaderExtension
    tags:
      - { name: twig.extension }
```

ReloadButton HTML
---
By default, the HTML of the ReloadButton use fontAwesome : 

``` php
'<i class="fa-solid fa-rotate fa-2x reloader" data-target="{{ id }}"></i>'
```

For change the ReloadButton HTML for all of your site, you can set in your package configuration file :

``` yaml
eltharin_reloadable_field:
    'reloadButtonHtml': '<span class="btn success reloader" data-target="{{ id }}" />reload</span>'
```

or for change only once, in Type Class : 

``` php
'reloadbtn' => '<span class="btn success reloader" data-target="{{ id }}" />reload</span>',
```

if you want your own route for ajax query you can set endpoint option in Type

``` php
'endpoint' => 'eltharin_reloadablefields_endpoint',
```

this route must have 3 parameters, 
* the FormType classname where the call is make
* the Options passed to entity (be carreful when you pass entity as options, you nedd to manage them for can be used)
* the field to reload

or set attr['data-reload-url'] with the complete route