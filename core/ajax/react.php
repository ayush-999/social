<?php

include '../load.php';
include '../../connect/login.php';

$userId = login::isLoggedIn();

if (isset($_POST['reactType'])) {
  $reactType = $_POST['reactType'];
  $postId = $_POST['postid'];
  $userId = $_POST['userid'];
  $profileId = $_POST['profileid'];

  $loadFromUser->delete('react', array('reactBy' => $userId, 'reactOn' => $postId, 'reactCommentOn' => '0', 'reactReplyOn' => '0'));

  $loadFromUser->create('react', array('reactBy' => $userId, 'reactOn' => $postId, 'reactType' => $reactType, 'reactTimeOn' => date('Y-m-d H:i:s')));

  if ($profileId != $userId) {
    $loadFromUser->create('notification', array('notificationFrom' => $userId, 'notificationFor' => $profileId, 'postid' => $postId, 'type' => 'postReact', 'status' => '0', 'notificationCount' => '0', 'notificationOn' => date('Y-m-d H:i:s')));
  }

  $react_max_show = $loadFromPost->react_max_show($postId);
  $main_react_count = $loadFromPost->main_react_count($postId);

?>
<div class="nf-3-react-icon">
  <div class="react-inst-img">
    <?php
      foreach ($react_max_show as $react_max) {
        echo '<img class="' . $react_max->reactType . '-max-show" src="assets/image/react/' . $react_max->reactType . '.png" alt="">';
      }
      ?>
  </div>
</div>
<div class="nf-3-react-username">
  <?php
    if ($main_react_count->maxreact == '0') {
    } else {
      echo $main_react_count->maxreact;
    }
    ?>
</div>

<?php
}

if (isset($_POST['deleteReactType'])) {
  $deleteReactType = $_POST['deleteReactType'];
  $postId = $_POST['postid'];
  $userId = $_POST['userid'];
  $profileId = $_POST['profileid'];

  $loadFromUser->delete('react', array('reactBy' => $userId, 'reactOn' => $postId, 'reactCommentOn' => '0', 'reactReplyOn' => '0'));
  $react_max_show = $loadFromPost->react_max_show($postId);
  $main_react_count = $loadFromPost->main_react_count($postId);

?>
<div class="nf-3-react-icon">
  <div class="react-inst-img">
    <?php
      foreach ($react_max_show as $react_max) {
        echo '<img class="' . $react_max->reactType . '-max-show" src="assets/image/react/' . $react_max->reactType . '.png" alt="" >';
      }

      ?>
  </div>
</div>
<div class="nf-3-react-username">
  <?php
    if ($main_react_count->maxreact == '0') {
    } else {
      echo $main_react_count->maxreact;
    }
    ?>
</div>
<?php
}