<?php require_once('../../initialize.php');  ?>

<?php  
sleep(1);
if(!is_ajax_request()) { exit; }

$id = isset($_POST['id']) ? $_POST['id'] : 0;

// PAGE ID USER
$user = User::find_by_id($id);
// PAGE ID USER BLOGS
$blogs = Blog::find_all($id);
// LOGGED IN USER
$f_user = User::find_by_id($_SESSION['user_id']);

$args['u_id'] = $user->id;
$args['u_username'] = $user->username;
$args['f_id'] = $f_user->id;
$args['f_username'] = $f_user->username;

$follower = new Follower($args);
$result = $follower->save();

if($result) {
    // Count the number of followers of the PAGE ID user
    $followers = Follower::count_followers_by_user($id);
    
    $result = [
        'followers' => $followers,
        'is_followed' => 'true'
    ];

    echo json_encode($result);
  }