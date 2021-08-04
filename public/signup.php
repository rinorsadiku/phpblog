<?php require_once('../private/initialize.php'); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>SignUp</title>
    <link rel="stylesheet" href="stylesheets/public.css">
    <link rel="icon" type="image/png" href="images/main-logo.png">
  </head>
  <body>

    <header class="v-header container">
      <div class="fullscreen-video-wrap">
        <video src="videos/better_vid.mp4" autoplay="true" loop="true"></video>
      </div>
      <div class="header-overlay"></div>
    </header>

    <div class="header-content">
      <h1>Tell a Story</h1>
      <p>A Rinor Sadiku Development Product</p>

      <form id="signup-form" action="../private/scripts/ajax_external_snippets/signup_ajax.php" method="POST">
        <input type="text" name="first_name" placeholder="Firstname"><br>
        <input type="text" name="last_name" placeholder="Lastname"><br>
        <input type="text" name="username" placeholder="Username"><br>
        <input type="text" name="email" placeholder="Email"><br>
        <input type="password" name="password" placeholder="Password"><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password"><br>
        <button id="signup" type="button" name="submit">Sign Up</button>
      </form>
    </div>
  </body>

  <script>
  var button = document.getElementById("signup");
  var orig_button_value = "Sign Up";
  // var input = document.getElementById("firstname");

  function enableSignupButton() {
    button.disabled = false;
    button.innerHTML = orig_button_value;
  }

  function disableSignupButton() {  
    button.disabled = true;
    button.innerHTML = "Loading...";
  }

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

  function signUpUser() {
    clearErrors();
    disableSignupButton();
    var form = document.getElementById('signup-form');
    var action = form.getAttribute("action");
    

    var form_data = new FormData(form);
     for([key, value] of form_data.entries()) {
       console.log(key + ':' + value);
      }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", action, true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.onreadystatechange = function () {
      if(xhr.readyState == 4 && xhr.status == 200) {
        var result = xhr.responseText;
        var json = JSON.parse(result);

        console.log("Result: " + result);
        enableSignupButton();

        if(json.hasOwnProperty('location')) {
          window.location.href = json.location;
        } else {
          displayErrors(json.errors);
        }

      }
    }
    xhr.send(form_data);
  }

  button.addEventListener("click", signUpUser);

  </script>

</html>
