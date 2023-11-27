<?php 
require('./assets/components/connect.php');
session_start();


if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id']; 
}else{
    $user_id = '';
    header('./login.php');
    exit();
}

if(isset($_GET['get_id'])){
    $get_id = $_GET['get_id'];
}else{
    $get_id = '';
    header('Location:./all_posts.php');
    exit();
}

if(isset($_POST['submit'])){

        $id = create_unique_id();
        $title = $_POST['title'];
        $title = filter_var($title, FILTER_SANITIZE_STRING);
        $description = $_POST['description'];
        $description = filter_var($description, FILTER_SANITIZE_STRING);
        $rating = $_POST['rating'];
        $rating = filter_var($rating, FILTER_SANITIZE_STRING);

        $select_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE post_id= ? AND user_id= ?");
        $select_reviews->execute(array($get_id, $user_id));
        if($select_reviews->rowCount() > 0){
            $warning_msg[]= 'Your review alreay added!';
            $_SESSION['warning_msg'] = $warning_msg;
        }else{
            $insert_reviews = $conn->prepare("INSERT INTO `reviews` (id, post_id, user_id, rating, title, description)
            VALUES (?, ?, ?, ?, ?, ?)");
            $insert_reviews->execute(array($id, $get_id, $user_id, $rating, $title, $description));
            $success_msg[] = 'Review added!!';
            $_SESSION['success_msg'] = $success_msg;
        }
        header('Location:./add_review.php?get_id='. $get_id);
        exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add review</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>
<!-- header section -->
<?php  require('./assets/components/header.php');?>

<!-- account-form section -->
<section class="account-form">

    <form action="" method="post">

        <h3>post your reviews</h3>
        <p class="placeholder">review title <span>*</span></p>
        <input type="text" name="title" class="box" required 
        maxlength="50" placeholder="enter review title">
        <p class="placeholder">review description</p>
        <textarea name="description" class="box" placeholder="enter review description" maxlength="1000" cols="30" rows="10"></textarea>
        <p class="placeholder">review rating <span>*</span></p>
        <select name="rating" class="box">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <input type="submit" name="submit" class="btn" value="submit review">
        <a href="./view_post.php?get_id=<?= $get_id; ?>" class="option-btn">go back</a>
    </form>

</section>

<!-- sweetalert js cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

<?php require('./assets/components/alerts.php') ?>
</body>
</html>

