// ************************************************************************** 
//  ME ME ME ME ME ME ME
$(document).ready(function(){
  const myModal = new bootstrap.Modal(document.getElementById('myModal-dashboardIncoming'));
  
  if(number_of_referrals === 0){
    myModal.show()
  }

  $('#total-processed-refer').text($('#total-processed-refer-inp').val())

  const playAudio = () =>{
    let audio = document.getElementById("notif-sound")
    audio.muted = false;
    audio.play().catch(function(error){
        'Error playing audio: ' , error
    }) 
  }

  function renderPieChart(chart, dataArray) {
    let xValues = [];
    for (let i = 0; i < dataArray.length; i++) {
        switch (chart) {
            case "case_type": xValues.push(dataArray[i]['type']); break;
            case "rhu": xValues.push(dataArray[i]['referred_by']); break;
            case "case_category": xValues.push(dataArray[i]['pat_class']); break;
        }
    }
    xValues.sort();

    var counts = {};

    xValues.forEach(function(item) {
        counts[item] = (counts[item] || 0) + 1;
    });

    var uniqueArray = Object.keys(counts);
    var duplicatesCount = uniqueArray.map(function(item) {
        return counts[item];
    });

    xValues = uniqueArray;
    const yValues = duplicatesCount;
    const barColors = [
        "#b91d47",
        "#00aba9",
        "#2b5797",
        "#e8c3b9",
        "#1e7145"
    ];

    let what_chart = "";
    switch (chart) {
        case "case_type": what_chart = "myChart-2"; break;
        case "rhu": what_chart = "myChart-3"; break;
        case "case_category": what_chart = "myChart-1"; break;
    }

    new Chart(document.getElementById(what_chart), {
        type: "pie",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: barColors,
                data: yValues,
                label: "Data"
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    align: 'center',
                    labels: {
                        font: {
                            size: 13, // Set the font size for legend labels
                            weight: 'bold' // Make legend labels bold
                        },
                        boxWidth: 15,
                        padding: 20
                    }
                },
                tooltip: {
                    bodyFont: {
                        size: 12, // Set the font size for tooltips
                        weight: 'bold' // Make tooltips font bold
                    }
                },
                datalabels: {
                    color: '#000',
                    font: {
                        weight: 'bold', // Make data labels bold
                        size: 14 // Font size for data labels
                    }
                }
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
                }
            }
        }
    });
}


renderPieChart("rhu" , dataReferFrom)
renderPieChart("case_type" , dataPatType)
renderPieChart("case_category" , dataPatClass)

