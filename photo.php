<?php
    // Connecting SQL Database with PHP
    session_start();
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "DA215_Lab4";
    $conn = "";
    $conn = mysqli_connect($db_server,$db_user,$db_pass,$db_name);
    if(!$conn){
       die("Connection to the Database failed due to " . mysqli_connect_error()); //Error Handling
    }
    
    if(!(isset($_SESSION['ClickCounter'])))$_SESSION['ClickCounter']=0;

    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['exit'])){
            unset($_SESSION['ClickCounter']);
            header("Location: stud_home.php");
            exit();
        }
        $status=0;
        if(isset($_POST['next'])){
            if($_SESSION['ClickCounter']>0){
                unset($_SESSION['ClickCounter']);
                $BookingID=$_SESSION['eBookingID'];
                $PhotoPath=$_SESSION['PhotoPath'];
                unset($_SESSION['PhotoPath']);
                $sql="INSERT INTO Student_Exam_Dashboard(`BookingID`,`PhotoPath`)
                      VALUES ('$BookingID','$PhotoPath')";
                $result=$conn->query($sql);
                header("Location: exam.php");
                exit();
            }else{
                $status=-1;
            }
        }
        
        if(isset($_POST['button'])){
            $_SESSION['ClickCounter']=1;
            // Fetch ONE random image
            $sql = "SELECT image_path FROM images ORDER BY RAND() LIMIT 1";  
            $result = $conn->query($sql);
            // Check if an image was found
            $image_path = ($result->num_rows > 0) ? $result->fetch_assoc()['image_path'] : "default.jpg"; 
            $_SESSION['PhotoPath']=$image_path;
        }
    }

    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="photo.css?v=1.0">    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IITG Exam Portal</title>
</head>
<body>
    <h1 class="heading">IIT Guwahati Exam Portal</h1>
    <div id="home" class="tab-content active">
        <h1>Capture a Photo</h1>
        <?php if($status==-1)echo "<p style='font-style:oblique; color:red; text-align:center;'>Click to Generate a Random Image</p>";?>
        <img src="<?php echo $image_path; ?>" alt="Profile Photo" class="profile-photo">
        <form method="post"><input type="submit" class="button" name="button" value="Generate Random Image"></form>
        <form method="post"><input type="submit" class="exit" name="exit" value="Exit"></form>
        <form method="post"><input type="submit" class="next" name="next" value="Next"></form>
    </div>
</body>
</html>