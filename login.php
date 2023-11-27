<?php 
require('./assets/components/connect.php');
session_start();


if(isset($_POST['submit'])){

    $email = $_POST['email'];
    $email= filter_var($email, FILTER_SANITIZE_STRING);
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);



    $select_users = $conn->prepare("SELECT * FROM `users` WHERE email= ? LIMIT 1");
    $select_users->execute(array($email));
    $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);
    if($select_users->rowCount() > 0){
        $verify_pass = password_verify($pass, $fetch_users['password']);
        if($verify_pass == true){
            setcookie('user_id', $fetch_users['id'],  time() + 60*60*24*30,'/');
            $success_msg[] = 'login successfully!';
            $_SESSION['success_msg'] = $success_msg;
            header('Location:./all_posts.php');
            exit();
        }else{
            $warning_msg[]= 'Incorrect password!';
            $_SESSION['warning_msg'] = $warning_msg;          
            header('Location:./login.php');
            exit();
        }
    }else{
        $warning_msg[]= 'Incorrect email!';
        $_SESSION['warning_msg'] = $warning_msg;
        header('Location:./login.php');
        exit();
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>

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
        <h3>welcome back!</h3>
        <p class="placeholder">your email <span>*</span></p>
        <input type="email" name="email" class="box" placeholder="enter your email" required
        maxlength="50">
        <p class="placeholder">your password <span>*</span></p>
        <input type="password" name="pass" class="box" placeholder="enter your password" required
        maxlength="50">
        <p class="link">don't have an account? <a href="./register.php">register now</a></p>
        <input type="submit" name="submit" class="btn" value="login now">
    </form>
</section>


<!-- sweetalert js cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

<?php require('./assets/components/alerts.php') ?>
</body>
</html>

