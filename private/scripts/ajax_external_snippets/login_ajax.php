<?php require_once('../../initialize.php');  ?>
<?php

$username = '';
$password = '';
$blank_inputs = [];

if (is_ajax_request()) {
  $username = $_POST['username'] ?? "";
  $password = $_POST['password'] ?? "";

  // Validations
  if (is_blank($username)) {
    array_push($blank_inputs, 'username');
  }

  if(is_blank($password)) {
    array_push($blank_inputs, 'password');
  }

  // Proceed if no errors were found
  if (empty($blank_inputs)) {
    $user = User::find_by_username($username);
    if($user != false) {
      // Check to see if the password was verified
      if ($user->verify_password($password)) {
          $session->login($user);
          $success = ['success' => 'true'];
          echo json_encode($success);
        } else {
            $result = ['result' => "Log In was unsuccessful"]; 
            echo json_encode($result);
        }
    }
  } else {
    
    $errors = ['errors' => $blank_inputs];
    echo json_encode($errors);
  }
}


?>