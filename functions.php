<?php

function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo"<pre/>";

    die();
}

function urlIs($value){
    return $_SERVER["REQUEST_URI"] == $value;
}
//  function run(string $url, array $routes):void{
//     $uri = parse_url($url);
//     $path = $uri["path"];
//     if(false === array_key_exists($path, $routes)){
//         return;
//     }
//     $callback = $routes[$path];
//     $callback();
//  }