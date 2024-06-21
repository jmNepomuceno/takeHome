<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Side Bar Demo</title>
<style>
  .sidebar {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background-color: #f0f0f0;
  }
  .content {
    margin-top: 20px;
    padding: 20px;
    border: 1px solid #ccc;
  }
</style>
</head>
<body>

<div class="sidebar">
  <button onclick="loadContent('side1')">Side 1</button>
  <button onclick="loadContent('side2')">Side 2</button>
  <button onclick="loadContent('side3')">Side 3</button>
</div>

<div class="content" id="content">
  <!-- Content will be loaded here -->
</div>

<script>
  var timerInterval; // Variable to store the interval ID
  var currentSide = null; // Variable to store the currently loaded side
  var seconds = 0; // Variable to store the timer count

  // Function to load content based on the side button clicked
  function loadContent(side) {
    var contentDiv = document.getElementById('content');
    
    // Update currentSide
    currentSide = side;
    
    switch (side) {
      case 'side1':
        contentDiv.innerHTML = '<h2>Content for Side 1</h2><p>This is the content for Side 1.</p>';
        break;
      case 'side2':
        contentDiv.innerHTML = '<h2>Content for Side 2</h2><p>This is the content for Side 2. <span id="timerLabel">0</span></p>';
        break;
      case 'side3':
        contentDiv.innerHTML = '<h2>Content for Side 3</h2><p>This is the content for Side 3.</p><p>Timer: <span id="timerLabel">0</span> seconds</p>';
        startTimer();
        break;
      default:
        contentDiv.innerHTML = '<h2>Default Content</h2><p>Select a side to load content.</p>';
    }
  }

  // Function to start the timer
  function startTimer() {
    timerInterval = setInterval(function() {
      var timerLabel = document.getElementById('timerLabel');
      // Check if timerLabel exists
      if (timerLabel) {
        seconds++;
        timerLabel.textContent = seconds;
        console.log(seconds)
      }
    }, 1000);
  }
</script>

</body>
</html>
