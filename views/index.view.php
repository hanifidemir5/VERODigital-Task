<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/index.css">

</head>
<body>
<form id="search-form" onsubmit="submitSearch(event)">
    <label for="search">Search:</label>
    <input type="text" id="search" name="search" placeholder="Enter search term" required>
    <button type="submit">Search</button>
</form>


<table id="table-container">
    <thead>
        <tr>
            <th>Task</th>
            <th>Title</th>
            <th>Description</th>
            <th>Color Code</th>
        </tr>
    </thead>
    <tbody id="tasks-body">
        
    </tbody>
    <?php
    $filtered_data = $decoded_data; // Default to all data

    // Check if search term exists
    if (isset($_GET['search'])) {
        // Get search term from the user input
        $searchTerm = $_GET['search'];

        // Call the function to filter results
        $filtered_data = searchTasks($searchTerm, $decoded_data);
    }

    if (!empty($filtered_data)) {
        foreach ($filtered_data as $data) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($data['task']) . "</td>";
            echo "<td>" . htmlspecialchars($data['title']) . "</td>";
            echo "<td>" . htmlspecialchars($data['description']) . "</td>";
            // echo "<td style='background-color: " . htmlspecialchars($data['colorCode']) . ";'>" . htmlspecialchars($data['colorCode']) . "</td>";
            echo "<td style='background-color: " . htmlspecialchars($data['colorCode']) . ";'></td>";
            echo "</tr>";
        }
    } else {
        // If no results found, show a message
        echo "<tr><td colspan='4'>No results found for '" . htmlspecialchars($searchTerm) . "'.</td></tr>";
    }
    ?>
</table>

<button id="openModal">Select Image</button>

<div id="modal" style="display: none;">
    <input type="file" id="imageInput" accept="image/*">
    <img id="selectedImage" style="display:none; max-width: 100%;">
    <button id="closeModal">Close</button>
</div>

</body>
<script src="script.js"></script>

</html>