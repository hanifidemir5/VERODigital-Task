<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="src\css\index.css">
    <title>VERO Digital</title>

</head>
<body>
    <div>
        <form id="search-form">
            <label for="search">Search:</label>
            <input type="text" id="search" name="search" placeholder="Enter search term" required>
            <button type="submit">Search</button>
            <button type="button" id="reset-button">Reset</button> <!-- Reset Button -->
        </form>
        <button>
            Open Modal
        </button>
    </div>

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
            <!-- Rows will be injected here by JavaScript -->
        </tbody>
    </table>

    <button id="openModal">Select Image</button>

    <div id="modal" style="display: none;">
        <input type="file" id="imageInput" accept="image/*">
        <img id="selectedImage" style="display:none; max-width: 100%;">
        <button id="closeModal">Close</button>
    </div>
</body>

    <script src="src\js\tasks.js"></script>

</html>