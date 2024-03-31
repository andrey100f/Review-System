<?php 
    include 'components/connect.php';

    if(isset($_POST["submit"])) {
        $select_user = $connection->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
        $select_user->execute([$user_id]);
        $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

        $name = $_POST["name"];
        $email = $_POST["email"];

        if(!empty($email)) {
            $verify_email = $connection->prepare("SELECT * FROM `users` WHERE email = ?");
            $verify_email->execute([$email]);
            
            if($verify_email->rowCount() > 0) {
                $warning_msg[] = "Email already taken!";
            }
        }

        $image = $_FILES['image']['name'];
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = create_unique_id().'.'.$ext;
        $image_size = $_FILES['image']['size'];
        $image_folder = 'uploaded_files/'.$rename;
        $image_tmp_name = $_FILES['image']['tmp_name'];
        
        if(!empty($image)) {
            if($image_size > 2000000) {
                $warning_msg[] = "Image size is too large!";
            }
            else {
                $update_image = $connection->prepare("UPDATE `users` SET image = ? WHERE id = ?");
                $update_image->execute([$rename, $user_id]);
                move_uploaded_file($image_tmp_name, $image_folder);
                if($fetch_user['image'] != "") {
                    unlink('uploaded_files/'.$fetch_user['image']);
                }
                $success_msg[] = "Image Updated!!";
            }
        }
        else {
            $rename = $fetch_user['image'];
        }

        $previous_password = $fetch_user['password'];

        $old_password = password_hash($_POST["old_password"], PASSWORD_DEFAULT);
        $empty_old = password_verify("", $old_password);

        $new_password = password_hash($_POST["new_password"], PASSWORD_DEFAULT);
        $empty_new = password_verify("", $new_password);

        $confirm_password = password_verify($_POST["confirm_password"], $new_password);

        if($empty_old != 1) {
            $verify_old_password = password_verify($_POST["old_password"], $previous_password);
            
            if($verify_old_password == 1) {
                if($confirm_password == 1) {
                    if($empty_new == 1) {
                        $warning_msg[] = "Please enter new password!!";
                    }
                }
                else {
                    $warning_msg[] = "Confirm password not matched!!";
                }
            }
            else {
                $warning_msg[] = "Old password not matched!!";
            }
        }

        $update_user = $connection->prepare("UPDATE `users` SET name = ?, email = ?, password = ? WHERE id = ?");
        $update_user->execute([$name, $email, $new_password, $user_id]);
        $success_msg[] = "Profile Updated!!";
    }

    if(isset($_POST["delete_image"])) {
        $select_old_picture = $connection->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
        $select_old_picture->execute([$user_id]);
        $fetch_old_picture = $select_old_picture->fetch(PDO::FETCH_ASSOC);

        if($fetch_old_picture["image"] == "") {
            $warning_msg[] = "Image already deleted!!";
        }
        else {
            $image_tmp_name = $fetch_old_picture['image'];
            $update_old_picture = $connection->prepare("UPDATE `users` SET image = ? WHERE id = ?");
            $update_old_picture->execute(["", $user_id]);

            if($fetch_old_picture["image"] != "") {
                unlink('uploaded_files/'.$fetch_old_picture['image']);
            }

            $success_msg[] = "Image Deleted!!";
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <?php include "components/header.php"; ?>

    <section class="account-form">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Update your Profile</h3>
            <p class="placeholder">Your name</p>
            <input type="text" name="name"  maxlength="50" placeholder="<?= $fetch_profile['name'] ?>" class="box">
            
            <p class="placeholder">Your email</p>
            <input type="email" name="email"  maxlength="50" placeholder="<?= $fetch_profile['email'] ?>" class="box">
            
            <p class="placeholder">Old password</p>
            <input type="password" name="old_password"  maxlength="50" placeholder="enter your old password" class="box">
            
            <p class="placeholder">New password</p>
            <input type="password" name="new_password"  maxlength="50" placeholder="enter your new password" class="box">

            <p class="placeholder">Confirm password</p>
            <input type="password" name="confirm_password"  maxlength="50" placeholder="confirm your new password" class="box">
        
            <?php if($fetch_profile['image'] != "") { ?>
                <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="" class="image">
                <input type="submit" value="Delete Image" name="delete_image" class="delete-btn" onclick="return confirm('Delete this image?');">
            <?php }; ?>

            <p class="placeholder">Profile picture</p>
            <input type="file" name="image" class="box" accept="image/*">
            <input type="submit" value="Update now" name="submit" class="btn">
        </form> 
    </section>

    <script src="js/script.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://kit.fontawesome.com/1a473c2b75.js" crossorigin="anonymous"></script>
    
    <?php include 'components/alerts.php'; ?>
</body>
</html>