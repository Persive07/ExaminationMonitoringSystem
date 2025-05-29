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

    $status=0; // For updating HTML code

    if($_SERVER['REQUEST_METHOD']=='POST'){

        if(isset($_POST['Form'])){
            //Checking if the student has already been registered
            $StudentID=$_POST['StudentID'];
            $sql1="SELECT *
                   FROM Student_Records
                   WHERE StudentID='$StudentID'";
            $result1 = $conn->query($sql1);

            if($result1->num_rows==0){
                //Checking if the Username is taken
                $Username=$_POST['Username'];
                $sql2="SELECT *
                       FROM Student_Records
                       WHERE Username='$Username'";
                $result2 = $conn->query($sql2);

                if($result2->num_rows==0){
                    //Checking if the Passwords match
                    $Password = password_hash($_POST['Password'], PASSWORD_BCRYPT);  //Hashing the Password
                    $ConfirmPassword =$_POST['ConfirmPassword'];

                    if(password_verify($ConfirmPassword, $Password)){
                        $FirstName=$_POST['FirstName'];
                        $LastName=$_POST['LastName'];
                        $Email=$_POST['Email'];
                        $PhoneNo=$_POST['PhoneNo'];
                        $sql3="INSERT INTO `Student_Records`(`StudentID`,`FirstName`,`LastName`,`PhoneNo`,`Email`,`Username`,`Password`)
                               VALUES ('$StudentID','$FirstName','$LastName','$PhoneNo','$Email','$Username','$Password')";
                        $result3=$conn->query($sql3);
                        $status=3; //echo "Successfully Registered";
                        $_SESSION['StudStatus']=$status;
                        header("Location: index.php");
                        exit();
                    }else{
                        $status=1; //echo "The Passwords do not match";
                    }
                } else {
                    $status=2; //echo "Username has already been taken";
                }
            } else{
                $status=3; //echo "The Student has already been registered before";
            }
        }
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="index.css?v=3.0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="regdiv">
        <h1>IITG Exam Portal</h1>
        <h2 style="text-align:center;">Student Registration</h2>
        <?php
            if($status==0){
                echo "<p style='font-style:oblique; color:grey; text-align:center;'>Fill in the required details</p>";
            }else if($status==1){
                echo "<p style='font-style:oblique; color:red; text-align:center;'>The Passwords do not match</p>";
            }else if($status==2){
                echo "<p style='font-style:oblique; color:red; text-align:center;'>The Username has already been taken</p>";
            }else if($status==3){
                echo "<p style='font-style:oblique; color:red; text-align:center;'>The student has already been registered before</p>";
            }
        ?>
        <form action="" method="post">
            <input type="text" name="FirstName" placeholder="First Name" required value="<?php if($status==2 || $status==3)echo isset($_POST['FirstName']) ? htmlspecialchars($_POST['FirstName']) : ''; ?>" >
            <input type="text" name="LastName" placeholder="Last Name" required value="<?php if($status==2 || $status==3)echo isset($_POST['LastName']) ? htmlspecialchars($_POST['LastName']) : ''; ?>">
            <input type="text" name="StudentID" placeholder="Student ID" required value="<?php if($status==2 || $status==3)echo isset($_POST['StudentID']) ? htmlspecialchars($_POST['StudentID']) : ''; ?>">
            <input type="email" name="Email" placeholder="Email ID" required value="<?php if($status==2 || $status==3)echo isset($_POST['Email']) ? htmlspecialchars($_POST['Email']) : ''; ?>">
            <input type="tele" name="PhoneNo" placeholder="Phone Number" required value="<?php if($status==2 || $status==3)echo isset($_POST['PhoneNo']) ? htmlspecialchars($_POST['PhoneNo']) : ''; ?>">
            <input type="text" name="Username" placeholder="Username" required value="<?php if($status==2)echo isset($_POST['Username']) ? htmlspecialchars($_POST['Username']) : ''; ?>">
            <input type="password" name="Password" placeholder="Password" required>
            <input type="password" name="ConfirmPassword" placeholder="Confirm Password" required>
            <input type="submit" id="login_button" value="Register" name="Form">
            <p class="note">Already have an account ? <a href="index.php" class="link">Login</a> </p>
        </form>
    </div>
</body>
</html>