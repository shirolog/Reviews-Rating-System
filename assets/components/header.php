<header class="header">

    <section class="flex">
        <a href="all_posts.php" class="logo">Logo.</a>    

        <nav class="navbar">
            <a href="all_posts.php" class="far fa-eye"></a>
            <a href="login.php" class="fas fa-arrow-right-to-bracket"></a>
            <a href="register.php" class="far fa-registered"></a>
            <?php
            if($user_id != ''){
            ?>
                <div id="user-btn" class="far fa-user" ></div>
            <?php 
            }
            ?>
        </nav>

               <div class="profile">
                <?php 
                if($user_id != ''){

                $select_users = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
                $select_users->execute(array($user_id));
                if($select_users->rowCount() > 0){
                    $fetch_users = $select_users->fetch(PDO::FETCH_ASSOC);
                ?>
                        <?php 
                            if($fetch_users['image'] != ''){
                        ?>
                            <img src="./assets/uploaded_files/<?= $fetch_users['image']; ?>" class="image" alt="">
                        <?php 
                        }
                        ?>
                        <p><?= $fetch_users['name']; ?></p>
    
                    <div class="flex-btn">
                        <a href="update.php" class="btn">update profile</a>
                        <a href="assets/components/logout.php" class="delete-btn"
                        onclick="return confirm('logout from this website?');">logout</a>
                    </div>
                <?php 
                }else{
                ?>
                    <div class="flex-btn">
                        <p>please login or register!</p>
                        <a href="../../login.php" class="inline-option-btn">login</a>
                        <a href="../../register.php" class="inline-option-btn">register</a>
                    </div>
                <?php 
                }
                ?>
               </div>
            <?php 
            }
            ?>
  
    </section>
</header>
