<!--Description: plant restore page-->
<!--Author: Liew You Wee-->
<!--Date: 20/11/2024 -->
<!--Validated: -->
<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');

if (isset($_GET['id'])) {
    $plant = mysqli_real_escape_string($conn, $_GET['id']);
    
    $query = "SELECT * FROM plant_table_restore WHERE id = '$plant'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    $r1 = "INSERT INTO plant_table (scientific_name, common_name, family, genus, species, approve, reject, plants_image, description) VALUES ('{$data['scientific_name']}', '{$data['common_name']}', '{$data['family']}', '{$data['genus']}', '{$data['species']}', '{$data['approve']}', '{$data['reject']}', '{$data['plants_image']}', '{$data['description']}')";
    mysqli_query($conn, $r1);

    $delete_query = "DELETE FROM plant_table_restore WHERE id = '$plant'";
    if (mysqli_query($conn, $delete_query)) {
        header('Location: plant_deleted.php'); 
        exit;
    } else {
        echo "Error restoring record: " . mysqli_error($conn);
    }
} else {
    echo "No user ID provided.";
    exit;
}
?>