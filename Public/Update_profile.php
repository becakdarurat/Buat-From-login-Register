<?php 

    require_once 'Config.php';
    session_start();
    $user_id = $_SESSION['user_id'];

    if(isset($_POST['update_profile'])){
         
       $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
       $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);

       mysqli_query($conn, "UPDATE `user_form` SET name = '$update_name', email = '$update_email' WHERE id = '$user_id'") or die ('query failed');

       $old_pass = $_POST['old_pass'];
       $update_pass = mysqli_real_escape_string($conn, md5($_POST['update_pass']));
       $new_pass = mysqli_real_escape_string($conn, md5($_POST['new_pass']));
       $confirm_pass = mysqli_real_escape_string($conn, md5($_POST['confirm_pass']));

       if(!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)){
        if($update_pass != $old_pass){
            $message[] = 'password lama tidak cocok!';
        } else if($new_pass != $confirm_pass){
            $message[] = 'password baru tidak cocok!';
        } else {
       mysqli_query($conn, "UPDATE `user_form` SET password = '$confirm_pass', email = '$update_email' WHERE id = '$user_id'") or die ('query failed');
            $message[] = 'Perubahan password Berhasil!';
        }
       }
       
       $update_image = $_FILES["update_image"]["name"];
        $update_image_size = $_FILES["update_image"]["size"];
        $update_image_tmp_name = $_FILES["update_image"]["tmp_name"];
        $update_image_folder = 'Uploaded_img/'.$update_image;

        if(!empty($update_image)){
            if ($update_image_size > 2_000_000){
                $message[] = 'Gambar terlalu besar';
            } else {
            $image_update_query = mysqli_query($conn, "UPDATE `user_form` SET image = '$update_image' WHERE id = '$user_id'") or die ('query failed');
                if($image_update_query){
                    move_uploaded_file($update_image_tmp_name, $update_image_folder);
                }
                $message[] = 'Perubahan gambar Berhasil!';
            }
        }

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    
    <!-- Link file CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    
    <div class="update-profile">

          <?php 
            $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die ('query gagal');
            if(mysqli_num_rows($select) > 0){
                $fetch = mysqli_fetch_assoc($select);
            }
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <?php if($fetch["image"] == ''){
                echo '<img src="image/Gambar_default.jpg">';
            } else {
                echo '<img src="Uploaded_img/'. $fetch['image'] .' ">';
            } 
            if(isset($message)){
                foreach($message as $message){
                echo '<div class="message">'. $message .'</div>';
                }
            } 
            ?>
            <div class="flex">
                <div class="inputBox">
                    <span>username :</span>
                    <input type="text" name="update_name" value="<?= $fetch['name'] ?>" class="box"> 
                    <span>your Email :</span>
                    <input type="email" name="update_email" value="<?= $fetch['email'] ?>" class="box"> 
                    <span>Perbarui Foto anda :</span>
                    <input type="file" name="update_image"  accept="image/jpg, image/jpeg, image/png" class="box">
                </div>
                <div class="inputBox">
                    <input type="hidden" name="old_pass" value="<?= $fetch["password"] ?>">
                    <span>password Lama :</span>
                    <input type="password" name="update_pass" placeholder="Masukkan kata sandi sebelumnya" class="box">
                    <span>password Baru :</span>
                    <input type="password" name="new_pass" placeholder="Masukkan kata sandi baru" class="box">
                    <span>Konfirmasi password :</span>
                    <input type="password" name="confirm_pass" placeholder="Konfirmasi password sebelumnya" class="box">
                </div>
            </div>
            <input type="submit" name="update_profile" value="Update Profile" class="btn">
            <a href="Home.php" class="delete-btn">Kembali</a>
        </form>

    </div>

</body>
</html>