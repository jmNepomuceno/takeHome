$(document).ready(function(){

    function validateElement(element) { 
        var isValid = true;

        if (!element.val()) {
            isValid = false;
            element.addClass('is-invalid').removeClass('is-valid');
        } else {
            element.removeClass('is-invalid').addClass('is-valid');
        }
    
        // if (element.is(':radio')) {
        //     var radioName = element.attr('name');
        //     var siblingRadios = $('input[name="' + radioName + '"]');
            
        //     siblingRadios.removeClass('is-invalid').addClass('is-valid'); 
        // }
    }

    $("#myModal").on('shown.bs.modal', function () {
        $(".modal-dialog").draggable({
          handle: ".modal-header"
        });
      });
      
    $('#query-signin-txt').on('click' , function(event){
        event.preventDefault();
        $('.main-content').css('display', 'none');
        $('.sub-content').css('display', 'flex');

        // if(tutorialMode_on){
        //     tutorial_modal.show()
        //     $('#tutorial_title').text("Registration of your RHUs")
        //     $('#tutorial_body').text("All field must be filled")
        //     $('#tutorial_dialog').removeClass('modal-lg')
        //     $('#tutorial_dialog').addClass('modal-md')
        // }
    })  

    $('.return').on('click' , function(event){
        event.preventDefault();
        $('.main-content').css('display', 'flex');
        $('.sub-content').css('display', 'none');
    })

    // registration-btn
    $('#registration-btn').on('click' , function(event){
        event.preventDefault();

        $('.sub-content-registration-form').css('display', 'block');
        $('.sub-content-authorization-form').css('display', 'none');

        $('#registration-btn').attr('class', 'btn btn-primary');
        $('#authorization-btn').attr('class', 'btn btn-dark');

    })

    $('#authorization-btn').on('click' , function(event){
        event.preventDefault();

        $('.sub-content-registration-form').css('display', 'none');
        $('.sub-content-authorization-form').css('display', 'block');

        $('#registration-btn').attr('class', 'btn btn-dark');
        $('#authorization-btn').attr('class', 'btn btn-primary');
    })

    $("#sdn-landline-no").on("input", function(){
        let value = $("#sdn-landline-no").val().replace(/[^0-9]/g, '');
        // Add dashes at specific positions
        if (value.length >= 3) {
            value = value.slice(0, 3) + '-' + value.slice(3);
        }
        if (value.length > 8) {
            value = value.slice(0, 8);
        }
        $("#sdn-landline-no").val(value);
    })

    const mobileNumValue = (val) => {
        // Remove any non-numeric characters
        let value;
        if(val === 1){
            value = $("#sdn-hospital-mobile-no").val().replace(/[^0-9]/g, '');
        }else if(val === 2){
            value = $("#sdn-hospital-director-mobile-no").val().replace(/[^0-9]/g, '');
        }else if(val === 3){
            value = $("#sdn-point-person-mobile-no").val().replace(/[^0-9]/g, '');
        }
        // Add dashes at specific positions
        if (value.length >= 4) {
            value = value.slice(0, 4) + '-' + value.slice(4);
          }
          if (value.length >= 9) {
            value = value.slice(0, 9) + '-' + value.slice(9);
          }
          if (value.length > 13) {
            value = value.slice(0, 13);
          }
          if(val === 1){
            $("#sdn-hospital-mobile-no").val(value);
        }else if(val === 2){
            $("#sdn-hospital-director-mobile-no").val(value);
        }else if(val === 3){
            $("#sdn-point-person-mobile-no").val(value);
        }
    }

    $("#sdn-hospital-mobile-no").on("input", () => mobileNumValue(1))
    $("#sdn-hospital-director-mobile-no").on("input", () => mobileNumValue(2))
    $("#sdn-point-person-mobile-no").on("input", () => mobileNumValue(3))

    $("#otp-input-1").on("input",function(){
        var maxLength = 1;
        var inputValue = document.querySelector('#otp-input-1').value
        document.querySelector('#otp-input-2').focus()
    
        if (inputValue.length > maxLength) {
            document.querySelector('#otp-input-1').value = inputValue.slice(0, maxLength);
    
        } 
    })
    
    $("#otp-input-2").on("input",function(){
        var maxLength = 1;
        var inputValue = document.querySelector('#otp-input-2').value
        document.querySelector('#otp-input-3').focus()
    
        // console.log(event.keyCode, event.charCode) 
    
        if (inputValue.length > maxLength) {
            document.querySelector('#otp-input-2').value = inputValue.slice(0, maxLength);
        } 
    })
    
    $("#otp-input-2").on("keydown",function(){
        if( event.keyCode == 8 || event.charCode == 46 ){
            document.querySelector('#otp-input-2').value = ""
        }
    })
    
    $("#otp-input-3").on("input",function(){
        var maxLength = 1;
        var inputValue = document.querySelector('#otp-input-3').value
        document.querySelector('#otp-input-4').focus()
    
        if (inputValue.length > maxLength) {
            document.querySelector('#otp-input-3').value = inputValue.slice(0, maxLength);
        } 
    })
    
    $("#otp-input-3").on("keydown",function(){
        if( event.keyCode == 8 || event.charCode == 46 ){
            document.querySelector('#otp-input-3').value = ""
        }
    })
    
    $("#otp-input-4").on("input",function(){
        var maxLength = 1;
        var inputValue = document.querySelector('#otp-input-4').value
        document.querySelector('#otp-input-5').focus()
    
        if (inputValue.length > maxLength) {
            document.querySelector('#otp-input-4').value = inputValue.slice(0, maxLength);
        } 
    })
    
    $("#otp-input-4").on("keydown",function(){
        if( event.keyCode == 8 || event.charCode == 46 ){
            document.querySelector('#otp-input-4').value = ""
        }
    })
    
    $("#otp-input-5").on("input",function(){
        var maxLength = 1;
        var inputValue = document.querySelector('#otp-input-5').value
        document.querySelector('#otp-input-6').focus()
    
        if (inputValue.length > maxLength) {
            document.querySelector('#otp-input-5').value = inputValue.slice(0, maxLength);
        } 
    })
    
    $("#otp-input-5").on("keydown",function(){
        if( event.keyCode == 8 || event.charCode == 46 ){
            document.querySelector('#otp-input-5').value = ""
        }
    })
    
    $("#otp-input-6").on("input",function(){
        var maxLength = 1;
        var inputValue = document.querySelector('#otp-input-6').value
        if (inputValue.length > maxLength) {
            document.querySelector('#otp-input-6').value = inputValue.slice(0, maxLength);
        } 
    })
    
    $("#otp-input-6").on("keydown",function(){
        if( (event.keyCode == 8 || event.charCode == 46) && document.querySelector('#otp-input-6').value == "" ){
            document.querySelector('#otp-input-5').value = ""
        }
    
        if( (event.keyCode == 8 || event.charCode == 46) && document.querySelector('#otp-input-6').value != "" ){
            document.querySelector('#otp-input-6').value = ""
        }
    })

    // $('#myModal').modal('show');
    function validateEmail(input) {
        var email = input.val();
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regex.test(email)) {
            input.removeClass('is-valid').addClass('is-invalid');
            $(input).val('');
        } else {
            input.removeClass('is-invalid').addClass('is-valid');
        }
    }
    
    $('#sdn-email-address').blur(function() {
        validateEmail($(this));
    });

    $('#sdn-hospital-name').blur(function() {
        validateElement($(this));
    });

    $('#sdn-hospital-code').blur(function() {
        validateElement($(this));
    });

    $('#sdn-region-select').blur(function() {
        validateElement($(this));
    });

    $('#sdn-province-select').blur(function() {
        validateElement($(this));
    });

    $('#sdn-city-select').blur(function() {
        validateElement($(this));
    });

    $('#sdn-brgy-select').blur(function() {
        validateElement($(this));
    });

    $('#sdn-zip-code').blur(function() {
        validateElement($(this));
    });

    $('#sdn-landline-no').blur(function() {
        validateElement($(this));
    });

    $('#sdn-hospital-mobile-no').blur(function() {
        validateElement($(this));
    });

    $('#sdn-hospital-director').blur(function() {
        validateElement($(this));
    });

    $('#sdn-hospital-director-mobile-no').blur(function() {
        validateElement($(this));
    });

    $('#sdn-point-person').blur(function() {
        validateElement($(this));
    });

    $('#sdn-point-person-mobile-no').blur(function() {
        validateElement($(this));
    });

    // **************************************

    $('#sdn-autho-hospital-code-id').blur(function() {
        validateElement($(this));
    });

    $('#sdn-autho-cipher-key-id').blur(function() {
        validateElement($(this));
    });

    $('#sdn-autho-last-name-id').blur(function() {
        validateElement($(this));
    });

    $('#sdn-autho-first-name-id').blur(function() {
        validateElement($(this));
    });

    $('#sdn-autho-middle-name-id').blur(function() {
        validateElement($(this));
    });

    $('#sdn-autho-ext-name-id').blur(function() {
        validateElement($(this));
    });

    $('#sdn-autho-password').blur(function() {
        validateElement($(this));
    });

    // $('#sdn-autho-confirm-password').blur(function() {
    //     validateElement($(this));
    // });

    // $('#authorization-confirm-btn').blur(function() {
    //     validateElement($(this));
    // });

    // tutorial_modal.show()
    
    $('#tutorial-btn').mouseenter(function(){
        $('#tutorial-btn').removeClass('fa-regular fa-circle-question');
        $('#tutorial-btn').addClass('fa-solid fa-circle-question');
    }).mouseout(function(){
        $('#tutorial-btn').removeClass('fa-solid fa-circle-question');
        $('#tutorial-btn').addClass('fa-regular fa-circle-question');
    })

    $('#tutorial-btn').on('click' , function(){
        console.log('here')
    })

})