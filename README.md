# Parser package

## Installing package

Currently only available as download or clone from Github.
Like any other package it must be put in its own 'parser' dir in the packages dir and added to your app/config/config.php as an always loaded package.

*Important:*
This package needs a recent version from the core develop branch.
RC2 and RC2.1 do not yet support this package.

## Installing parsers

Only SimpleTags parser is included, for other libraries you'll have to download them yourself and put library in `app/vendor/lib_name` (**capitalized lib_name**).

You can configure them to be loaded from other locations by copying the parser.php config file to your app and editing it.

## Usage

```php
// old usage still valid, will load app/views/example.php
View::factory('example');

// load a SimpleTags template, will load and parse app/views/example.stags
View::factory('example.stags');

// load a Mustache template, will load and parse app/views/example.mustache
View::factory('example.mustache');

// load a Twig template, will load and parse app/views/example.twig
View::factory('example.twig');

// load a Dwoo template, ATTENTION: this one expects app/views/example.tpl
View::factory('example.dwoo');
```

## Config and runtime config

Currently the drivers still lack a lot of config options they should probably accept.
They are currently all configured to work with one instance of their parser library, which is available to config:

```php
$view = View::factory('example.stags');
$view->parser()->set_delimiters('{', '}');
```