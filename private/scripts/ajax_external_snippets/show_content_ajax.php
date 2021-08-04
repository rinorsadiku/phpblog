<?php require_once('../../initialize.php');  ?>
<?php  
sleep(2);
if(!is_ajax_request()) {exit;}

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page = 3;
$offset = $per_page * ($page - 1);

$blogs = Blog::find_limited_blogs($per_page, $offset);
?>
<?php foreach($blogs as $blog) { ?>
<?php $user = User::find_by_id($blog->u_id); ?>
    <div class="blog-box">
        <h1><?php echo h($blog->title); ?></h1>
        <p><?php echo h($blog->preview); ?></p>
        <a class="blog-view" href="blog.php?id=<?php echo h($blog->id); ?>">View</a>
        <p class="date"><?php echo $blog->formatted_date(); ?>
        <br>
        <span><a href="<?php echo 'user.php?u_id='. h(u($user->id)); ?>"><?php echo $user->username; ?></a></span>
        </p>
        <hr>
    </div>
<?php } ?>