<?php require_once('../../initialize.php');  ?>
<?php  
$id = $_GET['id'];
$blog = Blog::find_by_id($id);
if (is_ajax_request()) {
  $args['title'] = $_POST['title'];
  $args['preview'] = $_POST['preview'];
  $args['content'] = $_POST['content'];
  $args['u_id'] = $_SESSION['user_id'];
  $blog->merge_attributes($args);
  $result = $blog->save();

  if ($result != false) {
    $location = ['location' => 'blog.php'];
    echo json_encode($location);
  } else {
    $errors = ['errors' => $blog->errors];
    echo json_encode($errors);
  }
}




?>