$('#notif-div').on('click' , function(event){
//   if ($('#notif-sub-div').hasClass('hidden')) {
//     $('#notif-sub-div').removeClass('hidden');
// } else {
//     $('#notif-sub-div').addClass('hidden');
// }
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

  $('#history-log-btn').on('click' , function(event){
    event.preventDefault();
    console.log('here')
    window.location.href = "../php_2/history_log.php";
  })

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

    $('#myModal-dashboardIncoming').modal('show');
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

  $('#nav-account-div').on('click' , function(event){
    event.preventDefault();
    if($("#nav-drop-account-div").css("display") === "none"){
      $("#nav-drop-account-div").css("display", "flex")
    }else{
        $("#nav-drop-account-div").css("display", "none")
    }
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

  $('#filter-date-btn').on('click' , function(event){
    event.preventDefault();
    console.log('here')

    const data = {
      from_date : $('#from-date-inp').val(),
      to_date : $('#to-date-inp').val(),
      where : 'incoming'
    }

    console.log(data)
    
    $.ajax({
      url: '../php_2/filter_date_incoming.php',
      method: "POST",
      data : data,
      success: function(response) { 
        response = JSON.parse(response);
        console.log(response)

        $('#total-processed-refer').text(response.totalReferrals)
        $('#average-reception-id').text(response.averageDuration_reception)
        $('#average-sdn-approve-id').text(response.average_sdn_average)
        $('#average-interdept-approve-id').text(response.averageTime_interdept)
        $('#average-approve-id').text(response.averageDuration_approval)
        $('#average-total-id').text(response.averageDuration_total)
        $('#fastest-id').text(response.fastest_response_final)
        $('#slowest-id').text(response.slowest_response_final)
      }
    });


    // populate table
    $.ajax({
      url: '../php_2/filter_date_table_incoming.php',
      method: "POST",
      data : data,
      success: function(response) {
        // console.log(response)
        document.getElementById('tbody-class').innerHTML = response
      }
    });

    $.ajax({
      url: '../php_2/filter_chart_incoming.php',
      method: "POST",
      data : data,
      success: function(response) {
        response = JSON.parse(response);
        console.log(response)

        const referredByObj = [];
        const patClassObj = [];
        const typeObj = [];

        response.forEach(item => {
          // Check each item for its key and push an object containing both the key and value into the corresponding array
          if ('referred_by' in item) {
            referredByObj.push({ referred_by: item.referred_by });
          } else if ('pat_class' in item) {
            patClassObj.push({ pat_class: item.pat_class });
          } else if ('type' in item) {
            typeObj.push({ type: item.type });
          }
        });

        for(let i = 1; i <= 3; i++){
          document.getElementById('main-graph-sub-div-' + i).removeChild(document.getElementById('myChart-'+ i))
          let canva = document.createElement('canvas')
          canva.id = 'myChart-'+ i
          document.getElementById('main-graph-sub-div-'+ i).appendChild(canva)
        }

        for(let i = 0; i < 3 ; i++){

        }

        renderPieChart("rhu" , referredByObj)
        renderPieChart("case_type" , typeObj)
        renderPieChart("case_category" , patClassObj)
      }
    });

  })

  // Get the timer element
  let recep_time = document.getElementById('average-reception-id').textContent
  let approve_time = document.getElementById('average-approve-id').textContent
  // let total_time = document.getElementById('average-total-id').textContent
  let fastest_time = document.getElementById('fastest-id').textContent
  let slowest_time = document.getElementById('slowest-id').textContent

  // Get the initial time in seconds
  var initialTime = getTimeInSeconds('00:00:01');

  // Set the initial time
  setTimer(initialTime);

  // Start the timer
  setInterval(function() {
      // Increment the time by 1 second
      initialTime++;

      // Update the timer display
      setTimer(initialTime , "reception");
      setTimer(initialTime , "approve");
      setTimer(initialTime , "total");
      setTimer(initialTime , "fastest");
      setTimer(initialTime , "slowest");
  }, 5);

  // Function to convert HH:MM:SS format to seconds
  function getTimeInSeconds(timeString) {
      var timeArray = timeString.split(':');
      return parseInt(timeArray[0]) * 3600 + parseInt(timeArray[1]) * 60 + parseInt(timeArray[2]);
  }

  // Function to set the timer display
  function setTimer(seconds, elem) {
      // let real_time = getTimeInSeconds('00:01:38')
      let real_time;  
      // = getTimeInSeconds('00:05:31')
      switch(elem){
        case 'reception': real_time = getTimeInSeconds(recep_time); break;
        case 'approve': real_time = getTimeInSeconds(approve_time); break; 
        // case 'total': real_time = getTimeInSeconds(total_time); break;
        case 'fastest': real_time = getTimeInSeconds(fastest_time); break;
        case 'slowest': real_time = getTimeInSeconds(slowest_time); break;
      }


      if(real_time >= seconds){
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor((seconds % 3600) / 60);
        var remainingSeconds = seconds % 60;

        // Format the time as HH:MM:SS
        var formattedTime = pad(hours) + ':' + pad(minutes) + ':' + pad(remainingSeconds);
        
        // Update the timer element content
        // document.getElementById('average-reception-id').textContent = formattedTime;
        switch(elem){
          case 'reception': document.getElementById('average-reception-id').textContent = formattedTime;; break;
          case 'approve':document.getElementById('average-approve-id').textContent = formattedTime;; break;
          case 'total': document.getElementById('average-total-id').textContent = formattedTime; break;
          case 'fastest': document.getElementById('fastest-id').textContent = formattedTime;; break;
          case 'slowest': document.getElementById('slowest-id').textContent = formattedTime;; break;
        }
      }else{
        clearInterval()
      }

  }

  // Function to pad single-digit numbers with a leading zero
  function pad(number) {
      return (number < 10) ? '0' + number : number;
  }

})

