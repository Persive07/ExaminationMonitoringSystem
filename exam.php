<?php
session_start();
if (!isset($_SESSION)) {
    echo "Session is not starting!";
}//echo session_id();
/*
 // Display errors on the screen
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);*/

    //Connecting to the Database
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "DA215_Lab4";
    $conn = "";
    $conn = mysqli_connect($db_server,$db_user,$db_pass,$db_name);
    if(!$conn){
       die("Connection to the Database failed due to " . mysqli_connect_error()); //Error Handling
    }

    //Getting Student ID , Exam ID , Profile Pic from Booking ID
    $BookingID=$_SESSION['eBookingID'];
    $sql0="SELECT * FROM Booking_Records WHERE BookingID='$BookingID'";
    $result0=$conn->query($sql0);
    $row0=$result0->fetch_assoc();
    $StudentID=$row0['StudentID'];
    $ExamID=$row0['ExamID'];
    $sql00="SELECT * FROM Student_Exam_Dashboard WHERE BookingID='$BookingID'";
    $result00=$conn->query($sql00);
    $row00=$result00->fetch_assoc();
    $image_path=$row00['PhotoPath'];
    $sql000="SELECT * FROM Student_Records WHERE StudentID='$StudentID'";
    $result000=$conn->query($sql000);
    $row000=$result000->fetch_assoc();

    //Time
    $time = $row00['TotalTime'];
    $remaining = strtotime('00:30:00') - strtotime($time) ;
    if(!(isset($_SESSION['start_time']))){
        $_SESSION['start_time']=time();
        $_SESSION['end_time']=$remaining+time();
        $_SESSION['curr_time']=time();
    }
    $remaining_time=$_SESSION['end_time']-time();
    
    //Easy , Medium , Hard Questions
    $sql01="SELECT * FROM Question_Records 
            WHERE ExamID='$ExamID' AND Difficulty='Easy'";
    $res01=$conn->query($sql01);
    $questions01 = $res01->fetch_all(MYSQLI_ASSOC);

    $sql02="SELECT * FROM Question_Records 
            WHERE ExamID='$ExamID' AND Difficulty='Medium'";
    $res02=$conn->query($sql02);
    $questions02 = $res02->fetch_all(MYSQLI_ASSOC);

    $sql03="SELECT * FROM Question_Records 
            WHERE ExamID='$ExamID' AND Difficulty='Hard'";
    $res03=$conn->query($sql03);
    $questions03 = $res03->fetch_all(MYSQLI_ASSOC);

    if(!(isset($_SESSION['QuestionIndex'])))$_SESSION['QuestionIndex']=$row00['QuestionIndex'];
    if(!(isset($_SESSION['difficultyCounter'])))$_SESSION['difficultyCounter']=$row00['difficultyCounter'];
    if(!(isset($_SESSION['easyCounter'])))$_SESSION['easyCounter']=$row00['easyCounter'];
    if(!(isset($_SESSION['mediumCounter'])))$_SESSION['mediumCounter']=$row00['mediumCounter'];

    //Question Submissionse
    if($_SERVER['REQUEST_METHOD']=='POST'){

        if(isset($_POST['SubQuest'])){
            $_SESSION['QuestionIndex']+=1;
            $QuestionID=$_SESSION['QuestID'];
            $sql1="SELECT * FROM Question_Records WHERE QuestionID='$QuestionID'";
            $res1=$conn->query($sql1);
            $row1=$res1->fetch_assoc();
            $Answer=$_POST['answer'];
            $Score=0;
            $_SESSION['prev_time']=$_SESSION['curr_time'];
            $_SESSION['curr_time']=time();
            $TimeSpent=$_SESSION['curr_time']-$_SESSION['prev_time'];
            if($row1['Answer']==$Answer){
                if($_SESSION['difficultyCounter']=='Easy'){
                    $Score=4;
                    $_SESSION['easyCounter']+=1;
                }else if(($_SESSION['difficultyCounter']=='Medium')){
                    $Score=6;
                    $_SESSION['mediumCounter']+=1;
                }else{
                    $Score=10;
                }
            }else{
                $Score=0;
                if($_SESSION['difficultyCounter']=='Easy'){
                    $Score=-2;
                }else if(($_SESSION['difficultyCounter']=='Medium')){
                    $Score=-1;
                }else{
                    $Score=0;
                }
            }

            $sql2="INSERT INTO Student_Exam_Responses(`BookingID`,`QuestionID`,`Responses`,`TimeSpent`,`Score`)
                    VALUES ('$BookingID','$QuestionID','$Answer','$TimeSpent','$Score')";
            $result2=$conn->query($sql2);
        }

        if(isset($_POST['Pause'])){
            $totalTime=time()-$_SESSION['start_time'];
            $QuestionIndex=$_SESSION['QuestionIndex'];
            $difficultyCounter=$_SESSION['difficultyCounter'];
            $easyCounter=$_SESSION['easyCounter'];
            $mediumCounter=$_SESSION['mediumCounter'];
            $sql5="UPDATE Booking_Records SET ExamStatus='Paused' WHERE BookingID='$BookingID'";
            $conn->query($sql5);
            $sql6="UPDATE Student_Exam_Dashboard SET 
                   TotalTime='$totalTime' , QuestionIndex='$QuestionIndex' , difficultyCounter='$difficultyCounter' , easyCounter='$easyCounter' , mediumCounter='$mediumCounter'
                   WHERE BookingID='$BookingID'";
            $conn->query($sql6);
            unset($_SESSION['QuestionIndex']);
            unset($_SESSION['difficultyCounter']);
            unset($_SESSION['easyCounter']);
            unset($_SESSION['mediumCounter']);
            unset($_SESSION['QuestID']);
            header("Location: stud_home.php");
            exit();
        }

        if(isset($_POST['Quit'])){
            $totalTime=time()-$_SESSION['start_time'];
            $QuestionIndex=$_SESSION['QuestionIndex'];
            $difficultyCounter=$_SESSION['difficultyCounter'];
            $easyCounter=$_SESSION['easyCounter'];
            $mediumCounter=$_SESSION['mediumCounter'];
            $sql7="UPDATE Booking_Records SET ExamStatus='Given' WHERE BookingID='$BookingID'";
            $conn->query($sql7);
            $sql8="UPDATE Student_Exam_Dashboard SET 
                   TotalTime='$totalTime' , QuestionIndex='$QuestionIndex' , difficultyCounter='$difficultyCounter' , easyCounter='$easyCounter' , mediumCounter='$mediumCounter'
                   WHERE BookingID='$BookingID'";
            $conn->query($sql8);
            unset($_SESSION['QuestionIndex']);
            unset($_SESSION['difficultyCounter']);
            unset($_SESSION['easyCounter']);
            unset($_SESSION['mediumCounter']);
            unset($_SESSION['QuestID']);
            $sql93="SELECT SUM(Score) as TotalScore FROM Student_Exam_Responses WHERE BookingID='$BookingID'";
            $result93=$conn->query($sql93);
            $TotalScore=($result93->fetch_assoc())['TotalScore'];
            $sql94="UPDATE Booking_Records SET TotalScore='$TotalScore' WHERE BookingID='$BookingID'";
            $conn->query($sql94);
            header("Location: analysis.php");
            exit();
        }
    }

    //Adaptive Questioning
    if($_SESSION['difficultyCounter']=='Easy'){
        if($_SESSION['easyCounter']==2){
            $_SESSION['difficultyCounter']='Medium';
            $_SESSION['QuestionIndex']=0;
            $row=$questions02[$_SESSION['QuestionIndex']];
        }else{
            if($_SESSION['QuestionIndex']==count($questions01)){
                $_SESSION['difficultyCounter']='Medium';
                $_SESSION['QuestionIndex']=0;
                $row=$questions02[$_SESSION['QuestionIndex']];
            }else{
                $row=$questions01[$_SESSION['QuestionIndex']];
            }
        }
    }else if($_SESSION['difficultyCounter']=='Medium'){
        if($_SESSION['mediumCounter']==2){
            $_SESSION['difficultyCounter']='Hard';
            $_SESSION['QuestionIndex']=0;
            $row=$questions03[$_SESSION['QuestionIndex']];
        }else{
            if($_SESSION['QuestionIndex']==count($questions02)){
                $_SESSION['difficultyCounter']='Hard';
                $_SESSION['QuestionIndex']=0;
                $row=$questions03[$_SESSION['QuestionIndex']];
            }else{
                $row=$questions02[$_SESSION['QuestionIndex']];
            }
        }
    }else{
        if($_SESSION['QuestionIndex']==count($questions03)){
            $totalTime=time()-$_SESSION['start_time'];
            $QuestionIndex=$_SESSION['QuestionIndex'];
            $difficultyCounter=$_SESSION['difficultyCounter'];
            $easyCounter=$_SESSION['easyCounter'];
            $mediumCounter=$_SESSION['mediumCounter'];
            $sql9="UPDATE Booking_Records SET ExamStatus='Given' WHERE BookingID='$BookingID'";
            $conn->query($sql9);
            $sql10="UPDATE Student_Exam_Dashboard SET 
                   TotalTime='$totalTime' , QuestionIndex='$QuestionIndex' , difficultyCounter='$difficultyCounter' , easyCounter='$easyCounter' , mediumCounter='$mediumCounter'
                   WHERE BookingID='$BookingID'";
            $conn->query($sql10);
            unset($_SESSION['QuestionIndex']);
            unset($_SESSION['difficultyCounter']);
            unset($_SESSION['easyCounter']);
            unset($_SESSION['mediumCounter']);
            unset($_SESSION['QuestID']);
            $sql92="SELECT SUM(Score) as TotalScore FROM Student_Exam_Responses WHERE BookingID='$BookingID'";
            $result92=$conn->query($sql92);
            $TotalScore=($result92->fetch_assoc())['TotalScore'];
            $sql9="UPDATE Booking_Records SET TotalScore='$TotalScore' WHERE BookingID='$BookingID'";
            $conn->query($sql9);
            header("Location: analysis.php");
            exit();
        }else{
            $row=$questions03[$_SESSION['QuestionIndex']];
        }
    }
    $_SESSION['QuestID']=$row['QuestionID'];
    
    $sql09="SELECT * FROM Exam_Records WHERE ExamID='$ExamID'";
    $res09=$conn->query($sql09);
    $row09=$res09->fetch_assoc();

    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="exam.css?v=3.0">    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IITG Exam Portal</title>

    <script>
        let remainingTime = <?php echo $remaining_time; ?>;

        function startTimer() {
            let timerDisplay = document.getElementById("timer");
            let QuitForm = document.getElementById("QuitForm");

            function updateTimer() {
                let minutes = Math.floor(remainingTime / 60);
                let seconds = remainingTime % 60;
                timerDisplay.innerHTML = `Time Left: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                
                if (remainingTime <= 0) {
                    clearInterval(countdown);
                    alert("Time is up! Submitting your exam.");
                    QuitForm.submit();  // Auto-submit when time is up
                }
                remainingTime--;
            }

            updateTimer(); // Update immediately
            let countdown = setInterval(updateTimer, 1000); // Update every second
        }

        window.onload = startTimer;
    </script>

</head>
<body>
    <h1 class="heading">IIT Guwahati Exam Portal</h1>
    <h2 style="margin-left:83%;margin-bottom:5px;">Time Remaining</h2>
    <p id="timer"></p>
    <img src="<?php echo $image_path; ?>" alt="Profile Photo" class="profile-photo">
    <div id="intro">
        <?php
            echo "
                <h3>Name : {$row000['FirstName']} {$row000['LastName']} </h3>
                <h3>Student ID : {$StudentID} </h3>
                <h3>Booking ID : {$BookingID} </h3> ";
        ?>
    </div>
    
    <form id="QuitForm" method='post'><input type='submit' class='Quit' name='Quit' value='End'></form>
    
    <div id="quest">
        <?php 
            echo"<h1>{$row09['Name']}</h1><br>";
            echo"
                <form method='post'>
                    <h4 style='font:block 15px black;'>Difficulty : {$_SESSION['difficultyCounter']}</h4><br>

                    <p id='question'>{$row['Question']}</p><br>

                    <input type='radio' name='answer' value='A' required>
                    <label for='html'>{$row['OptionA']}</label><br><br>

                    <input type='radio' name='answer' value='B'>
                    <label for='css'>{$row['OptionB']}</label><br><br>

                    <input type='radio' name='answer' value='C'>
                    <label for='javascript'>{$row['OptionC']}</label><br><br>

                    <input type='radio' name='answer' value='D'>
                    <label for='php'>{$row['OptionD']}</label><br><br>

                    <input type='submit' class='SubQuest' name='SubQuest' value='Submit'>
                    <input type='hidden' name='elapsed_time' id='elapsedTime' value='00:00:00'>
                </form>";
        ?>
        <form style="margin-top:-5%" method='post'><input type='submit' class='Pause' name='Pause' value='Pause'></form>
    </div>  
</body>
</html>