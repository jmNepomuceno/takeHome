$(document).ready(function(){
    let myModal = new bootstrap.Modal(document.getElementById('myModal-hospitalAndUsers'));
    // myModal.show()

    let intervalHistoryLog;
    let inactivityTimer;
    let userIsActive = true;

    function handleUserActivity() {
        userIsActive = true;
        // Additional code to handle user activity if needed
        // console.log('active')
        clearInterval(intervalHistoryLog)
  
    }
  
    function handleUserInactivity() {
        // console.log('inactive')
        userIsActive = false;
        // Additional code to handle user inactivity if needed
        // intervalHistoryLog = setInterval(fetchHistoryLog, 10000);
    }
  
    // Attach event listeners
    document.addEventListener('mousemove', handleUserActivity);
  
    // Set up a timer to check user inactivity periodically
    const inactivityInterval = 10000; // Execute every 5 seconds (adjust as needed)
  
    function startInactivityTimer() {
        inactivityTimer = setInterval(() => {
            if (!userIsActive) {
                handleUserInactivity();
            }
            userIsActive = false; // Reset userIsActive after each check
        }, inactivityInterval);
    }
  
    function resetInactivityTimer() {
        clearInterval(inactivityTimer);
  
        startInactivityTimer();
    }
    
    // Start the inactivity timer when the page loads
    startInactivityTimer();
    
    //----------------------------------------------------------------------------
  
      $('#total-processed-refer').text($('#total-processed-refer-inp').val())
    // console.log($('#total-processed-refer-inp').val())
    const playAudio = () =>{
      let audio = document.getElementById("notif-sound")
      audio.muted = false;
      audio.play().catch(function(error){
          'Error playing audio: ' , error
      })
    }
  
    $('#history-select').change(function() {
      var selectedValue = $(this).val();
  
      if(selectedValue === 'login'){
        selectedValue = 'user_login'
      }else if(selectedValue === 'incoming'){
        selectedValue = 'pat_refer'
      }else if(selectedValue === 'register'){
        selectedValue = 'pat_form'
      }else if(selectedValue === 'outgoing'){
        selectedValue = 'pat_defer'
      }else{
        selectedValue = 'all'
      }
  
      $.ajax({
        url: '../php/history_filter.php',
        method: "POST",
        data : {
          option : selectedValue
        },
        success: function(response) {
            let historyDiv = document.querySelector('.history-container')
  
            if (historyDiv) {
                while (historyDiv.firstChild) {
                    historyDiv.removeChild(historyDiv.firstChild);
                }
            }
  
            document.querySelector('.history-container').innerHTML = response
        }
      });
    });
  
    const loadContent = (url) => {
      $.ajax({
          url:url,
          success: function(response){
              // console.log(response)
              $('#container').html(response);
          }
      })
    }
  
    
    function fetchMySQLData() {
      $.ajax({
          url: '../php_2/fetch_interval.php',
          method: "POST",
          data : {
              from_where : 'bell'
          },
          success: function(data) {
              $('#notif-span').text(data);
              if (parseInt(data) >= 1) {
                  $('#notif-circle').removeClass('hidden');
                  
                  playAudio();
              } else {
                  $('#notif-circle').addClass('hidden');
              }
              
              setTimeout(fetchMySQLData, 10000);
          }
      });
    }
  
    fetchMySQLData(); 
  
      $('#side-bar-mobile-btn').on('click' , function(event){
        document.querySelector('#side-bar-div').classList.toggle('hidden');
      })
  
    $('#logout-btn').on('click' , function(event){
      event.preventDefault(); // Prevent the default behavior (navigating to the link)
      console.log('den')
  
      $('#modal-title-main').text('Warning')
      // $('#modal-body').text('Are you sure you want to logout?')
      $('#ok-modal-btn-main').text('No')
  
      $('#yes-modal-btn-main').text('Yes');
      $('#yes-modal-btn-main').removeClass('hidden')
  
      $('#myModal-main').modal('show');
    })
    
    $('#yes-modal-btn-main').on('click' , function(event){
      console.log('here')
      document.querySelector('#nav-drop-account-div').classList.toggle('hidden');
  
      let currentDate = new Date();
  
      let year = currentDate.getFullYear();
      let month = currentDate.getMonth() + 1; // Adding 1 to get the month in the human-readable format
      let day = currentDate.getDate();
  
      let hours = currentDate.getHours();
      let minutes = currentDate.getMinutes();
      let seconds = currentDate.getSeconds();
  
      let final_date = year + "/" + month + "/" + day + " " + hours + ":" + minutes + ":" + seconds
  
      $.ajax({
          url: '../php/save_process_time.php',
          data : {  
              what: 'save',
              date : final_date,
              sub_what: 'history_log'
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
  
    $('#nav-account-div').on('click' , function(event){
      event.preventDefault();
      document.querySelector('#nav-drop-account-div').classList.toggle('hidden');
    })
  
    $('#dashboard-incoming-btn').on('click' , function(event){
      event.preventDefault();
      window.location.href = "../php_2/dashboard_incoming.php";
    })
  
    $('#dashboard-outgoing-btn').on('click' , function(event){
        event.preventDefault();
        window.location.href = "../php_2/dashboard_outgoing.php";
    })
  
    $('#sdn-title-h1').on('click' , function(event){
      event.preventDefault();
      window.location.href = "../php_2/main2.php";
    })
  
    $('#incoming-sub-div-id').on('click' , function(event){
      event.preventDefault();
      window.location.href = "../main.php";
    })

    $('#history-log-btn').on('click' , function(event){
        event.preventDefault();
        window.location.href = "../php_2/history_log.php";
    })

  

  let toggle_accordion_obj = {}
  let global_breakdown_index = 0
  let global_classification_divs_index = 0;
  let single_classification_clicked = ""
  for(let i = 0; i < document.querySelectorAll('.table-tr').length; i++){
      toggle_accordion_obj[i] = true
  }
  // console.log(toggle_accordion_obj)

  function attachSeeMoreBtn() {
    const expand_elements = document.querySelectorAll('.see-more-btn');
    expand_elements.forEach(function(element, index) {
        element.addEventListener('click', function() {
            global_breakdown_index = index;
        });
    });
  }

  function attachInfoBtn() {
    const edit_info_elements = document.querySelectorAll('.edit-info-btn');
      edit_info_elements.forEach(function(element, index) {
        element.addEventListener('click', function() {
            global_breakdown_index = index;
        });
    });
  }

  function attachClassifications() {
    $('#populate-patclass-div').on('click', '.classification-sub-div', function() {
      global_classification_divs_index = $(this).index();
      console.log(global_classification_divs_index);
  });
}

  attachClassifications()
  attachSeeMoreBtn();
  attachInfoBtn();

  $('#add-classification-lbl').on('click' , function(event){
      $.ajax({
        url: '../php_2/populate_pat_class.php',
        method: "POST",
        success: function(response) {
            // response = JSON.parse(response); 
            console.log(response)
            document.getElementById('populate-patclass-div').innerHTML = ''
            document.getElementById('populate-patclass-div').innerHTML = response
        }
    });
  })

  $(document).on('click', '.classification-sub-div', function(event){
    // set the input fields to unclickabl
    $('#delete-classification-btn').removeClass('opacity-50 pointer-events-none')
    single_classification_clicked = $('.classification-sub-div').eq(global_classification_divs_index).text()
  })

  // add new classification
  $('#add-classification-btn').on('click' , function(event){
    console.log($('#add-classification-input').val())
    $.ajax({
        url: '../php_2/add_classification.php',
        data : {  
            classification : $('#add-classification-input').val(),
            what : 'add'
        },
        method: "POST",
        success: function(response) {
            // response = JSON.parse(response); 
            console.log(response)
            $('#add-classification-icon').removeClass('hidden')
            $('#add-classification-input').addClass('hidden')

            $('#modal-body-incoming-success').text('Added Successfully')
            $('#myModal-success').modal('show');
        }
    });
  })


  $(document).on('click', '#add-classification-icon', function(event){
    $('#add-classification-icon').addClass('hidden')
    $('#add-classification-input').removeClass('hidden')
    $('#add-classification-btn').removeClass('opacity-50 pointer-events-none')

    // Get the elements
    const dynamicWidthDiv = document.getElementById('dynamic-width-div');
    const inputField = document.getElementById('add-classification-input');

    // Listen for input events on the input field
    inputField.addEventListener('input', function(event) {
        if (event.inputType === 'deleteContentBackward') {
          dynamicWidthDiv.style.width = inputField.scrollWidth - 8 + 'px';
          inputField.style.width = inputField.scrollWidth - 8 + 'px';
        }else{
          dynamicWidthDiv.style.width = inputField.scrollWidth + 'px';
          inputField.style.width = inputField.scrollWidth + 'px';
        }
    });
  })

  $(document).on('click', '#delete-classification-btn', function(event){
    console.log('"' + single_classification_clicked + '"')
    $.ajax({
      url: '../php_2/add_classification.php',
      method: "POST",
      data : {
        classification : single_classification_clicked,
        what : 'delete'
      },
      success: function(response) {
          console.log(response)
          $('#modal-body-incoming-success').text('Deleted Successfully')
          $('#myModal-success').modal('show');
        }
    });
  })


  $(document).on('click', '.see-more-btn', function(event){
    // console.log(document.querySelectorAll('.number_users')[global_breakdown_index])
    console.log(global_breakdown_index)
    
      if(toggle_accordion_obj[global_breakdown_index]){
          document.querySelectorAll('.table-tr')[global_breakdown_index].style.height = "350px"
          document.querySelectorAll('.breakdown-div')[global_breakdown_index].style.display = 'flex'
          document.querySelectorAll('.number_users')[global_breakdown_index].style.display = 'none'

          $('.see-more-btn').eq(global_breakdown_index).removeClass('top-5')
          $('.see-more-btn').eq(global_breakdown_index).addClass('top-[45%]')

          toggle_accordion_obj[global_breakdown_index] = false
      }else{
          document.querySelectorAll('.table-tr')[global_breakdown_index].style.height = "70px"
          document.querySelectorAll('.breakdown-div')[global_breakdown_index].style.display = 'none'
          document.querySelectorAll('.number_users')[global_breakdown_index].style.display = 'flex'

          $('.see-more-btn').eq(global_breakdown_index).addClass('top-5')
          $('.see-more-btn').eq(global_breakdown_index).removeClass('top-[45%]')

          toggle_accordion_obj[global_breakdown_index] = true
      }

      if($('.see-more-btn').eq(global_breakdown_index).hasClass('fa-square-caret-down')){
        console.log('asdf')
        $('.see-more-btn').eq(global_breakdown_index).removeClass('fa-square-caret-down')
        $('.see-more-btn').eq(global_breakdown_index).addClass('fa-square-caret-up')
      }else{
        console.log('fdsa')
        $('.see-more-btn').eq(global_breakdown_index).addClass('fa-square-caret-down')
        $('.see-more-btn').eq(global_breakdown_index).removeClass('fa-square-caret-up')
      }
      
  })

  let prev_info_arr = []
  $(document).on('click', '.edit-info-btn', function(event){
    console.log('here')
    if($('.edit-info-btn').eq(global_breakdown_index).text() === 'Edit'){
      prev_info_arr = []
      for(let i = global_breakdown_index * 5; i <= (global_breakdown_index * 5) + 4; i++){
        prev_info_arr.push( $('.edit-users-info').eq(i).val())
        $('.edit-users-info').eq(i).removeClass('pointer-events-none')
        $('.edit-users-info').eq(i).addClass('border-b border-[#198754]')
      }

      $('.cancel-info-btn').eq(global_breakdown_index).removeClass('hidden')
      $('.edit-info-btn').eq(global_breakdown_index).text('Save')
      $('.edit-info-btn').eq(global_breakdown_index).removeClass('bg-[#0d6efd]')
      $('.edit-info-btn').eq(global_breakdown_index).addClass('bg-[#198754]')
  
      for(let i = 0; i < $('.edit-info-btn').length; i++){
        if(i !== global_breakdown_index){
          $('.edit-info-btn').eq(i).addClass('pointer-events-none')
          $('.edit-info-btn').eq(i).addClass('opacity-30')
        }
      }
    }else if($('.edit-info-btn').eq(global_breakdown_index).text() === 'Save'){
      let temp = [];
      for(let i = global_breakdown_index * 5; i <= (global_breakdown_index * 5) + 4; i++){
        temp.push( $('.edit-users-info').eq(i).val())
      }

      console.log(temp)
      const data = {
        prev_last_name : prev_info_arr[0],
        prev_first_name : prev_info_arr[1],
        prev_middle_name : prev_info_arr[2],
        prev_username : prev_info_arr[3],
        prev_password : prev_info_arr[4],

        last_name : temp[0],
        first_name : temp[1],
        middle_name : temp[2],
        username : temp[3],
        password : temp[4],
        hospital_code : $('.hcode-edit-info').eq(global_breakdown_index).val()
      }

      console.log(data)
      
      $.ajax({
        url: '../php_2/edit_user_acc.php',
        method: "POST",
        data : data,
        success: function(response) {
            console.log(response)
            $('#myModal-prompt').modal('show');

            // set the input fields to unclickable
            for(let i = 0; i <= (global_breakdown_index * 5) + 4; i++){
              $('.edit-users-info').eq(i).addClass('pointer-events-none')
              $('.edit-users-info').eq(i).removeClass('border-b border-[#198754]')
            }

            $('.cancel-info-btn').eq(global_breakdown_index).addClass('hidden')
            $('.edit-info-btn').eq(global_breakdown_index).addClass('bg-[#0d6efd]')
            $('.edit-info-btn').eq(global_breakdown_index).removeClass('bg-[#198754]')
            $('.edit-info-btn').eq(global_breakdown_index).text('Edit')

            for(let i = 0; i < $('.edit-info-btn').length; i++){
              $('.edit-info-btn').eq(i).removeClass('pointer-events-none')
              $('.edit-info-btn').eq(i).removeClass('opacity-30')
            }

            $('#myModal-hospitalAndUsers').modal('hide');
          }
      });
    }
  })

  // sort-up-btn
  $('.sort-up-btn').on('click' , function(event){
    let index = parseInt(event.target.id.match(/\d+/)[0]);

    $('.sort-up-btn').eq(index).removeClass('opacity-30')
    $('.sort-up-btn').eq(index).removeClass('hover:opacity-100')
    $('.sort-up-btn').eq(index).addClass('opacity-100')

    $('.sort-down-btn').eq(index).addClass('opacity-30')
    $('.sort-down-btn').eq(index).addClass('hover:opacity-100')
    $('.sort-down-btn').eq(index).removeClass('opacity-100')

    var div = document.querySelector(".table-body");
    while (div.firstChild) {
        div.removeChild(div.firstChild);
    }

    let temp = ""
    switch(event.target.id){
      case "sort-up-btn-id-0": temp = "hospital_name_ASC"; break;
      case "sort-up-btn-id-1": temp = "hospital_code_ASC"; break;
      case "sort-up-btn-id-2": temp = "hospital_isVerified_ASC"; break;
    }

    console.log(temp)

    $.ajax({
      url: '../php_2/fetch_admin_search_table.php',
      method: "POST",
      data : {
        temp : temp
      },
      success: function(response) {
          // console.log(response)
          div.innerHTML += response
          attachSeeMoreBtn();
          attachInfoBtn();
        }
    });
  })

  $('.sort-down-btn').on('click' , function(event){
    let index = parseInt(event.target.id.match(/\d+/)[0]);

    $('.sort-down-btn').eq(index).removeClass('opacity-30')
    $('.sort-down-btn').eq(index).removeClass('hover:opacity-100')
    $('.sort-down-btn').eq(index).addClass('opacity-100')

    $('.sort-up-btn').eq(index).addClass('opacity-30')
    $('.sort-up-btn').eq(index).addClass('hover:opacity-100')
    $('.sort-up-btn').eq(index).removeClass('opacity-100')

    var div = document.querySelector(".table-body");
    while (div.firstChild) {
        div.removeChild(div.firstChild);
    }

    let temp = ""
    switch(event.target.id){
      case "sort-down-btn-id-0": temp = "hospital_name_DESC"; break;
      case "sort-down-btn-id-1": temp = "hospital_code_DESC"; break;
      case "sort-down-btn-id-2": temp = "hospital_isVerified_DESC"; break;
    }
    console.log(temp)
    $.ajax({
      url: '../php_2/fetch_admin_search_table.php',
      method: "POST",
      data : {
        temp : temp
      },
      success: function(response) {
          // console.log(response)
          div.innerHTML += response
          attachSeeMoreBtn();
          attachInfoBtn();
        }
    });
  })
  
  $(document).on('click', '.cancel-info-btn', function(event){
    // set the input fields to unclickable
    for(let i = 0; i <= (global_breakdown_index * 5) + 4; i++){
      $('.edit-users-info').eq(i).addClass('pointer-events-none')
      $('.edit-users-info').eq(i).removeClass('border-b border-[#198754]')
    }

    $('.cancel-info-btn').eq(global_breakdown_index).addClass('hidden')
    $('.edit-info-btn').eq(global_breakdown_index).addClass('bg-[#0d6efd]')
    $('.edit-info-btn').eq(global_breakdown_index).removeClass('bg-[#198754]')
    $('.edit-info-btn').eq(global_breakdown_index).text('Edit')

    console.log(prev_info_arr)
    let j = 0;
    for(let i = global_breakdown_index * 5; i <= (global_breakdown_index * 5) + 4; i++){
      $('.edit-users-info').eq(i).val(prev_info_arr[j])
      j += 1
    }

    for(let i = 0; i < $('.edit-info-btn').length; i++){
      $('.edit-info-btn').eq(i).removeClass('pointer-events-none')
      $('.edit-info-btn').eq(i).removeClass('opacity-30')
    }
  })

})
