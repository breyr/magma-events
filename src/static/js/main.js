// logic to handle on page loads
$(document).ready(function () {

});

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