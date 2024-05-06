$(document).ready(function(){
    $('#sdn-otp-modal-btn-close').on('click' , function(event){
        // main OTP modal div
        const otp_modal_div = document.querySelector('.otp-modal-div')

        // handle close button for otp modal
        let response= confirm("Do you want to cancel the registration?");
        if (response == true) {
            otp_modal_div.className = "otp-modal-div hidden absolute flex flex-col justify-start items-center gap-3 w-11/12 sm:w-2/6 h-80 translate-y-[200px] sm:translate-y-[350px] translate-x-50px border bg-teleCreateAccColor rounded-lg"
            const data = {
                hospital_code : $('#sdn-hospital-code').val(),
            }
            console.log("closed, " + 1)
            $.ajax({
                url: './php_2/closed_otp.php',
                method: "POST",
                data:data,
                success: function(){
                    otp_modal_div.style.display = "none"
                }
            })
        }else {
            // alert("You may proceed, but you will only have limited access to features on the website.");
        }
    })
})