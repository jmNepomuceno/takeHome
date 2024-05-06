$(document).ready(function(){
    $('#resend-otp-btn').on('click' , function(event){
        function generateRandomNumber(min, max) {
            // console.log("w/out " , Math.random() * (max - min + 1))
            return Math.floor(Math.random() * (max - min + 1) + min);
        }
        
        let OTP = generateRandomNumber(100000, 999999);
        const data = {
            hospital_code : $('#sdn-hospital-code').val(),
            OTP : OTP,
            email : $('#sdn-email-address').val()
        }

        console.log(data)

        $.ajax({
            url: './php_2/resend_otp.php',
            method: "POST",
            data:data,
            success: function(response){
                console.log(response)
                // sdn_loading_modal_div.classList.remove('z-10')
                // sdn_loading_modal_div.classList.add('hidden')
                // const otp_modal_div = document.querySelector('.otp-modal-div');
                // otp_modal_div.className = "otp-modal-div z-10 absolute flex flex-col justify-start items-center gap-3 w-11/12 sm:w-2/6 h-80 translate-y-[200px] sm:translate-y-[350px] translate-x-50px border bg-white rounded-lg"
                // Set the countdown duration in seconds (5 minutes)

                // $('#resend-otp-btn').addClass('opacity-50 pointer-events-none')
                $('#resend-otp-btn').css('opacity', '0.5')
                $('#resend-otp-btn').css('pointer-events', 'none')
                const countdownDuration = 300;
                    
                // Get the timer element
                const timerElement = document.getElementById('resend-otp-timer');

                // Initialize the countdown value
                let countdown = countdownDuration;

                // Update the timer display function
                function updateTimer() {
                const minutes = Math.floor(countdown / 60);
                const seconds = countdown % 60;

                // Display minutes and seconds
                timerElement.textContent = `Resend OTP after: ${minutes}m ${seconds}s`;

                // Check if the countdown has reached zero
                if (countdown === 0) {
                    clearInterval(timerInterval); // Stop the timer
                    timerElement.textContent = '00:00';
                    $('#resend-otp-btn').css('opacity', '1')
                    $('#resend-otp-btn').css('pointer-events', 'none')
                } else {
                    countdown--; // Decrement the countdown
                }
                }

                // Set up the timer to update every second
                const timerInterval = setInterval(updateTimer, 1000);
            }
        })

    })
})