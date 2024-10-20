let originalData = []; // Store the original data

document.addEventListener('DOMContentLoaded', function() {
    // Fetch the JSON data when the page loads
    fetchJsonFile('../data/tasks.json')  // Adjusted path to the data file
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
