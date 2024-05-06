const closebut = document.getElementById('reg_close_but');

const reg_modal = document.getElementById('register_modal');
const reg_modal_but = document.getElementById ('not_reg');
const mama = document.getElementById('mamadiv');
const reg_but = document.getElementById('register_but');

const ovlay = document.getElementById('overlay');
const ovlay2 = document.getElementById('overlay2');
 
const close_otp_but = document.getElementById('otp_close_but');
const otpmod = document.getElementById('otp_modal');

const reg_label = document.getElementById('registration');
const auth_label = document.getElementById('auth');
const regis = document.getElementById('registration_form');
const autho = document.getElementById('authorization_form');

function close(){
    
    var textboxes = document.querySelectorAll('.informations_tbs');

    // Loop through each textbox and clear its content
    textboxes.forEach(textbox => {
        textbox.value = '';
    });

    reg_modal.style.display = 'none';
    ovlay.style.display = 'none';
    ovlay2.style.display = 'none';

    var selectboxes = document.querySelectorAll('.informations_drp');

    selectboxes.forEach(selectbox => {
        // Set the selectedIndex to -1 to clear the selection
        selectbox.selectedIndex = 0;
    });
    
}


function close_otp(){
    
    var userResponse = confirm("Do you want to cancel the registration?");

// Check the user's response

    if (userResponse) {
        alert("You clicked 'Yes'. Registration canceled.");
        otpmod.style.display = 'none';
        ovlay2.style.display = 'none';
        // Add your logic for "Yes" action here
    } else {
        alert("You clicked 'No' or closed the dialog. Registration not canceled.");
        // Add your logic for "No" action here
    }
   
}

function reg(){

      reg_modal.style.display = 'block' ;     
      reg_modal.style.zIndex = '2'; // Bring modal to the front
      ovlay.style.zIndex = '1'; // Bring overlay to the front
      reg_modal.style.display = 'flex';
      ovlay.style.display = 'block';
      ovlay.style.backgroundColor = 'black';
      ovlay.style.opacity = '0.5';

}



// function regtab(){

//     regis.style.display = 'flex';
//     autho.style.display = 'none';

    

// }


// function authtab(){

//     autho.style.display = 'flex';
//     regis.style.display = 'none';
// }


 

// Example usage



function regtab() {
    if (regis.style.display === 'none') {
        toggleTabs();
    }
}


    function authtab() {
        if (autho.style.display === 'none') {
            toggleTabs();
        }
}



function register_but(){

    otpmod.style.display = 'flex';  
    otpmod.style.zIndex = '2'; // Bring modal to the front
    ovlay2.style.zIndex = '1'; // Bring overlay to the front
    reg_modal.style.display = 'flex';
    ovlay2.style.display = 'flex';
    ovlay2.style.backgroundColor = 'black';
    ovlay2.style.opacity = '0.5';

}


function formatLandline(input) {
    // Ensure the default dash is maintained
    if (!input.value.includes('-')) {
        input.value = 'XXX-' + input.value;
    }
}

function validateNumericInput(input) {
    // Remove non-numeric characters using a regular expression
    input.value = input.value.replace(/[^0-9]/g, '');
}




reg_but.addEventListener('click', register_but); 
reg_label.addEventListener('click', regtab);
auth_label.addEventListener('click', authtab);

reg_modal_but.addEventListener('click', reg);
closebut.addEventListener('click', close);
close_otp_but.addEventListener('click', close_otp );