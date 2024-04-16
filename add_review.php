<?php include 'components/connect.php'; 

    if(isset($_GET['get_id'])) {
        $get_id = $_GET['get_id'];
    } else {
        $get_id = "";
        header('Location: all_posts.php');
    }

    if(isset($_POST['submit'])) {
        if($user_id != "") {
            $id = create_unique_id();
            $title = $_POST['title'];
            $description = $_POST['description'];
            $rating = $_POST['rating'];

            $verify_review = $connection->prepare("SELECT * FROM `reviews` WHERE post_id = ? AND user_id = ?");
            $verify_review->execute([$get_id, $user_id]);

            if($verify_review->rowCount() > 0) {
                $warning_msg[] = "Your review already added!!";
            }
            else {
                $add_review = $connection->prepare("INSERT INTO `reviews`(id, post_id, user_id, rating, title, description) VALUES (?, ?, ?, ?, ?, ?)");
                $add_review->execute([$id, $get_id, $user_id, $rating, $title, $description]);

                $success_msg[] = "Review added successfully!!";
            }
        }
        else {
            $warning_msg = "Please login first!!";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <?php include "components/header.php"; ?>

    <section class="account-form">
        <form action="" method="post">
            <h3>Post your Review</h3>
            <p class="placeholder">Review Title</p>
            <input type="text" name="title" required maxlength="50" placeholder="enter your review title" class="box">

            <p class="placeholder">Review Description</p>
            <textarea name="description" class="box" placeholder="enter review description" maxlength="1000" cols="30" rows="10"></textarea>

            <p class="placeholder">Review Rating<span>*</span></p>

            <select name="rating" class="box" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>

            <input type="submit" value="Submit Review" name="submit" class="btn">
            <a href="view_post.php?get_id=<?= $get_id; ?>" class="option-btn">Go Back</a>
        </form>
    </section>


    <script src="js/script.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://kit.fontawesome.com/1a473c2b75.js" crossorigin="anonymous"></script>
    
    <?php include 'components/alerts.php'; ?>
</body>
</html>