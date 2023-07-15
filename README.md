# phpRouter - Router + Templater

phpRouter is a simple router and template system.

## Router

Routes are defined as function calls and can take up to two arguments:

1.  Route path, with or without variables
2.  Target function or PHP file

### Route variables

A route may contain placeholder fields that are exported as variables when the route is accessed.

For example, we have a route: `/route/$variable`. When we access the route from a web browser, like `/route/text`, the `$variable` variable will take the `"text"` value.

### Available routers:

-   `Router::default()` - provides access to static content
-   `Router::all($route, $target)` - routes all requests
-   `Router::get($route, $target)` - routes GET requests only
-   `Router::post($route, $target)` - routes POST requests only
-   `Router::notfound($target)` - 404 route, if no other routes match

#### Default route

This route must be added before the `notfound` route in order to provide access to static content (stored in the `/static` folder).

### Router Examples:

```php
// Route pointing to a PHP file
Router::get('/path1', 'script.php');

// Route with a content function
Router::get('/path2', function() { ?>
    <p>HTML code here</p>
<?php });

// Route with a variable and content function
Router::get('/path3/$variable', function($variable) { ?>
    <p>Variable value: <?= $variable ?></p>
<?php });

// Static content provider
Router::default();
```

## Templater

Templater is a simple template system.

### Template file

The template file is a PHP file where two macros can be inserted:

-   Page title: `<?= $Templater_title ?>`
-   Page content: `<?php $Templater_content(); ?>`

### Using the template

To fill the template with content we use the render function:

```php
Templater::render($template_path, $insert_content, $title)
```

-   `$template_path` - path to template file
-   `$insert_content` - path to PHP content or a content function
-   `$title` - page title (optional)

### Templater Examples:

```php
// Route with page template and title
Router::get('/path2',
    Templater::render(
        '/path/to/template',
        function() { ?>
            <p>HTML code here</p>
        <?php },
        'Page Title'
    )
);

// Route with page template and a variable
Router::get('/path3/$variable',
    Templater::render(
        '/path/to/template',
        function($variable) { ?>
            <p>Variable value: <?= $variable ?></p>
        <?php }
    )
);
```

## Running

For development purposes, the `routes.php` script can be started in development server mode with the command:

```
php -S localhost:8000 routes.php
```

## Sample files

The repository comes already fitted with sample files.
