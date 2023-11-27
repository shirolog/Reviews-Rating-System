<?php 
require('./assets/components/connect.php');
session_start();

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id']; 
}else{
    $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>all posts</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>
<!-- header section -->
<?php  require('./assets/components/header.php');  ?>

<!-- all-posts section -->
<section class="all-posts">

    <div class="heading"><h1>all posts</h1></div>

    <div class="box-container">
        <?php 
        $select_posts = $conn->prepare("SELECT * FROM `posts`");
        $select_posts->execute();
        if($select_posts->rowCount() > 0){
            while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
            
            $post_id = $fetch_posts['id'];
            $select_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE post_id= ?");
            $select_reviews->execute(array($post_id));
            $total_reviews = $select_reviews->rowCount();
        ?>

        <div class="box">
            <img src="./assets/uploaded_files/<?= $fetch_posts['image']; ?>" class="image" alt="">
            <h3 class="title"><?= $fetch_posts['title']; ?></h3>
            <p class="total-reviews"><i class="fas fa-star"></i> (<?= $total_reviews; ?> reviews)</p>
            <a href="./view_post.php?get_id=<?= $post_id; ?>" class="inline-btn">view post</a>
        </div>

        <?php 
        }
        }else{
            echo '<p class="empty">no posts added yet!</p>';
        }        
        ?>
    </div>

</section>


<!-- sweetalert js cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js -->
<script src="./assets/js/app.js"></script>

<?php require('./assets/components/alerts.php') ?>
</body>
</html>

