<?php 
require('./assets/components/connect.php');
session_start();

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id']; 
}else{
    $user_id = '';
}

if(isset($_GET['get_id'])){
    $get_id = $_GET['get_id'];
}else{
    $get_id = '';
    header('Location:./all_posts.php');
    exit();
}

if(isset($_POST['delete_review'])){

    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $select_reviews= $conn->prepare("SELECT * FROM `reviews` WHERE id= ? ");
    $select_reviews->execute(array($delete_id));
    if($select_reviews->rowCount() > 0){

        $delete_reviews = $conn->prepare("DELETE FROM `reviews` WHERE id= ?");
        $delete_reviews->execute(array($delete_id));
        $success_msg[] = 'Review deleted!';
        $_SESSION['success_msg'] = $success_msg;
    }else{
        $warning_msg[]= 'Review alreay deleted!';
        $_SESSION['warning_msg'] = $warning_msg;
    }
    header('Location: ./view_post.php?get_id='. $get_id);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view post</title>

    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>
<!-- header section -->
<?php  require('./assets/components/header.php');  ?>

<!-- view-post section -->
<section class="view-post">

    <div class="heading"><h1>post details</h1> <a href="./all_posts.php"
    class="inline-option-btn" style="margin-top: 0;">all posts</a></div>

    <?php 
        $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE id= ? LIMIT 1");
        $select_posts->execute(array($get_id));
        if($select_posts->rowCount() > 0){
            while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
            
            $total_rating = 0;
            $rating_1 = 0;
            $rating_2 = 0;
            $rating_3 = 0;
            $rating_4 = 0;
            $rating_5 = 0;

            $select_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE post_id= ?");
            $select_reviews->execute(array($fetch_posts['id']));

            $total_reviews = $select_reviews->rowCount();
            while($fetch_reviews= $select_reviews->fetch(PDO::FETCH_ASSOC)){

                $total_rating += $fetch_reviews['rating'];

                if($fetch_reviews['rating'] == 1){
                    $rating_1 += $fetch_reviews['rating'];
                }
                if($fetch_reviews['rating'] == 2){
                    $rating_2 += $fetch_reviews['rating'];
                }
                if($fetch_reviews['rating'] == 3){
                    $rating_3 += $fetch_reviews['rating'];
                }
                if($fetch_reviews['rating'] == 4){
                    $rating_4 += $fetch_reviews['rating'];
                }
                if($fetch_reviews['rating'] == 5){
                    $rating_5 += $fetch_reviews['rating'];
                }
            }

            if($total_reviews != 0){
               $average = round($total_rating / $total_reviews, 1);
            }else{
                $average = 0;
            }
    ?>

      <div class="row">
        <div class="col">
            <img src="./assets/uploaded_files/<?= $fetch_posts['image']; ?>" class="image" alt="">
            <h3 class="tilte"><?= $fetch_posts['title']; ?></h3>
        </div>

        <div class="col">
            <div class="flex">
                <div class="total-reviews">
                    <h3><?= $average; ?><i class="fas fa-star"></i></h3>
                    <p><?= $total_reviews; ?> reviews</p>
                </div>

                <div class="total-ratings">
                    <p>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span><?= $rating_5 / 5; ?></span>
                    </p>
                    <p>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span><?= $rating_4 / 4; ?></span>
                    </p>
                    <p>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span><?= $rating_3 / 3; ?></span>
                    </p>
                    <p>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span><?= $rating_2 / 2; ?></span>
                    </p>
                    <p>
                        <i class="fas fa-star"></i>
                        <span><?= $rating_1 / 1; ?></span>
                    </p>
                </div>
            </div>
        </div>
      </div>

    <?php 
    }
    }else{
        echo '<p class="empty">post is missing!</p>';
    }
    ?>
    
</section>

<!-- review-container section -->
<section class="review-container">

    <div class="heading"><h1>user's reviews</h1> <a href="./add_review.php?get_id=<?= $get_id; ?>"
    class="inline-btn <?php echo ($user_id == '')?  'disabled': '';?>" style="margin-top: 0;">add review</a></div>

    <div class="box-container">
        <?php 
        $select_reviews= $conn->prepare("SELECT * FROM  `reviews` WHERE post_id= ?");
        $select_reviews->execute(array($get_id));
        if($select_reviews->rowCount() > 0){
            while($fetch_reviews= $select_reviews->fetch(PDO::FETCH_ASSOC)){
        ?>
            <div class="box" <?php if($fetch_reviews['user_id'] == $user_id){echo 'style="order: -1;"';} ?>>
                    <?php 
                    $select_users= $conn->prepare("SELECT * FROM `users` WHERE id= ?");
                    $select_users->execute(array($fetch_reviews['user_id']));
                    while($fetch_users= $select_users->fetch(PDO::FETCH_ASSOC)){
                    ?>
                        <div class="user">
                        <?php if($fetch_users['image'] != ''){ ?>
                            <img src="./assets/uploaded_files/<?= $fetch_users['image']; ?>" alt="">
                         <?php }else{ ?> 
                            <h3><?= substr($fetch_users['name'], 0, 1); ?></h3>
                         <?php }?> 
                         <div>
                            <p><?= $fetch_users['name']; ?></p>
                            <span><?= $fetch_reviews['date']; ?></span>
                         </div>  
                        </div>
                    <?php 
                    }
                    ?>
                    <div class="ratings">
                        <?php 
                        if($fetch_reviews['rating'] == 1){
                        ?>
                            <p style="background: var(--red);"><i class="fas fa-star"></i> <span><?= $fetch_reviews['rating']; ?></span></p>
                        <?php }?>

                        <?php 
                        if($fetch_reviews['rating'] == 2){
                        ?>
                            <p style="background: var(--orange);"><i class="fas fa-star"></i> <span><?= $fetch_reviews['rating']; ?></span></p>
                        <?php }?>

                        <?php 
                        if($fetch_reviews['rating'] == 3){
                        ?>
                            <p style="background: var(--orange);"><i class="fas fa-star"></i> <span><?= $fetch_reviews['rating']; ?></span></p>
                        <?php }?>

                        <?php 
                        if($fetch_reviews['rating'] == 4){
                        ?>
                            <p style="background: var(--main-color);"><i class="fas fa-star"></i> <span><?= $fetch_reviews['rating']; ?></span></p>
                        <?php }?>

                        <?php 
                        if($fetch_reviews['rating'] == 5){
                        ?>
                            <p style="background: var(--main-color);"><i class="fas fa-star"></i> <span><?= $fetch_reviews['rating']; ?></span></p>
                        <?php }?>
                    </div>
                    <h3 class="title"><?= $fetch_reviews['title']; ?></h3>
                    <?php if($fetch_reviews['description'] != ''){  ?>
                        <p class="description"><?= $fetch_reviews['description']; ?></p>
                    <?php }?>

                    <?php if($fetch_reviews['user_id'] == $user_id){ ?>    
                        <form action="" method="post" class="flex">
                            <input type="hidden" name="delete_id" value="<?= $fetch_reviews['id']; ?>">
                            <a href="./update_review.php?get_id=<?= $fetch_reviews['id']; ?>" 
                            class="inline-option-btn">edit review</a>
                            <input type="submit" name="delete_review" class="inline-delete-btn" 
                            value="delete review" onclick="return confirm('delete this review?');">        
                        </form>
                    <?php } ?>    
            </div>
        <?php 
        }
        }else{
            echo '<p class="empty">no reviews added yet!</p>';
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

