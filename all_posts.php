<?php include 'components/connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <?php include "components/header.php"; ?>

    <section class="all-posts">
        <div class="heading">
            <h1>All Posts</h1>
        </div>
        
        <div class="box-container">
            <?php
                $select_posts = $connection->prepare("SELECT * FROM `posts`");
                $select_posts->execute();

                if($select_posts->rowCount() > 0) {
                    while($fetch_post = $select_posts->fetch(PDO::FETCH_ASSOC)) {

                        $post_id = $fetch_post['id'];

                        $count_reviews = $connection->prepare("SELECT * FROM `reviews` WHERE `post_id` = ?");
                        $count_reviews->execute([$fetch_post['id']]);
                        $total_reviews = $count_reviews->rowCount();
            ?>

            <div class="box">
                <img src="uploaded_files/<?= $fetch_post['image']; ?>" alt="" class="image">
                <h3 class="title"><?= $fetch_post['title']; ?></h3>
                <p class="total-reviews">
                    <i class="fas fa-star"></i>
                    <span><?= $total_reviews; ?></span>
                </p>
                <a href="view_post.php?get_id=<?= $post_id; ?>" class="inline-btn"> View Post</a>
            </div>

            <?php
                    }
                }
                else {
                    echo `<p class="empty">No posts added yet!!</p>`;
                }
            ?>
        </div>
    </section>


    <script src="js/script.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://kit.fontawesome.com/1a473c2b75.js" crossorigin="anonymous"></script>
    
    <?php include 'components/alerts.php'; ?>
</body>
</html>