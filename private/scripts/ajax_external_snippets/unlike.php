<?php require_once('../../initialize.php');  ?>

<?php  
sleep(1);
if(!is_ajax_request()) { exit; }

$id = isset($_POST['id']) ? $_POST['id'] : 0;

// BLOG ARTICLE
$blog = Blog::find_by_id($id);

// PAGE ID USER
$user = User::find_by_id($blog->u_id);

// LOGGED IN USER
$l_user = User::find_by_id($_SESSION['user_id']);

$result = Like::delete($user->id, $blog->id, $l_user->id);
if($result === true) {

   $likes = Like::count_likes_per_post($user->id, $blog->id);

    $result = [
        'likes' => $likes,
        'is_liked' => 'false'
    ];  

    echo json_encode($result);
}