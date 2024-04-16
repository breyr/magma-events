// on page load
$(document).ready(function () {
  // enable tooltips
  $('[data-bs-toggle="tooltip"]').tooltip();
});

// handle login
$("#submit-login-btn").on("click", function () {
  // get login information
  const username = $("#username").val();
  const password = $("#password").val();
  // ajax request to login
  $.ajax({
    url: "./scripts/login.php",
    method: "post",
    data: {
      username: username,
      password: password,
    },
    success: function (res) {
      // hide login error if it was showing
      $("#login-error").removeClass("d-block");
      // hide the modal
      $("#loginModa").modal("hide");
      // clear inputs
      $("#username").val("");
      $("#password").val("");
      console.log(res);
      if (res === "Server" || res === "Preparer") {
        // redirect user to their schedule
        window.location.href = "schedule.php";
      } else if (res === "Admin") {
        // redirect user to admin page
        window.location.href = "admin.php";
      }
    },
    error: function (res) {
      // res.responseText
      $("#login-error").removeClass("d-none").addClass("d-block");
    },
  });
});

// handle logout
$("#submit-logout-btn").on("click", function () {
  // ajax request to logout
  $.ajax({
    url: "./scripts/logout.php",
    method: "post",
    success: function (res) {
      // redirect user to index.php
      window.location.href = "index.php";
    },
  });
});

// handle vacation request submission
$("#submit-vacation-request").on("click", function (e) {
  e.preventDefault();
  // get info
  let startDate = $("#start-date").val();
  let endDate = $("#end-date").val();
  const reason = $("#reason").val();

  // get start and end date
  startDate = new Date(startDate + "T00:00:00Z");

  // format start date in 'month/day' format
  const formattedStartDate = startDate.getUTCMonth() + 1 + "/" + startDate.getUTCDate();
  let formattedEndDate = "";

  // check if end date is not empty
  if (endDate) {
    endDate = new Date(endDate + "T00:00:00Z");
    formattedEndDate = endDate.getUTCMonth() + 1 + "/" + endDate.getUTCDate();
  }

  // concatenate dates with '-'
  const vacationDays = formattedEndDate ? formattedStartDate + "-" + formattedEndDate : formattedStartDate;
  console.log(vacationDays);
  // check if all fields are filled out
  if (!startDate || !reason) {
    alert("Please fill out all fields.");
    return;
  }
  // make sure start date is before end date
  if (endDate && startDate >= endDate) {
    alert("Start date must be before end date.");
    return;
  }
  // post data
  $.ajax({
    url: "./scripts/handleVacationRequest.php",
    method: "post",
    data: {
      vacationDays: vacationDays,
      reason: reason,
    },
    success: function (res) {
      // generate a unique ID for the toast
      var toastId = "toast" + Date.now();

      // append the toast to the toast container
      $("#toast-container")
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
      $("#" + toastId).toast("show");

      // clear inputs
      $("#start-date").val("");
      $("#end-date").val("");
      $("#reason").val("");
    },
  });
});

// handle admin actions for vacation requests
$(".approve, .deny").click(function () {
  // get row id, action, and row
  // get row id, action, and row
  const rowId = $(this).closest("tr").find("td:first").text();
  const action = $(this).hasClass("approve") ? "approve" : "deny";
  const row = $(this).closest("tr");
  const vacationDays = $(this).closest("tr").find("td").eq(4).text();
  const username = $(this).closest("tr").find("td").eq(1).text();
  // ajax request
  $.ajax({
    url: "./scripts/handleVacationAdminAction.php",
    type: "post",
    data: {
      id: rowId,
      username: username,
      action: action,
      vacationDays: vacationDays,
    },
    success: function (res) {
      // generate a unique ID for the toast
      var toastId = "toast" + Date.now();

      // append the toast to the toast container
      $("#toast-container")
        .append(`<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="${toastId}"><div class="toast-header">
                <strong class="me-auto text-success">Success</strong>
                <button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close">
                  <span aria-hidden="true"></span>
                </button>
              </div>
              <div class="toast-body">
              Vacation request ${action} submitted successfully.
              </div>
              </div>`);

      // show the toast
      $("#" + toastId).toast("show");

      // delete the table row
      row.remove();
    },
  });
});
