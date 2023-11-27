<?php 
require('./assets/components/connect.php');
session_start();

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id']; 
}else{
    $user_id = '';
    header('Location:./login.php');
    exit();
}


if(isset($_POST['submit'])){

    $select_users = $conn->prepare("SELECT * FROM `users` WHERE id= ? LIMIT 1");
    $select_users->execute(array($user_id));
    $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email= filter_var($email, FILTER_SANITIZE_STRING);
    
    if(!empty($name)){
        $update_users = $conn->prepare("UPDATE `users` SET name= ? WHERE id= ?");
        $update_users->execute(array($name, $user_id));
        $success_msg[] = 'Username updated!!';
        $_SESSION['success_msg'] = $success_msg;
    }
    
    if(!empty($email)){
       $select_users = $conn->prepare("SELECT * FROM `users` WHERE email= ?");
       $select_users->execute(array($email));
       if($select_users->rowCount() > 0){
        $warning_msg[]= 'Email already taken!';
        $_SESSION['warning_msg'] = $warning_msg;
       }else{
        $update_users = $conn->prepare("UPDATE `users` SET email= ? WHERE id= ?");
        $update_users->execute(array($email, $user_id));
        $success_msg[] = 'Email updated!!';
        $_SESSION['success_msg'] = $success_msg;
       }
    }

    $image = $_FILES['image']['name'];
    $ext_image= pathinfo($image, PATHINFO_EXTENSION);
    $rename_image= create_unique_id(). '.' .$ext_image;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name= $_FILES['image']['tmp_name'];
    $image_folder = './assets/uploaded_files/'.$rename_image;

    if(!empty($image)){
        if($image_size > 2000000){
            $warning_msg[]= 'Image size too large!';
            $_SESSION['warning_msg'] = $warning_msg;
        }else{
            $update_users= $conn->prepare("UPDATE `users` SET image= ? WHERE id= ?");
            $update_users->execute(array($rename_image, $user_id));
            move_uploaded_file($image_tmp_name, $image_folder);
            if($fetch_users['image'] != ''){
                unlink('./assets/uploaded_files/'.$fetch_users['image']);
            }
            $success_msg[] = 'Image updated!!';
            $_SESSION['success_msg'] = $success_msg;
        }
    }

    $prev_pass = $fetch_users['password'];
    $old_pass = password_hash($_POST['old_pass'], PASSWORD_DEFAULT) ;
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);

    $empty_old = password_verify('', $old_pass);


    $new_pass = password_hash($_POST['new_pass'], PASSWORD_DEFAULT) ;
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);

    $empty_new = password_verify('', $new_pass);

    $cpass = password_verify($cpass, $new_pass) ;
    $cpass = password_hash($_POST['cpass'], PASSWORD_DEFAULT);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    if($old_pass != $empty_old){
        $verify_old_pass = password_verify($_POST['old_pass'], $prev_pass);
        if($verify_old_pass == true){

            if($cpass == true){
                if($empty_new != true){
                    $update_users = $conn->prepare("UPDATE `users` SET password=? WHERE id= ?");
                    $update_users->execute(array($cpass, $user_id));
                    $success_msg[] = 'Password updated!';
                    $_SESSION['success_msg'] = $success_msg;  
                }else{
                    $warning_msg[]= 'Please enter new password!';
                    $_SESSION['warning_msg'] = $warning_msg;               
                }
            }else{
                $warning_msg[]= 'Confirm password not matched!';
                $_SESSION['warning_msg'] = $warning_msg;
            }
          
        }else{
            $warning_msg[]= 'Old password not matched!';
            $_SESSION['warning_msg'] = $warning_msg;
        }
    }
    header('Location:./update.php');
    exit();
}

if(isset($_POST['delete_image'])){

    $select_users = $conn->prepare("SELECT * FROM  `users` WHERE id= ? LIMIT 1");
    $select_users->execute(array($user_id));
    $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);

    if($fetch_users['image'] != ''){
        $update_users = $conn->prepare("UPDATE  `users` SET image= ? WHERE id= ?");
        $update_users->execute(array('', $user_id));
        unlink('./assets/uploaded_files/'.$fetch_users['image']);
        $success_msg[] = 'Image deleted!';
        $_SESSION['success_msg'] = $success_msg; 
    }else{
        $warning_msg[]= 'Image already deleted!';
        $_SESSION['warning_msg'] = $warning_msg;
    }
    header('Location:./update.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update profile</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>
<!-- header section -->
<?php  require('./assets/components/header.php');  ?>

<!-- account-form  section -->
<section class="account-form">
    <form action="" method="post" enctype="multipart/form-data">
        <h3>update your profile!</h3>
        <p class="placeholder">your name </p>
        <input type="text" name="name" class="box" placeholder="<?= $fetch_users['name']; ?>" 
        maxlength="50">
        <p class="placeholder">your email </p>
        <input type="email" name="email" class="box" placeholder="<?= $fetch_users['email']; ?>" 
        maxlength="50">
        <p class="placeholder">old password </p>
        <input type="password" name="old_pass" class="box" placeholder="enter your old password" 
        maxlength="50">
        <p class="placeholder">new password </p>
        <input type="password" name="new_pass" class="box" placeholder="enter your new password" 
        maxlength="50">
        <p class="placeholder">confirm password </p>
        <input type="password" name="cpass" class="box" placeholder="confirm your password" 
        maxlength="50">
        <?php 
            if($fetch_users['image'] != ''){
        ?>
            <img src="./assets/uploaded_files/<?= $fetch_users['image']; ?>" class="image" alt="">
            <input type="submit" name="delete_image" class="delete-btn" value="delete image"
            onclick="return confirm('delete this image?');">
        <?php 
        }
        ?>
        <p class="placeholder">profile pic</p>
        <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
        <input type="submit" name="submit" class="btn" value="update now">
    </form>
</section>

<!-- sweetalert js cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

<?php require('./assets/components/alerts.php') ?>
</body>
</html>

