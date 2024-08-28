<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toggle Button</title>
</head>
<body>
    <button id="toggleButton">Click Me</button>
    <p id="display"></p>
    <!-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            const button = document.getElementById('toggleButton');
            const display = document.getElementById('display');
            
            let isZero = true; // Initial state

            button.addEventListener('click', () => {
                // Append a new line with '0' or '1'
                if (isZero) {
                    display.textContent += '0\n';
                } else {
                    display.textContent += '1\n';
                }
                isZero = !isZero; // Toggle the state
            });
        });
    </script> -->
     <script>
        document.addEventListener('DOMContentLoaded', () => {
            const button = document.getElementById('toggleButton');
            const display = document.getElementById('display');
            
            let counter = 0; // Initial number

            button.addEventListener('click', () => {
                // Append the current number followed by a newline
                display.textContent += counter + '\n';
                counter++; // Increment the number for the next click
            });
        });
    </script>
</body>
</html>
