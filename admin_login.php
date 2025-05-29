<?php
    //Connecting SQL Database with PHP
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

    //For updating HTML
    if(!isset($_SESSION['AdminStatus'])){
        $status=0;
    }else{
        $status=$_SESSION['AdminStatus'];
        unset($_SESSION['AdminStatus']);
    }

    if($_SERVER['REQUEST_METHOD']=='POST'){
        
        if(isset($_POST['Login'])){
            //Checking if the Username exists
            $Username=$_POST['Username'];
            $sql1="SELECT *
                   FROM `Admin`
                   WHERE BINARY Username='$Username'";   //Binary Keyword is used to enable case sensitivity
            $result1 = $conn->query($sql1);
            
            if($result1->num_rows==1){
                //Checking if the Password matches
                $Password=$_POST['Password'];
                $row = $result1->fetch_assoc();
                if($Password==$row["Password"]){
                    $_SESSION['AdminUsername'] = $Username;
                    header("Location: admin_home.php");
                    exit();
                }else{
                    $status=1; //echo "The Password Entered is wrong";
                }
            } else{
                $status=2; //echo "The Username does not exist";
            }
        }     
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="index.css?v=1.0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Portal</title>
</head>
<body>
    <div class="logdiv">
        <h1>IITG Exam Portal</h1>
        <h2 style="text-align:center;">Admin Login</h2>
        <?php
            if($status==0){
                echo "<p style='font-style:oblique; color:grey; text-align:center;'>Fill in the Details</p>";
            }else if($status==1){
                echo "<p style='font-style:oblique; color:red; text-align:center;'>The Password entered is Wrong</p>";
            }else if($status==2){
                echo "<p style='font-style:oblique; color:red; text-align:center;'>The Username does not Exist</p>";
            }else if($status==3){
                echo "<p style='font-style:oblique; color:green; text-align:center;'>Successfully Registered</p>";
            }
        ?>
        <form action="" method="post">
            <input type="text" name="Username" placeholder="Username ( Admin )" required value="<?php if($status==1)echo isset($_POST['Username']) ? htmlspecialchars($_POST['Username']) : ''; ?>"><br> 
            <input type="password" name="Password" placeholder="Password ( Admin )" required><br> <br>
            <input type="submit" id="login_button" name="Login" value="Login"><br> 
            <p class="note">Student ? <a href="index.php" class="link">Student Portal</a> <br><br>
            Professor ?  <a href="prof_login.php" class="link">Professor Portal</a>  </p>
        </form>
    </div>
</body>
</html>