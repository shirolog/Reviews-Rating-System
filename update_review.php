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

    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $description = $_POST['description'];
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $rating = $_POST['rating'];
    $rating = filter_var($rating, FILTER_SANITIZE_STRING);

    $update_reviews = $conn->prepare("UPDATE  `reviews` SET title= ?, description= ?, rating= ? 
    WHERE id= ?");
    $update_reviews->execute(array($title, $description, $rating, $get_id));

    $success_msg[] = 'Review updated!';
    $_SESSION['success_msg'] = $success_msg;
    
    header('Location:./update_review.php?get_id='. $get_id);
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update review</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>
<!-- header section -->
<?php  require('./assets/components/header.php');  ?>


<!-- account-form section -->
<section class="account-form">

    <?php 
    $select_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE id= ? LIMIT 1");
    $select_reviews->execute(array($get_id));
    if($select_reviews->rowCount() > 0){
        while($fetch_reviews = $select_reviews->fetch(PDO::FETCH_ASSOC)){
    ?>

    <form action="" method="post">

        <h3>edit your review</h3>
        <p class="placeholder">review title <span>*</span></p>
        <input type="text" name="title" class="box" required 
        maxlength="50" placeholder="enter review title" 
        value="<?= $fetch_reviews['title']; ?>">
        <p class="placeholder">review description</p>
        <textarea name="description" class="box" placeholder="enter review description" maxlength="1000" cols="30" 
        rows="10"><?= $fetch_reviews['description']; ?></textarea>
        <p class="placeholder">review rating <span>*</span></p>
        <select name="rating" class="box">
            <option value="<?=$fetch_reviews['rating'];?>"><?=$fetch_reviews['rating'];?></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <input type="submit" name="submit" class="btn" value="update review">
        <a href="./view_post.php?get_id=<?= $fetch_reviews['post_id']; ?>" class="option-btn">go back</a>
    </form>

    <?php 
    }
    }else{
        echo '<p class="empty">something went wrong!</p>';
    }
    ?>

</section>

<!-- sweetalert js cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

<?php require('./assets/components/alerts.php') ?>
</body>
</html>

