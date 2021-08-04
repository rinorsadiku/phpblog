<?php
require_once('../private/initialize.php');
if ($session->last_login_is_recent()) {
  redirect_to(url_for("index.php"));
}

// $error = "";
// $username = "";
// $password = "";

// if (is_post_request()) {
//   $username = $_POST['username'] ?? "";
//   $password = $_POST['password'] ?? "";

//   // Validations
//   if (is_blank($username) || is_blank($password)) {
//     $error .= "Initials cannot be left blank";
//   }

//   // Proceed if no errors were found
//   if (empty($error)) {
//     $user = User::find_by_username($username);
//     // Check to see if the previous function returned false
//     if ($user != false && $user->verify_password($password)) {
//       $session->login($user);
//       redirect_to('index.php');
//     } else {
//       $error .= "Log In was unsuccessful";
//     }
//   }
// }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>LogIn</title>
    <link rel="stylesheet" href="stylesheets/login.css">
    <link rel="icon" type="image/png" href="images/main-logo.png">
  </head>
  <body>
    <div class="pimg1">
      <div class="ptext">
        <span class="border">
          Parallax Blog
        </span>
      </div>
    </div>

    <section class="section section-dark">
      <h2>Section One</h2>
      <p>
        It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
      </p>
    </section>

    <div class="pimg3">
      <div class="ptext">
        <span class="border trans">
          Rinor Sadiku
        </span>
      </div>
    </div>


    <section class="section section-light">
      <h2>Section Three</h2>
      <p>
        It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years.
      </p>
    </section>

    <div class="pimg1">
      <div class="ptext">
        <span class="border trans">
          Sign In
          <br>
        </span>
        <form class="form-border" id="login-form" action="../private/scripts/ajax_external_snippets/login_ajax.php" method="POST">
          <input type="text" name="username" placeholder="Username"><br>
          <input type="password" name="password" placeholder="Password"><br>
          <button type="button" id="login" name="button">Log In</button><br>
          <p id='error'>Log In was unsuccessful</p>
        </form>
      </div>
      <a href="signup.php" class="signup">Sign Up</a>
    </div>
  </body>
  
  <script>
  var button = document.getElementById('login');
  var result = document.getElementById('error');

  function postResult() {
    result.style.display = 'block';
  }

  function clearResult() {
    result.style.display = 'none';
  }

  function displayErrors(errors) {
    var inputs = document.getElementsByTagName('input');
    for(i = 0; i < inputs.length; i++) {
      var input = inputs[i];
      if(errors.indexOf(input.name) >= 0) {
        input.classList.add('blank');
      }
    }
  }

  function clearErrors() {
    var inputs = document.getElementsByTagName('input');
    for(i = 0; i < inputs.length; i++) {
      inputs[i].classList.remove('blank');
    }
  }
  
  function logIn() {
    clearErrors();
    clearResult();
    var form = document.getElementById('login-form');
    var action = form.getAttribute('action');
    
    var form_data = new FormData(form);
    for([key, value] of form_data.entries()) {
      console.log(key + ":" + value);
    }
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', "../private/scripts/ajax_external_snippets/login_ajax.php", true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function () {
      if(xhr.readyState == 4 && xhr.status == 200) {
        var result = xhr.responseText;
        console.log('Result: ' + result);
        var json = JSON.parse(result);
        
        if(json.hasOwnProperty('success') && json.success == 'true') {
          window.location.href = "index.php";
        } else {
          if(json.hasOwnProperty('errors')) {
            displayErrors(json.errors);
          } else {
            postResult();
          }
        }

      }
    }
    xhr.send(form_data)
  }

  button.addEventListener('click', logIn);
  </script>
  
  </html>
