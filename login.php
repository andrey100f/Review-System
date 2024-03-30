<?php 

include 'components/connect.php';

if(isset($_POST["submit"])) {
    $email = $_POST["email"];

    $password = $_POST["password"];

    $verify_email = $connection->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
    $verify_email->execute([$email]);

    if($verify_email->rowCount() > 0) {
        $fetch = $verify_email->fetch(PDO::FETCH_ASSOC);
        $verify_password = password_verify($password, $fetch["password"]);

        if($verify_password == 1) {
            setcookie("user_id", $fetch["id"], time() + 60*60*24*30, "/");
            header("location:all_posts.php");
        }
        else {
            $warning_msg[] = "Incorrect password!";
        }
    }
    else {
        $warning_msg[] = "Incorrect email!";
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <?php include "components/header.php"; ?>

    <section class="account-form">
        <form action="" method="post">
            <h3>Welcome Back!</h3>
            
            <p class="placeholder">Your email <span>*</span></p>
            <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="box">
            
            <p class="placeholder">Your password <span>*</span></p>
            <input type="password" name="password" required maxlength="50" placeholder="enter your password" class="box">
            
            <p class="link">Don't have an account? <a href="register.php">Register Now</a></p>
            
            <input type="submit" value="Login now" name="submit" class="btn">
        </form> 
    </section>


    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://kit.fontawesome.com/1a473c2b75.js" crossorigin="anonymous"></script>
    
    <?php include 'components/alerts.php'; ?>
</body>
</html>