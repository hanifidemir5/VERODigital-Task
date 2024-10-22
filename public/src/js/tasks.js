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

    
    checkForUpdates();
    
    setInterval(checkForUpdates, 1 * 60 * 1000);
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
        })}})
        .catch(error => console.error('Error checking for updates:', error));
}