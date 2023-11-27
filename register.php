<?php 
require('./assets/components/connect.php');
session_start();

if(isset($_POST['submit'])){

    $id = create_unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email= filter_var($email, FILTER_SANITIZE_STRING);
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT) ;
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = password_verify($_POST['cpass'], $pass);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

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
        move_uploaded_file($image_tmp_name, $image_folder);
       }
    }else{
        $rename_image= '';
    }

    $select_users = $conn->prepare("SELECT * FROM `users` WHERE email= ? LIMIT 1");
    $select_users->execute(array($email));
    if($select_users->rowCount() > 0){
        $warning_msg[]= 'Email already taken!';
        $_SESSION['warning_msg'] = $warning_msg;
        unlink('./assets/uploaded_files/'. $rename_image);
    }else{
        if($cpass == true){
            $insert_users = $conn->prepare("INSERT INTO `users` (id, name, email, password, image) VALUES
            (?, ?, ?, ?, ?)");
            $insert_users->execute(array($id, $name, $email, $pass, $rename_image));
            $success_msg[] = 'Registered successfully!';
            $_SESSION['success_msg'] = $success_msg;
        }else{
            $warning_msg[]= 'Confirm password not matched!';
            $_SESSION['warning_msg'] = $warning_msg;
            unlink('./assets/uploaded_files/'. $rename_image);
        }
    }
    header('Location:./register.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>
    <!-- header section -->
<?php  require('./assets/components/header.php');?>

<!-- account-form  section -->
<section class="account-form">
    <form action="" method="post" enctype="multipart/form-data">
        <h3>make your account!</h3>
        <p class="placeholder">your name <span>*</span></p>
        <input type="text" name="name" class="box" placeholder="enter your name" required
        maxlength="50">
        <p class="placeholder">your email <span>*</span></p>
        <input type="email" name="email" class="box" placeholder="enter your email" required
        maxlength="50">
        <p class="placeholder">your password <span>*</span></p>
        <input type="password" name="pass" class="box" placeholder="enter your password" required
        maxlength="50">
        <p class="placeholder">confirm password <span>*</span></p>
        <input type="password" name="cpass" class="box" placeholder="confirm your password" required
        maxlength="50">
        <p class="placeholder">profile pic</p>
        <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
        <p class="link">already have an account? <a href="./login.php">login now</a></p>
        <input type="submit" name="submit" class="btn" value="regiter now">
    </form>
</section>


<!-- sweetalert js cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

<?php require('./assets/components/alerts.php') ?>
</body>
</html>

