<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Input Width</title>
    <style>
        .dynamic-input {
            display: inline-block;
            padding: 5px;
            border: 1px solid #ccc;
            font-family: Arial, sans-serif;
            font-size: 16px;
            transition: width 0.2s; /* Smooth width adjustment */
        }
    </style>
</head>
<body>
    <input type="text" class="dynamic-input" id="dynamic-input" oninput="adjustWidth(this)" value="John Doe">
    
    <script>
        function adjustWidth(input) {
            // Create a canvas context to measure text width
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            
            // Get the computed style of the input element
            const computedStyle = window.getComputedStyle(input);
            
            // Set the canvas context font to match the input element's font
            context.font = computedStyle.font;
            
            // Measure the width of the input's value
            const textWidth = context.measureText(input.value).width;
            
            // Set the input width plus some padding
            input.style.width = `${textWidth + 20}px`; // Adding some padding (20px)
        }

        // Initialize width on page load
        window.onload = () => adjustWidth(document.getElementById('dynamic-input'));
    </script>
</body>
</html>
