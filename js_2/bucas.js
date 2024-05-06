$(document).ready(function() {
    $('.view-link').click(function() {
        var bucasID = $(this).data('bucas-id');
        var formData = { bucasID_parameter: bucasID };
        console.log(bucasID);
        
        $.ajax({
            type: 'POST',
            url: '../php_2/bucas_referral.php',
            data: formData,
            success: function(response) {
                $('.modal-body').html(response); 
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

    // $('#searchBtn').click(function() {
    //     $('#bucasBackdrop').modal('hide');
    // });
    
});

document.getElementById('submit-referral-btn').addEventListener("click", function() {
    var sdnPatientID = document.getElementById("sdnBucasID").value;
    var sdnCaseNo = document.getElementById("sdnCaseNo").value;
    var sdnStatusInput = document.getElementById("sdnStatus").value;
    var sdnProcessDT = document.getElementById("sdnProcessDT").value;
    var statusDefer = document.getElementById("statusDefer").value;    
    var sdnUserLog = document.getElementById("sdnUserID").value;

    if (sdnStatusInput.trim() === "") {
        alert("Please enter a value for the Response Status.");
        return;
    }

    var formData = {
        sdnPatientID: sdnPatientID,
        sdnCaseNo: sdnCaseNo,
        sdnStatusInput: sdnStatusInput,
        sdnProcessDT: sdnProcessDT,
        statusDefer: statusDefer,
        sdnUserLog: sdnUserLog
    }

    $.ajax({
        type: 'POST',
        url: '../php_2/referral_response.php',
        data: formData,
        success: function (response) {
            var _response = JSON.parse(response);
            if (_response.success == true) {
                alert(_response.message);
                // close the modal progmatically
                document.getElementById('bucasBackdrop').style.display = "none";
                var modalBackdrops = document.querySelectorAll('.modal-backdrop');
                modalBackdrops.forEach(function(backdrop) {
                    backdrop.remove();
                });
                document.body.classList.remove('modal-open');
                window.location.reload();
            } else {
                alert(_response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error inserting data:', error);
        }
    });
}); 
