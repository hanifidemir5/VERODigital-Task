<?php
    $uri = parse_url($_SERVER['REQUEST_URI'])['path'];

    echo $uri;

    $routes = [
        '/VERODigital-Task/' => 'controllers/index.php',
        '/VERODigital-Task/list' => 'controllers/list.php',
        '/VERODigital-Task/details' => 'conrollers/details.php',
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