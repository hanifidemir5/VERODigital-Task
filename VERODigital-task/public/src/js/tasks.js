let originalData = []; // Store the original data

document.addEventListener('DOMContentLoaded', function() {
    // Fetch the JSON data when the page loads
    fetchJsonFile('src/data/tasks.json')  // Adjusted path to the data file
        .then(data => {
            originalData = data; // Save original data
            updateTable(data);    // Update the table with original data
        });

    // Form submission handler for search
    document.getElementById('search-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const searchTerm = document.getElementById('search').value;
        const filteredData = filterData(originalData, searchTerm);
        updateTable(filteredData);
    });

    // Reset button functionality to restore original data
    const resetButton = document.getElementById('reset-button');
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            updateTable(originalData); // Reset table to the original data
        });
    }

    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalContent = document.getElementById('imagePreviewContainer');
    const responseMessage = document.getElementById('responseMessage');
    const maxSize = 8 * 1024 * 1024; // 8 MB in bytes
    const fileSubmit = document.getElementById('fileSubmit')
    // Open the modal
    openModalBtn.addEventListener('click', function() {
        fetch('/check-image')
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    // Display the image if it exists
                    modalContent.innerHTML = '<img src="' + data.url + '" alt="Selected Image" style="max-width:350px; max-height:600px;">';
                } else {
                    // Display the message if the image doesn't exist
                    modalContent.innerHTML = data.message;
                }
            })
            .catch(error => {
                console.error('Error:', error.message);
                modalContent.innerHTML = 'An error occurred while checking the image.';
            });
        modalOverlay.classList.add('open');
    });

    // Close the modal from header button
    closeModalBtn.addEventListener('click', function() {
        modalOverlay.classList.remove('open');
    });

    // Close the modal if the user clicks outside the modal
    modalOverlay.addEventListener('click', function(event) {
        if (event.target === modalOverlay) {
            modalOverlay.classList.remove('open');
        }
    });

    const fileUploadForm = document.getElementById('fileUploadForm');
    const fileInput = document.getElementById('file-upload');

    function checkFileInput() {
        const file = fileInput.files[0]; // Get the first selected file

        if (fileInput.files.length > 0) {
            if (file.size > maxSize) { // Check if the file size exceeds the limit
                responseMessage.innerHTML = 'The file size exceeds the limit of 8 MB.';
                responseMessage.style.color = '#d33'; // Set color for error
                responseMessage.style.display = "block"; // Make sure the message is visible
                fileSubmit.disabled = true; // Disable submit button if file size exceeds limit
                fileSubmit.classList.add('disabled'); // Add disabled class
                fileSubmit.classList.remove('abled'); // Remove abled class if it exists
                return false;
            }
            else{
                responseMessage.innerHTML = 'File is within the size limit.'; // Update message for valid file
                responseMessage.style.color = '#04AA6D'; // Set color for success
                responseMessage.style.display = "block"; // Make sure the message is visible
                fileSubmit.disabled = false; // Enable submit button if file size is within limit
                fileSubmit.classList.remove('disabled'); // Remove disabled class
                fileSubmit.classList.add('abled'); // Add abled class
                return true;
            }

        } else {
            responseMessage.innerHTML = 'Please select an image file.'; // Update message for no file
            responseMessage.style.color = '#d33'; // Set color for error
            responseMessage.style.display = "block"; // Make sure the message is visible
            fileSubmit.disabled = true; // Disable submit button if no file is selected
            fileSubmit.classList.add('disabled'); // Add disabled class
            fileSubmit.classList.remove('abled'); // Remove abled class
            return false;
        }
    }

    // Show image preview when a file is selected
    fileInput.addEventListener('change', function(event) {
        checkFileInput();
        const file = event.target.files[0]; // Get the selected file
        if (file) {
            const reader = new FileReader(); // Create a FileReader object
            reader.onload = function(e) {
                // Display the image in the preview container
                imagePreviewContainer.innerHTML = '<img src="' + e.target.result + '" alt="Selected Image" style="max-width:350px; max-height:600px;">';
            }
            reader.readAsDataURL(file); // Read the file as a data URL
        }
    });

    // Prevent the default form submission
    fileUploadForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent page reload

        // Handle file upload here (e.g., using FormData and AJAX)
       
        const fileInput = document.getElementById('file-upload');
        const file = fileInput.files[0]; // Get the first selected file

        if (!checkFileInput()) {
            return
        }
        else {
            const formData = new FormData();
            const file = fileInput.files[0];
            formData.append('file-upload', file);

            fetch('/upload', { // Specify the PHP script to handle the upload
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Handle success response from the server
                fileInput.value = ''; // Clear the file input
                checkFileInput()
                responseMessage.innerHTML =  data
                responseMessage.style.display = "block"
                responseMessage.style.color = "#04AA6D"
            })
            .catch(error => {
                console.error('Error:', error);
                responseMessage.innerHTML = error
                responseMessage.style.display = "block"
                responseMessage.style.color = "$d33"
            });
        }

    });

    function startHourlyUpdates() {
        const now = new Date();
        const nextUpdate = new Date();
        nextUpdate.setHours(now.getHours() + 1); 
        nextUpdate.setMinutes(1); 
        nextUpdate.setSeconds(0); 
        nextUpdate.setMilliseconds(0);
        const delay = nextUpdate - now;

        setTimeout(function() {
            checkForUpdates();
            setInterval(checkForUpdates, 60 * 60 * 1000);
        }, delay);
    }
    
    checkFileInput();
    
    startHourlyUpdates();
});

// Function to fetch and decode JSON data
function fetchJsonFile(filePath) {
    return fetch(filePath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Failed to load file: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => console.error('Error fetching or parsing data:', error));
}

// Function to filter data based on the search term
function filterData(data, searchTerm) {
    return data.filter(task =>
        task.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
        task.description.toLowerCase().includes(searchTerm.toLowerCase())
    );
}

// Function to update the table with the fetched or filtered data
function updateTable(data) {
    const tasksBody = document.getElementById('tasks-body');
    tasksBody.innerHTML = ''; // Clear existing rows

    if (data.length === 0) {
        tasksBody.innerHTML = '<tr><td colspan="4">No results found.</td></tr>';
        return;
    }

    data.forEach(task => {
        const row = document.createElement('tr');

        const taskCell = document.createElement('td');
        taskCell.textContent = task.task;
        row.appendChild(taskCell);

        const titleCell = document.createElement('td');
        titleCell.textContent = task.title;
        row.appendChild(titleCell);

        const descriptionCell = document.createElement('td');
        descriptionCell.textContent = task.description;
        row.appendChild(descriptionCell);

        const colorCell = document.createElement('td');
        colorCell.style.backgroundColor = task.colorCode;
        row.appendChild(colorCell);

        tasksBody.appendChild(row);
    });
}

function checkForUpdates() {
    fetch('/check-updates') // Adjust the URL as needed
    .then(response => response.json())
    .then(data => {
        const lastModified = new Date(data.last_modified * 1000); // Convert to milliseconds
        const now = new Date();

        // Check if the file was updated within the last hour
        if (lastModified > new Date(now.getTime() - (60 * 60 * 1000))) {
            fetchJsonFile('src/data/tasks.json')  // Adjusted path to the data file
            .then(data => {
                originalData = data; // Save original data
                updateTable(data);    // Update the table with original data
            });
        }
    })
    .catch(error => console.error('Error checking for updates:', error));
}
