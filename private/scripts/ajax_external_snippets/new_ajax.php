<?php require_once('../../initialize.php');  ?>
<?php  
  if(is_ajax_request()) {
    $user = User::find_by_id($_SESSION['user_id']);
    $args['title'] = $_POST['title'];
    $args['preview'] = $_POST['preview'];
    $args['content'] = $_POST['content'];
    $args['u_id'] = $user->id;
    $blog = new Blog($args);
    $result = $blog->save();
    
    if ($result != false) {
      $new_id = $blog->id;
    $user->increment_posts();
    $id_arr = ['id' => $new_id];
    echo json_encode($id_arr);
  } else {
    $errors = ['errors' => $blog->errors];
    echo json_encode($errors);
  }
}



?>