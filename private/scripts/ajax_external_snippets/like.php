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

$args['u_id'] = $user->id;
$args['u_username'] = $user->username;
$args['post_id'] = $blog->id;
$args['l_id'] = $l_user->id;
$args['l_username'] = $l_user->username;

$like = new Like($args);
$result = $like->save();

if($result) {
    //LIKES PER POST
    $likes = Like::count_likes_per_post($user->id, $blog->id);

    $result = [
        'likes' => $likes,
        'is_liked' => 'true'
    ];  

    echo json_encode($result);
}

?>
