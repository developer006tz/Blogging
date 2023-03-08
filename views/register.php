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

  <title>Register</title>
</head>

<body class="bg-patterned-body">

  <!-- Page Content -->
  <div class="page-content auth-page-content">
     <form action="<?php url("/register"); ?>" method="post" class="admin-form auth-form" enctype="multipart/form-data">
      <h1 class="center form-title">Sign Up</h1>

      <?php include ROOT_PATH . '/views/partials/form_errors.php';?>

      <div class="input-group avatar-input-group center">
        <input type="file" name="avatar" onChange="displayImage(this)" id="avatar-input" class="hide avatar-input">
        <button type="button" class="change-avatar-btn">
          <span>change</span>
        </button>
        <label for="avatar-input">Profile Image (optional)</label>
      </div>

      <div class="input-group">
        <label for="username">Username</label>
        <input type="text" name="username" value="<?php echo $this->username; ?>" id="username" class="input-control">
      </div>
      <div class="input-group">
        <label for="email">Email</label>
        <input type="email" name="email" value="<?php echo $this->email; ?>" id="email" class="input-control">
      </div>
      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" name="password" value="<?php echo $this->password; ?>" id="password" class="input-control">
      </div>
      <div class="input-group">
        <label for="passwordConf">Password Confirmation</label>
        <input type="password" name="passwordConf" value="<?php echo $this->passwordConf; ?>" id="passwordConf" class="input-control">
      </div>
      <div class="input-group submit-group">
        <button type="submit" class="btn primary-btn big-btn long-btn">Register</button>
      </div>
      <p class="center">
        <small>
          Already have an account? You can <a href="<?php url('/login') ?>">Log in</a>
        </small>
      </p>
    </form>
  </div>
  <!-- // Page Content -->

  <!-- Scripts -->
  <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>

  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script>
    const changeAvatarButton = document.querySelector('.change-avatar-btn');
    const avatarInput = document.querySelector('.avatar-input');
    changeAvatarButton.addEventListener('click', function(e) {
      avatarInput.click();
    });

    function displayImage(e) {
      if (e.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          changeAvatarButton.style.backgroundImage = `url(${e.target.result})`;
        }
        reader.readAsDataURL(e.files[0]);
      }
    }
  </script>

</body>

</html>