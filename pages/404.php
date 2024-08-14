<?php 
  include('../include/header.php');
?>

<div class="container-fluid">
  <?php if($access == 1) { ?>
    <div class="text-center">
      <div class="error mx-auto" data-text="404">404</div>
      <p class="lead text-gray-800 mb-5">Page Not Found</p>
      <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
      <a href="index.php">&larr; Back to Dashboard</a>
    </div>
  <?php } elseif($access == 2) { ?>
    <div class="text-center">
      <div class="error mx-auto" data-text="404">404</div>
      <p class="lead text-gray-800 mb-5">Page Not Found</p>
      <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
      <a href="index.php">&larr; Back to Dashboard</a>
    </div>
  <?php } elseif($access == 3) { ?>
    <div class="text-center">
      <div class="error mx-auto" data-text="404">404</div>
      <p class="lead text-gray-800 mb-5">Page Not Found</p>
      <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
      <a href="index.php">&larr; Back to Dashboard</a>
    </div>
  <?php } ?>
</div>
<?php include('../include/footer.php'); ?>