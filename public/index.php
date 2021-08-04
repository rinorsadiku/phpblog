<?php require_once('../private/initialize.php');  ?>
<?php require_login(); ?>
<?php
// The procedural form of the find_all() function
// $sql = "SELECT id, title, preview FROM test";
// $query = mysqli_query($database, $sql);
// $blogs = Blog::find_all();
$page_title = "Parallax Blog";
$show_post_header = true;
?>

<?php include(SHARED_PATH . '/header.php') ?>
  <body>
    <div id="blog-container" data-page="0">
      
    </div>
    <div id="loading-dots">
      <img src="images/content-dots.gif" alt="Loading...">
    </div>
  </body>

  <p id="end"></p>

  <script>
  var container = document.getElementById('blog-container');
  var request_in_progress = false;
  var loadingDots = document.getElementById('loading-dots');

  function showLoadingDots() {
    loadingDots.style.display = 'flex';
  }

  function hideLoadingDots() {
    loadingDots.style.display = 'none';
  }

  function setCurrentPage(page) {
    console.log('Incrementing Page To:' + page);
    container.setAttribute('data-page', page);
  }

  function appendToDiv(div, new_html) {
    var temp = document.createElement('div');
    temp.innerHTML = new_html;

    var class_name = temp.firstElementChild.className;
    var items = temp.getElementsByClassName(class_name);

    var len = items.length;
    for(i = 0; i < len; i++) {
      div.appendChild(items[0]);
    }
  }

  function scrollReaction() {
    var content_height = container.offsetHeight;
    var current_y = window.innerHeight + window.pageYOffset;
    console.log(current_y + ":" + content_height);
    if(current_y >= content_height) {
      showBlogs();
    }
  }
  
  function showBlogs() {

    if(request_in_progress) {return;}
    request_in_progress = true;

    showLoadingDots();

    var page = parseInt(container.getAttribute('data-page'));
    var next_page = page + 1;

    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../private/scripts/ajax_external_snippets/show_content_ajax.php?page=' + next_page, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function () {
      if(xhr.readyState == 4 && xhr.status == 200) {
        var result = xhr.responseText;
        console.log('Result: ' + result);
       
        if(!result) {
          var end = document.getElementById('end');
          end.innerHTML = "There are no more results to show";
        }
        
        hideLoadingDots();
        setCurrentPage(next_page);
        appendToDiv(container, result);
        request_in_progress = false;
      }
    }
    xhr.send();
  }

  window.onscroll = function () {
    scrollReaction();
  }
  showBlogs();
  
  </script>

  <?php include(SHARED_PATH . '/footer.php') ?>
