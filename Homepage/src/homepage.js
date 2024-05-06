const menubut = document.querySelector('.fa-bars');
const side = document.getElementById('sidebar');

const pat_reg = document.getElementById('patient_reg');
const pat_form = document.getElementById('sub_menu1');
const pat_form_2 = document.getElementById('sub_menu2');

const home_tab =document.getElementById('home');

const registration_form = document.getElementById('form');

const search_button = document.getElementById('search_but');
const search_form = document.getElementById('search_form');


let toggleBtn = true;

//toggle for sidebar

document.querySelector('.fa-bars').addEventListener('click' , function(){
    
    if(toggleBtn){

        document.getElementById('sidebar').style.display = 'flex'
       

        // document.getElementById('form').style.width = '84%'
        // document.getElementById('form').style.marginLeft = '15.9%'

        toggleBtn = false;
    }else {

        document.getElementById('incoming').style.display = 'none'
        document.getElementById('outgoing').style.display = 'none'
        document.getElementById('sub_menu2').style.display = 'none'
        document.getElementById('sidebar').style.display = 'none'
   
        
        // document.getElementById('form').style.width = '99%'
        // document.getElementById('form').style.marginLeft = '0%'

        toggleBtn = true;
    }
})

// closing sidebar by clicking anywhere outside the sidebar

document.body.addEventListener('click', function (event) {
    const sidebar = document.getElementById('sidebar');
    const barsIcon = document.querySelector('.fa-bars');

    if (!sidebar.contains(event.target) && !barsIcon.contains(event.target)) {
        // Click is outside the sidebar and the bars icon, so hide the sidebar
        document.getElementById('incoming').style.display = 'none';
        document.getElementById('outgoing').style.display = 'none';
        document.getElementById('sub_menu2').style.display = 'none';
        document.getElementById('sidebar').style.display = 'none';
        document.getElementById('home').style.width = '100%';
        document.getElementById('home').style.marginLeft = '0%';

        toggleBtn = true;
    }
});

//toggle for sidebar

document.getElementById('search_but').addEventListener('click', function () {
    if (toggleBtn) {
        // Code to show the search form
        document.getElementById('search_but').style.width = '100%';
        document.getElementById('search_form').style.display = 'flex';
        document.getElementById('search_but').style.marginLeft = '11%';
        document.getElementById('check_reg_label').style.display = 'flex';
        toggleBtn = false;
    } else {
        // Code to hide the search form
        document.getElementById('search_but').style.width = '10%';    
        document.getElementById('search_but').style.marginLeft = '97%';
        document.getElementById('search_form').style.display = 'none';
        document.getElementById('check_reg_label').style.display = 'none';
        toggleBtn = true;
    }
});


// closing sidebar by clicking anywhere outside the sidebar
document.body.addEventListener('click', function (event) {
    const searchForm = document.getElementById('search_form');
    const searchButton = document.getElementById('search_but');

    if (searchForm && searchButton && !searchForm.contains(event.target) && !searchButton.contains(event.target)) {
      
        document.getElementById('search_but').style.width = '10%';
        document.getElementById('search_but').style.marginLeft = '97%';
        document.getElementById('search_form').style.display = 'none';
        document.getElementById('check_reg_label').style.display = 'none';
        toggleBtn = true;
    }
});



search_button.addEventListener('click', function() {
   search_form.classList.toggle('active')
});

//Function for patient registration option in the side bar

document.getElementById('sub_menu1').addEventListener('click' , function(){

    
    if(toggleBtn){
        document.getElementById('sub_menu2').style.display = 'flex'
        document.querySelector('#patient_reg').style.height = '13%'
      
        toggleBtn = false;

    }else {
        document.getElementById('sub_menu2').style.display = 'none'
        document.querySelector('#patient_reg').style.height = '7%';

        toggleBtn = true;

    }
})

//Function for online referral option in the side bar

