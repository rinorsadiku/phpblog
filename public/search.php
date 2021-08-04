<?php require_once('../private/initialize.php');  ?>
<?php
if (!isset($_GET['q']) || $_GET['q'] == "") {
  redirect_to('index.php');
}
$raw_q = trim($_GET['q']);
$q = h($raw_q);
$page_title = $q;
$users = User::search($q);
$blogs = Blog::search($q);
?>
<?php include(SHARED_PATH . '/header.php') ?>
<body>
<div class="smaller-container">
  <div class="search">

    <h1>Users</h1>
    <hr>
    <?php if(!empty($users)) { ?>
      <?php foreach($users as $user) { ?>
        <div class="search-box">
          <ul>
            <li><a href="<?php echo url_for("user.php?u_id=".h(u($user->id))); ?>" class="main"><?php echo $user->full_name(); ?></a></li>
            <li><a href="<?php echo url_for("user.php?u_id=".h(u($user->id))); ?>"><?php echo $user->username; ?></a></li>
          </ul>
        </div>
      <?php } ?>
      <?php
    } else {
      echo "<span class=\"q-error\">Your query did not find any users</span>";
    }
    ?>

    <h1>Blogs</h1>
    <hr>
    <?php if(!empty($blogs)) { ?>
      <?php foreach($blogs as $blog) {
        $user = User::find_by_id($blog->u_id);
      ?>
        <div class="search-box">
          <ul>
            <li><a href="<?php echo url_for("blog.php?id=".h(u($blog->id))); ?>" class="main"><?php echo $blog->title; ?></a></li>
            <li>From: <a href="<?php echo url_for("user.php?u_id=". h(u($user->id))); ?>"><?php echo $user->full_name(); ?></a><li>
          </ul>
        </div>
      <?php } ?>
      <?php
    } else {
      echo "<span class=\"q-error\">Your query did not find any blogs</span>";
    }
    ?>

  </div>
</div>
</body>
<?php include(SHARED_PATH . '/footer.php') ?>
