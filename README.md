# Parser package

## Usage

    // old usage still valid, will load app/views/example.php
    View::factory('example');

    // load a SimpleTags template, will load and parse app/views/example.stags
    View::factory('example.stags');

    // load a Twig template, will load and parse app/views/example.twig
    View::factory('example.twig');

    // load a SimpleTags template, ATTENTION: this one expects app/views/example.tpl
    View::factory('example.dwoo');