<?php

include '../load.php';
include '../../connect/login.php';

$userId = login::isLoggedIn();

if (isset($_POST['onlyStatusText'])) {
  $statusText = $_POST['onlyStatusText'];
  $postId = $loadFromUser->create('post', array('userId' => $userId, 'post' => $statusText, 'postBy' => $userId, 'postedOn' => date('Y-m-d H:i:s')));
}


if (isset($_POST['stIm'])) {
  $stIm = $_POST['stIm'];
  $statusText = $_POST['statusText'];
  $postId = $loadFromUser->create('post', array('userId' => $userId, 'post' => $statusText, 'postBy' => $userId, 'postImage' => $stIm, 'postedOn' => date('Y-m-d H:i:s')));
}