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

    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "DA215_Lab4";
    $conn = "";
    $conn = mysqli_connect($db_server,$db_user,$db_pass,$db_name);
    if(!$conn){
       die("Connection to the Database failed due to " . mysqli_connect_error()); //Error Handling
    }

    //Checking if the User Logged in
    if (!isset($_SESSION['StudUsername'])) {
        header("Location: index.php"); // Redirect to the login page if not logged in
        exit();
    }
    
    //Home Page
    $Username=$_SESSION['StudUsername'];
    $sql1="SELECT *
           FROM Student_Records
           WHERE BINARY Username='$Username'";
    $result1=$conn->query($sql1);
    $row1=$result1->fetch_assoc();
    $StudentID=$row1["StudentID"];
    $_SESSION['StudentID']=$StudentID;

    //Upcoming Exams Table
    $sql11="SELECT * FROM Booking_Records as t1
            JOIN Exam_Records as t2 ON t1.ExamID=t2.ExamID
            JOIN Slot_Records as t3 ON t1.SlotID=t3.SlotID
            JOIN Prof_Records as t4 ON t2.ProfID=t4.ProfID
            WHERE t1.StudentID='$StudentID' AND (t1.ExamStatus='Not Given' OR t1.ExamStatus='Paused')
            ORDER BY STR_TO_DATE(CONCAT(`Date`, ' ', `StartTime`), '%Y-%m-%d %H:%i:%s') ASC";
    $result11=$conn->query($sql11);

    //Registered Exams Table
    $sql111="SELECT * FROM Booking_Records as t1
            JOIN Exam_Records as t2 ON t1.ExamID=t2.ExamID
            JOIN Slot_Records as t3 ON t1.SlotID=t3.SlotID
            JOIN Prof_Records as t4 ON t2.ProfID=t4.ProfID
            WHERE t1.StudentID='$StudentID' AND t1.ExamStatus='Given'
            ORDER BY STR_TO_DATE(CONCAT(`Date`, ' ', `StartTime`), '%Y-%m-%d %H:%i:%s') ASC";
    $result111=$conn->query($sql111);

    //Exams Page
    $sql2="SELECT * 
           FROM Exam_Records as t1 JOIN Prof_Records as t2
           ON t1.ProfID=t2.ProfID";
    $result2=$conn->query($sql2);

    //Form Submissions
    if($_SERVER['REQUEST_METHOD']=='POST'){
        //Booking Exam
        $bstatus=0;
        if(isset($_POST['BookExamNext'])){
            $ExamID=$_POST['ExamID'];
            $sql21="SELECT * FROM Exam_Records WHERE ExamID='$ExamID'";
            $result21=$conn->query($sql21);
            if($result21->num_rows==1){
                $sql222="SELECT * FROM Booking_Records WHERE ExamID='$ExamID' AND StudentID='$StudentID'";
                $result222=$conn->query($sql222);
                if($result222->num_rows==0){
                    $_SESSION['BookExamID']=$ExamID;
                    $bstatus=1;
                }else{
                    $bstatus=-2;
                }
            }else{
                $bstatus=-1;
            }
        }

        //Booking Exam -> Choosing Slot
        $chstatus=0;
        if(isset($_POST['ChooseSlot'])){
            $ExamID=$_SESSION['BookExamID'];
            $SlotID=$_POST['SlotID'];
            $sql40="SELECT * 
                    FROM Exam_Slots 
                    WHERE SlotID='$SlotID' AND ExamID='$ExamID'";
            $result40=$conn->query($sql40);
            if($result40->num_rows>0){
                $_SESSION['BookSlotID']=$SlotID;
                header("Location: fees.php");
                exit();
            }else{
                $chstatus=-1;
            }
        }

        //Rescheduling Exam
        $resh1=0;
        if(isset($_POST['Reshed1'])){
            $ExamID=$_POST['ExamID'];
            $sql50="SELECT * FROM Booking_Records 
                    WHERE ExamID='$ExamID' AND StudentID='$StudentID'";
            $result50=$conn->query($sql50);
            if($result50->num_rows>0){
                $_SESSION['reExamID']=$ExamID;
                $resh1=1;
            }else{
                $resh1=-1;
            }
        }

        //Rescheduling Exam -> Choosing Slot
        $resh2=0;
        if(isset($_POST['Reshed2'])){
            $ExamID=$_SESSION['reExamID'];
            $SlotID=$_POST['SlotID'];
            $sql51="SELECT * FROM Exam_Slots
                    WHERE ExamID='$ExamID' AND SlotID='$SlotID'";
            $result51=$conn->query($sql51);
            if($result51->num_rows>0){
                $sql53="SELECT * FROM Booking_Records WHERE StudentID='$StudentID' AND ExamID='$ExamID'";
                $result53=$conn->query($sql53);
                $BookingID=($result53->fetch_assoc())['BookingID'];
                $sql52="UPDATE Booking_Records 
                        SET SlotID='$SlotID'
                        WHERE BookingID='$BookingID'";
                $result52=$conn->query($sql52);
                $resh2=1;
            }else{
                $resh2=-1;
            }
        }
        
        //Give Exam
        if(isset($_POST['gExam'])){
            if($_SESSION['ExamStatus']=='Not Given'){
                header("Location: photo.php");
                exit();
            }else{
                header("Location: exam.php");
                exit();
            }
            
        }

        if(isset($_POST['Analysis'])){
            $_SESSION['eBookingID']=$_POST['BookingID'];
            header("Location: analysis.php");
            exit();
        }
    }

    //Booking Exam -> Slot Table
    if(isset($_SESSION['BookExamID'])){
        $ExamID=$_SESSION['BookExamID'];
        $sql3="SELECT * FROM Exam_Slots as t1 JOIN Slot_Records as t2 
                ON t1.SlotID=t2.SlotID
                WHERE t1.ExamID='$ExamID'";
        $result3=$conn->query($sql3);
    }

    //Rescheduling -> Slot Table
    if(isset($_SESSION['reExamID'])){
        $ExamID=$_SESSION['reExamID'];
        $sql333="SELECT * FROM Exam_Slots as t1 JOIN Slot_Records as t2 
                ON t1.SlotID=t2.SlotID
                WHERE t1.ExamID='$ExamID'";
        $result333=$conn->query($sql333);
    }

    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="stud_home.css?v=3.0">    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IITG Exam Portal</title>
