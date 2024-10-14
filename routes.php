<?php
// Define your routes
return [
    '/' => function(){
        echo "index page";
        // include "views/list.php";
    },
    '/details' => function (){
        // include 'details.php';
        echo "details page";
    }
];
?>
