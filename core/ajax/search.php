<?php

include '../load.php';
include '../../connect/login.php';


$user_id = login::isLoggedIn();

if (isset($_POST['searchText'])) {
  $searchText = $_POST['searchText'];

  $searchResult = $loadFromPost->searchText($searchText, $user_id);

  echo '<ul class="search-wrapper">';


  foreach ($searchResult as $search) {
    if ($search->userId == $user_id) {
    } else {

?>

<li class="search-mention-individuals">
  <a href="<?php echo BASE_URL . 'profile.php?username=' . $search->userLink; ?>" class="search-mention-link">
    <img src="<?php echo BASE_URL . $search->profilePic; ?>" class="search-mention-img img-fluid" alt="">
    <div class="search-mention-name">
      <?php echo '' . $search->first_name . ' ' . $search->last_name . ''; ?>
    </div>
  </a>
</li>
<?php
    }
  }
  echo '</ul>';
}

if (isset($_POST['msgUser'])) {
  $msgUser = $_POST['msgUser'];
  $userid = $_POST['userid'];
  $searchResult = $loadFromPost->searchMsgUser($msgUser, $userid);
  echo '<ul class="search-wrapper">';
  foreach ($searchResult as $search) {
    ?>
<li class="search-mention-individuals" data-profileid="<?php echo $search->user_id; ?>">
  <img src="<?php echo BASE_URL . $search->profilePic; ?>" class="search-mention-img img-fluid" alt="">
  <div class="search-mention-name">
    <?php echo '' . $search->first_name . ' ' . $search->last_name . ''; ?>
  </div>
</li>

<?php
  }
  echo '</ul>';
}
?>