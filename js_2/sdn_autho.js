$(document).ready(function(){

    $('#sdn-autho-confirm-password').on('input' , function() {
        const sdn_autho_password = document.querySelector('#sdn-autho-password')
        const sdn_autho_confirm_password = document.querySelector('#sdn-autho-confirm-password')

        let inputValue = $(this).val();
        if($('#sdn-autho-password').val() === inputValue){
            // $('#sdn-autho-password, #sdn-autho-confirm-password').removeClass('border-red-600');
            // $('#sdn-autho-password, #sdn-autho-confirm-password').addClass('border-[#666666]');

            sdn_autho_password.style.border = '3px solid #4fe34f'
            sdn_autho_confirm_password.style.border = '3px solid #4fe34f'

        }else{
            // $('#sdn-autho-password, #sdn-autho-confirm-password').addClass('border-red-600');
            // $('#sdn-autho-password, #sdn-autho-confirm-password').removeClass('border-[#666666]');

            sdn_autho_password.style.border = '2px solid red'
            sdn_autho_confirm_password.style.border = '2px solid red'
        }
      });

      $('#sdn-autho-password').on('input' , function() {
        const sdn_autho_password = document.querySelector('#sdn-autho-password')
        const sdn_autho_confirm_password = document.querySelector('#sdn-autho-confirm-password')
        
        let inputValue = $(this).val();
        if($('#sdn-autho-password').val() === inputValue){
            // $('#sdn-autho-password, #sdn-autho-confirm-password').removeClass('border-red-600');
            // $('#sdn-autho-password, #sdn-autho-confirm-password').addClass('border-[#666666]');

            sdn_autho_password.style.border = '2px solid #666666'
            sdn_autho_confirm_password.style.border = '2px solid #666666'

        }else{
            // $('#sdn-autho-password, #sdn-autho-confirm-password').addClass('border-red-600');
            // $('#sdn-autho-password, #sdn-autho-confirm-password').removeClass('border-[#666666]');

            sdn_autho_password.style.border = '2px solid red'
            sdn_autho_confirm_password.style.border = '2px solid red'
        }
      });
// $('#myModal').modal('show');
    $('#authorization-confirm-btn').on('click' , function(event){
        event.preventDefault();

        const currentDateTime = new Date();
        const year = currentDateTime.getFullYear();
        const month = currentDateTime.getMonth() + 1; // Month is zero-based, so add 1 to get the correct month.
        const day = currentDateTime.getDate();
        const hours = currentDateTime.getHours();
        const minutes = currentDateTime.getMinutes();
        const seconds = currentDateTime.getSeconds();
        let created_at = (`${year}-${month}-${day} ${hours}:${minutes}:${seconds}`)
        // console.log(created_at);
        // USER COUNT SAKA USER TYPE GL HF TOMORROW :)))))))

        const reg_inputs = [$('#sdn-autho-hospital-code-id'), $('#sdn-autho-cipher-key-id'), $('#sdn-autho-last-name-id'), $('#sdn-autho-first-name-id'), $('#sdn-autho-middle-name-id'),
                            $('#sdn-autho-username'), $('#sdn-autho-password'), $('#sdn-autho-confirm-password')]
        let filled_inputs = false

        reg_inputs.forEach((elem)=>{
            if(elem.val() !== "" ){
                filled_inputs = true
            }
        })

        if(filled_inputs){
            if($('#sdn-autho-password').val() === $('#sdn-autho-confirm-password').val()){
                const data = {
                    hospital_code : parseInt($('#sdn-autho-hospital-code-id').val()),
                    cipher_key : $('#sdn-autho-cipher-key-id').val(),
                    last_name : $('#sdn-autho-last-name-id').val(),
                    first_name : $('#sdn-autho-first-name-id').val(),
                    middle_name : $('#sdn-autho-middle-name-id').val(),
                    extension_name : $('#sdn-autho-ext-name-id').val(),
                    user_name : $('#sdn-autho-username').val(),
                    pass_word : $('#sdn-autho-password').val(),
                    confirm_password : $('#sdn-autho-confirm-password').val(),
                    created_at : created_at,
                    usert_ype: 'Sample',
                    user_isActive: false
                }
    
                if(data.extension_name === ""){
                    data.extension_name = "N/A"
                }
        
                // console.log(data)
        
                for (var key in data) {
                    if (data.hasOwnProperty(key)) {
                        console.log(key + " -> " + data[key] + " -> " + typeof data[key]);
                    }
                }
        
                $.ajax({
                    url: './php_2/sdn_autho.php',
                    method: "POST",
                    data:data,
                    success: function(response){
                        console.log(response)
                        if(response === 'maximum'){
                            $('#modal-title').text('Warning')
                            $('#modal-icon').addClass('fa-triangle-exclamation')
                            $('#modal-icon').removeClass('fa-circle-check')
                            $('#yes-modal-btn').addClass('hidden')
                            $('#modal-body').text('The maximum number of users has already signed up from your hospital.')
                            $('#myModal').modal('show'); 
                        }
                        if(response === 'not valid'){
                            $('#modal-title').text('Warning')
                            $('#modal-icon').addClass('fa-triangle-exclamation')
                            $('#modal-icon').removeClass('fa-circle-check')
                            $('#modal-body').text('Your hospital code is not yet registered with our database.')
                            $('#myModal').modal('show'); 
                        }
                        if(response === 'success'){
                            $('#modal-title').text('Successed')
                            $('#modal-icon').removeClass('fa-triangle-exclamation')
                            $('#modal-icon').addClass('fa-circle-check')
                            $('#modal-body').text('Successfully Created an account!')
                            $('#myModal').modal('show'); 
    
                            $('#sdn-autho-hospital-code-id').val("")
                            $('#sdn-autho-cipher-key-id').val("")
                            $('#sdn-autho-last-name-id').val("")
                            $('#sdn-autho-first-name-id').val("")
                            $('#sdn-autho-middle-name-id').val("")
                            $('#sdn-autho-ext-name-id').val("")
                            $('#sdn-autho-username').val("")
                            $('#sdn-autho-password').val("")
                            $('#sdn-autho-confirm-password').val("")
                        }

                        if(response === 'same_username'){
                            $('#modal-title').text('Warning')
                            $('#modal-icon').addClass('fa-triangle-exclamation')
                            $('#modal-icon').removeClass('fa-circle-check')
                            $('#modal-body').text('The username is already in use by another user.')
                            $('#myModal').modal('show'); 
                        }
                    }
                })
            }
        }

        
    })
})



