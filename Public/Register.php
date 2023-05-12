<?php

    require_once 'Config.php';
    if(isset($_POST["submit"])){

        $name = mysqli_real_escape_string($conn, $_POST["name"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $pass = mysqli_real_escape_string($conn, md5($_POST["password"]));
        $cpass = mysqli_real_escape_string($conn, md5($_POST["cpassword"]));
        // Mendapatkan value gambar , "name" berisi nama setelah di upload
        $image = $_FILES["image"]["name"];
        // Ukuran dari gambar
        $image_size = $_FILES["image"]["size"];
        // tempat penyimpanan sementara dari nama gambar
        $image_tmp_name = $_FILES["image"]["tmp_name"];
        // tempat gambar di simpan
        $image_folder = 'Uploaded_img/'.$image;

            // Kita mencek email dan password
        $select = mysqli_query($conn ,"SELECT * FROM `user_form` WHERE email = '$email' AND password ='$pass'") or die ('query failed');

            // Jika 1 - 1 , maka sudah ada email dan passwordnya
        if(mysqli_num_rows($select) > 0){
            $message[] = 'pengguna sudah ada';
        } else {
            if($pass != $cpass){
                $message[] = 'konfirmasi kata sandi tidak cocok';
                // jika gambar yang kita masukkan lebih dari 2mb
            } else if ($image_size > 2_000_000){
                $message[] = 'ukuran gambar terlalu besar!';
            } else {
                $insert = mysqli_query($conn, "INSERT INTO `user_form` (name,email,password,image) VALUES ('$name','$email','$pass','$image')") or die('query failed');
                if($insert){
                    move_uploaded_file($image_tmp_name, $image_folder);
                    $message[] = 'Registrasi Berhasil!';
                    header('location:Login.php');
                } else {
                    $message[] = 'Registrasi Gagal!';
                }
            }
        }

    };

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Link file CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    
    <div class="form-container">

        <form action="" method="post" enctype="multipart/form-data">
            <h3>Register now</h3>
            <?php 
            if(isset($message)){
                foreach($message as $message){
                echo '<div class="message">'. $message .'</div>';
                }
            } 
            ?>
            <input type="text" name="name" placeholder="Masukkan Nama" class="box" required>
            <input type="emai" name="email" placeholder="Masukkan Email" class="box" required>
            <input type="password" name="password" placeholder="Masukkan Password" class="box" required>
            <input type="password" name="cpassword" placeholder="Confirm Password" class="box" required>
            <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
            <input type="submit" name="submit" value="register now" class="btn">
            <p>Sudah memiliki akun? <a href="Login.php">login now</a></p>
            </form>

    </div>

</body>
</html>