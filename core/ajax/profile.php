<?php

include '../load.php';
include '../../connect/login.php';

$userId = login::isLoggedIn();

if (isset($_POST['imgName'])) {

  $imgName = $loadFromUser->checkInput($_POST['imgName']);
  $userId = $loadFromUser->checkInput($_POST['userId']);

  $loadFromUser->update('profile', $userId, array('coverPic' => $imgName));
  //echo $imgName;
} else {
}

if (0 < $_FILES['file']['error']) {
  echo 'Error: ' . $_FILES['file']['error'] . '<br>';
} else {
  $path_directory = $_SERVER['DOCUMENT_ROOT'] . "/php-practice/social/user/" . $userId . "/coverPhoto/";
  if (!file_exists($path_directory) && !is_dir($path_directory)) {
    mkdir($path_directory, 0777, true);
  }
  move_uploaded_file($_FILES['file']['tmp_name'], $path_directory . $_FILES['file']['name']);
}

echo 'user/' . $userId . '/coverPhoto/' . $_FILES['file']['name'];