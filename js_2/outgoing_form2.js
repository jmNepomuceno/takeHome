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

    const myModal = new bootstrap.Modal(document.getElementById('pendingModal'));
    const defaultMyModal = new bootstrap.Modal(document.getElementById('myModal-incoming'));
    // myModal.show()

    let global_index = 0, global_paging = 1, global_timer = "", global_breakdown_index = 0;
    let length_curr_table = document.querySelectorAll('.hpercode').length;
    let toggle_accordion_obj = {}
    for(let i = 0; i < length_curr_table; i++){
        toggle_accordion_obj[i] = true
    }
    
    // activity/inactivity user
    let inactivityTimer;
    let running_timer_interval = "";
    let userIsActive = true;
    function handleUserActivity() {
        userIsActive = true;
        // console.log('active')
    }

    function handleUserInactivity() {
        // console.log('inactive')
        userIsActive = false;
        $.ajax({
            url: '../php/fetch_interval.php',
            method: "POST",
            data : {
                from_where : 'outgoing'
            },
            success: function(response) {
                console.log('den')
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
            }
        });
    }

    document.addEventListener('mousemove', handleUserActivity);

    const inactivityInterval = 10000; 

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
            from:'outgoing'
        }
        $.ajax({
            url: '../php/process_pending.php',
            method: "POST", 
            data:data,
            success: function(response){
                document.querySelector('.ul-div').innerHTML = ''
                document.querySelector('.ul-div').innerHTML += response
            
                $('#pat-status-form').text(document.querySelectorAll('.pat-status-incoming')[index].textContent)
                myModal.show();

            }
        })
    }

    const pencil_elements = document.querySelectorAll('.pencil-btn');
        pencil_elements.forEach(function(element, index) {
        element.addEventListener('click', function() {
            console.log('den')
            ajax_method(index)

            lobal_index = index
            const data = {
                hpercode: document.querySelectorAll('.hpercode')[index].value
            }
            $.ajax({
                url: '../php/process_pending.php',
                method: "POST", 
                data:data,
                success: function(response){
                    document.querySelector('.ul-div').innerHTML = ''
                    document.querySelector('.ul-div').innerHTML += response
                    
                    // if(document.querySelectorAll('.pat-status-incoming')[index].textContent == 'Pending'){
                    //     console.log('here')
                    //     runTimer(index, 0, 0, 0) // secs, minutes, hours
                    // }
                    myModal.show();

                }
            })
        });
    });

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

        console.log(valid_search)

        if(valid_search){
            let data = {
                ref_no : $('#incoming-referral-no-search').val(),
                last_name : $('#incoming-last-name-search').val(),
                first_name : $('#incoming-first-name-search').val(),
                middle_name : $('#incoming-middle-name-search').val(),
                case_type : $('#incoming-type-select').val(),
                agency : $('#incoming-agency-select').val(),
                status : $('#incoming-status-select').val()
            }


            console.log(data)
            $.ajax({
                url: '../php_2/outgoing_search.php',
                method: "POST", 
                data:data,
                success: function(response){
                    // response = JSON.parse(response);  
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
        }else{
            defaultMyModal.show()
        }

    })

    $('#incoming-clear-search-btn').on('click' , function(event){
        $.ajax({
            url: '../php/fetch_interval.php',
            method: "POST",
            data:{
                from_where : 'outgoing'
            },
            success: function(response){
                // response = JSON.parse(response);    
                // console.log(response)

                startInactivityTimer()

                $('#incoming-referral-no-search').val("")
                $('#incoming-last-name-search').val("")
                $('#incoming-first-name-search').val("")
                $('#incoming-middle-name-search').val("")
                $('#incoming-type-select').val("")
                $('#incoming-agency-select').val("")
                $('#incoming-status-select').val('Pending')

                
                dataTable.clear();
                dataTable.rows.add($(response)).draw();
            }
        })
    })

    dataTable.on('page.dt', function () {
        // clearInterval(running_timer_interval)

        var currentPageIndex = dataTable.page();
        var currentPageNumber = currentPageIndex + 1;

        global_paging = currentPageNumber
    });

     $(document).on('click' , '.accordion-btn' , function(event){
        console.log(global_breakdown_index)

        if(toggle_accordion_obj[global_breakdown_index]){
            document.querySelectorAll('.tr-incoming #dt-turnaround')[global_breakdown_index].style.height = "300px"
            document.querySelectorAll('.tr-incoming #dt-turnaround')[global_breakdown_index].style.overflow = "auto"
            toggle_accordion_obj[global_breakdown_index] = false

            // fa-solid fa-plus
            $('.accordion-btn').eq(global_breakdown_index).removeClass('fa-plus')
            $('.accordion-btn').eq(global_breakdown_index).addClass('fa-minus')
        }else{
            document.querySelectorAll('.tr-incoming #dt-turnaround')[global_breakdown_index].style.height = "70px"
            document.querySelectorAll('.tr-incoming #dt-turnaround')[global_breakdown_index].style.overflow = "hidden"
            toggle_accordion_obj[global_breakdown_index] = true

            $('.accordion-btn').eq(global_breakdown_index).addClass('fa-plus')
            $('.accordion-btn').eq(global_breakdown_index).removeClass('fa-minus')
        }
    })
})