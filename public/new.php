<?php
require_once('../private/initialize.php');
require_login();
$id = $_GET['u_id'] ?? "";
if (!isset($id) || $id == "") {
  redirect_to('index.php');
}

$user = User::find_by_id($id);

$page_title = $user->username."'s Blog";

if (!$user->verify_page_access($_SESSION['user_id'])) {
  redirect_to(url_for('user.php?u_id='.h(u($_SESSION['user_id']))));
}
?>

<?php
// if (is_post_request()) {
//   $user = User::find_by_id($_SESSION['user_id']);
//   $args['title'] = $_POST['title'];
//   $args['preview'] = $_POST['preview'];
//   $args['content'] = $_POST['content'];
//   $args['u_id'] = $user->id;
//   $blog = new Blog($args);
//   $result = $blog->save();

//   if ($result != false) {
//     $new_id = $blog->id;
//     $user->increment_posts();
//     redirect_to('blog.php?id='.h(u($new_id)));
//   } else {

//   }
// }
?>
<?php include(SHARED_PATH . '/header.php') ?>
<body>

  <div class="smaller-container">
    <form class="new-blog-form" id="new-form" action="../private/scripts/ajax_external_snippets/new_ajax.php" method="POST">

      <h1>Create a New Blog Article</h1>
      <label for="title">Title</label><br>
      <input type="text" name="title" value=""><br>

      <label for="preview">Preview</label><br>
      <textarea name="preview" rows="6" cols="60"></textarea><br>

      <label for="content">Content</label><br>
      <textarea name="content" rows="8" cols="100"></textarea><br>

      <button type="button" id="new" name="button">Post</button>
    </form>
  </div>

</body>

<script> 
var button = document.getElementById('new');

function displayInputErrors(errors) {
  var inputs = document.getElementsByTagName('input');
  for(i = 0; i < inputs.length; i++) {
    var input = inputs[i];
    if(errors.indexOf(input.name) >= 0) {
      input.classList.add('error');
    }
  }
}

function displayTextareaErrors(errors) {
  var textareas = document.getElementsByTagName('textarea');
  for(i = 0; i < textareas.length; i++) {
    var textarea = textareas[i];
    if(errors.indexOf(textarea.name) >= 0) {
      textarea.classList.add('error');
    }
  }
}

function clearInputErrors() {  
  var inputs = document.getElementsByTagName('input');
  for(i = 0; i < inputs.length; i++) {
    inputs[i].classList.remove('error');
  }
}

function clearTextareaErrors() {  
  var textareas = document.getElementsByTagName('textarea');
  for(i = 0; i < textareas.length; i++) {
    textareas[i].classList.remove('error');
  }
}

function newBlog() {  
  clearInputErrors();
  clearTextareaErrors();
  var form = document.getElementById('new-form');
  var action = form.getAttribute('action');

  var form_data = new FormData(form);
  for([key, value] of form_data.entries()) {
    console.log(key + ":" + value);
  }

  var xhr = new XMLHttpRequest();
  xhr.open('POST', "../private/scripts/ajax_external_snippets/new_ajax.php", true);
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.onreadystatechange = function () {
    if(xhr.readyState == 4 && xhr.status == 200) {
      var result = xhr.responseText;
      var json = JSON.parse(result);
      console.log("Result: " + result);

      if(json.hasOwnProperty('id')) {
        window.location.href = "blog.php?id=" + json.id;
      } else {
        displayInputErrors(json.errors);
        displayTextareaErrors(json.errors);
      }
    }
  }

  xhr.send(form_data);

}

button.addEventListener("click", newBlog);


</script>
<?php include(SHARED_PATH . '/footer.php') ?>
