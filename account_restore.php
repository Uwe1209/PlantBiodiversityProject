<!--Description: account restore page-->
<!--Author: Liew You Wee-->
<!--Date: 03/11/2024 -->
<!--Validated: -->
<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');

if (isset($_GET['email'])) {
    $email = mysqli_real_escape_string($conn, $_GET['email']);
    
    $query = "SELECT * FROM user_table_restore WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    $r1 = "INSERT INTO user_table (email, first_name, last_name, dob, gender, contact_number, hometown, profile_image) VALUES ('{$data['email']}', '{$data['first_name']}', '{$data['last_name']}', '{$data['dob']}', '{$data['gender']}', '{$data['contact_number']}', '{$data['hometown']}', '{$data['profile_image']}')";
    mysqli_query($conn, $r1);

    $r2 = "INSERT INTO account_table (email, password, type) VALUES ('{$data['email']}', '{$data['password']}', '{$data['type']}') ON DUPLICATE KEY UPDATE password = '{$data['password']}', type = '{$data['type']}'";
        mysqli_query($conn, $r2);

    $delete_query = "DELETE FROM user_table_restore WHERE email = '$email'";
    if (mysqli_query($conn, $delete_query)) {
        header('Location: account_deleted.php'); 
        exit;
    } else {
        echo "Error restoring record: " . mysqli_error($conn);
    }
} else {
    echo "No user ID provided.";
    exit;
}
?>