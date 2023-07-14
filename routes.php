<?php

require_once('utils.php');
require_once('router.php');
require_once('templater.php');

Router::get('/', Router::redirect('/index'));
Router::get('/index', Templater::render('templates/main', 
    function() { ?>
        <h1>Index page!</h1>
        <h3>Pages:</h3>
        <ul>
            <li><a href="/page1">Page 1</a></li>
            <li><a href="/page1/argument">Page 1 with argument</a></li>
            <li><a href="/page1/argument1/argument2">Page 1 with 2 arguments</a></li>
            <li><a href="/page2">Page 2</a></li>
            <li><a href="/page2/argument">Page 2 with argument</a></li>
        </ul>
        <h3>Static content:</h3>
        <ul>
            <li><a href="/static/test.txt">test.txt</a></li>
            <li><a href="/static/cat.jpg">cat.jpg</a></li>
        </ul>
        <hr />
        <p>Sample cat picture:</p>
        <img src="/static/cat.jpg" width="150" alt="Cat picture" />
    <?php },
    'Index page')
);

$page1 = Templater::render('templates/main', function($variable = NULL, $variable2 = NULL) { 
    echo 'Page 1'; 
    if(isset($variable))
        echo ", with argument: $variable";
    if(isset($variable2))
        echo " and argument: $variable2";
}, 'Page 1');

Router::get('/page1', $page1);
Router::get('/page1/$variable', $page1);
Router::get('/page1/$variable/$variable2', $page1);


Router::get('/page2', './pages/page2');
Router::get('/page2/$variable', './pages/page2');

Router::default();
Router::notfound(function() { echo 'Page not found!'; });

