<?php

try{

$db_name = 'mysql:host=localhost;dbname=review_db';
$password= '';
$user_name= 'root';

$conn = new PDO($db_name, $user_name, $password);

function create_unique_id(){
    $charecters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz0123456789';
    $charecters_length = strlen($charecters);
    $random_str = '';
    for($i= 0; $i < 20; $i++){
        $random_str .= $charecters[mt_rand(0, $charecters_length - 1)];
    }
    return $random_str;
}

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
}

}catch(PDOException $e){
    echo 'Connection faild!'. $e->getMessage();
}

?>