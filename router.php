<?php
    $uri = parse_url($_SERVER['REQUEST_URI'])['path'];

    $routes = [
        '/' => 'controllers/index.php',
        '/list' => 'controllers/list.php',
        '/details' => 'conrollers/details.php',
    ];


    function routeToController($uri, $routes)
    {
        if (array_key_exists($uri, $routes)) {
            require $routes[$uri];
        }
        else {
            abort(404);
        }
    }

        function abort($code = 404)
    {
        http_response_code($code);

        require 'views/{#code}.php';

        die();
    }

    routeToController( $uri, $routes );