<?php 

    require_once 'Config.php';
    session_start();
    $user_id = $_SESSION['user_id'];

    if(!isset($user_id)){
        header('location:Login.php');
    };

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    
    <!-- Link file CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    
    <div class="container">
        <?php 
            $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die ('query gagal');
            if(mysqli_num_rows($select) > 0){
                $fetch = mysqli_fetch_assoc($select);
            }
        ?>
        <h3><?= $fetch['name'] ?></h3>
        <a href="Update_profile.php" class="btn">update profil</a>
        <a href="home.php?logout=<?= $user_id ?>" class="delete-btn">logout</a>
        <p>new <a href="Login.php">Login</a> or <a href="Register.php">register</a></p>
    </div>

</body>
</html>