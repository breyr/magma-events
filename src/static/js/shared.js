// handle login
$('#submit-login-btn').on('click', function () {
    // get login information
    const username = $('#username').val();
    const password = $('#password').val();
    // ajax request to login
    $.ajax({
        url: "./scripts/login.php",
        method: "post",
        data: {
            "username": username,
            "password": password,
        },
        success: function (res) {
            // hide login error if it was showing
            $('#login-error').removeClass('d-block');
            // hide the modal
            $('#loginModa').modal('hide');
            // clear inputs
            $('#username').val('');
            $('#password').val('');
            // redirect user to index
            window.location.href = 'index.php';
        },
        error: function (res) {
            // res.responseText
            $('#login-error').removeClass('d-none').addClass('d-block');
        }
    })
});

// handle logout
$('#submit-logout-btn').on('click', function () {
    // ajax request to logout
    $.ajax({
        url: "./scripts/logout.php",
        method: "post",
        success: function (res) {
            // redirect user to index.php
            window.location.href = 'index.php';
        }
    })
});

// handle vacation request submission
$('#submit-vacation-request').on('click', function (e) {
    e.preventDefault();
    // get info
    const startDate = $('#start-date').val();
    const endDate = $('#end-date').val();
    const reason = $('#reason').val();
    // check if all fields are filled out
    if (!startDate || !endDate || !reason) {
        alert('Please fill out all fields.');
        return;
    }
    // make sure start date is before end date
    if (startDate >= endDate) {
        alert('Start date must be before end date.');
        return;
    }
    // post data
    $.ajax({
        url: "./scripts/handleVacationRequest.php",
        method: "post",
        data: {
            "startDate": startDate,
            "endDate": endDate,
            "reason": reason
        },
        success: function (res) {
            // generate a unique ID for the toast
            var toastId = 'toast' + Date.now();

            // append the toast to the toast container
            $('#toast-container')
                .append(`<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="${toastId}"><div class="toast-header">
                <strong class="me-auto text-success">Success</strong>
                <button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close">
                  <span aria-hidden="true"></span>
                </button>
              </div>
              <div class="toast-body">
              Vacation request submitted successfully.
              </div>
              </div>`);

            // show the toast
            $('#' + toastId).toast('show');

            // clear inputs 
            $('#start-date').val('');
            $('#end-date').val('');
            $('#reason').val('');
        }
    })
});