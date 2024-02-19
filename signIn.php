<?php

include 'connect/login.php';
// require 'connect/DB.php';
require 'core/load.php';

// Check if user is already logged in
if (login::isLoggedIn()) {
  header('Location: index.php');
  exit;
}

if (isset($_POST['in-email-mobile']) && !empty($_POST['in-email-mobile'])) {
  $email_mobile = $_POST['in-email-mobile'];
  $in_pass = $_POST['in-pass'];

  if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email_mobile)) {
    if (!preg_match("^[0-9]{10}^", $email_mobile)) {
      $error = 'Email or Phone is not correct. Please try again';
    } else {
      if (DB::query("SELECT mobile FROM users WHERE mobile = :mobile", array(':mobile' => $email_mobile))) {
        if (password_verify($in_pass, DB::query('SELECT password FROM users WHERE mobile=:mobile', array(':mobile' => $email_mobile))[0]['password'])) {

          $user_id = DB::query('SELECT user_id FROM users WHERE mobile = :mobile', array(':mobile' => $email_mobile))[0]['user_id'];
          $tStrong = true;
          $token = bin2hex(openssl_random_pseudo_bytes(64, $tStrong));
          $loadFromUser->create('token', array('token' => sha1($token), 'user_id' => $user_id));

          setcookie('FBID', $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, true);

          header('Location: index.php');
        } else {
          $error = "Password is not correct";
        }
      } else {
        $error = "User hasn't found.";
      }
    }
  } else {
    if (DB::query("SELECT email FROM users WHERE email = :email", array(':email' => $email_mobile))) {
      if (password_verify($in_pass, DB::query('SELECT password FROM users WHERE email=:email', array(':email' => $email_mobile))[0]['password'])) {

        $user_id = DB::query('SELECT user_id FROM users WHERE email=:email', array(':email' => $email_mobile))[0]['user_id'];
        $tStrong = true;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $tStrong));
        $loadFromUser->create('token', array('token' => sha1($token), 'user_id' => $user_id));

        setcookie('FBID', $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, true);

        header('Location: index.php');
      } else {
        $error = "Password is not correct";
      }
    } else {
      $error = "User hasn't found.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Social | Sign In</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="assets/image/favicon.ico" type="image/x-icon" />
  <!-- CDN CSS-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" />
  <!-- Icon CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="assets/plugins/fontawesome-6/css/all.min.css" />
  <link rel="stylesheet" href="assets/dist/emojionearea.min.css" />
  <link rel="stylesheet" href="assets/plugins/alertBox/alertBox.css" />
  <!-- Main CSS -->
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>

  <header id="header"></header>
  <main>
    <div class="container">
      <div class="row justify-content-center align-items-center social-signIn-wrapper">
        <div class="col-12 col-md-4">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent text-center p-3">
              <h1 class="logo-text">Social</h1>
              <!-- <img src="assets/image/social-logo.png" class="login-logo img-fluid mt-2 mb-2" alt=""> -->
            </div>
            <div class="card-body">
              <!-- SignIn Form Start -->
              <?php
              if (!empty($error)) {
              ?>
              <div class="error-wrapper">
                <?php echo $error; ?>
              </div>
              <?php
              }
              ?>
              <form action="signIn.php" method="post">
                <div class="mb-3">
                  <input type="text" class="form-control" name="in-email-mobile"
                    placeholder="Mobile number or email address">
                </div>
                <div class="mb-3">
                  <input type="password" class="form-control" name="in-pass" placeholder="Password">
                </div>
                <div class="d-grid gap-2 mb-2">
                  <input class="btn login-btn" type="submit" value="Login">
                </div>
                <div class="redirect-link text-end">
                  <a href="#">
                    Forgot password
                  </a>
                </div>
              </form>
              <div class="line-wrapper mt-2 mb-3">
                <div class="horizontal-line"></div>
                <div class="d-inline-block text-secondary">OR</div>
                <div class="horizontal-line"></div>
              </div>
              <!-- Button trigger modal -->
              <div class="text-center">
                <a href="signUp.php" class="btn signUp-btn">
                  Sign up
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <footer id="footer"></footer>
  <!-- CDN JS-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js">
  </script>
  <script src="assets/dist/emojionearea.min.js"></script>
  <script src="assets/plugins/alertBox/alertBox.js"></script>
</body>

</html>