document.getElementById('online_ref_tab').addEventListener('click' , function(){
    
    if(toggleBtn){
        document.getElementById('incoming').style.display = 'flex'
        document.getElementById('outgoing').style.display = 'flex'

      
        toggleBtn = false;

    }else {
        document.getElementById('incoming').style.display = 'none'
        document.getElementById('outgoing').style.display = 'none'

        toggleBtn = true;

    }
})

//function for incoming div to appear when clicked

document.getElementById('incoming').addEventListener('click' , function(){

    document.getElementById('patient_registration_form').style.display = 'none'
    document.getElementById('incoming_tab').style.display = 'flex'
    document.getElementById('home').style.display = 'none'

})

//function to show user settings when clicked

document.getElementById('username').addEventListener('click' , function(){

    if(toggleBtn){

    document.getElementById('user_settings').style.display = 'flex'

    toggleBtn = false;

    }else{

    document.getElementById('user_settings').style.display = 'none'

    toggleBtn = true;

    }
})

//function to close the user settings whenever the pointer is clicked outisde it

document.body.addEventListener('click', function (event) {
    const username = document.getElementById('username');
    const user_settings = document.getElementById('user_settings');

    // Check if the elements are not null before using 'contains'
    if (user_settings && username && !user_settings.contains(event.target) && !username.contains(event.target)) {
        // Click is outside the search form and the search button, so hide the search form
        document.getElementById('user_settings').style.display = 'none'

        toggleBtn = true;
    }
});

//function to add all of the data that have been typed in the patient registration form

