<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="src\css\index.css">
    <title>VERO Digital</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
    <div class="root-container">
        <div class="logic-container">
            <form id="search-form">
                <input type="text" id="search" name="search" placeholder="Enter search term" required>
                <button type="submit" class="info">Search</button>
                <button type="button" id="reset-button" class="reset">Reset</button> <!-- Reset Button -->
            </form>
            <button id="openModalBtn">Open Modal</button>
        </div>


            <!-- Modal Structure -->
        <div id="modalOverlay" class="modal-overlay">
            <div class="modal">
                <div class="modal-header">
                    <h2>Modal</h2>
                    <button type="button" class="close-modal" id="closeModalBtn">Close</button>
                </div>
                <div class="modal-content" id="imagePreviewContainer">
                    
                </div>
                <h2 id="responseMessage" style="display:none"></h2>
                <form class="modal-footer" id="fileUploadForm" enctype="multipart/form-data">
                    <label for="file-upload" class="custom-file-upload">
                        Upload
                    </label>
                    <input id="file-upload" type="file" name="file-upload" accept="image/*" required/>
                    <button id="fileSubmit" type="submit">Submit</button>
                </form>
            </div>
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

    </div>
</body>

    <script src="src\js\tasks.js"></script>

</html>