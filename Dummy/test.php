<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Highlight Button</title>
    <style>
        /* Overlay to dim the screen */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
            z-index: 10;
            pointer-events: none; /* Allow clicks to pass through */
        }

        /* Highlighted area around Button 1 */
        #highlight {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.0); /* Fully transparent */
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5); /* Create the overlay effect */
            pointer-events: none; /* Allow clicks to pass through */
        }

        /* Ensure Button 1 is above the overlay */
        #button-1 {
            position: relative;
            z-index: 20; /* Higher than the overlay */
        }
    </style>
</head>
<body>
    <div id="overlay"></div>
    <button id="button-1">Button 1</button>
    <button id="button-2">Button 2</button>
    <button id="button-3">Button 3</button>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const button1 = document.getElementById("button-1");
            const overlay = document.getElementById("overlay");

            // Create the highlight effect
            const highlight = document.createElement("div");
            highlight.id = "highlight";
            document.body.appendChild(highlight);

            function updateHighlight() {
                const rect = button1.getBoundingClientRect();
                highlight.style.width = `${rect.width}px`;
                highlight.style.height = `${rect.height}px`;
                highlight.style.top = `${rect.top + window.scrollY}px`;
                highlight.style.left = `${rect.left + window.scrollX}px`;
            }

            // Initial highlight update
            updateHighlight();

            // Update highlight on window resize or scroll
            window.addEventListener("resize", updateHighlight);
            window.addEventListener("scroll", updateHighlight);
        });
    </script>
</body>
</html>
