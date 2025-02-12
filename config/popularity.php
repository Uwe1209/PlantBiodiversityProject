<?php
include('config/main.php');

$check_user_data = "SELECT COUNT(*) AS user_count FROM user_table";
$result = mysqli_query($conn, $check_user_data);
$row = mysqli_fetch_assoc($result);

if ($row['user_count'] == 1) {
$user_data = [
    ['john.doe@gmail.com', 'John', 'Doe', '1990-05-15', 'Male', '123456789', 'New York'],
    ['jane.smith@gmail.com', 'Jane', 'Smith', '1985-11-20', 'Female', '098765432', 'Los Angeles'],
    ['mark.brown@gmail.com', 'Mark', 'Brown', '1992-06-12', 'Male', '456123789', 'Chicago'],
    ['emily.white@gmail.com', 'Emily', 'White', '1995-01-30', 'Female', '321654987', 'Houston'],
];

foreach ($user_data as $user) {
    $sql = "INSERT IGNORE INTO user_table (email, first_name, last_name, dob, gender, contact_number, hometown) 
            VALUES ('$user[0]', '$user[1]', '$user[2]', '$user[3]', '$user[4]', '$user[5]', '$user[6]')";
    mysqli_query($conn, $sql);
}
}

$check_account_data = "SELECT COUNT(*) AS account_count FROM account_table";
$result = mysqli_query($conn, $check_account_data);
$row = mysqli_fetch_assoc($result);

if ($row['account_count'] == 1) {
$account_data = [
    ['john.doe@gmail.com', password_hash('!123456789', PASSWORD_DEFAULT), 'user'],
    ['jane.smith@gmail.com', password_hash('!098765432', PASSWORD_DEFAULT), 'user'],
    ['mark.brown@gmail.com', password_hash('!456123789', PASSWORD_DEFAULT), 'user'],
    ['emily.white@gmail.com', password_hash('!321654987', PASSWORD_DEFAULT), 'user'],
];

foreach ($account_data as $account) {
    $sql = "INSERT IGNORE INTO account_table (email, password, type) 
            VALUES ('$account[0]', '$account[1]', '$account[2]')";
    mysqli_query($conn, $sql);
}
}

$check_plant_data = "SELECT COUNT(*) AS plant_count FROM plant_table";
$result = mysqli_query($conn, $check_plant_data);
$row = mysqli_fetch_assoc($result);

if ($row['plant_count'] == 0) {
$plant_data = [
    ['Plantus Scientificus', 'Lungwort', 'family', '', '', '', '', 'plant1.jpg', 'plants_description/plant1.pdf'],
    ['Botanicus Greenicus', 'Jute mallow', 'familt', '', '', 'approve', '', 'palnt2.jpg', 'plants_description/plant2.pdf'],
    ['Amorphophallus Paeoniifolius', 'Araceae', '', 'genus', '', '', 'reject', 'plant3.jpg', 'plants_description/plant3.pdf'],
    ['Herbus Medicus', 'Bulbine', '', '', 'species', 'approve', '', 'plant4.jpg', 'plants_description/plant4.pdf'],
];

foreach ($plant_data as $plant) {
    $sql = "INSERT IGNORE INTO plant_table (scientific_name, common_name, family, genus, species, approve, reject, plants_image, description) 
            VALUES ('$plant[0]', '$plant[1]', '$plant[2]', '$plant[3]', '$plant[4]', '$plant[5]', '$plant[6]', '$plant[7]', '$plant[8]')";
    mysqli_query($conn, $sql);
}
}
?>
