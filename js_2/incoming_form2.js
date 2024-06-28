$(document).ready(function(){
    $('#myDataTable').DataTable({
        "bSort": false,
        "paging": true, 
        "pageLength": 6, 
        "lengthMenu": [ [6, 10, 25, 50, -1], [6, 10, 25, 50, "All"] ],
    });

    var dataTable = $('#myDataTable').DataTable();
    $('#myDataTable thead th').removeClass('sorting sorting_asc sorting_desc');
    dataTable.search('').draw(); 

    const inactivityInterval = 551000; 

    const myModal = new bootstrap.Modal(document.getElementById('pendingModal'));
    const defaultMyModal = new bootstrap.Modal(document.getElementById('myModal-incoming'));
    // myModal.show()

    let global_index = 0, global_paging = 1, global_timer = "", global_breakdown_index = 0;
    let final_time_total = ""
    let next_referral_index_table;
    let length_curr_table = document.querySelectorAll('.hpercode').length;
    let toggle_accordion_obj = {}
    let type_approval = true // true = immediate approval // false = interdepartamental approval

    let startTime;
    let elapsedTime = 0;
    let running = false;
    let requestId;
    let lastLoggedSecond = 0;

    for(let i = 0; i < length_curr_table; i++){
        toggle_accordion_obj[i] = true
    }
    
    // activity/inactivity user
    let inactivityTimer;
    let running_timer_interval = "", running_timer_interval_update;
    let userIsActive = true;

    // reusable functions
    function updateInterdeptFunc(){
        let data = {
            hpercode : document.querySelectorAll('.hpercode')[global_index].value
        }
        console.log(data)
        $.ajax({
            url: '../php_2/fetch_update_interdept.php',
            method: "POST", 
            data:data,
            success: function(response){
                clearInterval(running_timer_interval_update)
                response = JSON.parse(response);   
                console.log(response)

                if(response[0]['status_interdept'] === "Pending"){
                    $('#span-dept').text(response[1].department.toUpperCase() + " | ")
                    $('#span-status').text(response[0].status_interdept + " | ")
                    $('#span-time').text("00:00:00")

                    $('#v2-update-stat').text(`Last update: ${response[0]['currentDateTime']}`)
                }

                if(response[0]['status_interdept'] === "On-Process"){
                    const timeString = response[1].curr_time;
                    if(timeString){
                        // Split the time string into an array using the ":" delimiter
                        const timeParts = timeString.split(":");

                        var hours = parseInt(timeParts[0]);
                        var minutes = parseInt(timeParts[1]);
                        var seconds = parseInt(timeParts[2]);

                        running_timer_interval_update = setInterval(function() {
                            // seconds++;
                
                            // if (seconds === 60) {
                            //     seconds = 0;
                            //     minutes++;
                            // }
                
                            // if (minutes === 60) {
                            //     minutes = 0;
                            //     hours++;
                            // }
                
                            // const formattedTime = pad(hours) + ':' + pad(minutes) + ':' + pad(seconds);
                            const totalSeconds = Math.floor(timeString / 1000);
                            const hours = Math.floor(totalSeconds / 3600);
                            const minutes = Math.floor((totalSeconds % 3600) / 60);
                            const seconds = totalSeconds % 60;

                            const formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                            console.log(formattedTime)
                            
                            $('#v2-update-stat').text(`Last update: ${response[0]['currentDateTime']}`)

                            // <label for="" id="v2-stat"> <span id="span-dept">Surgery</span> - <span id="span-status">Pending</span> - <span id="span-time">00:00:00</span></span></label>
                            $('#span-dept').text(response[1].department.charAt(0).toUpperCase() + response[1].department.slice(1) + " | ") 
                            $('#span-status').text(response[0].status_interdept + " | ") 
                            $('#span-time').text(formattedTime)

                            // here
                            $('.interdept-div').css('display','none')
                            $('#cancel-btn').css('display','block')
                            $('.approval-main-content').css('display','none')
                            clearInterval(running_timer_interval)

                            // check if the status of the thingy is approve or deferred
                            // $.ajax({
                            //     url: '../php_2/fetch_update_interdept.php',
                            //     method: "POST", 
                            //     data:data,
                            //     success: function(response){
                                    
                                    
                            //         // document.querySelectorAll('.pat-status-incoming')[global_index].textContent = 'Pending - ' + $('#inter-depts-select').val().toUpperCase();;
                            //     }
                            // })

                        }, 1000); 
                    }
                }
                else if(response[0]['status_interdept'] === "Approved"){
                    console.log('107')
                    $('#v2-update-stat').text(`Last update: ${response[1]['final_progress_date']}`)

                    // <label for="" id="v2-stat"> <span id="span-dept">Surgery</span> - <span id="span-status">Pending</span> - <span id="span-time">00:00:00</span></span></label>
                    $('#span-dept').text(response[1].department.charAt(0).toUpperCase() + response[1].department.slice(1) + " | ") 
                    $('#span-status').text(response[0].status_interdept + " | ") 
                    $('#span-time').text(response[1]['final_progress_time'])
                    // console.log(response[0]['sent_interdept_time'] ,  response[1]['final_progress_time'])
                    
                    const [hours1, minutes1, seconds1] = response[0]['sent_interdept_time'].split(':').map(Number);
                    const [hours2, minutes2, seconds2] = response[1]['final_progress_time'].split(':').map(Number);
                    
                    // Create Date objects in UTC with the provided hours, minutes, and seconds
                    const date1 = new Date(Date.UTC(1970, 0, 1, hours1, minutes1, seconds1));
                    const date2 = new Date(Date.UTC(1970, 0, 1, hours2, minutes2, seconds2));
                    
                    const totalMilliseconds = date1.getTime() + date2.getTime();
                    
                    // Create a new Date object in UTC with the total milliseconds
                    const newDate = new Date(totalMilliseconds);
                    
                    // Format the result in UTC time "HH:mm:ss"
                    const result = `${String(newDate.getUTCHours()).padStart(2, '0')}:${String(newDate.getUTCMinutes()).padStart(2, '0')}:${String(newDate.getUTCSeconds()).padStart(2, '0')}`;
                    
                    // console.log(result);
                    final_time_total = result
                    $('#final-approve-btn').css('display','block')
                }
                
            }
        })
    }


    // for interdepartamental module. Whenever the first current referral is already pending on interdept, the next referral will be availabe to process.
    function enabledNextReferral(){
        // check the status of the referrals to get the index of the next referral to be enable
        for(let i = 0; i < document.querySelectorAll('.pat-status-incoming').length; i++){
            const str = document.querySelectorAll('.pat-status-incoming')[i].textContent.trim(); // Trim to remove leading and trailing whitespace

            if (str && typeof str === 'string') {
                const hasTwoSpaces = str.match(/^[^\s]*\s[^\s]*\s[^\s]*$/);; // Check if the string contains two consecutive spaces
                if (hasTwoSpaces) {
                    next_referral_index_table = i;
                } 
            }
        }
        if(next_referral_index_table >= 0 && next_referral_index_table + 1 < document.querySelectorAll('.tr-incoming').length){
            document.querySelectorAll('.tr-incoming')[next_referral_index_table + 1].style.opacity = "1"
            document.querySelectorAll('.tr-incoming')[next_referral_index_table + 1].style.pointerEvents = "auto"
        }
        
    }
    enabledNextReferral()

    function changePatientModalContent(){
        $('#pat-status-form').text('Approved')
        $('#approval-form').css('display' , 'none')
        $('#approval-details').css('display' , 'block')

        $('#update-stat-select').css('display' , 'block')
    }

    function handleUserActivity() {
        userIsActive = true;
    }

    function handleUserInactivity() {
        userIsActive = false;
        $.ajax({
            url: '../php_2/fetch_interval.php',
            method: "POST",
            data : {
                from_where : 'incoming'
            }, 
            success: function(response) {
                console.log("fetch_interval")

                // response = JSON.parse(response);    
                // console.log(response)

                dataTable.clear();
                dataTable.rows.add($(response)).draw();

                length_curr_table = $('.tr-incoming').length
                for(let i = 0; i < length_curr_table; i++){
                    toggle_accordion_obj[i] = true
                }

                const pencil_elements = document.querySelectorAll('.pencil-btn');
                    pencil_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log('den')
                        ajax_method(index)
                    });
                });

                const expand_elements = document.querySelectorAll('.accordion-btn');
                    expand_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log(index)
                        global_breakdown_index = index;
                    });
                });

                enabledNextReferral()

                // disable the timer when its showing and running, when the modal is open.
                // clearInterval(running_timer_interval_update)
                // console.log(document.querySelectorAll('.pat-status-incoming')[global_index].textContent)

                if(document.querySelectorAll('.pat-status-incoming').length > 0){
                    const myString = document.querySelectorAll('.pat-status-incoming')[global_index].textContent;
                    const substring = "Approve";
    
                    if (myString.toLowerCase().includes(substring.toLowerCase())) {
                        clearInterval(running_timer_interval_update)
                        $('#span-status').text("Approved | ") 
                        $('#final-approve-btn').css('display',  'block')
    
                    }
                }
               
            }
        });
    }

    document.addEventListener('mousemove', handleUserActivity);

    function startInactivityTimer() {
        inactivityTimer = setInterval(() => {
            if (!userIsActive) {
                handleUserInactivity();
            }
            userIsActive = false;
            
        }, inactivityInterval);
    }

    startInactivityTimer();

    const ajax_method = (index, event) => {
        global_index = index
        const data = {
            hpercode: document.querySelectorAll('.hpercode')[index].value,
            from:'incoming'
        }
        console.log(data)
        $.ajax({
            url: '../php_2/process_pending.php',
            method: "POST", 
            data:data,
            success: function(response){
                document.querySelector('.ul-div').innerHTML = ''
                document.querySelector('.ul-div').innerHTML += response
                if(document.querySelectorAll('.pat-status-incoming')[index].textContent == 'Pending'){
                    console.log(259, index)
                    runTimer(index)
                    let data = {
                        hpercode : document.querySelectorAll('.hpercode')[index].value,
                        from : 'incoming'
                    }
                    $.ajax({
                        url: '../php_2/pendingToOnProcess.php',
                        method: "POST", 
                        data:data
                    })
                    $('#update-stat-select').css('display' , 'none')

                }else if(document.querySelectorAll('.pat-status-incoming')[index].textContent == 'Approved'){
                    console.log('wopwopwop')
                    let data = {
                        hpercode : document.querySelectorAll('.hpercode')[index].value,
                    }
                    console.log(data)

                    $.ajax({
                        url: '../php_2/fetch_approve_details.php',
                        method: "POST", 
                        data:data,
                        dataType: 'JSON',
                        success: function(response){
                            console.log(response)
                            // response[0].pat_class
                            $('#approve-classification-select-details').val(response[0].pat_class)
                            $('#eraa-details').val(response[0].approval_details)
                            
                        }
                    })

                    changePatientModalContent()
                }

                
                // checking if the patient is already referred interdepartamentally
                console.log(data)

                $.ajax({
                    url: '../php_2/check_interdept_refer.php',
                    method: "POST", 
                    data:data,
                    success: function(response){
                        response = JSON.parse(response);    
                        console.log(response)
                        console.log(typeof response.status_interdept)

                        if(response.status_interdept){
                            $('#approval-form').css('display','none')
                            $('.interdept-div-v2').css('display','flex')
                            $('#cancel-btn').css('display','block')
                
                            updateInterdeptFunc()
                        }else{
                            $('#approval-form').css('display','flex')
                            $('.approval-main-content').css('display','block')
                            $('.interdept-div-v2').css('display','none')
                            $('#cancel-btn').css('display','none')
                        }

                        $('#seen-by-lbl span').text(response.referring_seenBy)
                        $('#seen-date-lbl span').text(response.referring_seenTime)
                        
                        if (document.querySelectorAll('.pat-status-incoming')[global_index].textContent.includes("Approve")) {
                            $('#final-approve-btn').css('display','block')
                        } 
                    }
                })

                myModal.show();

            }
        })
    }

    const pencil_elements = document.querySelectorAll('.pencil-btn');
        pencil_elements.forEach(function(element, index) {
        element.addEventListener('click', function() {       
            ajax_method(index)
        });
    });


    function pad(num) {
        return (num < 10 ? '0' : '') + num;
    }

    function loadStateFromSession() {
        // upon logout
        if(post_value_reload === 'true'){
            console.log('366')
            $.ajax({
                url: '../php_2/save_process_time.php',
                method: "POST",
                data : {what: 'continue'},
                dataType : 'JSON',
                success: function(response){
                    running_timer_var = response[0].progress_timer
                    post_value_reload_bool = (post_value_reload === "true") ? true : false;

                    running_bool_var =  (running_bool_var === "true") ? true : false;
                    // initialize mo na lang na false agad yung running na session, tanggalin mo na yung global variable sa taas(?)
                    // tapos ayusin mo yung code mo, nakadepende pa din sa hpercode, depende mo sa referral ID dapat pag multiple referral per account.
                    // running_bool_var = true

                    elapsedTime = (running_timer_var || 0) * 1000; // Convert seconds to milliseconds
                    startTime = running_startTime_var ? running_startTime_var : performance.now() - elapsedTime;
                    running = running_bool_var || false;

                    startTime = performance.now() - elapsedTime;
                    requestId = requestAnimationFrame(runTimer(0).updateTimer);
                }
            })
        }else{
            console.log('390')
            running_bool_var =  (running_bool_var === "true") ? true : false;
            elapsedTime = (running_timer_var || 0) * 1000; // Convert seconds to milliseconds
            startTime = running_startTime_var ? running_startTime_var : performance.now() - elapsedTime;
            running = running_bool_var || false;
    
    
            if (running && previous_loadcontent === "incoming_ref") {
                startTime = performance.now() - elapsedTime;
                requestId = requestAnimationFrame(runTimer(0).updateTimer);
            }
        }
    }

    // on load
    loadStateFromSession()

    function runTimer(index) {
        function formatTime(milliseconds) {
            const totalSeconds = Math.floor(milliseconds / 1000);
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }

        function updateTimer() {
            if (!running) return;

            const now = performance.now();
            elapsedTime = now - startTime;
            const secondsElapsed = Math.floor(elapsedTime / 1000);

            if (secondsElapsed > lastLoggedSecond) {
                // console.log(secondsElapsed);
                lastLoggedSecond = secondsElapsed;

                global_timer = formatTime(elapsedTime);

                if(document.querySelectorAll('.pat-status-incoming').length > 0){
                    if (global_paging === 1) {
                        // console.log(document.querySelectorAll('.stopwatch').length, index)
                        document.querySelectorAll('.stopwatch')[index].textContent = formatTime(elapsedTime);

                        document.querySelectorAll('.pat-status-incoming')[index].textContent = 'On-Process';
                    }
        
                    // console.log("global_timer: " + global_timer);
                    let curr_index = 0;
                    for(let i = 0; i < document.querySelectorAll('.pat-status-incoming').length; i++){
                        if(document.querySelectorAll('.pat-status-incoming')[i].textContent === "On-Process"){
                            curr_index = i;
                        }
                    }

                    $.ajax({
                        url: '../php_2/fetch_onProcess.php',
                        method: "POST", 
                        data:{
                            // timer: document.querySelectorAll('.stopwatch')[curr_index].textContent,
                            timer : elapsedTime / 1000,
                            running_bool : running,
                            startTime : running ? performance.now() : startTime,
                            hpercode: document.querySelectorAll('.hpercode')[curr_index].value,
                            index: curr_index // questionable
                        },
                        success: function(response){
                            // console.log(response)
                        }
                    })

                }else{
                    if (global_paging === 1) {
                        document.querySelectorAll('.stopwatch')[index].textContent = formatTime(elapsedTime);
                    }
                }
            }
            requestId = requestAnimationFrame(updateTimer);
        }

        function start() {
            if (running) return;

            running = true;
            startTime = performance.now() - elapsedTime;
            requestId = requestAnimationFrame(updateTimer);
            // saveStateToSession(); // Save state whenever the timer is started
        }

        function stop() {
            running = false;
            cancelAnimationFrame(requestId);
            // saveStateToSession(); // Save state whenever the timer is stopped
        }

        function reset() {
            running = false;
            elapsedTime = 0;
            document.getElementById('timer').textContent = '00:00:00';
            lastLoggedSecond = 0;
            cancelAnimationFrame(requestId);
            saveStateToSession(); // Save state whenever the timer is reset
        }
    
        // Start the timer
        start();
    
        // Expose control functions
        return { start, stop, reset, updateTimer };
    }

    function saveTimeSession(){
        // look only for the status that is On-Process

        let curr_index = 0;
        for(let i = 0; i < document.querySelectorAll('.pat-status-incoming').length; i++){
            if(document.querySelectorAll('.pat-status-incoming')[i].textContent === "On-Process"){
                curr_index = i;
            }
        }

        console.log({
            timer : elapsedTime / 1000,
            running_bool : running,
            startTime : running ? performance.now() : startTime,
            hpercode: document.querySelectorAll('.hpercode')[curr_index].value,
            index: curr_index // questionable)
        })
        
        $.ajax({
            url: '../php_2/fetch_onProcess.php',
            method: "POST", 
            data:{
                // timer: document.querySelectorAll('.stopwatch')[curr_index].textContent,
                timer : elapsedTime / 1000,
                running_bool : running,
                startTime : running ? performance.now() : startTime,
                hpercode: document.querySelectorAll('.hpercode')[curr_index].value,
                index: curr_index // questionable
            },
            success: function(response){
                // console.log(response)
            }
        })
    }
        
    window.addEventListener('beforeunload', function(event) {
        // e.preventDefault()
        saveTimeSession()
    });

    $(document).on('saveTimeSession', saveTimeSession);










    // search incoming patients
    $('#incoming-search-btn').on('click' , function(event){        
        $('#incoming-clear-search-btn').css('opacity' , '1')
        $('#incoming-clear-search-btn').css('pointer-events' , 'auto')

        let valid_search = false;
        let elements = [$('#incoming-referral-no-search').val(), $('#incoming-last-name-search').val(), $('#incoming-first-name-search').val(),
        $('#incoming-middle-name-search').val(), $('#incoming-type-select').val(),  $('#incoming-agency-select').val(), $('#incoming-status-select').val()]

        for(let i = 0; i < elements.length; i++){
            if(elements[i] !== "" && i != elements.length - 1){
                valid_search = true
            }
            if(elements[i] !== 'default' && i == elements.length - 1){
                valid_search = true
            }
        }

        if(valid_search){
            // find all status that is, sent already on the interdept or On-Process
            let hpercode_arr = []
            for(let i = 0; i < document.querySelectorAll('.pat-status-incoming').length; i++){
                let pat_stat = document.querySelectorAll('.pat-status-incoming')

                const str = pat_stat[i].textContent.trim(); // Trim to remove leading and trailing whitespace
                if (str && typeof str === 'string') {
                    const hasTwoSpaces = str.match(/^[^\s]*\s[^\s]*\s[^\s]*$/);; // Check if the string contains two consecutive spaces
                    if (hasTwoSpaces) {
                        hpercode_arr.push(document.querySelectorAll('.hpercode')[i].value)
                    } 
                }

                if(pat_stat[i].textContent === 'On-Process'){
                    hpercode_arr.push(document.querySelectorAll('.hpercode')[i].value)
                }

                if(pat_stat[i].textContent === 'Pending'){
                    hpercode_arr.push(document.querySelectorAll('.hpercode')[i].value)
                }
            }


            let data = {
                hpercode_arr : hpercode_arr,
                ref_no : $('#incoming-referral-no-search').val(),
                last_name : $('#incoming-last-name-search').val(),
                first_name : $('#incoming-first-name-search').val(),
                middle_name : $('#incoming-middle-name-search').val(),
                case_type : $('#incoming-type-select').val(),
                agency : $('#incoming-agency-select').val(),
                status : $('#incoming-status-select').val(),
                where : 'search'
            }
            console.log(data)

            $.ajax({
                url: '../php_2/incoming_search.php',
                method: "POST", 
                data:data,
                // dataType:'JSON',
                success: function(response){
                    // console.log(response)

                    dataTable.clear();
                    dataTable.rows.add($(response)).draw();

                    length_curr_table = $('.tr-incoming').length
                    for(let i = 0; i < length_curr_table; i++){
                        toggle_accordion_obj[i] = true
                    }

                    const expand_elements = document.querySelectorAll('.accordion-btn');
                    expand_elements.forEach(function(element, index) {
                        element.addEventListener('click', function() {
                            console.log(index)
                            global_breakdown_index = index;
                        });
                    });

                    const pencil_elements = document.querySelectorAll('.pencil-btn');
                    pencil_elements.forEach(function(element, index) {
                        element.addEventListener('click', function() {
                            console.log('den')
                            ajax_method(index)
                        });
                    });

                }
            }) 
        }else{
            defaultMyModal.show()
        }

    })

    $('#incoming-clear-search-btn').on('click' , () =>{
        $.ajax({
            url: '../php_2/incoming_search.php',
            method: "POST", 
            data:{
                'where' : "clear"
            },
            success: function(response){
                // console.log(response)

                dataTable.clear();
                dataTable.rows.add($(response)).draw();

                length_curr_table = $('.tr-incoming').length
                for(let i = 0; i < length_curr_table; i++){
                    toggle_accordion_obj[i] = true
                }

                const expand_elements = document.querySelectorAll('.accordion-btn');
                expand_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log(index)
                        global_breakdown_index = index;
                    });
                });
            }
        }) 
    })

    dataTable.on('page.dt', function () {
        // clearInterval(running_timer_interval)

        var currentPageIndex = dataTable.page();
        var currentPageNumber = currentPageIndex + 1;

        global_paging = currentPageNumber
    });

    function parseTimeToMilliseconds(timeString) {
        const [hours, minutes, seconds] = timeString.split(":");
        // console.log(hours, minutes, seconds)
        const totalMilliseconds = ((parseInt(hours, 10) * 60 + parseInt(minutes, 10)) * 60 + parseInt(seconds, 10)) * 1000;
        return totalMilliseconds;
        //5000
    }


    $('#inter-dept-referral-btn').on('click' , function(event){
        $('.interdept-div').css('display' , 'flex')
    })

    $('#int-dept-btn-forward').on('click' , function(event){
        // 
        $('#modal-title-incoming').text('Successed')
        document.querySelector('#modal-icon').className = 'fa-solid fa-circle-check'
        $('#modal-body-incoming').text('Successfully Forwarded')
        $('#ok-modal-btn-incoming').text('Close')
        defaultMyModal.show()
        $('.interdept-div-v2').css('display' , 'flex')

        let data = {
            dept : $('#inter-depts-select').val(),
            hpercode : document.querySelectorAll('.hpercode')[global_index].value,
            pause_time : global_timer,
            approve_details : $('#eraa').val(),
            case_category : $('#approve-classification-select').val(),
        }
        console.log(data)

        $.ajax({
            url: '../php_2/incoming_interdept.php',
            method: "POST", 
            data:data,
            success: function(response){
                response = JSON.parse(response);   
                console.log(response)

                $('.interdept-div').css('display','none')
                $('#cancel-btn').css('display','block')
                $('.approval-main-content').css('display','none')

                runTimer().stop()
                // clearInterval(running_timer_interval)
                
                document.querySelectorAll('.pat-status-incoming')[global_index].textContent = 'Pending - ' + $('#inter-depts-select').val().toUpperCase();

                // enable the second request on the table while waiting for the current request that is on interdepartment already
                // document.querySelectorAll('.tr-incoming').
                myModal.hide()

                enabledNextReferral()
            }
        })
    })

    
    $('#imme-approval-btn').on('click' , function(event){
       defaultMyModal.show()
       $('#modal-body-incoming').text('Are you sure you want to approve this?')
       $('#modal-title-incoming').text('Confimation')
       $('#ok-modal-btn-incoming').text('No')
       $('#yes-modal-btn-incoming').css('display', 'block')
       type_approval = true
    })

    $('#yes-modal-btn-incoming').on('click' , function(event){

        const data = {
            global_single_hpercode : document.querySelectorAll('.hpercode')[global_index].value,
            timer : global_timer,
            approve_details : $('#eraa').val(),
            case_category : $('#approve-classification-select').val(),
            action : 'Approve', // approve or deferr
            type_approval : type_approval
        }

        console.log(data);

        $.ajax({
            url: '../php_2/approved_pending.php',
            method: "POST",   
            data : data,
            // dataType:'JSON',
            success: function(response){
                // console.log(response)

                // clearInterval(running_timer_interval)
                runTimer().stop()
                document.querySelectorAll('.pat-status-incoming')[global_index].textContent = 'Approved';
                myModal.hide()
                
                dataTable.clear();
                dataTable.rows.add($(response)).draw();
                
                length_curr_table = $('.tr-incoming').length
                for(let i = 0; i < length_curr_table; i++){
                    toggle_accordion_obj[i] = true
                }

                // reset the prev value of the eraa and the select element
                const selectElement = document.getElementById('approve-classification-select');
                selectElement.value = '';
                selectElement.value = selectElement.options[0].value;
                $('#eraa').val("")

                //disabled again the interdepartamental buttons and immediate referral button
                $('#imme-approval-btn').css('opacity' , '0.6')
                $('#imme-approval-btn').css('pointer-events' , 'none')

                $('#inter-dept-referral-btn').css('opacity' , '0.6')
                $('#inter-dept-referral-btn').css('pointer-events' , 'none')

                const pencil_elements = document.querySelectorAll('.pencil-btn');
                    pencil_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log('den')
                        ajax_method(index)
                    });
                });

                const expand_elements = document.querySelectorAll('.accordion-btn');
                    expand_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log(index)
                        global_breakdown_index = index;
                    });
                });

                // reset timer variables
                elapsedTime = 0;
                running = false;
                lastLoggedSecond = 0;
            }
        })
     })

     $(document).on('click' , '.accordion-btn' , function(event){
        var accordion_index = $('.accordion-btn').index(this);
        console.log(accordion_index)

        var idString = event.target.id;
        // Use regular expression to extract the number

        if(toggle_accordion_obj[accordion_index]){
            console.log('up')
            document.querySelectorAll('.tr-incoming #dt-turnaround')[accordion_index].style.height = "300px"
            document.querySelectorAll('.tr-incoming #dt-turnaround')[accordion_index].style.overflow = "auto"
            toggle_accordion_obj[accordion_index] = false

            // fa-solid fa-plus
            $('.accordion-btn').eq(accordion_index).removeClass('fa-plus')
            $('.accordion-btn').eq(accordion_index).addClass('fa-minus')
        }else{
            document.querySelectorAll('.tr-incoming #dt-turnaround')[accordion_index].style.height = "61px"
            document.querySelectorAll('.tr-incoming #dt-turnaround')[accordion_index].style.overflow = "hidden"
            toggle_accordion_obj[accordion_index] = true

            $('.accordion-btn').eq(accordion_index).addClass('fa-plus')
            $('.accordion-btn').eq(accordion_index).removeClass('fa-minus')
        }

        
    })

    $('.pre-emp-text').on('click' , function(event){
        var originalString = event.target.textContent;
        // Using substring
        var stringWithoutPlus = originalString.substring(2);

        // Or using slice
        // var stringWithoutPlus = originalString.slice(2);
        $('#eraa').val($('#eraa').val() + " " + stringWithoutPlus  + " ")


        if ($('#approve-classification-select').val() !== '') {
            $('#imme-approval-btn').css('opacity' , '1')
            $('#imme-approval-btn').css('pointer-events' , 'auto')

            $('#inter-dept-referral-btn').css('opacity' , '1')
            $('#inter-dept-referral-btn').css('pointer-events' , 'auto')
        }
    })

    // 
    $('#inter-depts-select').on('change', function(event) {
        // Check if an option is selected
        if ($(this).val() !== '') {
            // Apply CSS changes when an option is selected
            $('#int-dept-btn-forward').css('opacity', '1');
            $('#int-dept-btn-forward').css('pointer-events', 'auto');
        } else {
            // Optionally, you can reset CSS when no option is selected
            $('#int-dept-btn-forward').css('opacity', '0.3');
            $('#int-dept-btn-forward').css('pointer-events', 'none');
        }
    });

    $('#approve-classification-select').on('change', function(event) {
        console.log('asdf')
        if ($(this).val() !== '' && $('#eraa').val().length > 1) {
            $('#imme-approval-btn').css('opacity' , '1')
            $('#imme-approval-btn').css('pointer-events' , 'auto')

            $('#inter-dept-referral-btn').css('opacity' , '1')
            $('#inter-dept-referral-btn').css('pointer-events' , 'auto')
        }else{
            
        }
    });

    $('#eraa').on('input', function(event) {
        if ($('#approve-classification-select').val() !== '' && $('#eraa').val().length > 20) {
            $('#imme-approval-btn').css('opacity' , '1')
            $('#imme-approval-btn').css('pointer-events' , 'auto')

            $('#inter-dept-referral-btn').css('opacity' , '1')
            $('#inter-dept-referral-btn').css('pointer-events' , 'auto')
        }else{
            
        }
    });

    $('#eraa').on('keydown', function(event) {
        if (event.keyCode === 8 && $('#eraa').val().length < 20) {
            $('#imme-approval-btn').css('opacity' , '0.3')
            $('#imme-approval-btn').css('pointer-events' , 'none')

            $('#inter-dept-referral-btn').css('opacity' , '0.3')
            $('#inter-dept-referral-btn').css('pointer-events' , 'none')
        }
    });
 
    $('#cancel-btn').on('click', function(event) {
        defaultMyModal.show()
        $('#modal-title-incoming').text('Confirmation')
        $('#modal-body-incoming').text('Are you sure you want to cancel this referral?')
        clearInterval(running_timer_interval_update)
    });

    $('#final-approve-btn').on('click', function(event) {
        const data = {
            global_single_hpercode : document.querySelectorAll('.hpercode')[global_index].value,
            timer : final_time_total,
            approve_details : $('#eraa').val(), 
            case_category : $('#approve-classification-select').val(),
            action : "Approve"
        }

        console.log(data);

        $.ajax({
            url: '../php_2/approved_pending.php',
            method: "POST",
            data : data,
            success: function(response){
                // response = JSON.parse(response);    
                // console.log(response)

                document.querySelectorAll('.pat-status-incoming')[global_index].textContent = 'Approved';
                myModal.hide()
                
                dataTable.clear();
                dataTable.rows.add($(response)).draw();
                
                length_curr_table = $('.tr-incoming').length
                for(let i = 0; i < length_curr_table; i++){
                    toggle_accordion_obj[i] = true
                }
                
                const pencil_elements = document.querySelectorAll('.pencil-btn');
                pencil_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log('den')
                        ajax_method(index)
                    });
                });
            }
         })
    });


    // sensitive case
    
    $(document).on('click', '.sensitive-case-btn', function(event){
        //reset the the buttons in modal after the previous transaction
        $('#ok-modal-btn-incoming').text('OK')
        $('#yes-modal-btn-incoming').css('display', 'none')

       console.log($('.sensitive-case-btn').index(this))

        var index = $('.sensitive-case-btn').index(this);
        let sensitive_hpercode = document.querySelectorAll('.sensitive-hpercode')

        $.ajax({
            url: '../php_2/fetch_sensitive_names.php',
            method: "POST",
            data : {
                hpercode : sensitive_hpercode[index].value // index = should always be = 0
            },
            dataType:'JSON',
            success: function(response){
                console.log(response)
                let fullNameLabel = `<label class='pat-full-name-lbl'>${response.patlast}, ${response.patfirst} ${response.patmiddle}</label>`;
                $('.pat-full-name-div').append(fullNameLabel);

                $('#modal-title-incoming').text('Verification')
                // <input id="sensitive-pw" type="password" placeholder="Input Password">
                $('#modal-body-incoming').text('')
                let sensitive_btn = document.createElement('input')
                sensitive_btn.id = 'sensitive-pw'
                sensitive_btn.type = 'password'
                sensitive_btn.placeholder = 'Input Password'

                $('#modal-body-incoming').append(sensitive_btn)

                defaultMyModal.show()
            }
        })
    })

    $('#ok-modal-btn-incoming').on('click' , function(event){
        if($('#ok-modal-btn-incoming').text() === 'Close'){
            console.log('done interdept referral shared')
        }
        else{
            let mcc_passwords_validity = false
            let input_pw = $('#sensitive-pw').val().toString()
            for (var key in mcc_passwords) {
                if (mcc_passwords.hasOwnProperty(key)) {
                    if(mcc_passwords[key] === input_pw){
                        mcc_passwords_validity = true;
                    }
                }
            }
            
            if (mcc_passwords_validity) {
                // Your existing code when validity is true
                // checking of all the sensitive-btn and get the index of the previous display=none, and +1 on the index for the current sensitive button
                console.log($('.sensitive-case-btn').length)
                let sensitive_btn_index = 0;
                for(let i = 0; i < $('.sensitive-case-btn').length; i++){
                    if($('.sensitive-case-btn').eq(0).css('display') === 'flex'){
                        break;
                    }
                    else if($('.sensitive-case-btn').eq(i).css('display') === 'none'){
                        sensitive_btn_index = i + 1
                        break;
                    }
                }

                $('.sensitive-lock-icon').eq(sensitive_btn_index)
                    .css('color', 'lightgreen')
                    .removeClass('fa-solid fa-lock')
                    .addClass('fa-solid fa-lock-open');
            
                $('.pencil-btn').eq(sensitive_btn_index)
                    .css('pointer-events', 'auto')
                    .css('opacity', '1');
                $('.sensitive-case-btn').eq(sensitive_btn_index).fadeOut(2000)
            } else {
                // Change color to red
                console.log(sensitive_btn_index)
                // $('.sensitive-lock-icon').eq(sensitive_btn_index).css('color', 'red');
            
                // // Fade back to normal color after 2 seconds
                // setTimeout(function() {
                //     $('.sensitive-lock-icon').eq(sensitive_btn_index).css('color', ''); // Reset to original color
                // }, 2000);
            }
        }
        
    })

    $('#update-stat-select').on('change', function() {
        var selectedValue = $(this).val();
        if (selectedValue) {
            $('#save-update').show(); 
        } else {
            $('#save-update').hide(); 
        }
    });

    $('#save-update').on('click', function() {
        const  selectedValue = $('#update-stat-select').val();
        let data = {
            hpercode : document.querySelectorAll('.hpercode')[global_index].value,
            newStatus : selectedValue
        }
        console.log(data)
        $.ajax({
            url: '../php_2/update_referral_status.php',
            method: "POST",
            data : data,
            success: function(response){
                console.log(response)
                myModal.hide()
                
                $('#pat-status-form').text(data.newStatus)
                $('#modal-body-incoming').text('Successfully Updated')
                defaultMyModal.show()
                $('#save-update').hide(); 
                $('#update-stat-select').prop('selectedIndex', 0);
            }
         })
    });
})