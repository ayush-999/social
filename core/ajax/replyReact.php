<?php

include '../load.php';
include '../../connect/login.php';

$userid = login::isLoggedIn();

if (isset($_POST['commentid'])) {
  $commentid = $_POST['commentid'];
  $reactType = $_POST['reactType'];
  $postid = $_POST['postid'];
  $userid = $_POST['userid'];
  $commentparentid = $_POST['commentparentid'];
  $profileid = $_POST['profileid'];

  $loadFromUser->delete('react', array('reactBy' => $userid, 'reactOn' => $postid, 'reactCommentOn' => $commentid, 'reactReplyOn' => $commentparentid));

  $loadFromUser->create('react', array('reactBy' => $userid, 'reactOn' => $postid, 'reactCommentOn' => $commentid, 'reactReplyOn' => $commentparentid, 'reactType' => $reactType, 'reactTimeOn' => date('Y-m-d H:i:s')));

  $reply_react_count = $loadFromPost->reply_main_react_count($postid, $commentid, $commentparentid);
  $reply_react_max_show = $loadFromPost->reply_react_max_show($postid, $commentid, $commentparentid);

  if (empty($reply_react_count) || empty($reply_react_max_show)) {
  } else {

?>
<!-- <div class="com-nf-3<?php echo $localhost; ?>"> -->
<div class="com-nf-3">
  <div class="nf-3-react-icon">
    <!-- <div class="react-inst-img<?php echo $localhost; ?>"> -->
    <div class="react-inst-img">
      <?php
          foreach ($reply_react_max_show as $react_max) {
            echo '<img class="com-' . $react_max->reactType . '-max-show"
            id="' . $react_max->reactType . '"
            src="assets/image/react/' . $react_max->reactType . '.png" alt=""';
          }
          ?>
    </div>
    <div class="nf-3-react-username">
      <?php if ($reply_react_count->maxreact == '0') {
          } else {
            echo $reply_react_count->maxreact;
          } ?>
    </div>
  </div>
</div>


<?php
  }
}

if (isset($_POST['delcommentid'])) {
  $delcommentid = $_POST['delcommentid'];
  $deleteReactType = $_POST['deleteReactType'];
  $postid = $_POST['postid'];
  $userid = $_POST['userid'];
  $commentparentid = $_POST['commentparentid'];
  $profileid = $_POST['profileid'];

  $loadFromUser->delete('react', array('reactBy' => $userid, 'reactOn' => $postid, 'reactCommentOn' => $delcommentid, 'reactReplyOn' => $commentparentid));

  $reply_react_count = $loadFromPost->reply_main_react_count($postid, $delcommentid, $commentparentid);
  $reply_react_max_show = $loadFromPost->reply_react_max_show($postid, $delcommentid, $commentparentid);

  if (empty($reply_react_count) || empty($reply_react_max_show)) {
  } else {

  ?>
<!-- <div class="com-nf-3<?php echo $localhost; ?>"> -->
<div class="com-nf-3">
  <div class="nf-3-react-icon">
    <!-- <div class="react-inst-img<?php echo $localhost; ?>"> -->
    <div class="react-inst-img">
      <?php
          foreach ($reply_react_max_show as $react_max) {
            echo '<img class="com-' . $react_max->reactType . '-max-show" src="assets/image/react/' . $react_max->reactType . '.png" alt=""';
          }
          ?>
    </div>
    <div class="nf-3-react-username">
      <?php if ($reply_react_count->maxreact == '0') {
          } else {
            echo $reply_react_count->maxreact;
          } ?>
    </div>
  </div>
</div>


<?php

  }
}


?>