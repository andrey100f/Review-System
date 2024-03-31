<header class="header">

   <section class="flex">

      <a href="all_posts.php" class="logo">Logo.</a>

      <nav class="navbar">
         <a href="all_posts.php" class="far fa-eye"></a>
         <a href="login.php" class="fas fa-arrow-right-to-bracket"></a>
         <a href="register.php" class="far fa-registered"></a>
         <?php
            if($user_id != ''){
         ?>
            <div id="user-btn" class="fa-regular fa-user"></div>
         <?php }; ?>
      </nav>

      <?php
         if($user_id != ''){
           
      ?>
         <div class="profile">
            <?php
               $select_profile = $connection->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
               $select_profile->execute([$user_id]);

               if($select_profile->rowCount() > 0) {
                  $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>

            <p><?= $fetch_profile["name"]; ?></p>
            <a href="update.php" class="btn">Update Profile</a>
            <a href="components/logout.php" class="delete-btn" onclick="return confirm('Logout from this website?');">Logout</a>

            <?php } else { ?>
               <div class="flex-btn">
                  <p>Please Login or Register</p>
                  <a href="login.php" class="inline-option-btn">Login</a>
                  <a href="register.php" class="inline-option-btn">Register</a>
               </div>
            <?php }; ?>
         </div>
      <?php }; ?>

   </section>

</header>