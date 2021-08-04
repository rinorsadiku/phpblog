<?php
require_once '../private/initialize.php';
require_login();
$show_post_header = false;
$id = $_GET['u_id'] ?? "";

if (!isset($id) || $id == "") {
  redirect_to('index.php');
}

// PAGE ID USER
$user = User::find_by_id($id);

// PAGE ID USER BLOGS
$blogs = Blog::find_all($id);

// Count the number of followers of the PAGE ID user
$user_followers = Follower::count_followers_by_user($id);

//CHECK TO SEE IF IT IS FOLLOWED
$is_followed = Follower::is_followed($id, $_SESSION['user_id']);

$page_title = $user->username;

?>

<?php include SHARED_PATH . '/header.php'?>
<body>

  <div class="wrapper">
    <div class="user-info">
      <ul>
        <li>
          <h1><?php echo $user->full_name(); ?></h1>
        </li>
        <li>
          <p><?php echo $user->email; ?></p>
        </li>
        <li>
          <p><?php echo $user->username; ?></p>
        </li>

        <?php if ($user->verify_page_access($_SESSION['user_id'])) {?>
          <li>
            <a title="Add a New Blog" href="<?php echo url_for("new.php?u_id=" . h(u($user->id))); ?>" class="new-blog-button">&#9776;</a>
          </li>
        <?php }?>
      
      </ul>
    </div>
  </div>

  <div class="wrapper<?php if($is_followed) { echo " followed"; } ?>">
    <div class="activity">
      <dl>
        <dt>
          Followers
          <dd id="followers"><?php echo $user_followers; ?></dd>
        </dt>
      </dl>

      <dl>
        <dt>
          Posts
          <dd><?php echo $user->posts ?? 0; ?></dd>
        </dt>
      </dl>
    </div>
    <?php if ($user->verify_page_access($_SESSION['user_id'])) {?>
      <a href="<?php echo url_for('edit.php?u_id=' . h(u($user->id))) ?>" class="settings-button">&#9881;</a>
    <?php } else {?>
      <?php $user_followed = Follower::is_followed($id, $_SESSION['user_id']);?>
    
          <button id="follow">Follow</button>
          <button id="unfollow">Unfollow</button>
          <div id="spinner">
            <img src="images/spinner.gif" width="60" height="60" alt="spinner-gif">
          </div>
      
    <?php } ?>
    <hr>
  </div>
  <section class="blog-container">
    <div class="smaller-container">
      <?php foreach ($blogs as $blog) {?>
        <div class="user-blog-box">
          <h1><?php echo h($blog->title); ?></h1>
          <p id="user-blog"><?php echo h($blog->preview); ?></p>
          <a class="view" href="blog.php?id=<?php echo h($blog->id); ?>">View</a>

          <?php if ($user->verify_page_access($_SESSION['user_id'])) {?>
            <a class="ed" href="b_edit.php?id=<?php echo h($blog->id); ?>">Edit</a>
            <a class="del" href="delete.php?id=<?php echo h($blog->id); ?>">Delete</a>
          <?php }?>

          <p class="date"><?php echo $blog->formatted_date(); ?><br><span><?php echo $user->username; ?></span></p>
        </div>
      <?php }?>
    </div>
  </section>
  <?php if ($user->verify_page_access($_SESSION['user_id'])) {?>
    <hr class="wrapper">
    <div class="logout">
      <a href="logout.php"><img src="images/logout-icon.png" alt="Log Out"></a>
    </div>
  <?php }?>

  <script>
    var output = document.getElementById("followers");
    var followButton = document.getElementById("follow");
    var unfollowButton = document.getElementById("unfollow");
    var parent = followButton.parentElement;
    var spinner = document.getElementById("spinner");

    function showSpinner() {
      spinner.style.display = "block";
    }

    function hideSpinner() {
      spinner.style.display = "none";
    }

    function follow() {
      showSpinner();

      var xhr = new XMLHttpRequest();
      xhr.open('POST', '../private/scripts/ajax_external_snippets/follow.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.onreadystatechange = function () {
        if(xhr.readyState == 4 && xhr.status == 200) {
          var json = JSON.parse(xhr.responseText);

          if(json.is_followed == 'true') {
            parent.classList.add('followed');
            hideSpinner();
          }

          console.log("Result: " + json.followers);
          output.innerHTML = json.followers;
        }
      };
      xhr.send("id=" + <?php echo $id; ?>);
    }

    followButton.addEventListener("click", follow);



    function unfollow() {

      showSpinner();

      var xhr = new XMLHttpRequest();
      xhr.open('POST', '../private/scripts/ajax_external_snippets/unfollow.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.onreadystatechange = function () {
        if(xhr.readyState == 4 && xhr.status == 200) {
          var json = JSON.parse(xhr.responseText);

          if(json.is_followed == 'false') {
            parent.classList.remove('followed');
            hideSpinner();
          }

          console.log("Result: " + json.followers);
          output.innerHTML = json.followers;
        }
      };
      xhr.send("id=" + <?php echo $id; ?>);

    }

    unfollowButton.addEventListener("click", unfollow);
  </script> 

</body>
<?php include SHARED_PATH . '/footer.php'?>
