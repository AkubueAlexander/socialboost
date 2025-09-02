<?php 

if (isset($_GET['otp']) && isset($_GET['id'])) {
    $otp = $_GET['otp'];
    $id = $_GET['id'];
    include_once 'inc/database.php';
    $query = 'SELECT otp,verifiedStatus FROM user WHERE id = :id LIMIT 1';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id',$id);
    $stmt->execute();
    $row = $stmt->fetch();    
    if ($row) {
        if ($row->otp == $otp) {
        $sql = 'UPDATE user SET verifiedStatus = 1 Where id = :id LIMIT 1';
        $update = $pdo->prepare($sql);        
        $update->bindParam(':id',$id);
        $update->execute();

               
        header('location: login.php');
        }
        else{
            die('something went wrong');
        }     
        
    }    
}
else{
    die('something went wrong');
}

?>