<?php require_once('../private/initialize.php'); ?>
<?php require_login(); ?>
<?php
  $id = $_GET['u_id'] ?? "";
  if (!isset($id) || $id == "") {
    redirect_to('index.php');
  }
  $user = User::find_by_id($id);
  $page_title = $user->username."'s Preferences";
  if (!$user->verify_page_access($_SESSION['user_id'])) {
    redirect_to(url_for('user.php?u_id='.h(u($_SESSION['user_id']))));
  }
?>
<?php
// if (is_post_request()) {
//   $args['first_name'] = trim($_POST['first_name']) ?? "";
//   $args['last_name'] = trim($_POST['last_name']) ?? "";
//   $args['username'] = trim($_POST['username']) ?? "";
//   $args['email'] = trim($_POST['email']) ?? "";
//   $args['password'] = trim($_POST['password']) ?? "";
//   $args['confirm_password'] = trim($_POST['confirm_password']) ?? "";  
//   $user->merge_attributes($args);
//   $result = $user->save();

//   if ($result === true) {
//     redirect_to(url_for('user.php?u_id='.h(u($id))));
//   } else {
//       // Display errors
//   }
// } else {
//   // Display form
// }

?>

<?php include(SHARED_PATH . '/header.php') ?>
<body>
  <div class="smaller-container">

    <div class="form-container">
      <form class="border" id="edit-form" action="../private/scripts/ajax_external_snippets/edit_ajax.php" method="POST">
        <h1>Edit Your Preferences</h1>

        <input type="text" name="first_name" value="<?php echo $user->first_name; ?>"><br>

        <input type="text" name="last_name" value="<?php echo $user->last_name; ?>"><br>

        <input type="text" name="username" value="<?php echo $user->username; ?>"><br>

        <input type="text" name="email" value="<?php echo $user->email; ?>"><br>

        <input type="password" name="password" placeholder="Password"><br>

        <input type="password" name="confirm_password" placeholder="Confirm Password"><br>

        <button type="button" id="edit-button" name="button">Submit</button>
      </form>
    </div>

  </div>
</body>

<script> 
var button = document.getElementById('edit-button');

function displayErrors(errors) {
  var inputs = document.getElementsByTagName('input');
  for(i = 0; i < inputs.length; i++) {
    var input = inputs[i];
    if(errors.indexOf(input.name) >= 0) {
      input.classList.add('error');
    }
  }
}

function clearErrors() {
  var inputs = document.getElementsByTagName('input');
  for(i = 0; i < inputs.length; i++) {
    inputs[i].classList.remove('error');
  }
}

function editPreferences() { 
  clearErrors();

  var form = document.getElementById('edit-form');
  var action = form.getAttribute('action');
  
  var form_data = new FormData(form);
  for([key, value] of form_data.entries()) {
    console.log(key + ":" + value);
  }

  var xhr = new XMLHttpRequest();
  xhr.open('POST', "../private/scripts/ajax_external_snippets/edit_ajax.php", true);
  // xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.onreadystatechange = function () {
    if(xhr.readyState == 4 & xhr.status == 200) {
      var result = xhr.responseText;
      console.log('Result: ' + result);
      var json = JSON.parse(result);

      if(json.hasOwnProperty('location')) {
        // redirect
        window.location.href = json.location + "?u_id=" + <?php echo $id; ?>;
      } else {
        // show errors
        displayErrors(json.errors);
      }
    }
  }
  xhr.send(form_data);
}

button.addEventListener('click', editPreferences);


</script>
<?php include(SHARED_PATH . '/footer.php') ?>
