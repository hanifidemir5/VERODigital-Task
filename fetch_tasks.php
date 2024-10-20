<?php

require "functions.php";

header('Content-Type: application/json');

// Assuming your data is stored in 'data/tasks.json'
$searchTerm = isset($_GET['search']) ? strtolower($_GET['search']) : '';

// Load the data from the JSON file
$data = fetchAndSaveData(); // Adjust this to your data fetching logic

$jsonFilePath = 'data/tasks.json';

$dataFileContent = file_get_contents($jsonFilePath);

$data = json_decode($dataFileContent, true);

// Filter the data based on the search term
foreach ($data as $task) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($task['task']) . "</td>";
    echo "<td>" . htmlspecialchars($task['title']) . "</td>";
    echo "<td>" . htmlspecialchars($task['description']) . "</td>";
    echo "<td style='background-color:" . htmlspecialchars($task['colorCode']) . "'></td>";
    echo "</tr>";
}