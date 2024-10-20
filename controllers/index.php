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

$data_file = './data/tasks.json';

$data_content = file_get_contents($data_file);
$decoded_data = json_decode($data_content, true);

if (isset($_GET['action']) && $_GET['action'] == 'fetch_data') {
    header('Content-Type: application/json');
    echo json_encode($decoded_data);
    exit;
}

require "views/index.view.php";