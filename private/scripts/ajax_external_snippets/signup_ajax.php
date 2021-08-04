<?php require_once('../../initialize.php');  ?>

<?php  
sleep(2);
if(is_ajax_request()) {
  $args['first_name'] = trim($_POST['first_name']) ?? "";
  $args['last_name'] = trim($_POST['last_name']) ?? "";
  $args['username'] = trim($_POST['username']) ?? "";
  $args['email'] = trim($_POST['email']) ?? "";
  $args['password'] = trim($_POST['password']) ?? "";
  $args['confirm_password'] = trim($_POST['confirm_password']) ?? "";
  $user = new User($args);
  $result = $user->save();

  if($result === true) {
    $location = array('location' => 'login.php');
    echo json_encode($location);
  } else {
    $errors = array('errors' => $user->errors);
    echo json_encode($errors);
  }
}



?>