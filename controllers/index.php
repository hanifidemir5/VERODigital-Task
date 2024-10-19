<?php

$heading = "home";

function searchTasks($searchTerm, $data) {
    // Filter results based on the search term
    return array_filter($data, function($item) use ($searchTerm) {
        $searchTerm = strtolower($searchTerm); // Make search case-insensitive
        return (
            strpos(strtolower($item['task']), $searchTerm) !== false ||
            strpos(strtolower($item['title']), $searchTerm) !== false ||
            strpos(strtolower($item['description']), $searchTerm) !== false ||
            strpos(strtolower($item['colorCode']), $searchTerm) !== false
        );
    });
}


// function filter($datas, $fn)
// {
//     $filteredData = [];

//     foreach($datas as  $data) {
//         if ($fn($data)) {
//             $filteredData[] = $data;
//         }
//     }

//     return $filteredData;
// }

// $filtered = filter($decoded_data,  function ($data) {
//     return $data["BusinessUnitKey"] == null;
// });


$decoded_data = fetchAndSaveData();

require "views/index.view.php";