function runTimer(index, sec, min, hrs) {
    seconds = sec;
    minutes = min;
    hours = hrs;

    function pad(num) {
        return num.toString().padStart(2, '0');
    }

    function updateTimer() {
        if (!running) return;

        const now = performance.now();
        const deltaTime = now - startTime;
        startTime = now;  // Reset start time for the next frame

        elapsedTime += deltaTime / 1000;  // Convert milliseconds to seconds
        const secondsElapsed = Math.floor(elapsedTime);

        if (secondsElapsed > lastLoggedSecond) {
            seconds += secondsElapsed - lastLoggedSecond;
            lastLoggedSecond = secondsElapsed;

            if (seconds >= 60) {
                minutes += Math.floor(seconds / 60);
                seconds = seconds % 60;
            }
            if (minutes >= 60) {
                hours += Math.floor(minutes / 60);
                minutes = minutes % 60;
            }

            const formattedTime = pad(hours) + ':' + pad(minutes) + ':' + pad(Math.floor(seconds));
            global_timer = formattedTime;
            
            if(document.querySelectorAll('.pat-status-incoming').length > 0){
                if (global_paging === 1) {
                    // console.log(document.querySelectorAll('.stopwatch').length, index)
                    document.querySelectorAll('.stopwatch')[index].textContent = formattedTime;

                    document.querySelectorAll('.pat-status-incoming')[index].textContent = 'On-Process';
                }
    
                // console.log("global_timer: " + global_timer);

                let data = {
                    formattedTime: formattedTime,
                    hpercode: document.querySelectorAll('.hpercode')[0].value, 
                    from : 'incoming'
                }
                // console.log(data)
                $.ajax({
                    url: '../php_2/session_timer.php',
                    method: "POST", 
                    data:data,
                    success: function(response){
                        // console.log("response: " + response)
                    }
                })
            }else{
                if (global_paging === 1) {
                    document.querySelectorAll('.stopwatch')[index].textContent = formattedTime;
                }
            }
            
        }

        requestId = requestAnimationFrame(updateTimer);
    }

    function start() {
        if (running) return;
        running = true;
        startTime = performance.now();
        requestId = requestAnimationFrame(updateTimer);
    }

    function stop() {
        running = false;
        cancelAnimationFrame(requestId);
    }

    function reset() {
        running = false;
        cancelAnimationFrame(requestId);
        elapsedTime = 0;
        seconds = 0;
        minutes = 0;
        hours = 0;
        lastLoggedSecond = 0;
        const formattedTime = '00:00:00';
        global_timer = formattedTime;

        if (global_paging === 1) {
            document.querySelectorAll('.stopwatch')[index].textContent = formattedTime;
            document.querySelectorAll('.pat-status-incoming')[index].textContent = 'Not Started';
        }
    }

    // Start the timer
    start();

    // Expose control functions
    return { start, stop, reset };
}