document.getElementById('add_button').addEventListener('click' , function(){

   var last_name = document.getElementById('last_name_textbox')
   var first_name = document.getElementById('first_name_textbox')
   var middle_name = document.getElementById('middle_name_textbox')
   var birthdate = document.getElementById('birthdate_textbox')
   var gender = document.getElementById('gender')
   var civil_stat = document.getElementById('civilstat')
   var religion = document.getElementById('religion_textbox')
   var nationality = document.getElementById('nationality_textbox')
   var phic = document.getElementById('phic_textbox')

   var house_no = document.getElementById('house_no_textbox')
   var street = document.getElementById('street_textbox')
   var region = document.getElementById('region_selection')
   var province = document.getElementById('province_selection')
   var city = document.getElementById('city_selection')
   var baranggay = document.getElementById('baranggay_selection')
   var mobile_no  = document.getElementById('mobile_no_textbox')

   var house_no_ca = document.getElementById('house_no_textbox_ca')
   var street_ca = document.getElementById('street_textbox_ca')
   var region_ca = document.getElementById('region_selection_ca')
   var province_ca = document.getElementById('province_selection_ca')
   var city_ca = document.getElementById('city_selection_ca')
   var baranggay_ca = document.getElementById('baranggay_selection_ca')
   var mobile_no_ca = document.getElementById('mobile_no_textbox_ca')

   if(last_name.value === ''){
        document.getElementById('last_name_textbox').style.border = '2px solid red'
    } else{
        document.getElementById('last_name_textbox').style.border ='2px solid #B6BBC4'
}

    if(first_name.value === ''){
        document.getElementById('first_name_textbox').style.border = '2px solid red'
    } else{
        document.getElementById('first_name_textbox').style.border ='2px solid #B6BBC4'
}

    if(middle_name.value === ''){
        document.getElementById('middle_name_textbox').style.border = '2px solid red'
    } else{
        document.getElementById('middle_name_textbox').style.border ='2px solid #B6BBC4'
}

    if(birthdate.value === ''){
        document.getElementById('birthdate_textbox').style.border = '2px solid red'
    } else{
        document.getElementById('birthdate_textbox').style.border ='2px solid #B6BBC4'
}

    if(gender.value === 'Choose'){
        document.getElementById('gender').style.border = '2px solid red'
    } else{
        document.getElementById('gender').style.border ='2px solid #B6BBC4'
    }

    if(civil_stat.value === 'Choose'){
        document.getElementById('civilstat').style.border = '2px solid red'
    } else{
        document.getElementById('civilstat').style.border ='2px solid #B6BBC4'
}

    if(religion.value === ''){
        document.getElementById('religion_textbox').style.border = '2px solid red'
    } else{
        document.getElementById('religion_textbox').style.border ='2px solid #B6BBC4'
}

    if(nationality.value === ''){
        document.getElementById('nationality_textbox').style.border = '2px solid red'
    } else{
        document.getElementById('nationality_textbox').style.border ='2px solid #B6BBC4'
}

    if(phic.value === ''){
        document.getElementById('phic_textbox').style.border = '2px solid red'
    } else{
        document.getElementById('phic_textbox').style.border ='2px solid #B6BBC4'
}

    if(house_no.value === ''){
        document.getElementById('house_no_textbox').style.border = '2px solid red'
    } else{
        document.getElementById('house_no_textbox').style.border ='2px solid #B6BBC4'
}

    if(street.value === ''){
        document.getElementById('street_textbox').style.border = '2px solid red'
    } else{
        document.getElementById('street_textbox').style.border ='2px solid #B6BBC4'
}

    if(region.value === 'Choose'){
        document.getElementById('region_selection').style.border = '2px solid red'
    } else{
        document.getElementById('region_selection').style.border ='2px solid #B6BBC4'
}

if(province.value === 'Choose'){
    document.getElementById('province_selection').style.border = '2px solid red'
} else{
    document.getElementById('province_selection').style.border ='2px solid #B6BBC4'
}

if(city.value === 'Choose'){
    document.getElementById('city_selection').style.border = '2px solid red'
} else{
    document.getElementById('city_selection').style.border ='2px solid #B6BBC4'
}

if(baranggay.value === 'Choose'){
    document.getElementById('baranggay_selection').style.border = '2px solid red'
} else{
    document.getElementById('baranggay_selection').style.border ='2px solid #B6BBC4'
}

if(mobile_no.value === ''){
    document.getElementById('mobile_no_textbox').style.border = '2px solid red'
} else{
    document.getElementById('mobile_no_textbox').style.border ='2px solid #B6BBC4'
}

if(house_no_ca.value === ''){
    document.getElementById('house_no_textbox_ca').style.border = '2px solid red'
} else{
    document.getElementById('house_no_textbox_ca').style.border ='2px solid #B6BBC4'
}

if(street_ca.value === ''){
    document.getElementById('street_textbox_ca').style.border = '2px solid red'
} else{
    document.getElementById('street_textbox_ca').style.border ='2px solid #B6BBC4'
}

if(province_ca.value === 'Choose'){
    document.getElementById('province_selection_ca').style.border = '2px solid red'
} else{
    document.getElementById('province_selection_ca').style.border ='2px solid #B6BBC4'
}

if(region_ca.value === 'Choose'){
    document.getElementById('region_selection_ca').style.border = '2px solid red'
} else{
    document.getElementById('region_selection_ca').style.border ='2px solid #B6BBC4'
}

if(city_ca.value === 'Choose'){
    document.getElementById('city_selection_ca').style.border = '2px solid red'
} else{
    document.getElementById('city_selection_ca').style.border ='2px solid #B6BBC4'
}

if(baranggay_ca.value === 'Choose'){
    document.getElementById('baranggay_selection_ca').style.border = '2px solid red'
} else{
    document.getElementById('baranggay_selection_ca').style.border ='2px solid #B6BBC4'
}

if(mobile_no_ca.value === ''){
    document.getElementById('mobile_no_textbox_ca').style.border = '2px solid red'
} else{
    document.getElementById('mobile_no_textbox_ca').style.border ='2px solid #B6BBC4'
}

if (last_name.value !== '' && first_name.value !== '' && middle_name.value !== '' && birthdate.value !== '' && gender.value !== '' && civil_stat.value !== '' &&
    religion.value !== '' && nationality.value !== '' && phic.value !== '' && house_no.value !== '' && street.value !== '' && region.value !== 'Choose' &&
    province.value !== 'Choose' && city.value !== 'Choose' && baranggay.value !== 'Choose' && mobile_no.value !== '' && house_no_ca.value !== '' && street_ca.value !== '' &&
    region_ca.value !== 'Choose' && province_ca.value !== 'Choose' && city_ca.value !== 'Choose' && baranggay_ca.value !== 'Choose' && mobile_no_ca.value !== '') {

    document.getElementById('empty_modal_alert').style.display = 'flex';
    document.getElementById('empty_modal').style.display = 'flex';
    document.getElementById('no_but').style.display = 'flex';
    document.getElementById('buttons_container').style.display = 'flex';
    document.getElementById('register_but').style.display = 'flex';
    document.getElementById('ok_button').style.display = 'none';
    document.getElementById('message_label').textContent = 'Are you sure with the information?';

} else {

    document.getElementById('empty_modal_alert').style.display = 'flex';
    document.getElementById('empty_modal').style.display = 'flex';
    document.getElementById('buttons_container').style.display = 'flex';
    document.getElementById('ok_button').style.display = 'flex';
    document.getElementById('message_label').textContent = 'Please fill out the required fields.';
}})

