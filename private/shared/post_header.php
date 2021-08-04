        <nav>
          <div class="post-header">
            <ul>
              <li><a href="<?php echo url_for('user.php?u_id='. $_SESSION['user_id']); ?>"><?php echo $h_user->full_name(); ?></a></li>
              <li><b>posts</b>: <?php echo $h_user->posts ?? 0; ?></li>
              <li><b>followers</b>: <?php echo $followers ?? 0; ?></li>
            </ul>
          </div>
        </nav>