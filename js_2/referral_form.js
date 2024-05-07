$(document).ready(function(){
    const loadContent = (url) => {
        $.ajax({
            url:url,
            success: function(response){
                // console.log(response)
                $('#container').html(response);
            }
        })
    }
    $('#submit-referral-btn-id').on('click' , function(event){
        var selectedValue = $('input[name="sensitive"]:checked').val();
        console.log(selectedValue)

        var sensitive_radios = document.querySelectorAll('input[name="sensitive"]');
        var sensitive_selected = true;

        for (var i = 0; i < sensitive_radios.length; i++) {
            if (sensitive_radios[i].checked) {
                sensitive_selected = false;
                break;
            }
        }
        
        if(sensitive_selected) {
            const data = {
                type : $('#type-input').val(),
                code : $('#code-input').val(),

                refer_to : $('#refer-to-select').val(),
                sensitive_case : $('input[name="sensitive_case"]:checked').val(),
                parent_guardian : $('#parent-guard-input').val(),
                phic_member : $('#phic-member-select').val(),
                transport : $('#transport-select').val(),
                referring_doc : $('#referring-doc-input').val(),

                complaint_history_input : $('#complaint-history-input').val(),
                reason_referral_input : $('#reason-referral-input').val(),
                diagnosis : $('#diagnosis').val(),


                bp_input : parseInt($('#bp-input').val()),
                hr_input : $('#hr-input').val(),
                rr_input : $('#rr-input').val(),
                temp_input : $('#temp-input').val(),
                weight_input : parseInt($('#weight-input').val()),
                pe_findings_input : $('#pe-findings-input').val(),

                // pre-empt data

                // refer_to : $('#refer-to-select').val(),
                // sensitive_case : 'true',
                // parent_guardian : "N/A",
                // phic_member : 'true',
                // transport : "Ambulance",
                // referring_doc : "Juan",

                // complaint_history_input : "asdf",
                // reason_referral_input : "asdf",
                // diagnosis : "asdf",


                // bp_input : 12,
                // hr_input : "12",
                // rr_input : "12",
                // temp_input : "12",
                // weight_input : parseInt(12),
                // pe_findings_input : "asdf",


                // refer_to : "Bataan General Hospital and Medical Center",
                // sensitive_case : 'true',
                // parent_guardian : "Potassium",
                // phic_member : "true",
                // transport : "Commute",
                // referring_doc : "Potassium",

                // complaint_history_input : "Potassium",
                // reason_referral_input : "Potassium",
                // diagnosis : "Potassium",


                // bp_input : 12,
                // hr_input : "12",
                // rr_input : "12",
                // temp_input : "12",
                // weight_input : parseInt(12),
                // pe_findings_input : "Potassium",
            }

            if($('#type-input').val() === "OB"){
                data['fetal_heart_inp'] = $('#fetal-heart-inp').val()
                data['fundal_height_inp'] = $('#fundal-height-inp').val()
                data['cervical_dilation_inp'] = $('#cervical-dilation-inp').val()
                data['bag_water_inp'] = $('#bag-water-inp').val()
                data['presentation_ob_inp'] = $('#presentation-ob-inp').val()
                data['others_ob_inp'] = $('#others-ob-inp').val()
            }

            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                    console.log(key + " -> " + data[key] + " -> " + typeof data[key]);
                }
            }

            function checkValues(obj) {
                for (var key in obj) {
                    if (obj[key] === null || obj[key] === "" || isNaN(obj[key]) || typeof obj[key] === 'undefined') {
                        return false;
                    }
                }
                return true;
            }

            var isValid = checkValues(data);

            if(isValid){
                $.ajax({
                    url: '../php_2/add_referral_form.php',
                    method: "POST",
                    data:data,
                    success: function(response){
                        // response = JSON.parse(response); 
                        console.log(response)
                        // if(response === "success" && $('#hospital_code').val() == '1437'){
                        //     $('#notif-circle').removeClass('hidden')
                        //     // let value = parseInt($('#notif-span').text())
                        //     // $('#notif-span').text(value + 1)
                        //     document.getElementById("notif-sound").play()
                        // }else{
                        //     //labas ng modal
                        // }

                        $('#modal-title').text('Successed')
                        $('#modal-icon').removeClass('fa-triangle-exclamation')
                        $('#modal-icon').addClass('fa-circle-check')
                        $('#modal-body').text('Successfully Referred')

                        $('#yes-modal-btn').css('display' , 'none')
                        $('#ok-modal-btn').text('OK')
                        // $('#myModal').modal('show');
                        

                        $('#ok-modal-btn').on('click' , function(event){
                            if($('#ok-modal-btn').text() == 'OK'){
                                loadContent('../php_2/default_view2.php')
                            }
                        })
                    }
                })
            }else{
                console.log("invalid")
            }

        }
        
    })

    $('#cancel-referral-btn-id').on('click' , function(){
        $('#modal-title').text('Warning')
        $('#modal-icon').attr('class', 'fa-solid fa-triangle-exclamation');
        $('#modal-body').text('Are you sure you want to cancel the referral?')
        $('#ok-modal-btn').text('No')

        $('#yes-modal-btn').css('display' , "block")
    })

    $('#yes-modal-btn').on('click' , () =>{
        if($('#yes-modal-btn').text() === 'Yes'){
            // window.location.href = "http://10.10.90.14:8079/index.php" 
            loadContent('../php_2/patient_register_form2.php')
        }
    })
})