</head>
<body>
    <h1 class="heading">IIT Guwahati Exam Portal</h1>
    <nav>
        <a onclick="showTab('home')">Home</a>
        <a onclick="showTab('exam')">Exams</a>
        <a onclick="showTab('book')">Book Exam</a>
        <a onclick="showTab('reschedule')">Reschedule</a>
        <a class="logout" href="logout.php">Logout</a>
    </nav>

    <div id="home" class="tab-content active">
        <br>
        <span style="font:22px bold;margin-left:00px;">Student Information</span><br>
        <span style="font:16px bold;margin-left:00px;">Name : </span><?php echo"{$row1["FirstName"]} {$row1["LastName"]}";?><br>
        <span style="font:16px bold;margin-left:00px;">Student ID : </span><?php echo"{$row1["StudentID"]}";?><br><br>
        <div style="margin-bottom:-125px;"><span style="font:16px bold;margin-left:65px;"></span></div>

        <table>
            <?php
                $row11=$result11->fetch_assoc();
                $_SESSION['ExamStatus']=$row11['ExamStatus'];
                if($result11->num_rows==0){
                    echo "<caption>Upcoming Exam</caption>";
                }else if($row11['ExamStatus']=='Paused'){
                    echo "<caption>In Progress</caption>";
                }else{
                    echo "<caption>Upcoming Exam</caption>";
                }
            ?>
            <tr>
                <th>Exam ID</th>
                <th>Exam Name</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>Duration</th>
                <th>Professor</th>
                <th>Email ID</th>
            </tr>
            <?php
                if($result11->num_rows>0){
                    $_SESSION['eBookingID']=$row11['BookingID'];
                        echo"<tr>
                             <td>{$row11['ExamID']}</td>
                             <td>{$row11['Name']}</td>
                             <td>{$row11['Date']}</td>
                             <td>{$row11['StartTime']}</td>
                             <td>{$row11['Duration']}</td>
                             <td>{$row11['FirstName']} {$row11['LastName']}</td>
                             <td>{$row11['Email']}</td>
                             </tr>";
                }
            ?>
        </table>
        <?php 
            if($result11->num_rows==0){
                echo"<p style='font: 18px bold; text-align:center;'>No Exams Found</p>";
            }else{
                if($row11['ExamStatus']=='Not Given'){
                    echo"<form method='post'><input type='submit' class='gExam' name='gExam' value='Start Exam'></form>";
                } else if($row11['ExamStatus']=='Paused'){
                    echo"<form method='post'><input type='submit' class='resExam' name='gExam' value='Resume Exam'></form>";
                }
               
            } 
            if($result111->num_rows!=0){
                echo"
                <div style='margin-top:75px;margin-bottom:-100px;'><span style='font:16px bold;margin-left:65px;'>Exams Registered : </span>{$row1['ExamsRegistered']}</div>
                <table>
                    <caption>Registered Exams</caption>
                    <tr>
                        <th>Exam ID</th>
                        <th>Exam Name</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>Duration</th>
                        <th>Professor</th>
                        <th>Email ID</th>
                        <th>Status</th>
                        <th>Analysis</th>
                    </tr>";
                    if($result111->num_rows>0){
                        while($row111=$result111->fetch_assoc()){
                            $aBookingID=$row111['BookingID'];
                            echo"<tr>
                                <td>{$row111['ExamID']}</td>
                                <td>{$row111['Name']}</td>
                                <td>{$row111['Date']}</td>
                                <td>{$row111['StartTime']}</td>
                                <td>{$row111['Duration']}</td>
                                <td>{$row111['FirstName']} {$row111['LastName']}</td>
                                <td>{$row111['Email']}</td>
                                <td>{$row111['ExamStatus']}</td>
                                <td>
                                    <form method='post'>
                                        <input type='hidden' name='BookingID' value={$aBookingID}>
                                        <input type='submit' class='Analysis' name='Analysis' value='Analysis'>
                                    </form>
                                </td>
                                </tr>";
                        }
                    }  
                echo"</table>";
            }
        ?>
    </div>

    <div id="exam" class="tab-content">
        <table>
            <caption>Available Exams</caption>
            <tr>
                <th>Exam ID</th>
                <th>Exam Name</th>
                <th>Professor Name</th>
                <th>Department</th>
                <th>Email ID</th>
                <th>Fees</th>
                <th>Students Registered</th>
                <th>Available Slots</th>
            </tr>
            <?php
                if($result2->num_rows>0){
                    while($row2=$result2->fetch_assoc()){
                        echo"<tr>
                             <td>{$row2['ExamID']}</td>
                             <td>{$row2['Name']}</td>
                             <td>{$row2['FirstName']} {$row2['LastName']}</td>
                             <td>{$row2['Department']}</td>
                             <td>{$row2['Email']}</td>
                             <td>{$row2['Fees']}</td>
                             <td>{$row2['StudentsRegistered']}</td>
                             <td>{$row2['AvailableSlots']}</td>
                             </tr>";
                    }
                }
            ?>
        </table><?php if($result2->num_rows==0) echo"<p style='font: 18px bold; text-align:center;'>No Exams Found</p>"; ?>
    </div>

    <div id="book" class="tab-content">
        <h1>Book an Exam</h1>
        <?php
            if($bstatus==-1){
                echo"<p style='font-style:oblique; color:red; text-align:center;'>Exam Does Not Exist</p>";
            }else if($bstatus==-2){
                echo"<p style='font-style:oblique; color:red; text-align:center;'>Exam Already Registered</p>";
            }else if($bstatus==0){
                echo"<p style='font-style:oblique; color:grey; text-align:center;'>Fill in the Details</p>";
            }
        ?>
        <form action="" method="post">
            <input type="text" name="ExamID" placeholder="Exam ID" required value="<?php echo isset($_POST['ExamID']) ? htmlspecialchars($_POST['ExamID']) : ''; ?>"><br>
            <input type="submit" class="button" name="BookExamNext" value="Next">
        </form>
    </div>

    <div id="chooseSlot" class="tab-content">
        <div id="chooseslot">
            <h1>Choose Slot</h1>
            <form method="post">
            <?php
                if($chstatus==-1){
                    echo"<p style='font-style:oblique; color:red; text-align:center;'>Slot Does Not Exist</p>";
                }else if($chstatus==0){
                    echo"<p style='font-style:oblique; color:grey; text-align:center;'>Fill in the Details</p>";
                }
            ?>
                <input type="text" name="SlotID" placeholder="Slot ID" required>
                <input type="submit" class="button" name="ChooseSlot" value="Continue to Payment">
            </form>
        </div>
        <div id="slotTable">
            <table>
                <caption>Available Slots</caption> 
                <tr>
                    <th>Slot ID</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>Duration</th>
                </tr>
                <?php
                    if($result3->num_rows>0){
                        while($row3=$result3->fetch_assoc()){
                            echo"<tr>
                                <td>{$row3['SlotID']}</td>
                                <td>{$row3['Date']}</td>
                                <td>{$row3['StartTime']}</td>
                                <td>{$row3['Duration']}</td>
                                </tr>";
                        }
                    }
                ?>
            </table><?php if($result3->num_rows==0) echo"<p style='font: 18px bold; text-align:center;'>No Slots Found</p>"; ?>
        </div>
    </div>

    <div id="reschedule" class="tab-content">
        <h1>Reschedule an Exam</h1>
        <?php
            if($resh1==-1){
                echo"<p style='font-style:oblique; color:red; text-align:center;'>Exam Does Not Exist</p>";
            }else if($resh1==0){
                echo"<p style='font-style:oblique; color:grey; text-align:center;'>Fill in the Details</p>";
            }
        ?>
        <form action="" method="post">
            <input type="text" name="ExamID" placeholder="Exam ID" required value="<?php echo isset($_POST['ExamID']) ? htmlspecialchars($_POST['ExamID']) : ''; ?>"><br>
            <input type="submit" class="button" name="Reshed1" value="Next">
        </form>
    </div>

    <div id="reshed" class="tab-content">
        <div id="chooseslot1">
            <h1>Choose a New Slot</h1>
                <form method="post">
                <?php
                    if($resh2==-1){
                        echo"<p style='font-style:oblique; color:red; text-align:center;'>Slot Does Not Exist</p>";
                    }else if($resh2==0){
                        echo"<p style='font-style:oblique; color:grey; text-align:center;'>Fill in the Details</p>";
                    }
                ?>
                    <input type="text" name="SlotID" placeholder="Slot ID" required>
                    <input type="submit" class="button" name="Reshed2" value="Reschedule">
                </form>
        </div>
        <div id="slotTable1">
            <table>
                <caption>Available Slots</caption> 
                <tr>
                    <th>Slot ID</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>Duration</th>
                </tr>
                <?php
                    if($result333->num_rows>0){
                        while($row333=$result333->fetch_assoc()){
                            echo"<tr>
                                <td>{$row333['SlotID']}</td>
                                <td>{$row333['Date']}</td>
                                <td>{$row333['StartTime']}</td>
                                <td>{$row333['Duration']}</td>
                                </tr>";
                        }
                    }
                ?>
            </table><?php if($result333->num_rows==0) echo"<p style='font: 18px bold; text-align:center;'>No Slots Found</p>"; ?>
        </div>
    </div>

    <script>
        function showTab(tabId) {
            // Hide all tab content
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => tab.classList.remove('active'));
            // Show the selected tab
            document.getElementById(tabId).classList.add('active');
        }
    </script>

    <?php 
        if($_SERVER['REQUEST_METHOD']=='POST'){
            if(isset($_POST['BookExamNext'])){
                if($bstatus<0){
                    echo"
                    <script>
                        const tabs = document.querySelectorAll('.tab-content');
                        tabs.forEach(tab => tab.classList.remove('active'));
                        document.getElementById('book').classList.add('active');
                    </script>";
                }else {
                    echo"
                    <script>
                        const tabs = document.querySelectorAll('.tab-content');
                        tabs.forEach(tab => tab.classList.remove('active'));
                        document.getElementById('chooseSlot').classList.add('active');
                    </script>";
                }
            }else if(isset($_POST['ChooseSlot'])){
                if($chstatus==-1){
                    echo"
                    <script>
                        const tabs = document.querySelectorAll('.tab-content');
                        tabs.forEach(tab => tab.classList.remove('active'));
                        document.getElementById('chooseSlot').classList.add('active');
                    </script>";
                }
            }else if(isset($_POST['Reshed1'])){
                if($resh1==1){
                    echo"
                    <script>
                        const tabs = document.querySelectorAll('.tab-content');
                        tabs.forEach(tab => tab.classList.remove('active'));
                        document.getElementById('reshed').classList.add('active');
                    </script>";
                }else if($resh1==-1){
                    echo"
                    <script>
                        const tabs = document.querySelectorAll('.tab-content');
                        tabs.forEach(tab => tab.classList.remove('active'));
                        document.getElementById('reschedule').classList.add('active');
                    </script>";
                }
            }else if(isset($_POST['Reshed2'])){
                if($resh2==1){
                    echo"
                    <script>
                        const tabs = document.querySelectorAll('.tab-content');
                        tabs.forEach(tab => tab.classList.remove('active'));
                        document.getElementById('home').classList.add('active');
                    </script>";
                }else if($resh2==-1){
                    echo"
                    <script>
                        const tabs = document.querySelectorAll('.tab-content');
                        tabs.forEach(tab => tab.classList.remove('active'));
                        document.getElementById('reshed').classList.add('active');
                    </script>";
                }
            }     
        }
    ?>
</body>
</html>