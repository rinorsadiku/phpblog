<?php
require_once('../private/initialize.php');
require_login();
$id = $_GET['id'] ?? "";
if (!isset($id) || $id == "") {
  redirect_to('index.php');
}
$blog = Blog::find_by_id($id);
$user = User::find_by_id($blog->u_id);
$page_title = "Edit: ".$blog->title;
if (!$user->verify_page_access($_SESSION['user_id'])) {
  redirect_to(url_for('user.php?u_id='.h(u($_SESSION['user_id']))));
}
?>
<?php
// if (is_post_request()) {
//   $args['title'] = $_POST['title'];
//   $args['preview'] = $_POST['preview'];
//   $args['content'] = $_POST['content'];
//   $args['u_id'] = $user->id;
//   $blog->merge_attributes($args);
//   $result = $blog->save();

//   if ($result != false) {
//     redirect_to('blog.php?id='.h(u($blog->id)));
//   } else {

//   }
// }
?>
<?php include(SHARED_PATH . '/header.php') ?>
<body>

  <div class="smaller-container">
    <form class="new-blog-form" id="edit-form" action="../private/scripts/ajax_external_snippets/b_edit_ajax.php" method="POST">

      <h1>Edit Current Blog Article</h1>
      <label for="title">Title</label><br>
      <input type="text" name="title" value="<?php echo $blog->title; ?>"><br>

      <label for="preview">Preview</label><br>
      <textarea name="preview" rows="6" cols="60"><?php echo $blog->preview; ?></textarea><br>

      <label for="content">Content</label><br>
      <textarea name="content" rows="8" cols="100"><?php echo $blog->content; ?></textarea><br>

      <button type="button" id="edit-button" name="submit">Submit</button>
    </form>
  </div>

</body>

<script>
  var button = document.getElementById('edit-button');

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
  
  function editBlog() {
    
    clearInputErrors();
    clearTextareaErrors();

    var form = document.getElementById('edit-form');
    var action = form.getAttribute('action');

    var form_data = new FormData(form);
    for([key, value] of form_data.entries()) {
      console.log(key + ":" + value);
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', "../private/scripts/ajax_external_snippets/b_edit_ajax.php?id=<?php echo $id; ?>", true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    // xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
      if(xhr.readyState == 4 && xhr.status == 200) {
        var result = xhr.responseText;
        console.log('Result:' + result);
        var json = JSON.parse(result);

        if(json.hasOwnProperty('location')) {
          window.location.href = json.location + "?id=" + <?php echo $id; ?>;
        } else {
          displayInputErrors(json.errors);
          displayTextareaErrors(json.errors);
        }
      }
    }
    xhr.send(form_data)
  }

  button.addEventListener("click", editBlog);

</script>
<?php include(SHARED_PATH . '/footer.php') ?>
