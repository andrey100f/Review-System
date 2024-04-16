<?php include 'components/connect.php';

    if(isset($_GET['get_id'])) {
        $get_id = $_GET['get_id'];
    } else {
        $get_id = "";
        header('Location: all_posts.php');
    }

    if(isset($_POST['submit'])) {
        $id = create_unique_id();
        $title = $_POST['title'];
        $description = $_POST['description'];
        $rating = $_POST['rating'];

        $update_review = $connection->prepare("UPDATE `reviews` SET rating = ?, title = ?, description = ? WHERE id = ?");
        $update_review->execute([$rating, $title, $description, $get_id]);

        $success_msg[] = "Review updated successfully!!";

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Review</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <?php include "components/header.php"; ?>


    <section class="account-form">

        <?php
            $select_review = $connection->prepare("SELECT * FROM `reviews` WHERE id = ? LIMIT 1");
            $select_review->execute([$get_id]);

            if($select_review->rowCount() > 0) {
                while($fetch_review = $select_review->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <form action="" method="post">
            <h3>Edit your Review</h3>
            <p class="placeholder">Review Title</p>
            <input type="text" name="title" required maxlength="50" placeholder="enter your review title" value="<?= $fetch_review['title']; ?>" class="box">

            <p class="placeholder">Review Description</p>
            <textarea name="description" class="box" placeholder="enter review description" maxlength="1000" cols="30" rows="10"><?= $fetch_review['description']; ?></textarea>

            <p class="placeholder">Review Rating<span>*</span></p>

            <select name="rating" class="box" required>
                <option value="<?= $fetch_review['rating']; ?>"><?= $fetch_review['rating']; ?></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>

            <input type="submit" value="Update Review" name="submit" class="btn">
            <a href="view_post.php?get_id=<?= $fetch_review['post_id']; ?>" class="option-btn">Go Back</a>
        </form>

        <?php
                }
            }
            else {
                echo `<p class="empty">Something went wrong!!</p>`;
            }
        ?>
    </section>

    <script src="js/script.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://kit.fontawesome.com/1a473c2b75.js" crossorigin="anonymous"></script>
    
    <?php include 'components/alerts.php'; ?>
</body>
</html>