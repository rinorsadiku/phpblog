<?php require_once('../../initialize.php');  ?>
<?php  
sleep(1);
if(!is_ajax_request()) {exit;}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 1;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
// BLOG ARTICLE
$blog = Blog::find_by_id($id);

// PAGE ID USER
$user = User::find_by_id($blog->u_id);

$per_page = 2;
$offset = $per_page * ($page - 1);
$comments = Comment::find_comments_by_post_id($id, $per_page, $offset);
?>
<?php if(!empty($comments)) { ?>
  <?php foreach($comments as $comment) {?>
    <div class="comment-box" id="<?php echo h($comment->id); ?>">
      <h3><?php echo h($comment->c_username) ?></h3> 
      <?php if($user->verify_page_access($_SESSION['user_id'])) { ?>
      <button class="comment-delete" onclick="deleteComment(<?php echo h($comment->id); ?>)">Delete</button>
      <?php } ?>
      <span class="date"><?php echo $comment->formatted_date(); ?></span>
      <p><?php echo h($comment->comment); ?></p>
    </div>

  <?php } ?>

<?php
 } else {
   echo false;
 } 
 
 ?>