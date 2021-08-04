<?php require_once('../../initialize.php');  ?>

<?php  
sleep(1);
if(!is_ajax_request()) { exit; }

$id = isset($_POST['id']) ? $_POST['id'] : 0;

$result = Follower::delete($id, $_SESSION['user_id']);

if($result) {
    // Count the number of followers of the PAGE ID user
    $followers = Follower::count_followers_by_user($id);
    
    $result = [
        'followers' => $followers,
        'is_followed' => 'false'
    ];

    echo json_encode($result);
  }