//function to copy the permanent address and apply it to  current address

document.getElementById('same_as_perm_add_label').addEventListener('click', function() {

    var house_no = document.getElementById('house_no_textbox')
    var street = document.getElementById('street_textbox')
    var region = document.getElementById('region_selection')
    var province = document.getElementById('province_selection')
    var city = document.getElementById('city_selection')
    var baranggay = document.getElementById('baranggay_selection')
    var mobile_no  = document.getElementById('mobile_no_textbox')

    var house_no_ca = document.getElementById('house_no_textbox_ca')
    var street_ca = document.getElementById('street_textbox_ca')
    var region_ca = document.getElementById('region_selection_ca')
    var province_ca = document.getElementById('province_selection_ca')
    var city_ca = document.getElementById('city_selection_ca')
    var baranggay_ca = document.getElementById('baranggay_selection_ca')
    var mobile_no_ca = document.getElementById('mobile_no_textbox_ca')

    var hphone = document.getElementById('home_phone_textbox')
    var hphone_ca = document.getElementById('hphone_textbox_ca')
    var email_add = document.getElementById('email_add_textbox')
    var email_add_ca = document.getElementById('email_add_textbox_ca')

    house_no_ca.value = house_no.value;
    street_ca.value = street.value;
    region_ca.value = region.value;
    province_ca.value = province.value;
    city_ca.value = city.value;
    baranggay_ca.value = baranggay.value;
    hphone_ca.value = hphone.value;
    mobile_no_ca.value = mobile_no.value;
    email_add_ca.value = email_add.value;

});

//function to clear all the contents of textboxes in pastient registration form

