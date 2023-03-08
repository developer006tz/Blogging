<?php $base_url = 'http://localhost/eveningclass101' ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    .email-body {
      width: 700px;
      margin: 4rem auto;
      font-family: Helvetica, Arial, sans-serif;
      font-size: 1.125rem;
      color: #333333;
      line-height: 1.725rem;
    }

    @media only screen and (max-width: 700px) {
      .email-body {
        max-width: 100%;
        padding: 16px;
      }
    }
  </style>

  <title>Reset your password</title>
</head>
<body>

<div class="container">
  <div class="email-body">
    <p>Hey there!</p>
    <p>Someone just requested a change of password on our site. If that person is you, please click on the link below to create a new password.</p>
    <p>
    <a href="<?php echo $base_url; ?>/reset-password/<?php echo urldecode($_GET['token']) ?>">Reset your password</a>
    </p>
    <p>
      If that link doesn't open, please copy this url and paste and visit it in your browser: <?php echo $base_url; ?>/reset-password/<?php echo urldecode($_GET['token']) ?>
    </p>
    <p>If you didn't request for a password change, please ignore this message.</p>
    <p>Thank you!</p>
  </div>
</div>

</body>
</html>