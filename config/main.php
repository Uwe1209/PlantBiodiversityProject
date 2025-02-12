<?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "PlantBiodiversity";

  $connn = mysqli_connect($servername, $username, $password);

  if (!$connn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "CREATE DATABASE IF NOT EXISTS PlantBiodiversity";
  mysqli_query($connn, $sql);
  mysqli_close($connn); 

  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $sql1 = "CREATE TABLE IF NOT EXISTS user_table(
    email VARCHAR(50) NOT NULL PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    dob DATE NULL,
    gender VARCHAR(6) NOT NULL,
    contact_number VARCHAR(15) NULL,
    hometown VARCHAR(50) NOT NULL,
    profile_image VARCHAR(100) NULL
  )";

  $sql2 = "CREATE TABLE IF NOT EXISTS account_table(
    email VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    type VARCHAR(5) NOT NULL,
    FOREIGN KEY (email) REFERENCES user_table(email) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
  )";

  $sql3 = "CREATE TABLE IF NOT EXISTS plant_table(
    id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    scientific_name VARCHAR(50) NOT NULL,
    common_name VARCHAR(50) NOT NULL,
    family VARCHAR(100) NOT NULL,
    genus VARCHAR(100) NOT NULL,
    species VARCHAR(100) NOT NULL,
    approve VARCHAR(100) NOT NULL,
    reject VARCHAR(100) NOT NULL,
    plants_image VARCHAR(100) NULL,
    description VARCHAR(100) NULL
  )";

  $sql4 = "CREATE TABLE IF NOT EXISTS user_table_restore(
    email VARCHAR(50) NOT NULL PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    dob DATE NULL,
    gender VARCHAR(6) NOT NULL,
    contact_number VARCHAR(15) NULL,
    hometown VARCHAR(50) NOT NULL,
    profile_image VARCHAR(100) NULL,
    password VARCHAR(255) NOT NULL,
    type VARCHAR(5) NOT NULL
  )";

  $sql5 = "CREATE TABLE IF NOT EXISTS plant_table_restore(
    id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    scientific_name VARCHAR(50) NOT NULL,
    common_name VARCHAR(50) NOT NULL,
    family VARCHAR(100) NOT NULL,
    genus VARCHAR(100) NOT NULL,
    species VARCHAR(100) NOT NULL,
    approve VARCHAR(100) NOT NULL,
    reject VARCHAR(100) NOT NULL,
    plants_image VARCHAR(100) NULL,
    description VARCHAR(100) NULL
  )";

  mysqli_query($conn, $sql1);
  mysqli_query($conn, $sql2);
  mysqli_query($conn, $sql3);
  mysqli_query($conn, $sql4);
  mysqli_query($conn, $sql5);

  $check_admin = "SELECT * FROM account_table WHERE email='admin@swin.edu.my'";
  $result = mysqli_query($conn, $check_admin);

  if (mysqli_num_rows($result) == 0) {
    $admin_user = "INSERT INTO user_table(email, first_name, last_name, gender, hometown) VALUES('admin@swin.edu.my', 'Admin', 'User', 'N/A', 'N/A')";
    mysqli_query($conn, $admin_user);

    $admin_account = "INSERT INTO account_table(email, password, type) VALUES('admin@swin.edu.my', 'admin', 'admin')";
    mysqli_query($conn, $admin_account);
  }

?>
