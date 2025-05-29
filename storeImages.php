<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "DA215_Lab4";
    $conn = "";
    $conn = mysqli_connect($db_server,$db_user,$db_pass,$db_name);
    if(!$conn){
        die("Connection to the Database failed due to " . mysqli_connect_error()); //Error Handling
    }

    // Folder where images are stored
    $folder = "images/"; 
    $files = glob($folder . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

    foreach ($files as $file) {
        $image_path = $conn->real_escape_string($file);
        // Insert into database
        $sql = "INSERT INTO Images (Image_path) VALUES ('$image_path')";
        $conn->query($sql);
    }
    $conn->close();
?>