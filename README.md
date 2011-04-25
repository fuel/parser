# Parser package

## Usage

```php
// old usage still valid, will load app/views/example.php
View::factory('example');

// load a SimpleTags template, will load and parse app/views/example.stags
View::factory('example.stags');

// load a Twig template, will load and parse app/views/example.twig
View::factory('example.twig');

// load a Dwoo template, ATTENTION: this one expects app/views/example.tpl
View::factory('example.dwoo');
```

## Installing parsers

Only SimpleTags is included. While many other drivers are included, their libraries are not and are by default expected in app/vendor/lib_name (lowercase lib_name), you'll have to download them yourself.

You can configure them to be loaded from other locations by copying the parser.php config file to your app and editing it.

## Config and runtime config

Currently the drivers still lack a lot of config options they should probably accept. They are currently all configured to work with one instance of their parser library, which is available to config:

```php
$view = View::factory('example.stags');
$view->parser()->set_delimiters('{', '}');
```