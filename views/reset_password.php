<?php include 'path.php';?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap"
    rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/admin_style.css'; ?>">
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/style.css'; ?>">

  <title>Reset Password</title>
</head>

<body class="bg-patterned-body">


  <!-- Page Content -->
  <div class="page-content auth-page-content">
     <form action="<?php url("/reset-password"); ?>" method="post" class="admin-form auth-form small-form">
      <h2 class="center form-title">Set new password</h2>

      <?php include ROOT_PATH . '/views/partials/form_errors.php';?>
      <input type="hidden" name="token" value="<?php echo $this->token; ?>">
      <div class="input-group">
        <label for="password">New Password</label>
        <input type="password" name="password" id="password" class="input-control">
      </div>
      <div class="input-group">
        <label for="passwordConf">Confirm Password</label>
        <input type="password" name="passwordConf" id="passwordConf" class="input-control">
      </div>
      <div class="input-group submit-group">
        <button type="submit" class="btn primary-btn big-btn long-btn">Update Password</button>
      </div>
    </form>
  </div>
  <!-- // Page Content -->

  <!-- Scripts -->
  <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>

  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</body>

</html>
