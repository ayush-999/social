<?php
include '../load.php';
include '../../connect/login.php';

$user_id = login::isLoggedIn();

if (isset($_POST['replyComment'])) {
  $comment_text = $loadFromUser->checkInput($_POST['replyComment']);
  $userid = $_POST['userid'];
  $postid = $_POST['postid'];
  $commentid = $_POST['commentid'];
  $profileid = $_POST['profileid'];

  $replyCommentId = $loadFromUser->create('comments', array('commentBy' => $userid, 'comment_parent_id' => $postid, 'commentReplyID' => $commentid, 'comment' => $comment_text, 'commentOn' => $postid, 'commentAt' => date('Y-m-d H:i:s')));

  $replyDetails = $loadFromPost->lastReplyFetch($replyCommentId);

  foreach ($replyDetails as $reply) {
    $reply_react_count = $loadFromPost->reply_main_react_count($reply->commentOn, $reply->commentID, $reply->commentReplyID);
    $reply_react_max_show = $loadFromPost->reply_react_max_show($reply->commentOn, $reply->commentID, $reply->commentReplyID);
    $replyReactCheck = $loadFromPost->replyReactCheck($user_id, $reply->commentOn, $reply->commentID, $reply->commentReplyID);
?>

<li class="new-reply">
  <div class="com-details">
    <div class="com-pro-pic">
      <a href="">
        <span class="comment-top-pic"><img src="<?php echo $reply->profilePic ?>" alt=""></span>
      </a>
    </div>
    <div class="com-pro-wrap">
      <div class="com-text-react-wrap">
        <div class="reply-text-option-wrap">
          <div class="com-pro-text position-relative">
            <div class="com-react-placeholder-wrap">
              <div>
                <span class="nf-pro-name">
                  <a href="" class="nf-pro-name">
                    <?php echo '' . $reply->firstName . ' ' . $reply->lastName . '' ?>
                  </a>
                </span>
                <span class="com-text" data-commentid="<?php echo $comment->commentID; ?>"
                  data-postid="<?php echo $comment->commentOn; ?>"
                  data-profilepic="<?php echo $userData->profilepic; ?>" data-replyid="<?php echo $reply->commentID; ?>"
                  data-userid="<?php echo $user_id; ?>">
                  <?php echo $reply->comment; ?>
                </span>
              </div>
              <div class="com-nf-3-wrap">
                <?php
                    if (empty($reply_react_count) || empty($reply_react_max_show)) {
                    } else {
                    ?>
                <div class="com-nf-3">
                  <div class="nf-3-react-icon">
                    <div class="react-inst-img">
                      <?php
                            foreach ($reply_react_max_show as $react_max) {
                              echo '<img src="assets/image/react/' . $react_max->reactType . '.png" class="com-' . $react_max->reactType . '-max-show" alt="">';
                            }
                            ?>
                    </div>
                  </div>
                  <div class="nf-3-react-username">
                    <?php
                          if ($reply_react_count->maxreact == '0') {
                          } else {
                            echo $reply_react_count->maxreact;
                          }
                          ?>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <?php if ($user_id == $reply->commentBy) { ?>
          <div class="reply-dot-option-wrap">
            <div class="reply-dot" data-postid="<?php echo $comment->commentOn ?>" data-userid="<?php echo $user_id; ?>"
              data-commentid="<?php echo $comment->commentID; ?>" data-replyid="<?php echo $reply->commentID; ?>">
              <i class="fa-solid fa-ellipsis-vertical"></i>
            </div>
            <div class="reply-option-details-container"></div>
          </div>
          <?php } else {
              } ?>
        </div>
        <div class="com-react">
          <div class="com-like-react-reply me-2" data-postid="<?php echo $reply->commentOn; ?>"
            data-userid="<?php echo $user_id; ?>" data-commentid="<?php echo $reply->commentID; ?>"
            data-commentparentid="<?php echo $reply->commentReplyID; ?>">
            <div class="com-react-bundle-wrap reply" data-commentid="<?php echo $reply->commentID; ?>"
              data-commentparentid="<?php echo $reply->commentReplyID; ?>">
            </div>
            <?php if (empty($replyReactCheck)) {
                  echo '<div class="reply-like-action-text"><span>Like</span></div>';
                } else {
                  echo '<div class="reply-like-action-text"><span class="' . $replyReactCheck->reactType . '-color">' . $replyReactCheck->reactType . '</span></div>';
                } ?>

          </div>
          <div class="com-reply-action-child me-2" data-postid="<?php echo $reply->commentOn; ?>"
            data-userid="<?php echo $user_id; ?>" data-commentid="<?php echo $reply->commentReplyID; ?>"
            data-profilepic="<?php echo $userData->profilePic; ?>">
            Reply
          </div>
          <div class="com-time">
            <?php echo  $loadFromPost->timeAgo($reply->commentAt);  ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</li>

<?php
  }
}



?>