document.getElementById('clear_button').addEventListener('click' , function(){



    document.getElementById('house_no_textbox').value = '';
    document.getElementById('street_textbox').value = '';
    document.getElementById('region_selection').selectedIndex = 0;
    document.getElementById('province_selection').selectedIndex = 0;
    document.getElementById('city_selection').selectedIndex = 0;
    document.getElementById('baranggay_selection').selectedIndex = 0;
    document.getElementById('mobile_no_textbox').value = '';
    document.getElementById('house_no_textbox_ca').value = '';
    document.getElementById('street_textbox_ca').value = '';
    document.getElementById('region_selection_ca').selectedIndex = 0;
    document.getElementById('province_selection_ca').selectedIndex = 0;
    document.getElementById('city_selection_ca').selectedIndex = 0;
    document.getElementById('baranggay_selection_ca').selectedIndex = 0;
    document.getElementById('mobile_no_textbox_ca').value = '';
    document.getElementById('home_phone_textbox').value = '';
    document.getElementById('hphone_textbox_ca').value = '';
    document.getElementById('email_add_textbox').value = '';
    document.getElementById('email_add_textbox_ca').value = '';

    document.getElementById('last_name_textbox').value = '';
    document.getElementById('first_name_textbox').value = '';
    document.getElementById('middle_name_textbox').value = '';
    document.getElementById('birthdate_textbox').value = '';
    document.getElementById('gender').selectedIndex = 0;
    document.getElementById('civilstat').selectedIndex = 0;
    document.getElementById('religion_textbox').value = '';
    document.getElementById('nationality_textbox').value = '';
    document.getElementById('phic_textbox').value = '';
    document.getElementById('house_no_textbox').value = '';
    document.getElementById('street_textbox').value = '';
    document.getElementById('region_selection').selectedIndex = 0;
    document.getElementById('province_selection').selectedIndex = 0;
    document.getElementById('city_selection').selectedIndex = 0;
    document.getElementById('baranggay_selection').selectedIndex = 0;
    document.getElementById('mobile_no_textbox').value = '';
    document.getElementById('house_no_textbox_ca').value = '';
    document.getElementById('street_textbox_ca').value = '';
    document.getElementById('region_selection_ca').selectedIndex = 0;
    document.getElementById('province_selection_ca').selectedIndex = 0;
    document.getElementById('city_selection_ca').selectedIndex = 0;
    document.getElementById('baranggay_selection_ca').selectedIndex = 0;
    document.getElementById('mobile_no_textbox_ca').value = '';

    document.getElementById('name_ext').value = '';
    document.getElementById('age').value = '';
    document.getElementById('occupation_textbox').value = '';
    document.getElementById('passport_no_textbox').value = '';
    document.getElementById('home_phone_textbox').value = '';
    document.getElementById('email_add_textbox').value = '';
    document.getElementById('hphone_textbox_ca').value = '';
    document.getElementById('email_add_textbox_ca').value = '';
    document.getElementById('house_no_textbox_curr_work').value = '';
    document.getElementById('street_textbox_curr_work').value = '';
    document.getElementById('region_selection_curr_work').selectedIndex = 0;
    document.getElementById('province_selection_curr_work').selectedIndex = 0;
    document.getElementById('city_selection_curr_work').selectedIndex = 0;
    document.getElementById('baranggay_selection_curr_work').selectedIndex = 0;
    document.getElementById('name_of_workplace_textbox').value = '';
    document.getElementById('mobile_no_textbox').value = '';
    document.getElementById('email_add_textbox_curr_work').value = '';
    document.getElementById('employers_textbox').value = '';
 

    var textBoxes = document.querySelectorAll('.all_textbox_in_ofw');

    textBoxes.forEach(function(textbox) {
        textbox.value = '';
});

//function to close the warning sign in patient registration form

})

document.querySelector('.fa-x').addEventListener('click' , function(){

    document.getElementById('empty_modal_alert').style.display = 'none'
    document.getElementById('empty_modal').style.display = 'none'
    document.getElementById('ok_button').style.display = 'none';
    document.getElementById('no_but').style.display = 'none';
    document.getElementById('buttons_container_2').style.display = 'none';
    document.getElementById('register_but').style.display = 'none';

})

//function to close the warning sign in patient registration form

document.getElementById('ok_button').addEventListener('click' , function(){

    document.getElementById('empty_modal_alert').style.display = 'none'
    document.getElementById('empty_modal').style.display = 'none'
    document.getElementById('no_but').style.display = 'none';
    document.getElementById('register_but').style.display = 'none';
    document.getElementById('ok_button').style.display = 'none'

})

//function to go back in the homepage of sdn

document.getElementById('home_but').addEventListener('click' , function(){

    document.getElementById('patient_registration_form').style.display = 'none'
    document.getElementById('home').style.display = 'flex'
    document.getElementById('incoming_tab').style.display = 'none'

})

//function to show the registration form

document.querySelector('#sub_menu2').addEventListener('click' , function(){

    document.getElementById('patient_registration_form').style.display = 'flex'
    document.getElementById('incoming_tab').style.display = 'none'
    document.getElementById('home').style.display = 'none'

})


document.getElementById('logout').addEventListener('click' , function(){


    document.getElementById('empty_modal_alert').style.display = 'flex';
    document.getElementById('empty_modal').style.display = 'flex';
    document.getElementById('buttons_container_2').style.display = 'flex';
    document.getElementById('message_label').textContent = 'Are you sure you want to log out?';
    
})


document.getElementById('yes_button').addEventListener('click', function(){


    window.location.href = './loginpage.html';



})
