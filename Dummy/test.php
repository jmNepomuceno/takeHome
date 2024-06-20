<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accurate Timer</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .timer-container {
            text-align: center;
        }
        .timer {
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .buttons button {
            font-size: 1rem;
            padding: 10px 20px;
            margin: 5px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
        }
        .buttons button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="timer-container">
        <div id="timer" class="timer">0.00</div>
        <div class="buttons">
            <button onclick="startTimer()">Start</button>
            <button onclick="stopTimer()">Stop</button>
            <button onclick="resetTimer()">Reset</button>
        </div>
    </div>

    <script>
        let startTime;
        let elapsedTime = 0;
        let running = false;
        let requestId;

        function updateTimer() {
            if (!running) return;

            const now = performance.now();
            elapsedTime = now - startTime;
            document.getElementById('timer').textContent = (elapsedTime / 1000).toFixed(2);
            requestId = requestAnimationFrame(updateTimer);
        }

        function startTimer() {
            if (running) return;

            running = true;
            startTime = performance.now() - elapsedTime;
            requestId = requestAnimationFrame(updateTimer);
        }

        function stopTimer() {
            running = false;
            cancelAnimationFrame(requestId);
        }

        function resetTimer() {
            running = false;
            elapsedTime = 0;
            document.getElementById('timer').textContent = '0.00';
            cancelAnimationFrame(requestId);
        }
    </script>
</body>
</html>
