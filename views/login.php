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

  <title>Login</title>
</head>

<body class="bg-patterned-body">

  <!-- Page Content -->
  <div class="page-content auth-page-content">
     <form action="<?php url('/login'); ?>" method="post" class="admin-form auth-form small-form">
      <h1 class="center form-title">Login</h1>

      <?php include ROOT_PATH . '/views/partials/form_errors.php';?>

      <div class="input-group">
        <label for="username">Username or email</label>
        <input type="text" name="username" value="<?php echo $this->username; ?>" id="username" class="input-control">
      </div>
      <div class="input-group">
        <div style="display: flex; justify-content: space-between;">
          <label for="password">Password</label>
          <a href="<?php url("/forgot-password"); ?>"><small>Forgot Password?</small></a>
        </div>
        <input type="password" name="password" value="<?php echo $this->password; ?>" id="password" class="input-control">
      </div>
      <div class="input-group">
        <label for="remember-me">
          <input type="checkbox" name="remember-me" id="remember-me">
          Remember Me
        </label>
      </div>
      <div class="input-group submit-group">
        <button type="submit" class="btn primary-btn big-btn long-btn">Login</button>
      </div>
      <br>
      <p class="center">
        <small>
          Don't yet have an account? You can <a href="<?php url("/register"); ?>">Sign Up</a>
        </small>
      </p>
    </form>
  </div>
  <!-- // Page Content -->

  <!-- Scripts -->
  <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>

  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</body>

</html>