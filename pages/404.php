<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="text-center mt-4">
          <img class="img-fluid p-4" src="../assets/img/illustrations/401-error-unauthorized.svg" alt="">
          <p class="lead">Access to this resource is denied.</p>
          <a class="text-arrow-icon" href="dashboard-1.html">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left ms-0 me-1">
              <line x1="19" y1="12" x2="5" y2="12"></line>
              <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Return to Dashboard
          </a>
        </div>
      </div>
    </div>
  <?php } elseif ($access == 2) { ?>
    <div class="text-center">
      <div class="error mx-auto" data-text="404">404</div>
      <p class="lead text-gray-800 mb-5">Page Not Found</p>
      <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
      <a href="index.php">&larr; Back to Dashboard</a>
    </div>
  <?php } elseif ($access == 3) { ?>
    <div class="text-center">
      <div class="error mx-auto" data-text="404">404</div>
      <p class="lead text-gray-800 mb-5">Page Not Found</p>
      <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
      <a href="index.php">&larr; Back to Dashboard</a>
    </div>
  <?php } ?>
</div>
<?php include('../include/footer.php'); ?>