<?php

include '../load.php';
include '../../connect/login.php';

$userId = login::isLoggedIn();

if (isset($_POST['fetchImgInfo'])) {
  $userId = $_POST['fetchImgInfo'];
  $postId = $_POST['postId'];
  $imgSrc = $_POST['imageSrc'];

?>
  <div class="top-wrap card border-0">
    <div class="post-img-wrap">
      <div class="row">
        <div class="col-md-6">
          <div class="post-img-action">
            <img src="<?php echo $imgSrc; ?>" class="" alt="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="post-img-details">
            <div class="nf-3"></div>
            <div class="nf-4">
              <div class="action-wrap border-bottom">
                <div class="like-action ra">
                  <div class="like-action-icon">
                    <img src="assets/image/likeAction.JPG" class="action-img img-fluid" alt="">
                  </div>
                  <div class="like-action-text grey-color">
                    <span>Like</span>
                  </div>
                </div>
                <div class="comment-action ra">
                  <div class="comment-action-icon">
                    <img src="assets/image/commentAction.JPG" class="action-img img-fluid" alt="">
                  </div>
                  <div class="comment-action-text grey-color">
                    <div class="comment-wrap"></div>
                    <div class="comment-text">Comment</div>
                  </div>
                </div>
                <div class="share-action ra" data-postid="<?php echo $postId; ?>" data-userid="<?php echo $userid ?>" data-profileid="<?php echo $profileId; ?>" data-profilePic="<?php echo $post->profilePic; ?>">
                  <div class="share-action-icon">
                    <img src="assets/image/shareAction.JPG" class="action-img img-fluid" alt="">
                  </div>
                  <div class="share-action-text grey-color">
                    <span>Share</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="nf-5"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
}
?>