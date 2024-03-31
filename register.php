<?php 
include 'components/connect.php';

if(isset($_POST["submit"])) {
    $id = create_unique_id();

    $name = $_POST["name"];

    $email = $_POST["email"];

    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $confirm_password = password_verify($_POST["confirm_password"], $password);
    
    $image = $_FILES['image']['name'];
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id().'.'.$ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_files/'.$rename;
 
    if(!empty($image)){
       if($image_size > 2000000){
          $warning_msg[] = 'Image size is too large!';
       }else{
          move_uploaded_file($image_tmp_name, $image_folder);
       }
    }else{
       $rename = '';
    }

    $verify_email = $connection->prepare("SELECT * FROM `users` WHERE email = ?");
    $verify_email->execute([$email]);

    if($verify_email->rowCount() > 0) {
        $warning_msg[] = "Email already taken!";
    }
    else {
        if($confirm_password == 1) {
            $insert_user = $connection->prepare("INSERT INTO `users` (id, name, email, password, image) VALUES (?, ?, ?, ?, ?)");
            $insert_user->execute([$id, $name, $email, $password, $rename]);
            $success_msg[] = "Registered successfully!!";
        }
        else {
            $warning_msg[] = "Confirm password not matched!!";
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <?php include "components/header.php"; ?>

    <section class="account-form">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Make your account!</h3>
            <p class="placeholder">Your name <span>*</span></p>
            <input type="text" name="name" required maxlength="50" placeholder="enter your name" class="box">
            
            <p class="placeholder">Your email <span>*</span></p>
            <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="box">
            
            <p class="placeholder">Your password <span>*</span></p>
            <input type="password" name="password" required maxlength="50" placeholder="enter your password" class="box">
            
            <p class="placeholder">Confirm password <span>*</span></p>
            <input type="password" name="confirm_password" required maxlength="50" placeholder="confirm your password" class="box">
            
            <p class="placeholder">Profile picture</p>
            <input type="file" name="image" class="box" accept="image/*">
            
            <p class="link">Already have an account? <a href="login.php">Login Now</a></p>
            
            <input type="submit" value="Register now" name="submit" class="btn">
        </form> 
    </section>

    <script src="js/script.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://kit.fontawesome.com/1a473c2b75.js" crossorigin="anonymous"></script>
    
    <?php include 'components/alerts.php'; ?>
</body>
</html>