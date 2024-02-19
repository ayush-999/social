<?php

include 'connect/login.php';
// require 'connect/DB.php';
require 'core/load.php';

// Check if user is already logged in
if (login::isLoggedIn()) {
  header('Location: index.php');
  exit;
}

if (isset($_POST['first-name']) && !empty($_POST['first-name'])) {
  $upFirst = $_POST['first-name'];
  $upLast = $_POST['last-name'];
  $upEmailMobile = $_POST['email-mobile'];
  $upPassword = $_POST['up-password'];
  $birthDay = $_POST['birth-day'];
  $birthMonth = $_POST['birth-month'];
  $birthYear = $_POST['birth-year'];
  $birth = '' . $birthYear . '-' . $birthMonth . '-' . $birthDay . '';

  if (!empty($_POST['gen'])) {
    $upGen = $_POST['gen'];
  }

  if (empty($upFirst) or empty($upLast) or empty($upEmailMobile) or empty($upGen)) {
    $error = 'All felids are required';
  } else {
    $first_name = $loadFromUser->checkInput($upFirst);
    $last_name = $loadFromUser->checkInput($upLast);
    $email_mobile = $loadFromUser->checkInput($upEmailMobile);
    $password = $loadFromUser->checkInput($upPassword);
    $screenName = strtolower('' . $first_name . '_' . $last_name . '');
    if (DB::query('SELECT screenName FROM users WHERE screenName = :screenName', array(':screenName' => $screenName))) {
      $screenRand = rand();
      $userLink = '' . $screenName . '' . $screenRand . '';
    } else {
      $userLink = $screenName;
    }
    if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email_mobile)) {
      if (!preg_match("^[0-9]{10}^", $email_mobile)) {
        $error = 'Email id or Mobile number is not correct. Please try again.';
      } else {
        $mob = strlen((string)$email_mobile);
        if ($mob > 10 || $mob < 10) {
          $error = 'Mobile number is not valid';
        } else if (strlen($password) < 5 || strlen($password) >= 60) {
          $error = 'Password is not correct';
        } else {
          if (DB::query('SELECT mobile FROM users WHERE mobile=:mobile', array(':mobile' => $email_mobile))) {
            $error = 'Mobile number is already in use.';
          } else {
            $user_id = $loadFromUser->create('users', array('first_name' => $first_name, 'last_name' => $last_name, 'mobile' => $email_mobile, 'password' => password_hash($password, PASSWORD_BCRYPT), 'screenName' => $screenName, 'userLink' => $userLink, 'birthday' => $birth, 'gender' => $upGen));

            $loadFromUser->create('profile', array('userId' => $user_id, 'birthday' => $birth, 'firstName' => $first_name, 'lastName' => $last_name, 'profilePic' => 'assets/image/defaultProfile.png', 'coverPic' => 'assets/image/defaultCover.png', 'gender' => $upGen));

            $tStrong = true;
            $token = bin2hex(openssl_random_pseudo_bytes(64, $tStrong));
            $loadFromUser->create('token', array('token' => sha1($token), 'user_id' => $user_id));
            setcookie('FBID', $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, true);
            header('Location: index.php');
          }
        }
      }
    } else {
      if (!filter_var($email_mobile)) {
        $error = "Invalid Email Format";
      } else if (strlen($first_name) > 20 || strlen($first_name) < 2) {
        $error = "Name must be between 2-20 character";
      } else if (strlen($password) < 5 || strlen($password) >= 60) {
        $error = "The password is either too short or too long";
      } else {
        if ((filter_var($email_mobile, FILTER_VALIDATE_EMAIL)) && $loadFromUser->checkEmail($email_mobile) === true) {
          $error = "Email is already in use";
        } else {
          $user_id = $loadFromUser->create('users', array('first_name' => $first_name, 'last_name' => $last_name, 'email' => $email_mobile, 'password' => password_hash($password, PASSWORD_BCRYPT), 'screenName' => $screenName, 'userLink' => $userLink, 'birthday' => $birth, 'gender' => $upGen));

          $loadFromUser->create('profile', array('userId' => $user_id, 'birthday' => $birth, 'firstName' => $first_name, 'lastName' => $last_name, 'profilePic' => 'assets/image/defaultProfile.png', 'coverPic' => 'assets/image/defaultCover.png', 'gender' => $upGen));

          $tStrong = true;
          $token = bin2hex(openssl_random_pseudo_bytes(64, $tStrong));
          $loadFromUser->create('token', array('token' => sha1($token), 'user_id' => $user_id));
          setcookie('FBID', $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, true);
          header('Location: index.php');
        }
      }
    }
  }
}
// else {
//   echo 'User not found';
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Social | Sign Up</title>
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
        <div class="col-12 col-md-6">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent text-center p-3">
              <!-- <img src="assets/image/social-logo.png" class="login-logo img-fluid mt-2 mb-2" alt=""> -->
              <h1 class="logo-text">Social</h1>
            </div>
            <div class="card-body">
              <?php
              if (!empty($error)) {
              ?>
              <div class="error-wrapper">
                <?php echo $error; ?>
              </div>
              <?php
              }
              ?>
              <form action="signUp.php" method="post" name="user-sign-up">
                <div class="row g-2">
                  <div class="col-md-6 mb-2">
                    <label for="first-name" class="form-label visually-hidden">First Name</label>
                    <input type="text" class="form-control" id="first-name" name="first-name" placeholder="First Name">
                  </div>
                  <div class="col-md-6 mb-2">
                    <label for="last-name" class="form-label visually-hidden">Last Name</label>
                    <input type="text" class="form-control" id="last-name" name="last-name" placeholder="Last Name">
                  </div>
                  <div class="col-md-12 mb-2">
                    <label for="email-mobile" class="form-label visually-hidden">Mobile number or email address</label>
                    <input type="text" class="form-control" id="email-mobile" name="email-mobile"
                      placeholder="Mobile number or email address">
                  </div>
                  <div class="col-md-12 mb-2">
                    <label for="up-password" class="form-label visually-hidden">Password</label>
                    <input type="password" class="form-control" name="up-password" id="up-password"
                      placeholder="Password">
                  </div>
                  <div class="row g-1 mb-2 p-1">
                    <label class="form-label mt-0 mb-2">
                      Birthday
                    </label>
                    <div class="col-4">
                      <select class="form-select" name="birth-day" id="days">
                      </select>
                    </div>
                    <div class="col-4">
                      <select class="form-select" name="birth-month" id="months">
                      </select>
                    </div>
                    <div class="col-4">
                      <select class="form-select" name="birth-year" id="years">
                      </select>
                    </div>
                  </div>
                  <div class="row g-0 mb-2 p-1">
                    <label class="form-label mt-0 mb-2">
                      Gender
                    </label>
                    <div class="col-md-2">
                      <div class="form-check d-flex align-items-center">
                        <input class="form-check-input" type="radio" name="gen" id="male" value="male">
                        <label class="form-check-label ms-2" for="male">
                          Male
                        </label>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-check d-flex align-items-center">
                        <input class="form-check-input" type="radio" name="gen" id="fem" value="female">
                        <label class="form-check-label ms-2" for="fem">
                          Female
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 text-center">
                    <div class="mb-3">
                      <input class="btn signUp-btn" type="submit" value="Sign up">
                    </div>
                    <div class="redirect-link">
                      <a href="signIn.php">
                        Already have an account? Login here!
                      </a>
                    </div>
                  </div>
                </div>
              </form>
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
  <script>
  for (i = new Date().getFullYear(); i > 1900; i--) {
    //    2019,2018, 2017,2016.....1901
    $("#years").append($('<option/>').val(i).html(i));

  }
  for (i = 1; i < 13; i++) {
    $('#months').append($('<option/>').val(i).html(i));
  }
  updateNumberOfDays();

  function updateNumberOfDays() {
    $('#days').html('');
    month = $('#months').val();
    year = $('#years').val();
    days = daysInMonth(month, year);
    for (i = 1; i < days + 1; i++) {
      $('#days').append($('<option/>').val(i).html(i));
    }

  }
  $('#years, #months').on('change', function() {
    updateNumberOfDays();
  })

  function daysInMonth(month, year) {
    return new Date(year, month, 0).getDate();

  }
  </script>
</body>

</html>