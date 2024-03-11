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



What is ReloadableFields Bundle?
---------------------------
This bundle will help you to work with reloadbale fields, it will make the ajax query and the backend for reload a form select.

The select will have a new event "reload" witch call an ajax query.

When you modify in other page the data linked to the field you can easiest reload them.


This library use junior.js, my small library for manage events, http request and other. 

you can download it like this : 

You nedd to have the JuniorJs suite for use this bundle :

``` bash
composer require eltharin/juniorjs
```


Or you can use your own script by settings this configuration :

``` yaml
# config/packages/eltharin_reloadable_field.yaml

eltharin_reloadable_field:
  useOwnJsFile: true
```

if you want to use default JS for ajax query, leave useOwnJsFile parameters at false and import eltharin/twigfilesgetter for load unique Js : https://github.com/eltharin/TwigFilesGetter 

by default, it adds a markup in params/after, you need to update your form theme to view it by add : 

``` php
# form theme twig template

{%- block form_row -%}

...

{%- for after in params.after -%}
    {{ block(after[0]) }}
{%- endfor -%}

...

{%- endblock form_row -%}

```


ReloadButton HTML
---
By default, the HTML of the ReloadButton use fontAwesome : 

``` php
'<i class="fa-solid fa-rotate fa-2x reloader" data-target="{{ id }}"></i>'
```

For change the ReloadButton HTML for all of your site, you can create a form template with a block named reloadablefield_reload_button :

``` php
{%- block reloadablefield_reload_button -%}
	<i class="fa-solid fa-rotate fa-2x reload_button" data-target="{{ id }}"></i>
{%- endblock reloadablefield_reload_button -%}
```

or for change only once, in Type Class : 

``` php
'reloadbtn' => '<span class="btn success reload_button" data-target="{{ id }}" />reload</span>',
```
Don't forget to add reload_button class for use default js.



Bonus
---

How add a "add" button for launch a form popup and reload automaticly the select : 

1- in Type Class : add params :
``` php
'params' => ['after' => ['<i class="fa-regular fa-square-plus openpopup  fa-2x addAndReload" data-target="{{ id }}" data-formfield="type[libelle]" href="/gestion/type/new" ></i>']]
```

2- with openpopup class, form will be called in ajax and print in a popup

3- by default automaticly, it add an event "onFormSubmitSuccess" executed after form submit : 

``` js
JR.events.add('onFormSubmitSuccess','.addAndReload',  function(event)
{
    var textToSearch = "";
    if(this.dataset.formfield !== undefined)
    {
        textToSearch = event.detail.formData.get(this.dataset.formfield) || "";
    }

    JR.events.dispatch('reload', '#' + this.dataset.target, {
        "detail": {
            onReload : function (select,httprequest) {
                if(textToSearch != "")
                {
                    const optionToSelect = Array.from(select.options).find(item => item.text === textToSearch);
                    optionToSelect.selected = true;
                }
            }
        }
    });
});
```
