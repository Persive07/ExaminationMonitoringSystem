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

    //Checking if the User Logged in
    if (!isset($_SESSION['ProfUsername'])) {
        header("Location: index.php"); // Redirect to the login page if not logged in
        exit();
    }

    function generateRandomString() {
        $letters1 = '';$letters2 = '';
        for ($i = 0; $i < 2; $i++) {
            $letters1 .= chr(random_int(65, 90)); // ASCII A-Z
            $letters2 .= chr(random_int(65, 90));
        }
        $numbers1 = '';$numbers2 = '';
        for ($i = 0; $i < 3; $i++) {
            $numbers1 .= random_int(0, 9);
            $numbers2 .= random_int(0, 9);
        }
        return $letters1 . $numbers1 . $letters2 . $numbers2;
    }
    
    //Home Page
    $Username=$_SESSION['ProfUsername'];
    $sql1="SELECT *
           FROM Prof_Records
           WHERE BINARY Username='$Username'";
    $result1=$conn->query($sql1);
    $row1=$result1->fetch_assoc();
    $ProfID=$row1["ProfID"];
    $_SESSION['ProfID']=$ProfID;

    $sql11="SELECT * FROM Exam_Records WHERE ProfID='$ProfID'";
    $result11=$conn->query($sql11);

    $sql2="SELECT * FROM Slot_Records
        ORDER BY STR_TO_DATE(CONCAT(`Date`, ' ', `StartTime`), '%Y-%m-%d %H:%i:%s') ASC";
    $result2=$conn->query($sql2);

    if($_SERVER['REQUEST_METHOD']=='POST'){
        //Register an Exam
        $regstatus=0;
        if(isset($_POST['RegExam'])){
            $Name=$_POST['Name'];
            $Fees=$_POST['Fees'];
            $sql30="SELECT * FROM Exam_Records WHERE `Name`='$Name'";
            $result30=$conn->query($sql30);
            if($result30->num_rows==0){
                $temp = 1;
                $ExamID = "";
                while ($temp) {
                    $ExamID = generateRandomString();
                    $sql32 = "SELECT * FROM Exam_Records WHERE ExamID ='$ExamID'";
                    $result32 = $conn->query($sql32);
                    if ($result32->num_rows == 0) {
                        $temp = 0;
                    }
                }
                $_SESSION['AddQuestExamID']=$ExamID;
                $sql31="INSERT INTO `Exam_Records`(`ExamID`,`ProfID`,`Name`,`Fees`)
                        VALUES ('$ExamID','$ProfID','$Name','$Fees')";
                $result31=$conn->query($sql31);
                $sql32="UPDATE Prof_Records SET ExamsConducting=ExamsConducting+1 WHERE `ProfID`='$ProfID'";
                $result32=$conn->query($sql32);
            }else{
                $regstatus=-1;
            }
        }

        //Add Questions
        $adquest=0;
        if(isset($_POST['AddQuest'])){
            $temp = 1;
            $QuestionID = "";
            while ($temp) {
                $QuestionID = generateRandomString();
                $sql40 = "SELECT * FROM Question_Records WHERE QuestionID ='$QuestionID'";
                $result40 = $conn->query($sql40);
                if ($result40->num_rows == 0) {
                    $temp = 0;
                }
            }
            $ExamID=$_POST['ExamID'];
            $Topic=$_POST['Topic'];
            $Difficulty=$_POST['Difficulty'];
            $Question=$_POST['Question'];
            $OptionA=$_POST['OptionA'];
            $OptionB=$_POST['OptionB'];
            $OptionC=$_POST['OptionC'];
            $OptionD=$_POST['OptionD'];
            $Answer=$_POST['Answer'];
            $ExamID=$_SESSION['AddQuestExamID'];
            $sql42="SELECT * FROM Exam_Records WHERE ExamID='$ExamID'";
            $result42=$conn->query($sql42);
            if($result42->num_rows==1){
                $sql41="INSERT INTO `Question_Records`(`QuestionID`,`Topic`,`Question`,`Difficulty`,`OptionA`,`OptionB`,`OptionC`,`OptionD`,`Answer`,`CorrectSubmissions`,`WrongSubmissions`,`ExamID`)
                        VALUES ('$QuestionID','$Topic','$Question','$Difficulty','$OptionA','$OptionB','$OptionC','$OptionD','$Answer',0,0,'$ExamID')";
                $result41=$conn->query($sql41);
                $sql42="UPDATE Exam_Records SET Questions=Questions+1 WHERE ExamID='$ExamID'";
                $result42=$conn->query($sql42);
                $adquest=1;
            }else{
                $adquest=-1;
            }
        }

        //Add Slots
        $adslot=0;
        if(isset($_POST['AddSlot'])){
            $SlotID=$_POST['SlotID'];
            $sql52="SELECT * FROM Slot_Records WHERE SlotID='$SlotID'";
            $result52=$conn->query($sql52);
            if($result52->num_rows==1){
                $ExamID=$_POST['ExamID'];
                $sql51="SELECT * FROM Exam_Records WHERE ExamID='$ExamID'";
                $result51=$conn->query($sql51);
                if($result51->num_rows==1){
                    $sql50="SELECT * FROM Exam_Slots WHERE ExamID='$ExamID' AND SlotID='$SlotID'";
                    $result50=$conn->query($sql50);
                    if($result50->num_rows==0){
                        $sql51="INSERT INTO `Exam_Slots`(`ExamID`,`SlotID`)
                                VALUES ('$ExamID','$SlotID')";
                        $result51=$conn->query($sql51);
                        $sql53="UPDATE Slot_Records SET ExamsAllotted=ExamsAllotted+1 WHERE SlotID='$SlotID'";
                        $sql54="UPDATE Exam_Records SET AvailableSlots=AvailableSlots+1 WHERE ExamID='$ExamID'";
                        $result53=$conn->query($sql53);
                    $result54=$conn->query($sql54);
                        $adslot=1;
                    }else{
                        $adslot=-1; // Already Registered
                    }
                }else{
                    $adslot=-3; // Exam Id is wrong
                }
            }else{
                $adslot=-2; // Slot ID is wrong
            }
        }

        if(isset($_POST['Analysis'])){
            $_SESSION['aExamID']=$_POST['ExamID'];
            header("Location: prof_stats.php");
            exit();
        }
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="prof_home.css?v=2.0">    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IITG Exam Portal</title>
</head>
<body>
    <h1 class="heading">IIT Guwahati Exam Portal</h1>
    <nav>
        <a onclick="showTab('home')">Home</a>
        <a onclick="showTab('register')">Add Exams</a>
        <a onclick="showTab('regSlot')">Allot Slots</a>
        <a onclick="showTab('regQuest')">Add Questions</a>
        <a class="logout" href="logout.php">Logout</a>
    </nav>

    <div id="home" class="tab-content active">
        <br>
        <span style="font:22px bold;margin-left:00px;">Prof Information</span><br>
        <span style="font:16px bold;margin-left:00px;">Name : </span><?php echo"{$row1["FirstName"]} {$row1["LastName"]}";?><br>
        <span style="font:16px bold;margin-left:00px;">Prof ID : </span><?php echo"{$row1["ProfID"]}";?><br><br>

        <table>
            <caption>Exams Conducting</caption>
            
            <tr>
                <th>Exam ID</th>
                <th>Name</th>
                <th>Fees</th>
                <th>Students Registered</th>
                <th>Available Slots</th>
                <th>Questions</th>
                <th>Analysis</th>
            </tr>
            <?php
                if($result11->num_rows>0){
                    while($row11=$result11->fetch_assoc()){
                        $aExamID=$row11['ExamID'];
                        echo"<tr>
                                <td>{$row11['ExamID']}</td>
                                <td>{$row11['Name']}</td>
                                <td>{$row11['Fees']}</td>
                                <td>{$row11['StudentsRegistered']}</td>
                                <td>{$row11['AvailableSlots']}</td>
                                <td>{$row11['Questions']}</td>
                                <td>
                                    <form method='post'>
                                        <input type='hidden' name='ExamID' value={$aExamID}>
                                        <input type='submit' class='Analysis' name='Analysis' value='Analysis'>
                                    </form>
                                </td>
                             </tr>";
                    }
                }
            ?>
        </table>
        <?php if($result11->num_rows==0) echo"<p style='font: 18px bold; text-align:center;'>No Exams Found</p>"; ?>
    </div>
    
    <div id="register" class="tab-content">
        <h1>Register an Exam</h1>
        <?php
            if($regstatus==-1){
                echo"<p style='font-style:oblique; color:red; text-align:center;'>Exam Name Already Exists</p>";
            }else if($regstatus=0){
                echo"<p style='font-style:oblique; color:grey; text-align:center;'>Fill in the Details</p>";
            }
        ?>
        <form action="" method="post">
            <input type="text" name="Name" placeholder="Name" required value="<?php echo isset($_POST['Date']) ? htmlspecialchars($_POST['Date']) : ''; ?>"><br>
            <input type="int" name="Fees" placeholder="Fees ( INR )" required value="<?php echo isset($_POST['Fees']) ? htmlspecialchars($_POST['Fees']) : ''; ?>"><br>
            <input type="submit" class="button" name="RegExam" value="Next">
        </form>
    </div>

    <div id="regQuest" class="tab-content">
        <h1>Add Questions</h1>
        <?php
            if($adquest==-1){
                echo"<p style='font-style:oblique; color:green; text-align:center;'>Exam Does Not Exist</p>";
            }else if($adquest==1){
                echo"<p style='font-style:oblique; color:green; text-align:center;'>Question Successfully Registered</p>";
            }
        ?>
        <p style='font-style:oblique; color:grey; text-align:center;'>Fill in the Details</p>
        <form action="" method="post">
            <input type="text" name="ExamID" placeholder="Exam ID" required value="<?php echo isset($_POST['ExamID']) ? htmlspecialchars($_POST['ExamID']) : ''; ?>">
            <input type="text" name="Topic" placeholder="Topic" required value="<?php echo isset($_POST['Topic']) ? htmlspecialchars($_POST['Topic']) : ''; ?>">
            <select name="Difficulty" id="dropdown" placeholder="Difficulty">
                <option name="easy">Easy</option>
                <option name="medium">Medium</option>
                <option name="hard">Hard</option>
            </select>
            <input type="text" name="Question" placeholder="Question" required>
            <input type="text" name="OptionA" placeholder="OptionA" required>
            <input type="text" name="OptionB" placeholder="OptionB" required>
            <input type="text" name="OptionC" placeholder="OptionC" required>
            <input type="text" name="OptionD" placeholder="OptionD" required>
            <select name="Answer" id="dropdown" placeholder="Answer">
                <option name="A">A</option>
                <option name="B">B</option>
                <option name="C">C</option>
                <option name="D">D</option>
            </select>
            <input type="submit" class="button" id="AddQuest" name="AddQuest" value="Add Question">
        </form>
    </div>

    <div id="regSlot" class="tab-content">
        <div id="regslot">
            <h1>Add Slots</h1>
            <form method="post">
            <?php
                if($adslot==-3){
                    echo"<p style='font-style:oblique; color:red; text-align:center;'>Exam Does Not Exist</p>";
                }else if($adslot==-2){
                    echo"<p style='font-style:oblique; color:red; text-align:center;'>Slot Does Not Exist</p>";
                }else if($adslot==-1){
                    echo"<p style='font-style:oblique; color:red; text-align:center;'>Slot Already Registered</p>";
                }else if($adslot==0){
                    echo"<p style='font-style:oblique; color:grey; text-align:center;'>Fill in the Details</p>";
                }else if($adslot==1){
                    echo"<p style='font-style:oblique; color:green; text-align:center;'>Slot Successfully Registered</p>";
                }
            ?>
                <input type="text" name="SlotID" placeholder="Slot ID">
                <input type="text" name="ExamID" placeholder="Exam ID">
                <input type="submit" class="button" name="AddSlot" value="Add Slot">
            </form>
        </div><br>
        <div id="slot">
            <br>
            <table>
                <caption>Available Slots</caption>    
                <tr>
                    <th>Slot ID</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>Duration</th>
                    <th>Exams</th>
                </tr>
                <?php
                    if($result2->num_rows>0){
                        while($row2=$result2->fetch_assoc()){
                            echo"<tr>
                                <td>{$row2['SlotID']}</td>
                                <td>{$row2['Date']}</td>
                                <td>{$row2['StartTime']}</td>
                                <td>{$row2['Duration']}</td>
                                <td>{$row2['ExamsAllotted']}</td>
                                </tr>";
                        }
                    }
                ?>
            </table>
            <?php if($result2->num_rows==0) echo"<p style='font: 18px bold; text-align:center;'>No Slots Found</p>"; ?>
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
            if(isset($_POST['RegExam'])){
                if($regstatus==-1){
                    echo"
                    <script>
                        const tabs = document.querySelectorAll('.tab-content');
                        tabs.forEach(tab => tab.classList.remove('active'));
                        document.getElementById('register').classList.add('active');
                    </script>";
                }else{
                    echo"
                    <script>
                        const tabs = document.querySelectorAll('.tab-content');
                        tabs.forEach(tab => tab.classList.remove('active'));
                        document.getElementById('regQuest').classList.add('active');
                    </script>";
                }
            }else if(isset($_POST['AddQuest'])){
                echo"
                <script>
                    const tabs = document.querySelectorAll('.tab-content');
                    tabs.forEach(tab => tab.classList.remove('active'));
                    document.getElementById('regQuest').classList.add('active');
                </script>";
            }else if(isset($_POST['AddSlot'])){
                echo"
                <script>
                    const tabs = document.querySelectorAll('.tab-content');
                    tabs.forEach(tab => tab.classList.remove('active'));
                    document.getElementById('regSlot').classList.add('active');
                </script>";
            }      
        }
    ?>
</body>
</html>