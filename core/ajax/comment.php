<?php
include '../load.php';
include '../../connect/login.php';

$user_id = login::isLoggedIn();

if (isset($_POST['comment'])) {
  $comment_text = $loadFromUser->checkInput($_POST['comment']);
  $userid = ($_POST['userid']);
  $postid = ($_POST['postid']);
  $profileid = ($_POST['profileid']);

  $commentid = $loadFromUser->create('comments', array('commentBy' => $userid, 'comment_parent_id' => $postid, 'comment' => $comment_text, 'commentOn' => $postid, 'commentAt' => date('Y-m-d H:i:s')));

  $commentDetails = $loadFromPost->lastCommentFetch($commentid);


  if (!empty($commentDetails)) {
    foreach ($commentDetails as $comment) {
      $com_react_max_show = $loadFromPost->com_react_max_show($comment->commentOn, $comment->commentID);
      $com_main_react_count = $loadFromPost->com_main_react_count($comment->commentOn, $comment->commentID);
      $commentReactCheck = $loadFromPost->commentReactCheck($user_id, $comment->commentOn, $comment->commentID);
?>
<li class="new-comment">
  <div class="com-details">
    <div class="com-pro-pic">
      <a href="#">
        <span class="comment-top-pic">
          <img src="<?php echo $comment->profilePic; ?>" alt="" title="">
        </span>
      </a>
    </div>
    <div class="com-pro-wrap">
      <div class="com-text-react-wrap">
        <div class="com-text-option-wrap">
          <div class="com-pro-text position-relative">
            <div class="com-react-placeholder-wrap">
              <div>
                <span class="nf-pro-name">
                  <a href="#" class="nf-pro-name">
                    <?php echo '' . $comment->firstName . ' ' . $comment->lastName . '' ?>
                  </a>
                </span>
                <span class="com-text" data-postid="<?php echo $comment->commentOn; ?> "
                  data-userid="<?php echo $user_id; ?>" data-commentid="<?php echo $comment->commentID;  ?>"
                  data-profilepic="<?php echo $userData->profilePic; ?>">
                  <?php echo $comment->comment; ?>
                </span>
              </div>
              <div class="com-nf-3-wrap">
                <?php
                      if ($com_main_react_count->maxreact == '0') {
                      } else {
                      ?>
                <div class="com-nf-3">
                  <div class="nf-3-react-icon">
                    <div class="react-inst-img">
                      <?php
                              foreach ($com_react_max_show as $react_max) {
                                echo '<img src="assets/image/react/' . $react_max->reactType . '.png" class="' . $react_max->reactType . '-max-show" alt="" style="height:12px; width:12px; margin-right:2px; cursor:pointer;">';
                              }
                              ?>
                    </div>
                  </div>
                  <div class="nf-3-react-username">
                    <?php
                            if ($com_main_react_count->maxreact == '0') {
                            } else {
                              echo $com_main_react_count->maxreact;
                            }
                            ?>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <?php if ($user_id == $comment->commentBy) { ?>
          <div class="com-dot-option-wrap">
            <div class="com-dot" data-postid="<?php echo $comment->commentOn; ?>" data-userid="<?php echo $user_id; ?>"
              data-commentid="<?php echo $comment->commentID; ?>">
              <i class="fa-solid fa-ellipsis-vertical"></i>
            </div>
            <div class="com-option-details-container">

            </div>
          </div>
          <?php
                } else {
                } ?>
        </div>
        <div class="com-react">
          <div class="com-like-react me-2" data-postid="<?php echo $comment->commentOn; ?>"
            data-userid="<?php echo $user_id; ?>" data-commentid="<?php echo $comment->commentID; ?>">
            <div class="com-react-bundle-wrap" data-commentid="<?php echo $comment->commentID; ?>"></div>
            <?php
                  if (empty($commentReactCheck)) {
                    echo '<div class="com-like-action-text"><span>Like</span></div>';
                  } else {
                    echo '<div class="com-like-action-text"><span class="' . $commentReactCheck->reactType . '-color">' . $commentReactCheck->reactType . '</span></div>';
                  }
                  ?>
          </div>
          <div class="com-reply-action me-2" data-postid="<?php echo $comment->commentOn; ?>"
            data-userid="<?php echo $user_id; ?>" data-commentid="<?php echo $comment->commentID; ?>"
            data-profileid="<?php echo $userData->profilePic; ?>">
            <span>Reply</span>
          </div>
          <div class="com-time me-2">
            <span><?php echo $loadFromPost->timeAgo($comment->commentAt); ?></span>
          </div>
        </div>
      </div>
      <div class="reply-wrap">
        <!--  -->
      </div>
    </div>
  </div>
</li>
<?php
    }
  }
}

?>