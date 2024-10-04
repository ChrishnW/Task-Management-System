window.onload = function () {
  document.getElementById('preloader').style.display = 'none';
  document.getElementById('wrapper').style.visibility = 'visible';
};

function togglePreloader(show) {
  if (show) {
    $('#preloader').show();
    // $('#wrapper').css('visibility', 'hidden');
  } else {
    $('#preloader').hide();
    // $('#wrapper').css('visibility', 'visible');
  }
}

document.addEventListener('DOMContentLoaded', function () {
  var inactivityTime = 900000;
  var timeout;

  function resetTimeout() {
    clearTimeout(timeout);
    timeout = setTimeout(function () {
      location.reload();
    }, inactivityTime);
  }

  // window.addEventListener('mousemove', resetTimeout);
  window.addEventListener('keypress', resetTimeout);
  window.addEventListener('touchstart', resetTimeout);

  resetTimeout();
});

function openSpecificModal(modalId, size) {
  var modalDialog = document.querySelector(`#${modalId} .modal-dialog`);
  modalDialog.classList.remove('modal-sm', 'modal-lg', 'modal-xl');
  if (size) {
    modalDialog.classList.add(size);
  }
  $(`#${modalId}`).modal('show');
}

function readNotification(element) {
  var id = element.value;
  $.ajax({
    method: "POST",
    url: "../config/index.php",
    data: {
      "readNotification": true,
      "id": id,
    },
    success: function (response) {
      console.log(response);
    }
  })
}

function readAllNotification(element) {
  var checkboxes = document.querySelectorAll('input[type="hidden"][name="notificationID[]"]');
  var checkedIds = [];
  checkboxes.forEach(function (checkbox) {
    if (checkbox.value != null) {
      checkedIds.push(checkbox.value);
    }
  });
  $.ajax({
    method: "POST",
    url: "../config/index.php",
    data: {
      "readAllNotification": true,
      "checkedIds": checkedIds,
    },
    success: function (response) {
      console.log(response);
    }
  })
}

$('#activityLogs').on('shown.bs.modal', function () {
  if (!$.fn.DataTable.isDataTable('#activityTable')) {
    $('#activityTable').DataTable({
      "paging": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "order": [
        [0, "desc"]
      ],
      "pageLength": 5,
      "lengthMenu": [5, 10, 25, 50, 100]
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  var currentPath = window.location.pathname.split("/").pop();
  var navItems = document.querySelectorAll('.nav-item');

  navItems.forEach(function (navItem) {
    var mainNavLink = navItem.querySelector('a.nav-link');
    var mainLinkPath = mainNavLink.getAttribute('href');

    if (mainLinkPath === currentPath) {
      navItem.classList.add('active');
    } else {
      var collapseItems = navItem.querySelectorAll('.collapse-item');
      collapseItems.forEach(function (collapseItem) {
        var collapseLinkPath = collapseItem.getAttribute('href');
        if (collapseLinkPath === currentPath) {
          navItem.classList.add('active');
          mainNavLink.classList.add('active');
        }
      });
    }
  });
});

function checkPasswordStrength() {
  var password = document.getElementById("new-password").value;
  var strengthBar = document.getElementById("strength-bar");
  var strengthText = document.getElementById("strength-text");

  var strength = 0;

  // Check password strength conditions
  if (password.length >= 8) strength += 1;  // Length
  if (password.match(/[a-z]/)) strength += 1;  // Lowercase
  if (password.match(/[A-Z]/)) strength += 1;  // Uppercase
  if (password.match(/[0-9]/)) strength += 1;  // Numbers
  if (password.match(/[\W_]/)) strength += 1;  // Special characters

  // Update progress bar and text based on strength
  switch (strength) {
    case 0:
      strengthBar.style.width = '0%';
      strengthBar.className = 'progress-bar bg-danger strength-meter';
      strengthText.textContent = 'Too weak';
      break;
    case 1:
      strengthBar.style.width = '20%';
      strengthBar.className = 'progress-bar bg-danger strength-meter';
      strengthText.textContent = 'Very weak';
      break;
    case 2:
      strengthBar.style.width = '40%';
      strengthBar.className = 'progress-bar bg-warning strength-meter';
      strengthText.textContent = 'Weak';
      break;
    case 3:
      strengthBar.style.width = '60%';
      strengthBar.className = 'progress-bar bg-info strength-meter';
      strengthText.textContent = 'Good';
      break;
    case 4:
      strengthBar.style.width = '80%';
      strengthBar.className = 'progress-bar bg-primary strength-meter';
      strengthText.textContent = 'Strong';
      break;
    case 5:
      strengthBar.style.width = '100%';
      strengthBar.className = 'progress-bar bg-success strength-meter';
      strengthText.textContent = 'Very strong';
      break;
  }
}

function accountEdit(element) {
  var accountID = element.value;
  $.ajax({
    method: "POST",
    url: "../config/accounts.php",
    data: {
      'accountEdit': true,
      'accountID': accountID,
    },
    success: function (response) {
      $('#editBody').html(response);
      openSpecificModal('accountEdit', 'modal-lg');
      $('.selectpicker').selectpicker('refresh');
      attachEventListeners();
    }
  })
}

function attachEventListeners() {
  document.getElementById('uploadPicture').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById('profileImage').src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });

  document.getElementById('removeButton').addEventListener('click', function () {
    document.getElementById('uploadPicture').value = '';
    document.getElementById('profileImage').src = '../assets/img/user-profiles/nologo.png';
  });
}

function detailsUpdate(element) {
  element.disabled = true;
  const imgSrc = $('#profileImage').attr('src').substring($('#profileImage').attr('src').lastIndexOf('/') + 1);
  const accountDetails = new FormData(document.getElementById('accountEditForm'));
  if (imgSrc === accountDetails.get('curImg')) {
    accountDetails.delete('imgCon');
  } else if (imgSrc !== accountDetails.get('curImg')) {
    if (imgSrc.includes('nologo')) {
      accountDetails.append('imgCon', 1);
    } else {
      accountDetails.append('imgCon', 2);
    }
  }
  accountDetails.append('detailsUpdate', true);
  $.ajax({
    url: "../config/accounts.php",
    type: "POST",
    data: accountDetails,
    processData: false,
    contentType: false,
    success: function (response) {
      console.log(response);
      if (response === 'Success') {
        $('#success').modal('show');
      } else {
        if (response !== '' && !response.includes('Warning')) {
          document.getElementById('error_found').innerHTML = response;
        } else {
          document.getElementById('error_found').innerHTML = 'There was an error processing your request.';
        }
        $('#error').modal('show');
        element.disabled = false;
      }
    }
  });
}

function passwordUpdate(element) {
  element.disabled = true;
  const accountPassword = new FormData(document.getElementById('accountSecurity'));
  accountPassword.append('passwordUpdate', true);
  $.ajax({
    url: "../config/accounts.php",
    type: "POST",
    data: accountPassword,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response === 'Success') {
        $('#success').modal('show');
      } else {
        if (response !== '' && !response.includes('Warning')) {
          document.getElementById('error_found').innerHTML = response;
        } else {
          document.getElementById('error_found').innerHTML = 'There was an error processing your request.';
        }
        $('#error').modal('show');
        element.disabled = false;
      }
    }
  })
}

function permissionUpdate(element) {
  document.getElementById('error_found').innerHTML = 'There was an error processing your request.';
  $('#error').modal('show');
}