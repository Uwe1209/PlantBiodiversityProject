<!--Description: account delect page-->
<!--Author: Liew You Wee-->
<!--Date: 03/11/2024 -->
<!--Validated: OK-->
<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');

if (isset($_GET['email'])) {
    $email = mysqli_real_escape_string($conn, $_GET['email']);
    $query = "SELECT user_table.*, account_table.* 
        FROM user_table 
        JOIN account_table ON user_table.email = account_table.email 
        WHERE user_table.email = '$email'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    $insert_query = "INSERT INTO user_table_restore (email, first_name, last_name, dob, gender, contact_number, hometown, profile_image, password, type)VALUES ('{$data['email']}', '{$data['first_name']}', '{$data['last_name']}', '{$data['dob']}', '{$data['gender']}', '{$data['contact_number']}', '{$data['hometown']}', '{$data['profile_image']}', '{$data['password']}', '{$data['type']}')";
    mysqli_query($conn, $insert_query);

    $delete_query = "DELETE FROM user_table WHERE email = '$email'";
    $delete_query2 = "DELETE FROM account_table WHERE email = '$email'";
    if (mysqli_query($conn, $delete_query) && mysqli_query($conn, $delete_query2)) {
        header('Location: manage_accounts.php');
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "No user ID provided.";
    exit;
}
?>
