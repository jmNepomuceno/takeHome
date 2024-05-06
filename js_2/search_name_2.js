function calculateAge(dateOfBirth) {
    // Create a Date object for the current date
    const currentDate = new Date();

    // Parse the date of birth string into a Date object
    const dob = new Date(dateOfBirth);

    // Calculate the time difference in milliseconds
    const timeDiff = currentDate - dob;

    // Calculate the age in years
    const age = Math.floor(timeDiff / (1000 * 60 * 60 * 24 * 365.25));

    return age;
}

$(document).ready(function(){
    let input_arr = ['#hperson-last-name' , '#hperson-first-name' , '#hperson-middle-name' , ' #hperson-birthday' , '#hperson-gender' , '#hperson-civil-status' , 
                    '#hperson-religion' , '#hperson-nationality' , '#hperson-phic' , '#hperson-hospital-no' , '#hperson-house-no-pa' , '#hperson-street-block-pa' , '#hperson-region-select-pa' ,
                    '#hperson-region-select-pa' , '#hperson-province-select-pa' , '#hperson-city-select-pa' , '#hperson-brgy-select-pa' , '#hperson-mobile-no-pa',
                    '#hperson-house-no-ca' , '#hperson-street-block-ca' , '#hperson-region-select-ca' , '#hperson-province-select-ca' , 
                    '#hperson-city-select-ca' , '#hperson-brgy-select-ca' , '#hperson-mobile-no-ca']

    let all_non_req_input_arr = ['#hperson-ext-name' , '#hperson-age', '#hperson-occupation' , '#hperson-passport-no' , '#hperson-home-phone-no-pa' , '#hperson-email-pa',
                                '#hperson-email-ca' , '#hperson-home-phone-no-ca' , '#hperson-house-no-cwa' , '#hperson-street-block-cwa' , '#hperson-region-select-cwa' , 
                                '#hperson-province-select-cwa','#hperson-city-select-cwa', '#hperson-brgy-select-cwa' , '#hperson-workplace-cwa', '#hperson-ll-mb-no-cwa' , '#hperson-email-cwa',
                                '#hperson-emp-name-ofw','#hperson-occupation-ofw','#hperson-place-work-ofw','#hperson-house-no-ofw','#hperson-street-ofw','#hperson-region-select-ofw',
                                '#hperson-province-select-ofw','#hperson-city-select-ofw', '#hperson-country-select-ofw' , '#hperson-office-phone-no-ofw' , '#hperson-mobile-no-ofw']
    let all_input_arr = input_arr.concat(all_non_req_input_arr); 

    let if_clicked_same_perma = false;
    let global_breakdown_index = 0;

    function searchSubDiv() {
        const search_Sub_Div_elements = document.querySelectorAll('.search-sub-div');
          search_Sub_Div_elements.forEach(function(element, index) {
            element.addEventListener('click', function() {
                global_breakdown_index = index;
                console.log(global_breakdown_index)

                const data = {
                    hpercode : $('.search-sub-code').eq(global_breakdown_index).text()
                }
                console.log(data)
                $.ajax({
                    url: '../php_2/populate_pat_form.php',
                    method: "POST",
                    data:data,
                    success: function(response){
                        response = JSON.parse(response);
                        console.log(response)

                        // document.querySelector('#hperson-province-select-pa').innerHTML
                        var parentElement = document.querySelector('#hperson-province-select-pa');
    
                        while (parentElement.firstChild) {
                            parentElement.removeChild(parentElement.firstChild);
                        }
                        
                        //Personal Information
                        $('#hpercode-input').val(response[0].hpercode)
                        document.querySelector('#hperson-last-name').value = response[0].patlast    
                        document.querySelector('#hperson-first-name').value = response[0].patfirst
                        document.querySelector('#hperson-middle-name').value = response[0].patlast
                        document.querySelector('#hperson-ext-name').value = response[0].patsuffix

                        //converting of birthdate
                        const timestamp = Date.parse(response[0].patbdate);
                        const date = new Date(timestamp)
                        let year = date.getFullYear()
                        let month = date.getMonth() + 1
                        month = month <= 9 ? "0" + month.toString() : month
                        let day = (date.getDate() < 10) ? "0" + date.getDate().toString() : date.getDate().toString()
                        document.querySelector('#hperson-birthday').value = year.toString() + "-" + month.toString() + "-" + day.toString()
                        // console.log(year.toString() + "-" + month.toString() + "-" + day.toString())

                        //calculating the age based on day of birth
                        const dateOfBirth = year.toString() + "-" + month.toString() + "-" + day.toString()
                        const age = calculateAge(dateOfBirth);
                        document.querySelector('#hperson-age').value = age

                        document.querySelector('#hperson-gender').value = response[0].patsex


                        let cstat = ""
                        switch(response[0].patcstat){
                            case "1": cstat = "Single";break;
                            case "2": cstat = "Married";break;
                            case "3": cstat = "Divorced";break;
                            case "4": cstat = "Widowed";break;
                            default: break;
                        }
                        document.querySelector('#hperson-civil-status').value = response[0].patcstat
                        
                        document.querySelector('#hperson-religion').value = response[0].relcode
                        
                        document.querySelector('#hperson-occupation').value = (response[0].pat_occupation) ? response[0].pat_occupation : "N/A"
                        document.querySelector('#hperson-nationality').value = (response[0].natcode) ? response[0].natcode : "N/A"
                        document.querySelector('#hperson-passport-no').value = (response[0].pat_passport_no) ? response[0].pat_passport_no : "N/A"


                         //Others
                        
                        document.querySelector('#hperson-hospital-no').value = parseInt((response[0].hospital_code)) ? parseInt(response[0].hospital_code) : 0
                        document.querySelector('#hperson-phic').value = (response[0].phicnum) ? response[0].phicnum : "N/A"
                        // document.querySelector('#hperson-nationality').value = (response[0].natcode) ? response[0].natcode : "N/A"


                        // PERMANENT ADDRESS
                        document.querySelector('#hperson-house-no-pa').value = response[0].pat_bldg
                        document.querySelector('#hperson-street-block-pa').value = response[0].pat_street_block


                        document.querySelector('#hperson-region-select-pa').value = response[0].pat_region

                        // create option element for the province select input
                        let province_element = document.createElement('option')
                        province_element.value = response[0].pat_province;
                        province_element.text =  response[0].pat_province;
                        document.querySelector('#hperson-province-select-pa').appendChild(province_element);
                        document.querySelector('#hperson-province-select-pa').value = province_element.value
                        

                        // create option element for the city select input
                        let city_element = document.createElement('option')
                        city_element.value = response[0].pat_municipality;
                        city_element.text =  response[0].pat_municipality;
                        document.querySelector('#hperson-city-select-pa').appendChild(city_element);
                        document.querySelector('#hperson-city-select-pa').value = city_element.value

                         // create option element for the barangay select input
                         let brgy_element = document.createElement('option')
                         brgy_element.value = response[0].pat_barangay;
                         brgy_element.text =  response[0].pat_barangay;
                         document.querySelector('#hperson-brgy-select-pa').appendChild(brgy_element);
                         document.querySelector('#hperson-brgy-select-pa').value = brgy_element.value

                        document.querySelector('#hperson-home-phone-no-pa').value = response[0].pat_homephone_no
                        document.querySelector('#hperson-mobile-no-pa').value = response[0].pat_mobile_no
                        document.querySelector('#hperson-email-pa').value = response[0].pat_email


                        // CURRENT ADDRESS
                        document.querySelector('#hperson-house-no-ca').value = response[0].pat_curr_bldg
                        document.querySelector('#hperson-street-block-ca').value = response[0].pat_curr_street

                        document.querySelector('#hperson-region-select-ca').value = response[0].pat_curr_region

                        // create option element for the province select input
                        let province_element_ca = document.createElement('option')
                        province_element_ca.value = response[0].pat_curr_province;
                        province_element_ca.text =  response[0].pat_curr_province;
                        document.querySelector('#hperson-province-select-ca').appendChild(province_element_ca);
                        document.querySelector('#hperson-province-select-ca').value = province_element_ca.value
                        

                        // create option element for the city select input
                        let city_element_ca = document.createElement('option')
                        city_element_ca.value = response[0].pat_curr_municipality;
                        city_element_ca.text =  response[0].pat_curr_municipality;
                        document.querySelector('#hperson-city-select-ca').appendChild(city_element_ca);
                        document.querySelector('#hperson-city-select-ca').value = city_element_ca.value

                        // create option element for the barangay select input
                        let brgy_element_ca = document.createElement('option')
                        brgy_element_ca.value = response[0].pat_curr_barangay;
                        brgy_element_ca.text =  response[0].pat_curr_barangay;
                        document.querySelector('#hperson-brgy-select-ca').appendChild(brgy_element_ca);
                        document.querySelector('#hperson-brgy-select-ca').value = brgy_element_ca.value


                        document.querySelector('#hperson-home-phone-no-ca').value = response[0].pat_curr_homephone_no
                        document.querySelector('#hperson-mobile-no-ca').value = response[0].pat_curr_mobile_no
                        document.querySelector('#hperson-email-ca').value = response[0].pat_email_ca

                         
                        // CURRENT WORKPLACE ADDRESS
                        document.querySelector('#hperson-house-no-cwa').value = response[0].pat_work_bldg
                        document.querySelector('#hperson-street-block-cwa').value = response[0].pat_work_street


                        document.querySelector('#hperson-region-select-cwa').value = response[0].pat_work_region

                        // create option element for the province select input
                        let province_element_cwa = document.createElement('option')
                        province_element_cwa.value = response[0].pat_work_province;
                        province_element_cwa.text =  response[0].pat_work_province;
                        document.querySelector('#hperson-province-select-cwa').appendChild(province_element_cwa);
                        document.querySelector('#hperson-province-select-cwa').value = province_element_cwa.value
                        

                        // create option element for the city select input
                        let city_element_cwa = document.createElement('option')
                        city_element_cwa.value = response[0].pat_work_municipality;
                        city_element_cwa.text =  response[0].pat_work_municipality;
                        document.querySelector('#hperson-city-select-cwa').appendChild(city_element_cwa);
                        document.querySelector('#hperson-city-select-cwa').value = city_element_cwa.value

                        // create option element for the barangay select input
                        let brgy_element_cwa = document.createElement('option')
                        brgy_element_cwa.value = response[0].pat_work_barangay;
                        brgy_element_cwa.text =  response[0].pat_work_barangay;
                        document.querySelector('#hperson-brgy-select-cwa').appendChild(brgy_element_cwa);
                        document.querySelector('#hperson-brgy-select-cwa').value = brgy_element_cwa.value


                        document.querySelector('#hperson-workplace-cwa').value = response[0].pat_namework_place
                        document.querySelector('#hperson-ll-mb-no-cwa').value = response[0].pat_work_landline_no
                        document.querySelector('#hperson-email-cwa').value = response[0].pat_work_email_add

                        // OFW
                        document.querySelector('#hperson-emp-name-ofw').value = response[0].ofw_employers_name
                        document.querySelector('#hperson-occupation-ofw').value = response[0].ofw_occupation
                        document.querySelector('#hperson-place-work-ofw').value = response[0].ofw_place_of_work
                        document.querySelector('#hperson-house-no-ofw').value = response[0].ofw_bldg
                        document.querySelector('#hperson-street-ofw').value = response[0].ofw_street

                        document.querySelector('#hperson-region-select-ofw').value = response[0].ofw_region
                        document.querySelector('#hperson-province-select-ofw').value = response[0].ofw_province
                        document.querySelector('#hperson-city-select-ofw').value = response[0].ofw_municipality
                        document.querySelector('#hperson-country-select-ofw').value = response[0].ofw_country

                        document.querySelector('#hperson-office-phone-no-ofw').value = response[0].ofw_office_phone_no
                        document.querySelector('#hperson-mobile-no-ofw').value = response[0].ofw_mobile_phone_no

                        for(let j = 0; j < all_input_arr.length; j++){
                            $(all_input_arr[j]).css('border' , '2px solid #bfbfbf')

                            $(all_input_arr[j]).css('pointer-events' , 'none')
                            $(all_input_arr[j]).css('background' , '#cccccc')
                            // $(all_input_arr[j]).css('border' , '2px solid red')
                        }
                        
                        if(response[0].status === null){
                            $("#classification-dropdown").removeClass('hidden')

                        }else{
                            $('#add-patform-btn-id').removeClass('bg-cyan-600 hover:bg-cyan-700')
                            $('#add-patform-btn-id').addClass('bg-green-600 hover:bg-green-700')
                            $('#add-patform-btn-id').addClass('pointer-events-none opacity-20')
                            $("#classification-dropdown").addClass('hidden')

                        }

                        $('#clear-patform-btn-id').text('Cancel')
                        $('#clear-patform-btn-id').css('width' , '90px')
                        $('#classification-dropdown').css('display', 'flex')
                        $('#add-patform-btn-id').css('margin-left', '5%')
                        $('#add-patform-btn-id').css('pointer-events', 'none')
                        $('#add-patform-btn-id').css('opacity', '0.3')
                        
                        // $('#privacy-reminder-div').css('display' , 'flex')
                        // $('#add-clear-btn-div').addClass('mt-10')
                    }
                })
            });
        });
    }

    $(document).on('keypress', function(event) {
        if (event.which === 13 || event.keyCode === 13) {
            $('#search-patient-btn').trigger('click');
        }
    });

    $('#search-patient-btn').on('click' , function(event){
        event.preventDefault();

        const search_lname = document.querySelector('#search-lname').value
        const search_fname = document.querySelector('#search-fname').value
        const search_mname = document.querySelector('#search-mname').value

        const data = {
            search_lname: search_lname,
            search_fname: search_fname,
            search_mname: search_mname
        }

        console.log(data)

        // // console.log(data.otp_number, " type of " , typeof(data.otp_number))
        // // console.log(total)

        const search_query_result = document.querySelector('#search-result-div')
        while (search_query_result.hasChildNodes()) {
            search_query_result.removeChild(search_query_result.firstChild);
            }


        let lname_has_value = search_lname != ""
        let fname_has_value = search_fname != ""
        let mname_has_value = search_mname != ""

        // console.log(lname_has_value, fname_has_value, mname_has_value)
        const insuf_input_h1 = document.createElement('h1')
        insuf_input_h1.id = 'insuf_input_h1'
        // insuf_input_h1.textContent = "Please input two(2) or more characters."
        document.querySelector('#search-result-div').appendChild(insuf_input_h1)

        
        if(lname_has_value && search_lname.length < 2){
            insuf_input_h1.textContent = "Please input two(2) or more characters."
        }else if(fname_has_value && search_fname.length < 2){
            insuf_input_h1.textContent = "Please input two(2) or more characters."
        }else if(mname_has_value && search_mname.length < 2){
            insuf_input_h1.textContent = "Please input two(2) or more characters."
        }else{
            $.ajax({
                url: '../php_2/search_name_2.php',
                method: "POST",
                data:data,
                success: function(response){
                    // response = JSON.parse(response);
                    // console.log(response)

                    // SEARCH QUERY RESULT
                    const search_query_result = document.querySelector('#search-result-div')
                    while (search_query_result.hasChildNodes()) {
                        search_query_result.removeChild(search_query_result.firstChild);
                    }

                    search_query_result.innerHTML += response;

                    searchSubDiv();
                }
            })
        }
        

    })
})