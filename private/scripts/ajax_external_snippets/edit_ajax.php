<?php require_once('../../initialize.php');  ?>
<?php  
if(is_ajax_request()) {
   $id = $_SESSION['user_id'];
   $user = User::find_by_id($id);

   $args['first_name'] = trim($_POST['first_name']) ?? "";
   $args['last_name'] = trim($_POST['last_name']) ?? "";
   $args['username'] = trim($_POST['username']) ?? "";
   $args['email'] = trim($_POST['email']) ?? "";
   $args['password'] = trim($_POST['password']) ?? "";
   $args['confirm_password'] = trim($_POST['confirm_password']) ?? "";  
   $user->merge_attributes($args);
   $result = $user->save();

  if ($result === true) {
    $location = ['location' => 'user.php'];
    echo json_encode($location);
  } else {
    $errors = ['errors' => $user->errors];
    echo json_encode($errors);
  }

}


?>