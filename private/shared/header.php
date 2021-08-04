<?php
$show_post_header = $show_post_header ?? true;
$page_title = $page_title ?? "";
$stylesheet = $stylesheet ?? "public.css";
$h_user = User::find_by_id($_SESSION['user_id']);
$followers = Follower::count_followers_by_user($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="stylesheets/<?php echo $stylesheet; ?>">
    <link rel="icon" type="image/png" href="images/main-logo.png">
  </head>
  <div class="wrapper">
    <header>
    <?php include("pre_header.php"); ?>
      <nav>
        <?php
        if (isset($_GET['q']) && $_GET['q'] != "") {
          $raw_query = $_GET['q'];
          $query = h($raw_query);
        }
        ?>
        <div class="public-header">
          <form action="search.php" method="GET">
            <input type="text" name="q" placeholder="Search..." value="<?php echo $query ?? ""; ?>">
            <button type="submit">&#x1F50E;</button>
          </form>
          <a href="index.php"><img src="images/main-logo.png" class="main-logo"></a>
            <a href="user.php?u_id=<?php echo $h_user->id; ?>"><img src="images/user-symbol.png" class="user-symbol"></img></a>
        </nav>
        <?php if($show_post_header === true) { include("post_header.php"); } ?>
      </header>
    </div>
  </div>
