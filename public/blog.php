<?php require_once('../private/initialize.php');  ?>
<?php require_login(); ?>
<?php
$id = $_GET['id'];
if (!isset($id)) {
  redirect_to('index.php');
}

// BLOG ARTICLE
$blog = Blog::find_by_id($id);

// PAGE ID USER
$user = User::find_by_id($blog->u_id);

// LOGGED IN USER
$l_user = User::find_by_id($_SESSION['user_id']);

// LIKES PER POST
$likes = Like::count_likes_per_post($user->id, $blog->id);

// COMMENTS PER POST
// $comments = Comment::find_comments_by_post_id($id);

//CHECK TO SEE IF THE POST IS LIKED
$post_liked = Like::is_liked($user->id, $blog->id, $l_user->id);

$page_title = $blog->title;
$show_post_header = true;

// if(isset($_POST['post-comment'])) {
  // $c_args['u_id'] = $user->id;
  // $c_args['u_username'] = $user->username;
  // $c_args['post_id'] = $blog->id;
  // $c_args['c_id'] = $l_user->id;
  // $c_args['c_username'] = $l_user->username;
  // $c_args['comment'] = $_POST['comment'];

  // $comment = new Comment($c_args);
  // $result = $comment->save();
  // if($result) {
  //   redirect_to(url_for("blog.php?id=".h(u($id))));
  // }
// }

// if(isset($_GET['d'])) {
//   $c_id = $_GET['d'];
//   $result = Comment::delete($c_id);
//   if($result) {
//     redirect_to(url_for("blog.php?id=".h(u($id))));
//   }
// }

