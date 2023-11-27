<?php 

if(isset($_SESSION['success_msg'])){
    foreach($_SESSION['success_msg'] as $success_msg){
        echo '<script>swal("'.$success_msg.'", "", "success");</script>';
    }
    unset($_SESSION['success_msg']);
}

if(isset($_SESSION['warning_msg'])){
    foreach($_SESSION['warning_msg'] as $warning_msg){
        echo '<script>swal("'.$warning_msg.'", "", "warning");</script>';
    }
    unset($_SESSION['warning_msg']);
}

if(isset($_SESSION['error_msg'])){
    foreach($_SESSION['error_msg'] as $error_msg){
        echo '<script>swal("'.$error_msg.'", "", "error");</script>';
    }
    unset($_SESSION['error_msg']);
}

if(isset($_SESSION['info_msg'])){
    foreach($_SESSION['info_msg'] as $info_msg){
        echo '<script>swal("'.$info_msg.'", "", "info");</script>';
    }
    unset($_SESSION['info_msg']);
}

?>
