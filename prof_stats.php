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

    $ExamID=$_SESSION['aExamID'];
    $ProfID=$_SESSION['ProfID'];

    $sql0="SELECT * FROM Exam_Records WHERE ExamID='$ExamID'";
    $res0=$conn->query($sql0);
    $row0=$res0->fetch_assoc();
    $ExamName=$row0['Name'];

    $sql00="SELECT * FROM Prof_Records WHERE ProfID='$ProfID'";
    $res00=$conn->query($sql00);
    $row00=$res00->fetch_assoc();

    $sql1="SELECT * FROM Question_Records WHERE ExamID='$ExamID'";
    $res1=$conn->query($sql1);

    $sql2="SELECT AVG(t1.TotalScore) as Avg_Total_Score , SEC_TO_TIME(AVG(t1.TotalTime)) as Avg_Time_Spent
           FROM Student_Exam_Dashboard as t1 JOIN
           Booking_Records as t2
           ON t1.BookingID=t2.BookingID
           WHERE t2.ExamID='$ExamID'";
    $res2=$conn->query($sql2);
    $row2=$res2->fetch_assoc();
    $AvgTotalScore=round($row2['Avg_Total_Score'],2);
    $AvgTimeSpent=$row2['Avg_Time_Spent'];
    $timeSpent = $AvgTimeSpent;
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
    $AvgTimeSpent=$formattedTime;

    $sql8="SELECT COUNT(*) as TotStudents 
           FROM Student_Exam_Dashboard as t1 JOIN
           Booking_Records as t2
           ON t1.BookingID=t2.BookingID
           WHERE ExamID='$ExamID'";
    $res8=$conn->query($sql8);
    $row8=$res8->fetch_assoc();
    $TotStudents=$row8['TotStudents'];

    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['Quit'])){
            unset($_SESSION['aExamID']);
            header("Location: prof_home.php");
            exit();
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="prof_stats.css?v=3.0">    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IITG Exam Portal</title>
</head>
<body>
    <h1 class="heading">IIT Guwahati Exam Portal</h1>
    <div id="intro">
        <?php
            echo "
                <h3>Name : {$row00['FirstName']} {$row00['LastName']} </h3>
                <h3>Professor ID : {$ProfID} </h3>
                <h3>Exam ID : {$ExamID} </h3> 
                <h3>No. of Students : {$TotStudents}</h3>";
        ?>
    </div>
    <form id="exit" method='post'><input type='submit' class='Quit' name='Quit' value='Exit'></form>
    <div id="quest">
        <?php 
            echo"<h1>{$ExamName}</h1><br>";
            echo "<span style='font-size: 18px; font-weight: bold; color: black;'>Average Total Score: </span>{$AvgTotalScore}";
            echo "<span style='font-size: 18px; font-weight: bold; color: black;margin-left:55%;'>Average Time Spent: </span>{$AvgTimeSpent}<br><br>";
           while($row1=$res1->fetch_assoc()){
                   $QuestionID = $row1['QuestionID'];
                    $sql11 = "SELECT * FROM Question_Records WHERE QuestionID='$QuestionID'";
                    $result11 = $conn->query($sql11);
                    $row11 = $result11->fetch_assoc();
                    $correctAnswer = $row11['Answer'];
                    $sql5="SELECT 
                           SUM(CASE WHEN t1.Answer=t2.Responses THEN 1 ELSE 0 END) AS Correct_Responses,
                           SUM(CASE WHEN t1.Answer!=t2.Responses THEN 1 ELSE 0 END) AS Incorrect_Responses
                           FROM Question_Records as t1 JOIN Student_Exam_Responses as t2
                           ON t1.QuestionID=t2.QuestionID
                           WHERE t1.QuestionID='$QuestionID'";
                    $res5=$conn->query($sql5);
                    $row5=$res5->fetch_assoc();
                    $Correct_Responses=0;if(isset($row5['Correct_Responses']))$Correct_Responses=$row5['Correct_Responses'];
                    $Incorrect_Responses=0;if(isset($row5['Incorrect_Responses']))$Incorrect_Responses=$row5['Incorrect_Responses'];
                    $highlightClass = ($Incorrect_Responses > $Correct_Responses) ? 'low-performance' : 'high-performance';
                
                    echo "
                        <div class='question-box $highlightClass'>
                            <p class='question-text'>{$row11['Question']}</p><br>
                            <p>A) {$row11['OptionA']}</p>
                            <p>B) {$row11['OptionB']}</p>
                            <p>C) {$row11['OptionC']}</p>
                            <p>D) {$row11['OptionD']}</p><br>
                            <p><strong>Correct Answer:</strong> {$correctAnswer}</p>
                            <p><strong>Topic:</strong> {$row11['Topic']}</p>
                            <p><strong>Difficulty:</strong> {$row11['Difficulty']}</p>";
                            $sql7="SELECT SEC_TO_TIME(AVG(TimeSpent)) as Avg_Time,AVG(Score) as Avg_Score FROM Student_Exam_Responses WHERE QuestionID='$QuestionID'";
                            $res7=$conn->query($sql7);
                            $row7=$res7->fetch_assoc();
                            $AvgTime=$row7['Avg_Time'];
                            $AvgScore=round($row7['Avg_Score'],2);
                            $timeSpent = $AvgTime;
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
                            $AvgTime=$formattedTime;
                        
                        echo" <p><strong>Avg Time:</strong> {$AvgTime}</p>
                        <p><strong>Avg Score:</strong> {$AvgScore}</p>
                        <p><strong>Correct Responses:</strong> {$Correct_Responses}</p>
                        <p><strong>Incorrect Responses:</strong> {$Incorrect_Responses}</p>
                        </div>";
            }
            $sql3="SELECT 
                   t1.Topic , 
                   SUM(CASE WHEN t1.Answer=t2.Responses THEN 1 ELSE 0 END) as Correct_Count , 
                   SUM(CASE WHEN t1.Answer!=t2.Responses THEN 1 ELSE 0 END) as Wrong_Count 
                   FROM Question_Records as t1 JOIN Student_Exam_Responses as t2
                   ON t1.QuestionID=t2.QuestionID
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