?>
<?php include(SHARED_PATH . '/header.php') ?>
<body>
  <section>
    <div class="wrapper">

      <div class="blog-article">
        <article class="">
        <h1><?php echo h($blog->title); ?></h1>
        <pre><?php echo h($blog->content); ?></pre>
        <hr>

        <div class="control-panel">
          <h2><a href="<?php echo url_for('user.php?u_id='.$blog->u_id) ?>"><?php echo $user->full_name(); ?></a></h2>

          <nav class="nav<?php if($post_liked) {echo " liked";} ?>">
            
            <button id="like-button"><img id="like-img" src="images/thumbs-up-icon.png"></button>
            <button id="unlike-button"><img id="unlike-img"  src="images/thumbs-down-icon.png"></button>
       
            <form id="comment-form">
              <input type="text" id="comment-input" name="comment" placeholder="Add a Comment...">
              <button type="button" id="comment-post">Post</button>
            </form>
            
            <p id="likes"><?php echo $likes . " likes"; ?></p>
          </nav>
        
        </div>

      </article>
      </div>
      
      <hr>

      <div id="comment-section">           
      </div>


      <button id="load-more" data-page="0">Load More Comments</button>
      <p id="empty">There are no more comments to show</p>
    </div>
  </section>
  
  <script>
    var likes = document.getElementById("likes");
    var commentInput = document.getElementById("comment-input");
    var loadMore = document.getElementById("load-more");
    var emptyComments = document.getElementById("empty");
    var post_comment = document.getElementById("comment-post");
    var container = document.getElementById("comment-section");
    var likeButton = document.getElementById("like-button");
    var unlikeButton = document.getElementById("unlike-button");
    var likeImg = document.getElementById("like-img"); 
    var unlikeImg = document.getElementById("unlike-img"); 
    var parent = likeButton.parentElement;

    function disableLoadMore() {
      loadMore.disabled = true;
    }

    function enableLoadMore() {
      loadMore.disabled = false;
    }

    function hideButton() {
      loadMore.style.display = 'none';
    }

    function showEmptyCommentsMsg() {
      emptyComments.style.display = 'block';
    }

    function setCurrentPage(page) {
      // console.log("Incrementing page to: " + page);
      loadMore.setAttribute('data-page', page);
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

    function appendNewComment(div, new_html) {
      var temp = document.createElement('div');
      temp.innerHTML = new_html;
      
      var class_name = temp.firstElementChild.className;
      var items = temp.getElementsByClassName(class_name);
      
      div.prepend(items[0]);
    }

    function removeExistingDiv(div, id) {
      var targetedDiv = document.getElementById(id);
      div.removeChild(targetedDiv);
    }

    function clearValue() {
      commentInput.value = "";
    }

    function favorite() {
      likeImg.src = "images/like-spinner.gif";
      likeButton.disabled = true;

      var xhr = new XMLHttpRequest();
      xhr.open('POST', '../private/scripts/ajax_external_snippets/like.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.onreadystatechange = function () {
        if(xhr.readyState == 4 && xhr.status == 200) {
          var json = JSON.parse(xhr.responseText);

          if(json.is_liked == 'true') {
            parent.classList.add("liked");
            likeImg.src = "images/thumbs-up-icon.png";
            likeButton.disabled = false;
          }

          console.log("Result: " + json.is_liked);
          likes.innerHTML = json.likes + " likes";
        }
      };
      xhr.send("id=" + <?php echo $id; ?>);
    }

    
    function unfavorite() {
      unlikeImg.src = "images/like-spinner.gif";
      unlikeButton.disabled = true;

      var xhr = new XMLHttpRequest();
      xhr.open("POST", '../private/scripts/ajax_external_snippets/unlike.php', true) 
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.onreadystatechange = function () {
        if(xhr.readyState == 4 && xhr.status == 200) {
          var json = JSON.parse(xhr.responseText);

          if(json.is_liked == 'false') {
            parent.classList.remove("liked");
            unlikeImg.src = "images/thumbs-down-icon.png";
            unlikeButton.disabled = false;
          }

          console.log("Result: " + json.is_liked);
          likes.innerHTML = json.likes + " likes";
        }
      };
      xhr.send("id=" + <?php echo $id; ?>);
    }


    function loadComments() {
      disableLoadMore();
      var page = parseInt(loadMore.getAttribute('data-page'));
      var next_page = page + 1;

      var xhr = new XMLHttpRequest();
      xhr.open('GET', '../private/scripts/ajax_external_snippets/comments_ajax.php?page=' + next_page + "&id=" + <?php echo $id; ?>, true);
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.onreadystatechange = function () {
        if(xhr.readyState == 4 && xhr.status == 200) {
          var result = xhr.responseText;
          // console.log("Result: " + result);
          if(!result) {
            hideButton();
            showEmptyCommentsMsg();
          }
          setCurrentPage(next_page);
          appendToDiv(container, result);
          enableLoadMore();
        }
      }
      xhr.send();
    }

    function postComment() {
      var form = document.getElementById('comment-form');
      var form_data = new FormData(form);
      for([key, value] of form_data.entries()) {
        console.log(key + ":" + value);
      }

      var xhr = new XMLHttpRequest();
      xhr.open('POST', '../private/scripts/ajax_external_snippets/post_comment_ajax.php?id=' + <?php echo $id; ?>, true);
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.onreadystatechange = function () {
        if(xhr.readyState == 4 && xhr.status == 200) {
          var result = xhr.responseText;
          console.log("Result: " + result);
          appendNewComment(container, result);
          clearValue();
        }
      }
      xhr.send(form_data)
    }

    function deleteComment(commentId) {
     
      var xhr = new XMLHttpRequest();
      xhr.open('POST',  '../private/scripts/ajax_external_snippets/delete_comment_ajax.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.onreadystatechange = function () {
        if(xhr.readyState == 4 && xhr.status == 200) {
          var result = xhr.responseText;
          console.log("Result: " + result);
          removeExistingDiv(container, result);
        }
      }
      xhr.send('id=' + commentId);
    }

    likeButton.addEventListener("click", favorite);
    unlikeButton.addEventListener("click", unfavorite);
    post_comment.addEventListener("click", postComment);
    loadMore.addEventListener("click", loadComments);
    loadComments();
  
  </script>

</body>
<?php include(SHARED_PATH . '/footer.php') ?>
