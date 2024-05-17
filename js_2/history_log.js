$(document).ready(function(){
  let intervalHistoryLog;

  let inactivityTimer;
  let userIsActive = true;
  function handleUserActivity() {
      userIsActive = true;
      // Additional code to handle user activity if needed
      console.log('active')
      clearInterval(intervalHistoryLog)

  }

  function handleUserInactivity() {
      console.log('inactive')
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
        success: function(data) {
            console.log(data);
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

  function fetchHistoryLog() {
    console.log('fds')
    $.ajax({
        url: '../php_2/fetch_interval.php',
        method: "POST",
        data : {
            from_where : 'history_log'
        },
        success: function(data) {
            document.querySelector('.history-container').innerHTML = data
        }
    });
  }

  intervalHistoryLog = setInterval(fetchHistoryLog, 10000);
  
  fetchHistoryLog();

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
        url: '../php_2/save_process_time.php',
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

  $('#admin-module-id').on('click' , function(event){
    event.preventDefault();
    window.location.href = "../php_2/admin.php";
  })
    
})