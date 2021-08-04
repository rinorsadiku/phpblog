<?php
require_once('../private/initialize.php');
require_login();

$id = $_GET['id'];
if (!isset($id) || $id == "") {
  redirect_to('index.php');
}

$blog = Blog::find_by_id($id);
$user = User::find_by_id($blog->u_id);
$result = $blog->delete($id);

if ($result) {
  $user->decrement_posts();
  redirect_to(url_for("user.php?u_id=". h(u($blog->u_id))));
} else {
  redirect_to(url_for("index.php"));
}
?>
