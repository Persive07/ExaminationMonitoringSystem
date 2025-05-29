<?php
session_start();
if (!isset($_SESSION)) {
    echo "Session is not starting!";
}//echo session_id();

 // Display errors on the screen
 /*error_reporting(E_ALL);
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

    $sql1="SELECT * FROM Student_Exam_Responses WHERE BookingID='$BookingID'
           ORDER BY `TimeStamp` ASC";
    $result1=$conn->query($sql1);

    $sql2="SELECT SUM(Score) as TotalScore FROM Student_Exam_Responses WHERE BookingID='$BookingID'";
    $result2=$conn->query($sql2);
    $TotalScore=($result2->fetch_assoc())['TotalScore'];
    $sql40="UPDATE Student_Exam_Dashboard SET TotalScore='$TotalScore' WHERE BookingID='$BookingID'";
    $conn->query($sql40);

    $sql4="SELECT * FROM Booking_Records WHERE ExamID='$ExamID' ORDER BY TotalScore DESC";
    $result4=$conn->query($sql4);
    $row=$result4->fetch_all(MYSQLI_ASSOC);;
    $TotStud=count($row);
    $result4=$conn->query($sql4);
    $index=0;
    while($row4=$result4->fetch_assoc()){
        if($row4['BookingID']!=$BookingID){
            $index+=1;
        }else{
            break;
        }
    }
    $percentile=round(100*($TotStud-$index)/($TotStud),2);

    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['Quit'])){
            unset($_SESSION['eBookingID']);
            header("Location: stud_home.php");
            exit();
        }
    }

    $sql09="SELECT * FROM Exam_Records WHERE ExamID='$ExamID'";
    $res09=$conn->query($sql09);
    $row09=$res09->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="analysis.css?v=1.0">    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IITG Exam Portal</title>
</head>
<body>
    <h1 class="heading">IIT Guwahati Exam Portal</h1>
    <img src="<?php echo $image_path; ?>" alt="Profile Photo" class="profile-photo">
    <div id="intro">
        <?php
            echo "
                <h3>Name : {$row000['FirstName']} {$row000['LastName']} </h3>
                <h3>Student ID : {$StudentID} </h3>
                <h3>Booking ID : {$BookingID} </h3> ";
        ?>
    </div>
    <form id="exit" method='post'><input type='submit' class='Quit' name='Quit' value='Exit'></form>
    <div id="quest">
        <?php 
            echo"<h1>{$row09['Name']}</h1><br>";
            echo "<span style='font-size: 18px; font-weight: bold; color: black;'>Total Score</span> : {$TotalScore}";
            echo "<span style='font-size: 18px; font-weight: bold; color: black;margin-left:70%;'>Percentile</span> : {$percentile}%<br><br>";
            while($row1=$result1->fetch_assoc()){
                    $QuestionID = $row1['QuestionID'];
                    $sql11 = "SELECT * FROM Question_Records WHERE QuestionID='$QuestionID'";
                    $result11 = $conn->query($sql11);
                    $row11 = $result11->fetch_assoc();
                    
                    $chosenAnswer = $row1['Responses'];
                    $correctAnswer = $row11['Answer'];
                    
                    // Set the background color based on correctness
                    $bgColor = ($chosenAnswer == $correctAnswer) ? 'correct' : 'incorrect';
                
                    echo "
                        <div class='question-box {$bgColor}'>
                            <p class='question-text'>{$row11['Question']}</p>
                            <p>A) {$row11['OptionA']}</p>
                            <p>B) {$row11['OptionB']}</p>
                            <p>C) {$row11['OptionC']}</p>
                            <p>D) {$row11['OptionD']}</p>
                            <p><strong>Chosen Answer:</strong> {$chosenAnswer}</p>
                            <p><strong>Correct Answer:</strong> {$correctAnswer}</p>
                            <p><strong>Difficulty:</strong> {$row11['Difficulty']}</p>";
                            $timeSpent = $row1['TimeSpent'];
                            list($hours, $minutes, $seconds) = explode(":", $timeSpent);
                            $hours = (int)$hours;
                            $minutes = (int)$minutes;
                            $seconds = (int)$seconds;
                            $formattedTime = "";
                            if ($hours > 0) {
                                $formattedTime .= $hours . "h ";
                            }
                            if ($minutes > 0) {
                                $formattedTime .= $minutes . "m ";
                            }
                            if ($seconds > 0 || $formattedTime === "") {
                                $formattedTime .= $seconds . "s";
                            }
                            echo "<p><strong>Time Spent:</strong> $formattedTime</p>
                            <p><strong>Score :</strong> {$row1['Score']}</p>
                        </div>
                    ";
                
            }
            $sql3="SELECT 
                   t1.Topic , 
                   SUM(CASE WHEN t1.Answer=t2.Responses THEN 1 ELSE 0 END) as Correct_Count , 
                   SUM(CASE WHEN t1.Answer!=t2.Responses THEN 1 ELSE 0 END) as Wrong_Count 
                   FROM Question_Records as t1 JOIN Student_Exam_Responses as t2
                   ON t1.QuestionID=t2.QuestionID
                   WHERE t2.BookingID='$BookingID'
                   GROUP BY t1.Topic";
            $result3=$conn->query($sql3);
            $StrongTopics=[];
            $WeakTopics=[];
            while($row3=$result3->fetch_assoc()){
                if($row3['Correct_Count']>=$row3['Wrong_Count']){
                    $StrongTopics[]=$row3['Topic'];
                }else{
                    $WeakTopics[]=$row3['Topic'];
                }
            }
            echo "<div class='topics-container'>";
            echo "<div class='strong-topics'><strong>Strong Topics:</strong> ";
            if(sizeof($StrongTopics)>0){
                foreach ($StrongTopics as $topic) {
                    echo "<span class='topic strong'>$topic</span> ";
                }
            }else{
                echo "None";
            }
            echo "</div>";

            echo "<div class='weak-topics'><strong>Weak Topics:</strong> ";
            if(sizeof($WeakTopics)>0){
                foreach ($WeakTopics as $topic) {
                    echo "<span class='topic weak'>$topic</span> ";
                }
            }else{
                echo "None";
            }
            echo "</div>";
            echo "</div>";
            $conn->close();
        ?>
    </div> 
</body>
</html>