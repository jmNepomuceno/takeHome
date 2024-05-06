$(document).ready(function(){
    const loadContent = (url) => {
        $.ajax({
            url:url,
            success: function(response){
                $('#container').html(response);
            }
        })
    }

    // loadContent('php/opd_referral_form.php?type=' + $('#tertiary-case').val() + "&code=" + $('#hpercode-input').val())
    // loadContent('../php_2/referral_form.php?type="OB"&code="BGHMC-0058"')

    $('#check-if-registered-btn').on('click' , function(event){
        console.log('here')
        if($("#check-if-registered-btn").css("width") === "50px"){
            $("#check-if-registered-btn").css("width" , "493px")
            $("#check-if-registered-div").css("right" , "8px")

            $("#check-if-registered-btn").css("border-top-left-radius" , "0.5rem")
            $("#check-if-registered-btn").css("border-top-right-radius" , "0.5rem")
            $("#check-if-registered-btn").css("border-bottom-left-radius" , "0")
            $("#check-if-registered-btn").css("border-bottom-right-radius" , "0")

            $("#check-if-registered-btn").css("border-top" , "5px solid #1f292e")
            $("#check-if-registered-btn").css("border-right" , "5px solid #1f292e")
            $("#check-if-registered-btn").css("border-left" , "5px solid #1f292e")

            $('#check-if-registered-h3').css('display' , 'block')
        }else {
            $("#check-if-registered-btn").css("width" , "50px")
            $("#check-if-registered-div").css("right" , "-500px")

            $("#check-if-registered-btn").css("border-top-left-radius" , "0.5rem")
            $("#check-if-registered-btn").css("border-top-right-radius" , "0.5rem")
            $("#check-if-registered-btn").css("border-bottom-left-radius" , "0.5rem")
            $("#check-if-registered-btn").css("border-bottom-right-radius" , "0.5rem")

            $("#check-if-registered-btn").css("border-top" , "none")
            $("#check-if-registered-btn").css("border-right" , "none")
            $("#check-if-registered-btn").css("border-left" , "none")

            $('#check-if-registered-h3').css('display' , 'none')
        }   
    })

    

    // console.log(document.querySelectorAll('.input-txt-classes-non'))
    //***********************************************************************************************************************/

    let input_arr = document.querySelectorAll('.input-txt-classes')
    let all_non_req_input_arr = document.querySelectorAll('.input-txt-classes-non')
    let all_input_arr = document.querySelectorAll('.input-txt-classes-non, .input-txt-classes')
    let zero_inputs = 0;
    let data;

    $('#same-as-perma-btn').on('click' , function(event){
        document.querySelector('#hperson-house-no-ca').value = document.querySelector('#hperson-house-no-pa').value
        document.querySelector('#hperson-street-block-ca').value = document.querySelector('#hperson-street-block-pa').value
        document.querySelector('#hperson-region-select-ca').value = document.querySelector('#hperson-region-select-pa').value

        let province_element_ca = document.createElement('option')
        province_element_ca.value = $('#hperson-province-select-pa').val()
        province_element_ca.text =  $('#hperson-province-select-pa').find(':selected').text()
        document.querySelector('#hperson-province-select-ca').appendChild(province_element_ca);
        document.querySelector('#hperson-province-select-ca').value = province_element_ca.value

        let city_element_ca = document.createElement('option')
        city_element_ca.value = $('#hperson-city-select-pa').val()
        city_element_ca.text =  $('#hperson-city-select-pa').find(':selected').text()
        document.querySelector('#hperson-city-select-ca').appendChild(city_element_ca);
        document.querySelector('#hperson-city-select-ca').value = city_element_ca.value

        let brgy_element_ca = document.createElement('option')
        brgy_element_ca.value = $('#hperson-brgy-select-pa').val()
        brgy_element_ca.text =  $('#hperson-brgy-select-pa').find(':selected').text()
        document.querySelector('#hperson-brgy-select-ca').appendChild(brgy_element_ca);
        document.querySelector('#hperson-brgy-select-ca').value = brgy_element_ca.value
        
        document.querySelector('#hperson-home-phone-no-ca').value = document.querySelector('#hperson-home-phone-no-pa').value
        document.querySelector('#hperson-mobile-no-ca').value = document.querySelector('#hperson-mobile-no-pa').value
        document.querySelector('#hperson-email-ca').value = document.querySelector('#hperson-email-pa').value
    })
    

    let age_value = 0
    $('#hperson-birthday').on('input' , function(event){
        //converting of birthdate
        const timestamp = Date.parse( $('#hperson-birthday').val());
        const date = new Date(timestamp)
        let year = date.getFullYear()
        let month = date.getMonth() + 1
        month = month <= 9 ? "0" + month.toString() : month
        let day = (date.getDate() < 10) ? "0" + date.getDate().toString() : date.getDate().toString()
        // console.log(year.toString() + "-" + month.toString() + "-" + day.toString())

        //calculating the age based on day of birth
        const dateOfBirth = year.toString() + "-" + month.toString() + "-" + day.toString()
        const age = calculateAge(dateOfBirth);
        age_value = age
        document.querySelector('#hperson-age').value = age_value

        // document.querySelector('#hperson-gender').value = response[i].patsex
    })


    $('#add-patform-btn-id').on('click' , function(event){
        event.preventDefault();

        if($('#add-patform-btn-id').text() == 'Add'){
            zero_inputs = 0;

            // check if the required inputs have values , if no, border color = red.
            for(let i = 0; i < input_arr.length; i++){
                if($(input_arr[i]).val() === ""){
                    $('.input-txt-classes').eq(i).css('border' , '2px solid red')
                    zero_inputs += 1
                }
            }

            zero_inputs = 0;
            if(zero_inputs >= 1){
                console.log('here')
                $('#modal-body').text('Please fill out the required fields.')
                $('#ok-modal-btn').text('OK')
                $('#modal-title').text('Warning')
                const myModal = new bootstrap.Modal(document.getElementById('myModal_pat_reg'));
                myModal.show();
            }else{
                $('#modal-title').text('Warning')
                // $('#modal-icon').attr('class', 'fa-triangle-exclamation');
                $('#modal-body').text('Are you sure with the information?')
                $('#ok-modal-btn').text('No')

                $('#yes-modal-btn').text('Register');
                $('#yes-modal-btn').css('display' , 'block')

                const myModal = new bootstrap.Modal(document.getElementById('myModal_pat_reg'));
                myModal.show();
            }
        }else if($('#add-patform-btn-id').text() == 'Refer'){
            $('#modal-title').text('Warning')
            // $('#modal-icon').addClass('fa-triangle-exclamation')
            // $('#modal-icon').removeClass('fa-circle-check')
            $('#modal-body').text('Confirmation?')
            $('#ok-modal-btn').text('No')

            $('#yes-modal-btn').text('Confirm');
            $('#yes-modal-btn').css('display' , 'flex')

            const myModal = new bootstrap.Modal(document.getElementById('myModal_pat_reg'));
            myModal.show();
        }
    })

    $('#clear-patform-btn-id').on('click' , function(event){
        if($('#clear-patform-btn-id').text() == "Cancel"){
            $('#modal-body').text('Are you sure you want to cancel the Referral?')
            $('#ok-modal-btn').text('No')

            $('#yes-modal-btn').text('Yes');

            $('#modal-title').text('Warning')

            $('#yes-modal-btn').css('display' , 'block')

            const myModal = new bootstrap.Modal(document.getElementById('myModal_pat_reg'));
            myModal.show();
            // $('#myModal').modal('show');

            for(let i = 0; i < all_input_arr.length; i++){
                $(all_input_arr[i]).css('pointer-events' , 'none')
                $(all_input_arr[i]).css('background' , '#cccccc')
            }
        }
        else{
            for(let i = 0; i < all_input_arr.length; i++){
                $(all_input_arr[i]).val('')
            }
        }
        
    })

    $('#yes-modal-btn').on('click' , function(event){
        // console.log($('#yes-modal-btn').val())
        if($('#yes-modal-btn').text() == 'Yes'){
            console.log('den')

            $('#ok-modal-btn').text('Ok')
            $('#clear-patform-btn-id').text('Clear')

            $('#add-patform-btn-id').text('Add')
            $('#add-patform-btn-id').css('pointer-events', 'auto');
            $('#add-patform-btn-id').css('opacity', '1');
            $('#add-patform-btn-id').css('margin-left', '0');

            $('#classification-dropdown').css('display', 'none');

            for(let i = 0; i < all_input_arr.length; i++){
                if($(all_input_arr[i]).prop('id') !== 'hperson-hospital-no'){
                    $(all_input_arr[i]).val('')
                    $(all_input_arr[i]).css('pointer-events' , 'auto')
                    $(all_input_arr[i]).css('background' , 'white')
                }
                

            }

        }else if($('#yes-modal-btn').text() == 'Register'){

            const currentDateTime = new Date();
            const year = currentDateTime.getFullYear();
            const month = currentDateTime.getMonth() + 1; // Month is zero-based, so add 1 to get the correct month.
            const day = currentDateTime.getDate();
            const hours = currentDateTime.getHours();
            const minutes = currentDateTime.getMinutes();
            const seconds = currentDateTime.getSeconds();
            let created_at = (`${year}-${month}-${day} ${hours}:${minutes}:${seconds}`)

            data = {
                //PERSONAL INFORMATIONS
                //initial idea is to fetch the last patient hpatcode from the database whenever the patient registration form clicked
                //16
                // hpercode : (Math.floor(Math.random() * 1000) + 1).toString(),
                hpatcode : $('#hpatcode-input').val(),
                patlast : $('#hperson-last-name').val(),
                patfirst : $('#hperson-first-name').val(),
                patmiddle : $('#hperson-middle-name').val(),
                patsuffix : ($('#hperson-ext-name').val()) ? $('#hperson-ext-name').val() : "N/A",
                pat_bdate : $('#hperson-birthday').val(),
                pat_age : $('#hperson-age').val(),
                patsex : $('#hperson-gender').val(),
                patcstat : $('#hperson-civil-status').val(), //accepts null = yes
                relcode : $('#hperson-religion').val(),
                
                pat_occupation :($('#hperson-occupation').val()) ? $('#hperson-occupation').val() : "N/A",
                natcode : $('#hperson-nationality').val(),
                pat_passport_no : ($('#hperson-passport-no').val()) ? $('#hperson-passport-no').val() : "N/A",
                hospital_code : $('#hpatcode-input').val(),
                phicnum : $('#hperson-phic').val(),
    
                //PERMANENT ADDRESS
                pat_bldg_pa : $('#hperson-house-no-pa').val(),
                hperson_street_block_pa: $('#hperson-street-block-pa').val(),
                pat_region_pa : $('#hperson-region-select-pa').val(),
                pat_province_pa : $('#hperson-province-select-pa').val(),
                pat_municipality_pa : $('#hperson-city-select-pa').val(),
                pat_barangay_pa : $('#hperson-brgy-select-pa').val(),
                pat_email_pa :($('#hperson-email-pa').val()) ? $('#hperson-email-pa').val() : "N/A",
                pat_homephone_no_pa : parseInt(($('#hperson-home-phone-no-pa').val())) ? $('#hperson-home-phone-no-pa').val() : 0,
                pat_mobile_no_pa : $('#hperson-mobile-no-pa').val(),
    
                //CURRENT ADDRESS
                pat_bldg_ca : $('#hperson-house-no-ca').val(),
                hperson_street_block_ca: $('#hperson-street-block-ca').val(),
                pat_region_ca : $('#hperson-region-select-ca').val(),
                pat_province_ca : $('#hperson-province-select-ca').val(),
                pat_municipality_ca : $('#hperson-city-select-ca').val(),
                pat_barangay_ca : $('#hperson-brgy-select-ca').val(),
                pat_email_ca :($('#hperson-email-ca').val()),
                pat_homephone_no_ca : parseInt(($('#hperson-home-phone-no-ca').val())) ? $('#hperson-home-phone-no-ca').val() : 0,
                pat_mobile_no_ca : $('#hperson-mobile-no-ca').val(),
    
                // CURRENT WORKPLACE ADDRESS
                pat_bldg_cwa : $('#hperson-house-no-cwa').val() ? $('#hperson-house-no-cwa').val() : "N/A",
                hperson_street_block_pa_cwa: $('#hperson-street-block-cwa').val() ? $('#hperson-street-block-cwa').val() : "N/A",
                pat_region_cwa : $('#hperson-region-select-cwa').val() ? $('#hperson-region-select-cwa').val() : "N/A",
                pat_province_cwa : $('#hperson-province-select-cwa').val() ? $('#hperson-province-select-cwa').val() : "N/A",
                pat_municipality_cwa : $('#hperson-city-select-cwa').val() ? $('#hperson-city-select-cwa').val() : "N/A",
                pat_barangay_cwa : $('#hperson-brgy-select-cwa').val() ? $('#hperson-brgy-select-cwa').val() : "N/A",
                pat_namework_place : $('#hperson-workplace-cwa').val() ? $('#hperson-workplace-cwa').val() : "N/A",
                pat_landline_no : parseInt($('#hperson-ll-mb-no-cwa').val()) ? $('#hperson-ll-mb-no-cwa').val() : "N/A",
                pat_email_cwa : $('#hperson-email-cwa').val() ? $('#hperson-email-cwa').val() : "N/A",
    
    
                // FOR OFW ONLY
                pat_emp_name : $('#hperson-emp-name-ofw').val() ? $('#hperson-emp-name-ofw').val() : "N/A",
                pat_occupation_ofw: $('#hperson-occupation-ofw').val() ? $('#hperson-occupation-ofw').val() : "N/A",
                pat_place_work : $('#hperson-place-work-ofw').val()? $('#hperson-place-work-ofw').val() : "N/A",
                pat_bldg_ofw : $('#hperson-house-no-ofw').val() ? $('#hperson-house-no-ofw').val() : "N/A",
                hperson_street_block_ofw : $('#hperson-street-ofw').val() ? $('#hperson-street-ofw').val() : "N/A",
                pat_region_ofw : $('#hperson-region-select-ofw').val() ? $('#hperson-region-select-ofw').val() : "N/A",
                pat_province_ofw : $('#hperson-province-select-ofw').val() ? $('#hperson-province-select-ofw').val() : "N/A",
                pat_city_ofw : $('#hperson-city-select-ofw').val() ? $('#hperson-city-select-ofw').val() : "N/A",
                pat_country_ofw : $('#hperson-country-select-ofw').val() ? $('#hperson-country-select-ofw').val() : "N/A",
                pat_office_mobile_no_ofw : parseInt($('#hperson-office-phone-no-ofw').val()) ? $('#hperson-office-phone-no-ofw').val() : 0,
                pat_mobile_no_ofw : parseInt($('#hperson-mobile-no-ofw').val()) ? $('#hperson-mobile-no-ofw').val() : 0,

                created_at : created_at,
            }   

            // data = {
            //     //PERSONAL INFORMATIONS
            //     //initial idea is to fetch the last patient hpatcode from the database whenever the patient registration form clicked
            //     //16
            //     // hpercode : (Math.floor(Math.random() * 1000) + 1).toString(),
            //     hpatcode : $('#hpatcode-input').val(),
            //     patlast : "Test 0319",
            //     patfirst : "Test 0319",
            //     patmiddle : "Test 0319",
            //     patsuffix : "N/A",
            //     pat_bdate : '03/05/2000',
            //     pat_age : 23,
            //     patsex : 'Male',
            //     patcstat :"Test 0319", //accepts null = yes
            //     relcode : "Test 0319",
                
            //     pat_occupation: "Test 0319",
            //     natcode : "Test 0319",
            //     pat_passport_no : "N/A",
            //     hospital_code : $('#hpatcode-input').val(),
            //     phicnum : 34252522535,
    
            //     //PERMANENT ADDRESS
            //     pat_bldg_pa : "Test 0319",
            //     hperson_street_block_pa: "Test 0319",
            //     pat_region_pa : '3',
            //     pat_province_pa : "308",
            //     pat_municipality_pa : '30804',
            //     pat_barangay_pa : '30804015',
            //     pat_email_pa :"N/A",
            //     pat_homephone_no_pa : 0,
            //     pat_mobile_no_pa : '09823425253',
    
            //     //CURRENT ADDRESS
            //     pat_bldg_ca : "Test 0319",
            //     hperson_street_block_ca: "Test 0319",
            //     pat_region_ca : '3',
            //     pat_province_ca : "308",
            //     pat_municipality_ca : '30804',
            //     pat_barangay_ca : '30804015',
            //     pat_email_ca :"N/A",
            //     pat_homephone_no_ca : 0,
            //     pat_mobile_no_ca : '09823425253',
    
            //     // CURRENT WORKPLACE ADDRESS
            //     pat_bldg_cwa : $('#hperson-house-no-cwa').val() ? $('#hperson-house-no-cwa').val() : "N/A",
            //     hperson_street_block_pa_cwa: $('#hperson-street-block-cwa').val() ? $('#hperson-street-block-cwa').val() : "N/A",
            //     pat_region_cwa : $('#hperson-region-select-cwa').val() ? $('#hperson-region-select-cwa').val() : "N/A",
            //     pat_province_cwa : $('#hperson-province-select-cwa').val() ? $('#hperson-province-select-cwa').val() : "N/A",
            //     pat_municipality_cwa : $('#hperson-city-select-cwa').val() ? $('#hperson-city-select-cwa').val() : "N/A",
            //     pat_barangay_cwa : $('#hperson-brgy-select-cwa').val() ? $('#hperson-brgy-select-cwa').val() : "N/A",
            //     pat_namework_place : $('#hperson-workplace-cwa').val() ? $('#hperson-workplace-cwa').val() : "N/A",
            //     pat_landline_no : parseInt($('#hperson-ll-mb-no-cwa').val()) ? $('#hperson-ll-mb-no-cwa').val() : "N/A",
            //     pat_email_cwa : $('#hperson-email-cwa').val() ? $('#hperson-email-cwa').val() : "N/A",
    
    
            //     // FOR OFW ONLY
            //     pat_emp_name : $('#hperson-emp-name-ofw').val() ? $('#hperson-emp-name-ofw').val() : "N/A",
            //     pat_occupation_ofw: $('#hperson-occupation-ofw').val() ? $('#hperson-occupation-ofw').val() : "N/A",
            //     pat_place_work : $('#hperson-place-work-ofw').val()? $('#hperson-place-work-ofw').val() : "N/A",
            //     pat_bldg_ofw : $('#hperson-house-no-ofw').val() ? $('#hperson-house-no-ofw').val() : "N/A",
            //     hperson_street_block_ofw : $('#hperson-street-ofw').val() ? $('#hperson-street-ofw').val() : "N/A",
            //     pat_region_ofw : $('#hperson-region-select-ofw').val() ? $('#hperson-region-select-ofw').val() : "N/A",
            //     pat_province_ofw : $('#hperson-province-select-ofw').val() ? $('#hperson-province-select-ofw').val() : "N/A",
            //     pat_city_ofw : $('#hperson-city-select-ofw').val() ? $('#hperson-city-select-ofw').val() : "N/A",
            //     pat_country_ofw : $('#hperson-country-select-ofw').val() ? $('#hperson-country-select-ofw').val() : "N/A",
            //     pat_office_mobile_no_ofw : parseInt($('#hperson-office-phone-no-ofw').val()) ? $('#hperson-office-phone-no-ofw').val() : 0,
            //     pat_mobile_no_ofw : parseInt($('#hperson-mobile-no-ofw').val()) ? $('#hperson-mobile-no-ofw').val() : 0,

            //     created_at : created_at,
            // }


            for(let i = 0; i < all_non_req_input_arr.length; i++){
                // console.log($('#hperson-home-phone-no-ca').val())
                if($(all_non_req_input_arr[i]).val() === ""){
                    $(all_non_req_input_arr[i]).val("N/A")
                }
            }

            console.log(data)

            $.ajax({
                url: '../php_2/add_patient_form.php',
                method: "POST",
                data:data,
                success: function(response){
                    $('#modal-title').text('Sucessful')
                    $('#modal-icon').attr('class', 'fa-solid fa-circle-check');
                    $('#modal-body').text('Successfully registered')
                    $('#ok-modal-btn').text('OK')

                    $('#yes-modal-btn').text('Yes');
                    $('#yes-modal-btn').css('display' , 'none')

                    const myModal = new bootstrap.Modal(document.getElementById('myModal_pat_reg'));
                    myModal.show();
                }
            })


            // setTimeout(function() {
            //     $('#modal-title').text('Successed')
            //     $('#modal-icon').removeClass('fa-triangle-exclamation')
            //     $('#modal-icon').addClass('fa-circle-check')
            //     $('#modal-body').text('Registered Successfully')

            //     $('#yes-modal-btn').addClass('hidden')
            //     $('#ok-modal-btn').text('Ok')
            //     const myModal = new bootstrap.Modal(document.getElementById('myModal_pat_reg'));
            //     myModal.show();
            //     // $('#myModal').modal('show');
            // }, 500);
            
        }
        else if($('#yes-modal-btn').text() == 'Confirm'){
            loadContent('../php_2/referral_form.php?type=' + $('#tertiary-case').val() + "&code=" + $('#hpercode-input').val())
        }
    })

    $('#ok-modal-btn').on('click' , function(event){
        // if($('#ok-modal-btn').text() == 'No' && $('#clear-patform-btn-id').text() == "Cancel"){
        //     console.log("den")
        //     $('#add-patform-btn-id').removeClass('hidden')
        //     $('#clear-patform-btn-id').removeClass('hidden')
        // }
        // else if($('#ok-modal-btn').text() == 'OK' && $('#clear-patform-btn-id').text() == "Cancel"){
        //     console.log("ned")
        //     $('#add-patform-btn-id').text('Refer')
        //     for(let i = 0; i < input_arr.length; i++){
        //         $(input_arr[i]).removeClass('border-2 border-red-600')
        //         $(input_arr[i]).addClass('border-2 border-[#bfbfbf]')
        //     }
        // }
    })
    

    // let classification_dd_counter = true
    // $('#classification-dropdown').on('click' , function(event){
    //     if(classification_dd_counter){
    //         $('#add-clear-btn-div').removeClass('mt-10')
    //         $('#add-clear-btn-div').addClass('mt-[70%]')

    //         classification_dd_counter = false
    //     }else{
    //         $('#add-clear-btn-div').addClass('mt-10')
    //         $('#add-clear-btn-div').removeClass('mt-[70%]')

    //         classification_dd_counter = true
    //     }
    // })

    

    // Use jQuery to handle the change event
    $("#classification-dropdown").change(function() {
        // Get the selected value using val()
        var selectedValue = $(this).val();
  
        // Display the selected value
        console.log("Selected Value: " + selectedValue);
        let chosen_case = ""
        switch(selectedValue){
            case 'er' : chosen_case = "ER"; break;
            case 'ob' : chosen_case = "OB"; break;
            case 'opd' : chosen_case = "OPD"; break;
            case 'toxicology' : chosen_case = "Toxicology"; break;
            // case 'er' : chosen_case = "ER";
        }
        console.log(chosen_case)
        $("#add-patform-btn-id").css("pointer-events" , "auto")
        $("#add-patform-btn-id").css("opacity" , "1")
        $('#add-patform-btn-id').text('Refer')
        $('#tertiary-case').val(chosen_case)
      });
})



