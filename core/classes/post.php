<?php

class Post extends User
{
  protected $pdo;

  function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function posts($user_id, $profileId, $num)
  {
    $userData = $this->userData($user_id);
    $stmt = $this->pdo->prepare("SELECT * FROM users LEFT JOIN profile ON users.user_id = profile.userId LEFT JOIN post ON post.userId = users.user_id WHERE post.userId = :user_id ORDER BY post.postedOn DESC LIMIT :num");

    $stmt->bindParam(":user_id", $profileId, PDO::PARAM_INT);
    $stmt->bindParam(":num", $num, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($posts as $post) {
      $main_react = $this->main_react($user_id, $post->post_id);
      $react_max_show = $this->react_max_show($post->post_id);
      $main_react_count = $this->main_react_count($post->post_id);
      $commentDetails = $this->commentFetch($post->post_id);
      $totalCommentCount = $this->totalCommentCount($post->post_id);

      $totalShareCount = $this->totalShareCount($post->post_id);
      if (empty($post->shareId)) {
      } else {
        $shareDetails = $this->shareFetch($post->shareId, $post->postBy);
      }
?>
<div class="profile-timeLine">
  <div class="card news-feed-component border-0 mb-2">
    <div class="card-header bg-transparent">
      <div class="nf-1">
        <div class="nf-1-left">
          <div class="nf-1-profile-pic-wrapper">
            <a href="#">
              <img src="<?php echo BASE_URL . $post->profilePic; ?>" class=" nf-1-profile-pic img-fluid"
                alt="<?php echo '' . $post->firstName . ' ' . $post->lastName . ''; ?>"
                title="<?php echo '' . $post->firstName . ' ' . $post->lastName . ''; ?>">
            </a>
          </div>
          <div class="nf-1-profile-name-time-wrapper">
            <div class="nf-1-profile-name">
              <h6 class="nf-1-profile-name-text">
                <a href="#">
                  <?php echo '' . $post->firstName . ' ' . $post->lastName . ''; ?>
                </a>
              </h6>
            </div>
            <div class="nf-1-profile-time-privacy grey-color">
              <div class="nf-1-profile-time">
                <span>
                  <?php echo $this->timeAgo($post->postedOn); ?>
                </span>
              </div>
              <div class="nf-1-profile-privacy">
                <i class="bi bi-globe-central-south-asia"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="nf-1-right">
          <?php
                if (empty($post->shareId)) {
                  if ($user_id == $profileId) {
                ?>
          <div class="post-option">
            <div class="post-option-icon" data-postid="<?php echo $post->post_id; ?>"
              data-userid="<?php echo $user_id ?>">
              <i class="bi bi-three-dots grey-color"></i>
            </div>
            <div class="post-option-details-container"></div>
          </div>
          <?php
                  } else {
                  }
                } else {
                  if ($user_id == $profileId) {
                  ?>
          <div class="shared-post-option">
            <div class="shared-post-option-icon" data-postid="<?php echo $post->post_id; ?>"
              data-userid="<?php echo $user_id ?>">
              <i class="bi bi-three-dots grey-color"></i>
            </div>
            <div class="shared-post-option-details-container"></div>
          </div>
          <?php
                  } else {
                  }
                }
                ?>
        </div>
      </div>
      <div class="nf-2 news-feed-text">
        <div class="nf-2-text" data-postid="<?php echo $post->post_id; ?>" data-userid=" <?php echo $user_id; ?>"
          data-profilepic="<?php echo $post->profilePic; ?>">
          <p class="post-text">
            <?php
                  if (empty($post->shareId)) {
                    echo $post->post;
                  } else {
                    if (empty($shareDetails)) {
                      echo 'Share has not found.';
                    } else {
                      echo '<span class="nf-2-text-span" data-postid = "' . $post->post_id . '" data-userid="' . $user_id . '" data-profilepic="' . $post->profilePic . '">' . $post->shareText . '</span>';
                    }
                    foreach ($shareDetails as $share) { ?>
          <div class="share-container" data-userlink="<?php echo $share->userLink; ?>">
            <div class="nf-1">
              <div class="nf-1-left">
                <div class="nf-1-profile-pic-wrapper">
                  <a href="#">
                    <img src="<?php echo BASE_URL . $share->profilePic; ?>" class="nf-1-profile-pic img-fluid"
                      alt="<?php echo '' . $share->firstName . ' ' . $share->lastName . ''; ?>"
                      title="<?php echo '' . $share->firstName . ' ' . $share->lastName . ''; ?>">
                  </a>
                </div>
                <div class="nf-1-profile-name-time-wrapper">
                  <div class="nf-1-profile-name">
                    <h6 class="nf-1-profile-name-text">
                      <a href="#">
                        <?php echo '' . $share->firstName . ' ' . $share->lastName . ''; ?>
                      </a>
                    </h6>
                  </div>
                  <div class="nf-1-profile-time-privacy grey-color">
                    <div class="nf-1-profile-time">
                      <span>
                        <?php echo $this->timeAgo($share->postedOn); ?>
                      </span>
                    </div>
                    <div class="nf-1-profile-privacy">
                      <i class="bi bi-globe-central-south-asia"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="nf-1-right">
                <!--  -->
              </div>
            </div>
            <div class="nf-2 news-feed-text">
              <div class="nf-2-text" data-postid="<?php echo $share->post_id; ?>" data-userid="<?php echo $user_id; ?>"
                data-profilepic="<?php echo $share->profilePic; ?>">
                <p class="post-text">
                  <?php echo $share->post; ?>
                </p>
              </div>
              <div class="nf-2-img" data-postid="<?php echo $share->post_id; ?>" data-userid="<?php echo $user_id; ?>">
                <?php
                      $shareImgJson = json_decode($share->postImage);

                      $shareCount = 0;
                      if (is_array($shareImgJson)) {
                        for ($i = 0; $i < count($shareImgJson); $i++) {
                          echo '<div class="post-img-box" data-postimgid="' . $share->id . '" ><img src="' . BASE_URL . $shareImgJson['' . $shareCount++ . '']->imageName . '" class="postImage img-fluid"></div>';
                        }
                      }
                      ?>
              </div>
            </div>
          </div>
          <?php
                    }
                  }
            ?>
          </p>
        </div>
        <div class="nf-2-img" data-postid="<?php echo $post->post_id; ?>" data-userid="<?php echo $user_id; ?>"
          data-profilepic="<?php echo $post->profilePic; ?>">
          <?php
                $imgJson = json_decode($post->postImage);

                $count = 0;
                if (is_array($imgJson)) {
                  for ($i = 0; $i < count($imgJson); $i++) {
                    echo '<div class="post-img-box" data-postimgid="' . $post->id . '" ><img src="' . BASE_URL . $imgJson['' . $count++ . '']->imageName . '" class="postImage img-fluid" data-userid="' . $user_id . '" data-postid="' . $post->post_id . '" data-profileid="' . $profileId . '"></div>';
                  }
                }
                ?>
        </div>
      </div>
      <div class="nf-3">
        <div class="react-comment-count-wrap">
          <div class="react-count-wrap">
            <div class="nf-3-react-icon">
              <div class="react-inst-img">
                <?php
                      foreach ($react_max_show as $react_max) {
                        echo '<img class = "' . $react_max->reactType . '-max-show" src="assets/image/react/' . $react_max->reactType . '.png" alt="">';
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
          </div>
          <div class="comment-share-count-wrap grey-color">
            <div class="comment-count-wrap">
              <?php if (empty($totalCommentCount->totalComment)) {
                    } else {
                      echo '' . $totalCommentCount->totalComment . ' Comments';
                    } ?>
            </div>
            <div class="share-count-wrap ms-2">
              <?php if (empty($totalShareCount->totalShare)) {
                    } else {
                      echo '' . $totalShareCount->totalShare . ' Share';
                    } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="nf-4">
        <div class="action-wrap">
          <div class="like-action-wrap position-relative" data-postid="<?php echo $post->post_id; ?>"
            data-userid="<?php echo $user_id; ?>">
            <div class="react-bundle-wrap"></div>
            <div class="like-action ra">
              <?php if (empty($main_react)) { ?>
              <div class="like-action-icon">
                <img src="assets/image/likeAction.JPG" class="img-fluid" alt="">
              </div>
              <div class="like-action-text">
                <span>Like</span>
              </div>
              <?php } else { ?>
              <div class="like-action-icon">
                <img src="assets/image/react/<?php echo $main_react->reactType; ?>.png" class="reactIconSize img-fluid"
                  alt="">
              </div>
              <div class="like-action-text">
                <span class="<?php echo $main_react->reactType; ?>-color">
                  <?php echo $main_react->reactType; ?>
                </span>
              </div>
              <?php
                    } ?>
            </div>
          </div>
          <div class="comment-action-wrap position-relative">
            <div class="comment-action ra">
              <div class="comment-action-icon">
                <img src="assets/image/commentAction.JPG" class="img-fluid" alt="">
              </div>
              <div class="comment-action-text">
                <div class="comment-wrap"></div>
                <div class="comment-text">
                  <span>Comment</span>
                </div>
              </div>
            </div>
          </div>
          <!--  -->
          <div class="share-action-wrap position-relative">
            <div class="share-action ra" data-postid="<?php echo $post->post_id; ?>"
              data-userid="<?php echo $user_id; ?>" data-profileid="<?php echo $profileId; ?>"
              data-profilepic="<?php echo $post->profilePic; ?>">
              <div class="share-action-icon">
                <img src="assets/image/shareAction.JPG" class="img-fluid" alt="">
              </div>
              <div class="share-action-text">
                <span>Share</span>
              </div>
            </div>
          </div>
          <!--  -->
        </div>
      </div>
    </div>
    <div class="card-footer news-feed-photo bg-transparent">
      <div class="nf-5">
        <div class="comment-list">
          <!-- Comment Start -->
          <ul class="add-comment mb-0 p-0">
            <?php
                  if (!empty($commentDetails)) {
                    foreach ($commentDetails as $comment) {
                      $com_react_max_show = $this->com_react_max_show($comment->commentOn, $comment->commentID);
                      $com_main_react_count = $this->com_main_react_count($comment->commentOn, $comment->commentID);
                      $commentReactCheck = $this->commentReactCheck($user_id, $comment->commentOn, $comment->commentID);
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
                              data-userid="<?php echo $user_id; ?>" data-commentid="<?php echo $comment->commentID; ?>"
                              data-profilepic="<?php echo $userData->profilePic; ?>">
                              <?php echo $comment->comment; ?>
                            </span>
                          </div>
                          <div class=" com-nf-3-wrap">
                            <?php
                                      if ($com_main_react_count->maxreact == '0') {
                                      } else {
                                      ?>
                            <div class="com-nf-3">
                              <div class="nf-3-react-icon">
                                <div class="react-inst-img">
                                  <?php
                                              foreach ($com_react_max_show as $react_max) {
                                                echo '<img src="assets/image/react/' . $react_max->reactType . '.png" class="com-' . $react_max->reactType . '-max-show" alt="">';
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
                        <div class="com-dot" data-postid="<?php echo $comment->commentOn; ?>"
                          data-userid="<?php echo $user_id; ?>" data-commentid="<?php echo $comment->commentID; ?>">
                          <i class="fa-solid fa-ellipsis-vertical"></i>
                        </div>
                        <div class="com-option-details-container"></div>
                      </div>
                      <?php } else {
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
                        data-profilepic="<?php echo $userData->profilePic; ?>">
                        <span>Reply</span>
                      </div>
                      <div class="com-time me-2">
                        <span>
                          <?php echo $this->timeAgo($comment->commentAt); ?>
                        </span>
                      </div>
                    </div>
                  </div>
                  <!-- Comment Reply Start -->
                  <div class="reply-wrap">
                    <div class="reply-text-wrap">
                      <ul class="old-reply p-0">
                        <?php
                                  $replyDetails = $this->replyFetch($comment->commentOn, $comment->commentID);
                                  foreach ($replyDetails as $reply) {
                                    $reply_react_count = $this->reply_main_react_count($reply->commentOn, $reply->commentID, $reply->commentReplyID);
                                    $reply_react_max_show = $this->reply_react_max_show($reply->commentOn, $reply->commentID, $reply->commentReplyID);
                                    $replyReactCheck = $this->replyReactCheck($user_id, $reply->commentOn, $reply->commentID, $reply->commentReplyID);
                                  ?>
                        <li class="new-reply">
                          <div class="com-details">
                            <div class="com-pro-pic">
                              <a href="">
                                <span class="comment-top-pic">
                                  <img src="<?php echo $reply->profilePic ?>" alt="" title="">
                                </span>
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
                                          data-profilepic="<?php echo $userData->profilePic; ?>"
                                          data-replyid="<?php echo $reply->commentID; ?>"
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
                                    <div class="reply-dot" data-postid="<?php echo $comment->commentOn ?>"
                                      data-userid="<?php echo $user_id; ?>"
                                      data-commentid="<?php echo $comment->commentID; ?>"
                                      data-replyid="<?php echo $reply->commentID; ?>">
                                      <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </div>
                                    <div class="reply-option-details-container"></div>
                                  </div>
                                  <?php } else {
                                              } ?>
                                </div>
                                <div class="com-react">
                                  <div class="com-like-react-reply me-2" data-postid="<?php echo $reply->commentOn; ?>"
                                    data-userid="<?php echo $user_id; ?>"
                                    data-commentid="<?php echo $reply->commentID; ?>"
                                    data-commentparentid="<?php echo $reply->commentReplyID; ?>">
                                    <div class="com-react-bundle-wrap reply"
                                      data-commentid="<?php echo $reply->commentID; ?>"
                                      data-commentparentid="<?php echo $reply->commentReplyID; ?>">
                                    </div>
                                    <?php if (empty($replyReactCheck)) {
                                                  echo '<div class="reply-like-action-text"><span>Like</span></div>';
                                                } else {
                                                  echo '<div class="reply-like-action-text"><span class="' . $replyReactCheck->reactType . '-color">' . $replyReactCheck->reactType . '</span></div>';
                                                } ?>
                                  </div>
                                  <div class="com-reply-action-child me-2"
                                    data-postid="<?php echo $reply->commentOn; ?>" data-userid="<?php echo $user_id; ?>"
                                    data-commentid="<?php echo $reply->commentReplyID; ?>"
                                    data-profilepic="<?php echo $userData->profilePic; ?>">
                                    Reply
                                  </div>
                                  <div class="com-time me-2">
                                    <?php echo  $this->timeAgoForCom($reply->commentAt);  ?>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </li>
                        <?php } ?>
                      </ul>
                    </div>
                    <div class="replyInput"></div>
                  </div>
                  <!-- Comment Reply End -->
                </div>
              </div>
            </li>
            <?php }
                  } ?>
          </ul>
          <!-- Comment End -->
        </div>
        <div class="comment-write">
          <div class="com-pro-pic">
            <a href="#">
              <div class="comment-top-pic">
                <img src="<?php echo $userData->profilePic; ?>" alt="" title="">
              </div>
            </a>
          </div>
          <div class="com-input">
            <div class="comment-input">
              <input type="text" name="" id="" class="comment-input-style comment-submit"
                placeholder="Write a comment..." data-postid="<?php echo $post->post_id; ?>"
                data-userid="<?php echo $user_id; ?>">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
    }
  }
  public function postUpd($user_id, $post_id, $editText)
  {
    $stmt = $this->pdo->prepare('UPDATE post SET post = :editText WHERE post_id =:post_id AND userId = :user_id');
    $stmt->bindParam(":editText", $editText, PDO::PARAM_STR);
    $stmt->bindParam(":post_id", $post_id, PDO::PARAM_INT);
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
  }
  public function main_react($userid, $postid)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM `react` WHERE `reactBy` = :user_id AND `reactOn` = :postid AND `reactCommentOn`= '0' AND `reactReplyOn` = '0' ");
    $stmt->bindParam(":user_id", $userid, PDO::PARAM_INT);
    $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }
  public function react_max_show($postid)
  {
    $stmt = $this->pdo->prepare("SELECT reactType, count(*) as maxreact from react WHERE reactOn = :postid AND reactCommentOn = '0' AND reactReplyOn = '0' GROUP BY reactType LIMIT 3");
    $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  public function main_react_count($postid)
  {
    $stmt = $this->pdo->prepare("SELECT count(*) as maxreact from react WHERE reactOn = :postid AND reactCommentOn = '0' AND reactReplyOn = '0'");
    $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }
  public function commentFetch($postid)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM comments INNER JOIN profile ON comments.commentBy = profile.userId WHERE comments.commentOn = :postid AND comments.commentReplyID = '0' LIMIT 10");
    $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  public function totalCommentCount($postid)
  {
    $stmt = $this->pdo->prepare("SELECT count(*) as totalComment FROM comments WHERE comments.commentOn =:postid");
    $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }
  public function com_react_max_show($postid, $commentid)
  {
    $stmt = $this->pdo->prepare("SELECT reactType, count(*) as maxreact FROM react WHERE reactOn = :postid AND reactCommentOn = :commentID AND reactReplyOn = '0' GROUP BY reactType LIMIT 3");
    $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
    $stmt->bindParam(":commentID", $commentid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  public function com_main_react_count($postid, $commentid)
  {
    $stmt = $this->pdo->prepare("SELECT count(*) as maxreact FROM react WHERE reactOn = :postid AND reactCommentOn = :commentID AND reactReplyOn = '0' ");
    $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
    $stmt->bindParam(":commentID", $commentid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }
  public function commentReactCheck($userid, $postid, $commentid)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM react WHERE reactBy = :userid AND reactOn = :postid AND reactCommentOn = :commentid and reactReplyOn = '0' ");
    $stmt->bindParam(":userid", $userid, PDO::PARAM_INT);
    $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
    $stmt->bindParam(":commentid", $commentid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }
  public function lastCommentFetch($commentid)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM comments INNER JOIN profile ON comments.commentBy = profile.userId WHERE comments.commentID = :commentid");
    $stmt->bindParam(":commentid", $commentid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  public function commentUpd($userid, $postid, $editedTextVal, $commentid)
  {
    $stmt = $this->pdo->prepare("UPDATE comments SET comment = :editedText WHERE commentID =:commentid AND commentBy = :userid AND commentOn = :postid");
    $stmt->bindParam(":commentid", $commentid, PDO::PARAM_INT);
    $stmt->bindParam(":userid", $userid, PDO::PARAM_INT);
    $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
    $stmt->bindParam(":editedText", $editedTextVal, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  public function replyFetch($postid, $commentid)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM comments INNER JOIN profile ON comments.commentBy = profile.userId WHERE comments.commentOn = :postid and comments.commentReplyID =:commentid LIMIT 5");
    $stmt->bindParam(":commentid", $commentid, PDO::PARAM_INT);
    $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  public function reply_main_react_count($postid, $commentid, $replyid)
  {
    $stmt = $this->pdo->prepare("SELECT count(*) as maxreact FROM react WHERE reactOn = :postid AND reactCommentOn = :commentid AND reactReplyOn = :replyid");

    $stmt->bindParam(":commentid", $commentid, PDO::PARAM_INT);
    $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
    $stmt->bindParam(":replyid", $replyid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }
  public function reply_react_max_show($postid, $commentid, $replyid)
  {
    $stmt = $this->pdo->prepare("SELECT reactType, count(*) as maxreact FROM react WHERE reactOn=:postid AND reactCommentOn=:commentid AND reactReplyOn = :replyid GROUP BY reactType LIMIT 3");
    $stmt->bindParam(":commentid", $commentid, PDO::PARAM_INT);
    $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
    $stmt->bindParam(":replyid", $replyid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  public function replyReactCheck($user_id, $postid, $commentid, $replyid)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM react WHERE reactBy = :userid AND reactOn=:postid AND reactCommentOn = :commentid AND reactReplyOn= :replyid");
    $stmt->bindParam(":userid", $user_id, PDO::PARAM_INT);
    $stmt->bindParam(":commentid", $commentid, PDO::PARAM_INT);
    $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
    $stmt->bindParam(":replyid", $replyid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }
  public function lastReplyFetch($replyid)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM comments INNER JOIN profile ON comments.commentBy = profile.userId WHERE comments.commentID = :replyid");
    $stmt->bindParam(":replyid", $replyid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  public function replyUpd($userid, $postid, $editedTextVal, $replyid)
  {
    $stmt = $this->pdo->prepare("UPDATE comments SET comment = :editText WHERE commentBy = :user_id AND commentOn = :post_id AND commentID = :replyid ");

    $stmt->bindParam(":replyid", $replyid, PDO::PARAM_INT);
    $stmt->bindParam(":editText", $editedTextVal, PDO::PARAM_STR);
    $stmt->bindParam(":post_id", $postid, PDO::PARAM_INT);
    $stmt->bindParam(":user_id", $userid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function totalShareCount($postid)
  {
    $stmt = $this->pdo->prepare("SELECT count(*) as totalShare FROM post WHERE post.shareId = :post_id");

    $stmt->bindParam(":post_id", $postid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }

  public function shareFetch($postid, $profileId)
  {
    $stmt = $this->pdo->prepare("SELECT users.*, post.*, profile.* FROM users, post, profile WHERE users.user_id = :user_id AND post.post_id = :post_id AND profile.userId = :user_id");

    $stmt->bindParam(":post_id", $postid, PDO::PARAM_INT);
    $stmt->bindParam(":user_id", $profileId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  public function sharedPostUpd($userid, $postid, $editText)
  {
    $stmt = $this->pdo->prepare("UPDATE post SET shareText = :editText WHERE post_id =:post_id AND userId = :user_id");

    $stmt->bindParam(":post_id", $postid, PDO::PARAM_INT);
    $stmt->bindParam(":user_id", $userid, PDO::PARAM_INT);
    $stmt->bindParam(":editText", $editText, PDO::PARAM_STR);
    $stmt->execute();
  }
  public function searchText($search)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM users LEFT JOIN profile ON users.user_id = profile.userId WHERE  users.userLInk LIKE ? ");

    $stmt->bindValue(1, $search . '%', PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function searchMsgUser($msgUser, $userid)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM users LEFT JOIN profile ON users.user_id = profile.userId WHERE users.user_id != ? AND users.userLink LIKE ? ");
    $stmt->bindValue(1, $userid, PDO::PARAM_INT);
    $stmt->bindValue(2, $msgUser . '%', PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function requestCheck($userId, $profileId)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM request WHERE reqtReceiver = :profileid and ReqtSender = :userid");
    $stmt->bindParam(":profileid", $profileId, PDO::PARAM_INT);
    $stmt->bindParam(":userid", $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }

  public function requestConf($profileId, $userId)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM request WHERE reqtReceiver = :userid and ReqtSender = :profileid");
    $stmt->bindParam(":userid", $userId, PDO::PARAM_INT);
    $stmt->bindParam(":profileid", $profileId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }

  public function updateConfirmReq($profileid, $userid)
  {
    $stmt = $this->pdo->prepare("UPDATE request SET reqStatus = 1 WHERE reqtReceiver = :userid AND reqtSender = :profileid");
    $stmt->bindParam(":profileid", $profileid, PDO::PARAM_INT);
    $stmt->bindParam(":userid", $userid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }

  public function confirmRequestUpdate($profileid, $userid)
  {
    $stmt = $this->pdo->prepare("UPDATE notification SET friendStatus = '1', notificationCount = '0' WHERE notificationFrom = :profileid AND notificationFor = :userid   ");
    $stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindValue(':profileid', $profileid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
}
?>