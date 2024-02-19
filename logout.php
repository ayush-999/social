<?php
include 'connect/login.php';
include 'core/load.php';


if (login::isLoggedIn()) {
  $userId = login::isLoggedIn();
} else {
  header('Location: signIn.php');
}

$loadFromUser->delete('token', array('user_id' => $userId));

if (isset($_COOKIE['FBID'])) {
  unset($_COOKIE['FBID']);
  header('Refresh:0');
}