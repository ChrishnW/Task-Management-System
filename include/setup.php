<?php include('auth.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Cache control meta tags -->
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <title>Task Management System</title>

  <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/Logo.png">

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../assets/fonts/Nunito.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">

</head>

<body class="bg-gradient-light">
  <div id="preloader"><img src="../assets/img/illustrations/loading.gif" alt="Loading..."></div>
  <div class="container center-screen">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header text-center">
          <h3 class="mt-4 font-weight-bolder">Account Setup</h3>
          <p class="text-muted mb-0">Welcome to Task Mangement System!</p>
          <p class="text-muted">Please fill in your details to proceed.</p>
        </div>
        <div class="card-body">
          <form id="userDetails" class="mb-5">

            <!-- First Name -->
            <div class="mb-3">
              <label for="firstName" class="form-label">First Name <small class="text-danger">*</small></label>
              <input type="text" class="form-control" id="firstName" name="firstName"
                placeholder="Enter your first name" required>
            </div>

            <!-- Last Name -->
            <div class="mb-3">
              <label for="lastName" class="form-label">Last Name <small class="text-danger">*</small></label>
              <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter your last name"
                required>
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label for="email" class="form-label">Email <small class="text-danger">*</small></label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <!-- Employee ID -->
            <div class="mb-3">
              <label for="empId" class="form-label">Employee ID <small class="text-danger">*</small></label>
              <input type="text" class="form-control" id="empId" name="empId" placeholder="Enter your employee ID"
                required>
            </div>

            <!-- Profile Image Selection -->
            <div class="mb-3">
              <label class="form-label">Profile Picture <small class="text-danger">*</small></label>
              <div id="imageOptions">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="profileImage" id="defaultImage" value="default">
                  <label class="form-check-label" for="defaultImage">
                    Choose from Default Images
                  </label>
                </div>
                <div class="form-check mt-2">
                  <input class="form-check-input" type="radio" name="profileImage" id="customImage" value="custom">
                  <label class="form-check-label" for="customImage">
                    Upload Custom Image
                  </label>
                </div>
              </div>

              <!-- Default Images (Hidden by Default) -->
              <div id="defaultImages" class="mt-3 d-none">
                <div class="row">
                  <div class="col-3">
                    <img src="../assets/img/user-profiles/Default/woman_1.png" alt="Image 1" class="img-thumbnail-circle default-image" data-image="woman_1.png">
                  </div>
                  <div class="col-3">
                    <img src="../assets/img/user-profiles/Default/man_1.png" alt="Image 2" class="img-thumbnail-circle default-image" data-image="man_1.png">
                  </div>
                  <div class="col-3">
                    <img src="../assets/img/user-profiles/Default/woman_2.png" alt="Image 3" class="img-thumbnail-circle default-image" data-image="woman_2.png">
                  </div>
                  <div class="col-3">
                    <img src="../assets/img/user-profiles/Default/man_2.png" alt="Image 4" class="img-thumbnail-circle default-image" data-image="man_2.png">
                  </div>
                  <div class="col-3">
                    <img src="../assets/img/user-profiles/Default/woman_3.png" alt="Image 1" class="img-thumbnail-circle default-image" data-image="woman_3.png">
                  </div>
                  <div class="col-3">
                    <img src="../assets/img/user-profiles/Default/man_3.png" alt="Image 1" class="img-thumbnail-circle default-image" data-image="man_3.png">
                  </div>
                </div>
              </div>

              <!-- Custom Image Upload (Hidden by Default) -->
              <div id="customImageUpload" class="mt-3 d-none">
                <input type="file" class="form-control" id="customImageInput" accept="image/*">
              </div>

              <!-- Display selected profile image -->
              <div class="mt-3 d-none" id="selectedProfileContainer">
                <img id="selectedProfileImage" src="" alt="Selected Image" class="img-thumbnail-circle" style="width: 100px;">
              </div>

            </div>
          </form>
          <div class="d-grid">
            <button id="completeButton" class="btn btn-block btn-primary" disabled><i class="fas fa-save fa-fw"></i> Complete</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../assets/js/sb-admin-2.min.js"></script>

<script>
  window.onload = function() {
    document.getElementById('preloader').style.display = 'none';
  };

  function togglePreloader(show) {
    if (show) {
      $('#preloader').show();
    } else {
      $('#preloader').hide();
    }
  }
</script>

<script>
  // Toggle visibility of default images and custom image upload
  document.querySelectorAll('input[name="profileImage"]').forEach((input) => {
    input.addEventListener('change', function() {
      const defaultImages = document.getElementById('defaultImages');
      const customImageUpload = document.getElementById('customImageUpload');
      const selectedProfileContainer = document.getElementById('selectedProfileContainer');

      if (this.value === 'default') {
        // Show default images, hide custom upload
        defaultImages.classList.remove('d-none');
        customImageUpload.classList.add('d-none');

        // Hide selected profile container (no preview for default images)
        selectedProfileContainer.classList.add('d-none');
      } else if (this.value === 'custom') {
        // Show custom upload, hide default images
        customImageUpload.classList.remove('d-none');
        defaultImages.classList.add('d-none');

        // Hide selected profile container if no custom image is uploaded
        const customImageInput = document.getElementById('customImageInput');
        if (customImageInput.files.length === 0) {
          selectedProfileContainer.classList.add('d-none');
        }
      }
    });
  });

  // Handle default image selection (no preview update)
  document.querySelectorAll('.default-image').forEach((img) => {
    img.addEventListener('click', function() {
      // Remove selected class from all images
      document.querySelectorAll('.default-image').forEach((image) => {
        image.classList.remove('selected');
      });

      // Add selected class to the clicked image
      this.classList.add('selected');

      // No preview update for default images
      checkFormValidity(); // Check form validity when a default image is selected
    });
  });

  // Handle custom image file input change
  document.getElementById('customImageInput').addEventListener('change', function() {
    const customImageInput = document.getElementById('customImageInput');
    const selectedImageElement = document.getElementById('selectedProfileImage');
    const selectedProfileContainer = document.getElementById('selectedProfileContainer');

    if (customImageInput.files.length > 0) {
      const file = customImageInput.files[0];
      const objectURL = URL.createObjectURL(file);

      // Display the selected custom image
      selectedImageElement.src = objectURL;
      selectedProfileContainer.classList.remove('d-none');
    } else {
      // Hide the preview if no custom image is selected
      selectedProfileContainer.classList.add('d-none');
    }

    checkFormValidity(); // Check form validity when a custom image is uploaded
  });

  // Form validation to enable/disable submit button
  function checkFormValidity() {
    const form = document.getElementById('userDetails');
    const inputs = form.querySelectorAll('input[required]');
    const button = document.getElementById('completeButton');
    let allFilled = true;

    // Check if all required fields are filled
    inputs.forEach(input => {
      if (!input.value.trim()) {
        allFilled = false;
      }
    });

    // Check if an image is selected (default or custom)
    const profileImageOption = document.querySelector('input[name="profileImage"]:checked');
    let imageSelected = false;

    if (profileImageOption) {
      if (profileImageOption.value === 'default') {
        // Check if any default image is selected
        const selectedDefaultImage = document.querySelector('.default-image.selected');
        imageSelected = selectedDefaultImage ? true : false;
      } else if (profileImageOption.value === 'custom') {
        // Check if a file is selected
        const customImageInput = document.getElementById('customImageInput');
        imageSelected = customImageInput.files.length > 0;
      }
    }

    // Enable the button only if all fields are filled and an image is selected
    button.disabled = !(allFilled && imageSelected);
  }



  // Initialize form validity when the page loads
  document.addEventListener('DOMContentLoaded', checkFormValidity);
</script>

<script>
  document.getElementById('completeButton').addEventListener('click', function() {
    togglePreloader(true);
    const form = document.getElementById('userDetails');
    const formData = new FormData(form);

    // Isset Submit
    formData.append('submitDetails', true);

    // Check which profile image option is selected
    const selectedProfileOption = formData.get('profileImage'); // Get selected radio button value

    if (selectedProfileOption === 'default') {
      // Get the `data-image` value from the selected default image
      const selectedDefaultImage = document.querySelector('.default-image.selected');
      if (selectedDefaultImage) {
        const fileName = selectedDefaultImage.getAttribute('data-image');
        formData.append('profileImageFileName', fileName);
      }
    } else if (selectedProfileOption === 'custom') {
      // Get the file from the custom image input
      const customImageInput = document.getElementById('customImageInput');
      if (customImageInput.files.length > 0) {
        formData.append('profileImageFile', customImageInput.files[0]);
      }
    }

    // Log the FormData contents (for debugging)
    for (let [key, value] of formData.entries()) {
      console.log(key, value);
    }

    $.ajax({
      type: 'POST',
      url: '../config/setup.php',
      data: formData,
      processData: false,
      contentType: false,
      success: function(data) {
        window.location.href = "../pages/index.php";
      },
      error: function(xhr, status, error) {
        console.error(xhr, status, error);
      }
    });

    // You can now send the FormData to a server via fetch or another API
    // Example:
    // fetch('/submit-form', { method: 'POST', body: formData })
    //     .then(response => response.json())
    //     .then(data => console.log(data))
    //     .catch(error => console.error('Error:', error));
  });
</script>

</html>