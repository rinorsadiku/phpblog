<?php require_once('../../initialize.php');  ?>
<?php  
sleep(1);
if(!is_ajax_request()) {exit;}
$id = isset($_GET['id']) ? (int) $_GET['id'] : 1;

// BLOG ARTICLE
$blog = Blog::find_by_id($id);

// PAGE ID USER
$user = User::find_by_id($blog->u_id);

// LOGGED IN USER
$l_user = User::find_by_id($_SESSION['user_id']);


$comment = isset($_POST['comment']) ? $_POST['comment'] : "";

  $c_args['u_id'] = $user->id;
  $c_args['u_username'] = $user->username;
  $c_args['post_id'] = $blog->id;
  $c_args['c_id'] = $l_user->id;
  $c_args['c_username'] = $l_user->username;
  $c_args['comment'] = $comment;

  $comment = new Comment($c_args);
  $result = $comment->save();
?>

<?php if($result) { ?>
<div class="comment-box" id="<?php echo $comment->id; ?>">
    <h3><?php echo h($comment->c_username) ?></h3> 
    <?php if($user->verify_page_access($_SESSION['user_id'])) { ?>
    <button class="comment-delete" onclick="deleteComment(<?php echo h($comment->id); ?>)">Delete</button>
    <?php } ?>
    <span class="date"><?php echo h($comment->formatted_date()); ?></span>
    <p><?php echo h($comment->comment); ?></p>
  </div>
<?php } ?>