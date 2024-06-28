$(document).ready(function(){
    // load the 4 web pages
    const loadContent = (url) => {
        $.ajax({
            url:url, 
            success: function(response){
                $('#container').html(response);
            }
        })
    }

    const myModal_main = new bootstrap.Modal(document.getElementById('myModal-main'));

    // BGHMC adheres to all satutatory mandatory and regulatory requirements to ensure standard implementation
    // loadContent('../php_2/default_view2.php')
    // loadContent('../php_2/patient_register_form2.php')
    // loadContent('php/opd_referral_form.php?type=OB&code=BGHMC-0001')
    loadContent('../php_2/incoming_form2.php')
    // loadContent('../php_2/bucas_queue.php')
    // loadContent('../php_2/bucas_history.php')
    // loadContent('../php_2/outgoing_form2.php')
    // loadContent('../php_2/interdept_form.php')

    // Function to parse query parameters from URL  
    function getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] === variable) {
                return pair[1];
            }
        }
        return null;
    }

    // Check if the loadContent parameter exists in the URL
    var loadContentParam = getQueryVariable('loadContent');

    console.log('Loaded Content Param: ' + loadContentParam);
    if (loadContentParam) {
        loadContent(loadContentParam);
    }else{
        // loadContent('../php_2/default_view2.php');
    }

    jQuery.noConflict();
    let current_page = ""
    let fetch_timer = 0

    const playAudio = () =>{
        let audio = document.getElementById("notif-sound")
        audio.muted = false;
        audio.play().catch(function(error){
            'Error playing audio: ' , error
        })
    }

    const stopSound = () =>{
        let audio = document.getElementById("notif-sound")
        audio.pause;
        audio.muted = true;
        audio.currentTime = 0;
    }

    function fetchMySQLData() {
        $.ajax({
            url: '../php_2/fetch_interval.php',
            method: "POST",
            data : {
                from_where : 'bell'
            },
            success: function(response) {
                response = JSON.parse(response);  
                $('#notif-span').text(response.length);
                if(response.length > 9){
                    $('#notif-span').css('font-size' , '0.65rem');
                }

                if (parseInt(response.length) >= 1) {
                    if(current_page === 'incoming_page'){
                        stopSound()
                    }else{
                        playAudio();
                    }
                    timer_running = true;
                    $('#notif-circle').removeClass('hidden');
                    
                    let type_counter = []
                    for(let i = 0; i < response.length; i++){

                        if(!type_counter.includes(response[i]['type'])){
                            type_counter.push(response[i]['type'])
                        }
                    }

                    
                    document.getElementById('notif-sub-div').innerHTML = '';
                    for(let i = 0; i < type_counter.length; i++){
                        let type_var  = type_counter[i]
                        let type_counts  = 0

                        for(let j = 0; j < response.length; j++){
                            if(type_counter[i] ===  response[j]['type']){
                                type_counts += 1
                            }
                        }

                        if(i % 2 === 0){ 
                            document.getElementById('notif-sub-div').innerHTML += '\
                            <div>\
                                <h4 class="font-bold text-lg">' + type_counts + '</h4>\
                                <h4 class="font-bold text-lg">' + type_var + '</h4>\
                            </div>\
                        ';
                        }else{
                            document.getElementById('notif-sub-div').innerHTML += '\
                            <div>\
                                <h4 class="font-bold text-lg">' + type_counts + '</h4>\
                                <h4 class="font-bold text-lg">' + type_var + '</h4>\
                            </div>\
                        ';
                        }
                    }

                } else {
                    $('#notif-circle').addClass('hidden');
                    stopSound()
                }
                
                fetch_timer = setTimeout(fetchMySQLData, 5000);
            }
        });
    }   

    fetchMySQLData(); 

    let side_bar_btn_counter = 0
    $('#side-bar-mobile-btn').on('click' , function(event){
        document.querySelector('#side-bar-div').classList.toggle('hidden');

        if(side_bar_btn_counter === 0){
            document.querySelector('#side-bar-mobile-btn').className = 'side-bar-mobile-btn w-[50%] ml-2 h-[10px] absolute flex flex-row justify-start items-center cursor-pointer transition duration-700 ease-in-out'
            side_bar_btn_counter = 1;
            $('#sdn-title-h1').addClass('hidden')
        }else{
            document.querySelector('#side-bar-mobile-btn').className = 'side-bar-mobile-btn w-[50%] ml-2 h-full flex flex-row justify-start items-center cursor-pointer delay-150'
            $('#sdn-title-h1').removeClass('hidden')
            side_bar_btn_counter = 0;
        }
    })

    
    $('#logout-btn').on('click' , function(event){
        event.preventDefault(); 
        $('#modal-title-main').text('Warning')
        $('#ok-modal-btn-main').text('No')

        $('#yes-modal-btn-main').text('Yes');
        $("#yes-modal-btn-main").css("display", "flex")

        // console.log('here')
    })

    $('#yes-modal-btn-main').on('click' , function(event){
        document.querySelector('#nav-drop-account-div').classList.toggle('hidden');

        let currentDate = new Date();

        let year = currentDate.getFullYear();
        let month = currentDate.getMonth() + 1;
        let day = currentDate.getDate();

        let hours = currentDate.getHours();
        let minutes = currentDate.getMinutes();
        let seconds = currentDate.getSeconds();

        let final_date = year + "/" + month + "/" + day + " " + hours + ":" + minutes + ":" + seconds
        $.ajax({
            url: '../php_2/save_process_time.php',
            data : {
                what: 'save',
                date : final_date,
                sub_what: 'logout'
            },                        
            method: "POST",
            success: function(response) {
                // response = JSON.parse(response);
                console.log(response , " here")
                // window.location.href = "http://192.168.42.222:8035/index.php" 
                window.location.href = "http://10.10.90.14:8079/index.php" 
            }
        });
    })

    $('#ok-modal-btn-main').on('click' , function(event){
        console.log('asdfc')
    })

    $('#nav-account-div').on('click' , function(event){
        event.preventDefault();
        if($("#nav-drop-account-div").css("display") === "none"){
            $("#nav-drop-account-div").css("display", "flex")
        }else{
            $("#nav-drop-account-div").css("display", "none")
        }
    })

    //welcome modal
    $('#closeModal').on('click' , function(event){
        $('#myModal').addClass('hidden')
        $('#main-div').css('filter', 'blur(0)');
        $('#modal-div').addClass('hidden')

        document.getElementById("notif-sound").play()
    })

    if(parseInt($('#notif-circle').text()) > 0){
        console.log("here")
        // document.getElementById("notif-sound").play()

        // setTimeout(function() {
        //     document.getElementById("notif-sound").play()
        // }, 2000); // Delay in milliseconds (2 seconds in this example)
    }

    $('#sdn-title-h1').on('click' , function(event){
        event.preventDefault();
        loadContent('../php_2/default_view2.php')
        
    })

    $('#dashboard-incoming-btn').on('click' , function(event){
        event.preventDefault();
        window.location.href = "../php_2/dashboard_incoming.php";
    })

    $('#dashboard-outgoing-btn').on('click' , function(event){
        event.preventDefault();
        window.location.href = "../php_2/dashboard_outgoing.php";
    })

    $('#history-log-btn').on('click' , function(event){
        event.preventDefault();

        let currentDate = new Date();

        let year = currentDate.getFullYear();
        let month = currentDate.getMonth() + 1; // Adding 1 to get the month in the human-readable format
        let day = currentDate.getDate();

        let hours = currentDate.getHours();
        let minutes = currentDate.getMinutes();
        let seconds = currentDate.getSeconds();

        let final_date = year + "/" + month + "/" + day + " " + hours + ":" + minutes + ":" + seconds
        // console.log('here')
        $.ajax({
            url: '../php_2/save_process_time.php',
            data : {
                what: 'save',
                date : final_date,
                sub_what: 'history_log'
            },
            method: "POST",
            success: function(response) {
                window.location.href = "../php_2/history_log.php";
            }
        });
    })

    $('#admin-module-btn').on('click' , function(event){
        event.preventDefault();
        // 
        let currentDate = new Date();

        let year = currentDate.getFullYear();
        let month = currentDate.getMonth() + 1; // Adding 1 to get the month in the human-readable format
        let day = currentDate.getDate();

        let hours = currentDate.getHours();
        let minutes = currentDate.getMinutes();
        let seconds = currentDate.getSeconds();

        let final_date = year + "/" + month + "/" + day + " " + hours + ":" + minutes + ":" + seconds

        $.ajax({
            url: '../php_2/save_process_time.php',
            data : {
                what: 'save',
                date : final_date,
                sub_what: 'history_log'
            },
            method: "POST",
            success: function(response) {
                window.location.href = "../php_2/admin.php";
            }
        });
    })

    let notif_sub_div_open = true
    $('#notif-div').on('click' , function(event){
        console.log(notif_sub_div_open)

        if(!notif_sub_div_open){
            document.getElementById('notif-sub-div').style.display = 'none'
            notif_sub_div_open = true
        }else{
            notif_sub_div_open = false
            document.getElementById('notif-sub-div').style.display = 'flex'
        }
    })

    $('#notif-sub-div').on('click' , function(event){
        console.log('rofl')
        if(parseInt($('#notif-span').text() === 0)){
            $('#notif-circle').addClass('hidden')
            document.getElementById("notif-sound").pause();
            document.getElementById("notif-sound").currentTime = 0;
        }else{
            $('#notif-sub-div').addClass('hidden')
            loadContent('../php_2/incoming_form2.php')
            current_page = "incoming_page"
            $('#current-page-input').val(current_page)
        }

        document.getElementById('notif-sub-div').style.display = 'none'
    })

    // mikas
    // MIKAS3255

    $('#outgoing-sub-div-id').on('click' , function(event){
        event.preventDefault();
        loadContent('../php_2/outgoing_form2.php')
    })

    $('#incoming-sub-div-id').on('click' , function(event){
        event.preventDefault();

        loadContent('../php_2/incoming_form2.php')
    })

    $('#patient-reg-form-sub-side-bar').on('click' , function(event){
        event.preventDefault();
        $(document).trigger('saveTimeSession');

        loadContent('../php_2/patient_register_form2.php')
    })

    $('#interdept-sub-div-id').on('click' , function(event){
        event.preventDefault();
        // loadContent('../php_2/interdept_form.php')
    })

    $('#bucasPending-sub-div-id').on('click' , function(event){
        event.preventDefault();
        loadContent('../php_2/bucas_queue.php')
    })


    $('#bucasHistory-sub-div-id').on('click' , function(event){
        event.preventDefault();
        loadContent('../php_2/bucas_history.php')
    })
    

    $(window).on('load' , function(event){
        event.preventDefault();
        current_page = "default_page"
        $('#current-page-input').val(current_page)

        // loadContent('php/default_view.php')
        // loadContent('php/patient_register_form.php')
        // loadContent('php/opd_referral_form.php')
    })

    $(window).on('beforeunload', function() {
        localStorage.setItem('scrollPosition', $(window).scrollTop());
    });

    $('#navbar-icon').on('click' , function(event){
        let  width = ((($("#side-bar-div").width() / $("#side-bar-div").parent().width()) * 100).toFixed(1)) + "%";

        if(width === "13.5%"){
            $('#side-bar-div').css("width", "0")
            $('#bgh-name').text('')
            $('#main-side-bar-1-subdiv').css("display", "none")
            $('#main-side-bar-2-subdiv').css("display", "none")

            $('#main-div .aside-main-div #container').css("width", "100%")
        }else{
            $('#side-bar-div').css("width", "13.5%")
            $('#bgh-name').text('Bataan General Hospital and Medical Center')
            $('#main-side-bar-1-subdiv').css("display", "flex")
            $('#main-side-bar-2-subdiv').css("display", "flex")

            $('#main-div .aside-main-div #container').css("width", "86.5%")
        }
    })
})