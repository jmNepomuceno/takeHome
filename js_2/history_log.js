$(document).ready(function(){
  let intervalHistoryLog;

  let inactivityTimer;
  let userIsActive = true;
  function handleUserActivity() {
      userIsActive = true;
      // Additional code to handle user activity if needed
      clearInterval(intervalHistoryLog)

  }

  function handleUserInactivity() {
      userIsActive = false;
      // Additional code to handle user inactivity if needed
      intervalHistoryLog = setInterval(fetchHistoryLog, 10000);
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
  console.log($('#total-processed-refer-inp').val())
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
      url: '../php_2/history_filter.php',
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
      success: function(response) {
          response = JSON.parse(response);  
          // console.log(response);
          // console.log('pot')

          $('#notif-span').text(response.length);
          $('#notif-circle').removeClass('hidden');
              
              // populate notif-sub-div
              // document.querySelector('.notif-sub-div').innerHTML = 

              let type_counter = []
              for(let i = 0; i < response.length; i++){

                  if(!type_counter.includes(response[i]['type'])){
                      type_counter.push(response[i]['type'])
                  }
              }

              // console.log(type_counter)
              
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
                      <div class="h-[30px] w-[90%] border border-black flex flex-row justify-evenly items-center mt-1 bg-transparent text-white opacity-30 hover:opacity-100">\
                      <h4 class="font-bold text-lg">' + type_counts + '</h4>\
                          <h4 class="font-bold text-lg">' + type_var + '</h4>\
                      </div>\
                  ';
                  }else{
                      document.getElementById('notif-sub-div').innerHTML += '\
                      <div class="h-[30px] w-[90%] border border-black flex flex-row justify-evenly items-center mt-1 bg-white opacity-30 hover:opacity-100">\
                      <h4 class="font-bold text-lg">' + type_counts + '</h4>\
                          <h4 class="font-bold text-lg">' + type_var + '</h4>\
                      </div>\
                  ';
                  }
              }
          
          fetch_timer = setTimeout(fetchMySQLData, 5000);
      }
  });
  }

  fetchMySQLData(); 

  function fetchHistoryLog() {
    console.log('fds')
    $.ajax({
        url: '../php_2/fetch_interval.php',
        method: "POST",
        data : {
            from_where : 'history_log'
        },
        success: function(data) {
            // document.querySelector('.history-container').innerHTML = data
        }
    });
  }

  intervalHistoryLog = setInterval(fetchHistoryLog, 10000);
  
  fetchHistoryLog();

    $('#side-bar-mobile-btn').on('click' , function(event){
      document.querySelector('#side-bar-div').classList.toggle('hidden');
    })

    $('#logout-btn').on('click' , function(event){
      event.preventDefault(); 
      $('#myModal-prompt #modal-title-incoming').text('Warning')
      $('#myModal-prompt #ok-modal-btn-incoming').text('No')
  
      $('#myModal-prompt #yes-modal-btn-incoming').text('Yes');
      $("#myModal-prompt #yes-modal-btn-incoming").css("display", "flex")
  
      // Are you sure you want to logout?
      $('#myModal-prompt #modal-body-incoming').text('Are you sure you want to logout?');
  })
  
  $('#yes-modal-btn-incoming').on('click' , function(event){
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
              // window.location.href = "http://10.10.90.14:8079/index.php" 
              window.location.href = "https://sdnplus.bataanghmc.net/" 
          }
      });
  })

$('#notif-sub-div').on('click' , function(event){
  if($('#notif-span').val() === 0){
      $('#notif-circle').addClass('hidden')
      document.getElementById("notif-sound").pause();
      document.getElementById("notif-sound").currentTime = 0;
  }else{
      window.location.href = "http://192.168.42.222:8035/main.php?loadContent=php/incoming_form.php"

      // window.location.pathname = "/newpage.html";
      current_page = "incoming_page"
      $('#current-page-input').val(current_page)
      $('#notif-sub-div').addClass('hidden')
  }
})

  $('#nav-account-div').on('click' , function(event){
    event.preventDefault();
    if($("#nav-drop-account-div").css("display") === "none"){
      $("#nav-drop-account-div").css("display", "flex")
    }else{
        $("#nav-drop-account-div").css("display", "none")
    }
  })

  $('#nav-account-div').on('click' , function(event){
    event.preventDefault();
    document.querySelector('#nav-drop-account-div').classList.toggle('hidden');
  })

  $('#admin-module-btn').on('click' , function(event){
    event.preventDefault();
    window.location.href = "../php_2/admin.php";
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

  $('#admin-module-id').on('click' , function(event){
    event.preventDefault();
    window.location.href = "../php_2/admin.php";
  })
    
})