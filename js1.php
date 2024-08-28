<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Input Fields</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .input-container {
            margin-top: 20px; /* Space between the button and input fields */
        }
        .task-time-container {
            margin-bottom: 20px; /* Space between each task-time pair */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Dynamic Task and Time Fields</h1>
        
        <div id="input-container" class="input-container">
            <!-- Predefined input fields -->
            <div class="row task-time-container">
                <div class="col-md-6">
                    <div class="mb-3 col">
                        <label for="task-1" class="form-label">Task</label>
                        <input type="text" name="task" class="form-control" id="task-1" placeholder="Enter Your Task" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3 col">
                        <label for="time-1" class="form-label">Time</label>
                        <input type="text" name="time" class="form-control" id="time-1" placeholder="Enter Your Time" required>
                    </div>
                </div>
            </div>
        </div>
        
        <button id="add-input-btn" class="btn btn-primary">Add Task and Time</button>
    </div>

    <script>
        let taskCount = 1;

        // Function to add a new task-time pair
        function addTaskTimeFields() {
            taskCount++; // Increment task count

            // Get the container where the input fields will be added
            const container = document.getElementById('input-container');
            
            // Create a new task-time container
            const newTaskTimeContainer = document.createElement('div');
            newTaskTimeContainer.className = 'row task-time-container';
            
            // Create Task input field
            const taskDiv = document.createElement('div');
            taskDiv.className = 'col-md-6';
            taskDiv.innerHTML = `
                <div class="mb-3 col">
                    <label for="task-${taskCount}" class="form-label">Task</label>
                    <input type="text" name="task-${taskCount}" class="form-control" id="task-${taskCount}" placeholder="Enter Your Task" required>
                </div>
            `;
            
            // Create Time input field
            const timeDiv = document.createElement('div');
            timeDiv.className = 'col-md-6';
            timeDiv.innerHTML = `
                <div class="mb-3 col">
                    <label for="time-${taskCount}" class="form-label">Time</label>
                    <input type="text" name="time" class="form-control" id="time-${taskCount}" placeholder="Enter Your Time" required>
                </div>
            `;

            // Append Task and Time fields to the new container
            newTaskTimeContainer.appendChild(taskDiv);
            newTaskTimeContainer.appendChild(timeDiv);

            // Append the new container to the main container
            container.appendChild(newTaskTimeContainer);
        }

        // Attach the function to the button click event
        document.getElementById('add-input-btn').addEventListener('click', addTaskTimeFields);
    </script>
</body>
</html>
