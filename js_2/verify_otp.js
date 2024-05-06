$(document).ready(function(){
    $('#otp-verify-btn').on('click' , function(event){
        let otp_input_1 = $('#otp-input-1').val().toString()
        let otp_input_2 = $('#otp-input-2').val().toString()
        let otp_input_3 = $('#otp-input-3').val().toString()
        let otp_input_4 = $('#otp-input-4').val().toString()
        let otp_input_5 = $('#otp-input-5').val().toString()
        let otp_input_6 = $('#otp-input-6').val().toString()
        let total = parseInt(otp_input_1 + otp_input_2 + otp_input_3 + otp_input_4 + otp_input_5 + otp_input_6)
        
        const data = {
            otp_number : total,
            hospital_code : $('#sdn-hospital-code').val(),
        }

        // console.log(data.otp_number, " type of " , typeof(data.otp_number))
        $.ajax({
            url: './php_2/verify_otp.php',
            method: "POST",
            data:data,
            success: function(response){
                
                if(response === 'verified'){
                    $('#myModal').modal('show');
                    // sdn_loading_modal_div.classList.remove('z-10')
                    // sdn_loading_modal_div.classList.add('hidden')
                    const otp_modal_div = document.querySelector('.otp-modal-div');
                    const sdn_loading_div = document.querySelector('.sdn-loading-div');
                    otp_modal_div.style.display = 'none'
                    otp_modal_div.style.position = ''

                    sdn_loading_div.style.display = 'none'
                    
                    // magic gatas = after ng microfiber cloth
                    // helmet spray
                    // guapo motorcycle soap
                    // microfiber cloth = pag tuyo na
                    // chamois cloth = after maligo
                    // footrest
                    // bomber jacket
                    // ls2

                    $('#sdn-hospital-name').val('')
                    $('#sdn-hospital-code').val('')

                    $('#sdn-region-select').val('')
                    $('#sdn-province-select').val('')
                    $('#sdn-city-select').val('')
                    $('#sdn-brgy-select').val('')
                    $('#sdn-zip-code').val('')
                    $('#sdn-email-address').val('')
                    $('#sdn-landline-no').val('')

                    $('#sdn-hospital-mobile-no').val('')

                    $('#sdn-hospital-director').val('')
                    $('#sdn-hospital-director-mobile-no').val('')

                    $('#sdn-point-person').val('')
                    $('#sdn-point-person-mobile-no').val('')

                    $('#modal-title').text('Successed')
                    $('#modal-icon').removeClass('fa-triangle-exclamation')
                    $('#modal-icon').addClass('fa-circle-check')
                    $('#modal-body').text('Verified OTP successfully')
                    $('#ok-modal-btn').text('OK')

                    $('#myModal').modal('show');

                    $('#otp-input-1').val("")
                    $('#otp-input-2').val("")
                    $('#otp-input-3').val("")
                    $('#otp-input-4').val("")
                    $('#otp-input-5').val("")
                    $('#otp-input-6').val("")
                }else{
                    $('#modal-title').text('Warning')
                    $('#modal-icon').addClass('fa-triangle-exclamation')
                    $('#modal-icon').removeClass('fa-circle-check')
                    $('#modal-body').text('Wrong OTP number')
                    $('#ok-modal-btn').text('OK')

                    $('#myModal').modal('show');

                    $('#otp-input-1').val("")
                    $('#otp-input-2').val("")
                    $('#otp-input-3').val("")
                    $('#otp-input-4').val("")
                    $('#otp-input-5').val("")
                    $('#otp-input-6').val("")

                    $('#otp-input-1').addClass("border-2 border-red-400")
                    $('#otp-input-2').addClass("border-2 border-red-400")
                    $('#otp-input-3').addClass("border-2 border-red-400")
                    $('#otp-input-4').addClass("border-2 border-red-400")
                    $('#otp-input-5').addClass("border-2 border-red-400")
                    $('#otp-input-6').addClass("border-2 border-red-400")
                }

            }
        })

    })

    window.addEventListener('beforeunload', function (event) {
        // Your code to execute before the page is reloaded
        // For example, you might want to show a confirmation message
    
        const data = {
            hospital_code : $('#sdn-hospital-code').val(),
        }
        console.log("closed, " + 1)
        $.ajax({
            url: './php_2/closed_otp.php',
            method: "POST",
            data:data,
            success: function(){
                
            }
        })

        // var confirmationMessage = 'Are you sure you want to leave? All data will be remove upon leaving.';
        // event.returnValue = confirmationMessage;
        // return confirmationMessage; 
        
    });
})