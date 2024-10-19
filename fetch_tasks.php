<?php
header('Content-Type: application/json');

// Assuming your data is stored in 'data/tasks.json'
$searchTerm = isset($_GET['search']) ? strtolower($_GET['search']) : '';

// Load the data from the JSON file
$data = fetchAndSaveData(); // Adjust this to your data fetching logic

// Filter the data based on the search term
if($searchTerm)
{
    $filteredData = array_filter($data, function ($task) use ($searchTerm) {
        return (
            strpos(strtolower($task['task']), $searchTerm) !== false ||
            strpos(strtolower($task['title']), $searchTerm) !== false ||
            strpos(strtolower($task['description']), $searchTerm) !== false
        );
    });
}
else{
    return $data;
}


// Return the filtered data as JSON
echo json_encode($filteredData);
