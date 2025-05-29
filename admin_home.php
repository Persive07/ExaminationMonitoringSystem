<?php
    // Display errors on the screen
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
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

    //Slots
    $Date='';$StartTime='';$Duration='';$ExamsAllotted='';
    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['SearchButton'])){
            if(isset($_POST['SearchDate']))$Date=$_POST['SearchDate'];
            if(isset($_POST['SearchTime']))$StartTime=($_POST['SearchTime'].':00');
            if(isset($_POST['SearchDuration']))$Duration=$_POST['SearchDuration'];
            if(isset($_POST['SearchExamsAllotted']))$ExamsAllotted=$_POST['SearchExamsAllotted'];
        }
    }
    $sql2="SELECT * 
           FROM Slot_Records
           WHERE `Date` LIKE '%$Date%' AND `StartTime` LIKE '%$StartTime%' AND `Duration` LIKE '%$Duration%' AND ExamsAllotted LIKE '%$ExamsAllotted%'
           ORDER BY STR_TO_DATE(CONCAT(`Date`, ' ', `StartTime`), '%Y-%m-%d %H:%i:%s') ASC";
    $result2=$conn->query($sql2);

    //SlotID
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

    //Registering Slots
    $rstatus = 0;
    $restatus=0;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['RegisterSlot'])) {
            $Date = $_POST['Date'];
            $StartTime = $_POST['StartTime'];
            $StartTime = $StartTime . ":00";
            $Duration = $_POST['Duration'];
            $sql31="SELECT *  
                    FROM Slot_Records 
                    WHERE `Date`='$Date' AND `StartTime`='$StartTime' AND `Duration`='$Duration'";
            $result31=$conn->query($sql31);
            if ($result31->num_rows== 0) {
                $temp = 1;
                $SlotID = "";
                while ($temp) {
                    $SlotID = generateRandomString();
                    $sql30 = "SELECT * FROM Slot_Records WHERE SlotID = ?";
                    $stmt30 = $conn->prepare($sql30);
                    $stmt30->bind_param("s", $SlotID);
                    $stmt30->execute();
                    $result30 = $stmt30->get_result();
                    if ($result30->num_rows == 0) {
                        $temp = 0;
                    }
                }
                $sql3="INSERT INTO `Slot_Records`(`SlotID`, `Date`, `StartTime`, `Duration`) 
                        VALUES ('$SlotID','$Date','$StartTime','$Duration')";
                $result3=$conn->query($sql3);
                $rstatus = 1; // Success in Registering a new slot
            } else {
                $rstatus = -1; // Slot already exists
            }
        } 
        
        if(isset($_POST['RemoveSlot'])) {
            $SlotID=$_POST['SlotID'];
            $sql40="SELECT * FROM Slot_Records WHERE SlotID='$SlotID'";
            $result40=$conn->query($sql40);
            if($result40->num_rows==1){
                $row40=$result40->fetch_assoc();
                if($row40['ExamsAllotted']==0){
                    $sql41="DELETE FROM Slot_Records WHERE SlotID='$SlotID'";
                    $result41=$conn->query($sql41);
                    $restatus=1;
                }else{
                    $restatus=-2;
                }
            }else{
                $restatus=-1;
            }
        }
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="admin.css?v=2.0">    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IITG Exam System</title>
</head>
<body>
    <h1 class="heading">Administrator Portal</h1>
    <nav>
        <a onclick="showTab('slot')">Slots</a>
        <a onclick="showTab('register')">Add Slots</a>
        <a onclick="showTab('remove')">Remove Slots</a>
        <a class="logout" href="logout.php">Logout</a>
    </nav><br>
    <div id="slot" class="tab-content active">
        <div id="searchform">
            <form method="post">
                <input type="date" placeholder="Date" name="SearchDate" value="<?php echo isset($_POST['SearchDate']) ? htmlspecialchars($_POST['SearchDate']) : ''; ?>">
                <input type="time" placeholder="Time" name="SearchTime" value="<?php echo isset($_POST['SearchTime']) ? htmlspecialchars($_POST['SearchTime']) : ''; ?>">
                <input type="text" placeholder="Duration" name="SearchDuration" value="<?php echo isset($_POST['SearchDuration']) ? htmlspecialchars($_POST['SearchDuration']) : ''; ?>">
                <input type="int" placeholder="Exams Allotted" name="SearchExamsAllotted" value="<?php echo isset($_POST['SearchExamsAllotted']) ? htmlspecialchars($_POST['SearchExamsAllotted']) : ''; ?>">
                <input type="submit" name="SearchButton" value="Go">
            </form>
        </div>
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
    
    <div id="register" class="tab-content">
        <h1>Add a New Slot</h1>
        <?php
            if($rstatus==-1){
                echo "<p style='font-style:oblique; color:red; text-align:center;'>Slot Already Exists</p>";
            }else if($rstatus==0){
                echo "<p style='font-style:oblique; color:grey; text-align:center;'>Fill in the Details</p>";
            }else if($rstatus==1){
                echo "<p style='font-style:oblique; color:green; text-align:center;'>Slot Successfully Registered</p>";
            }
        ?>
        <form action="" method="post">
            <input type="date" name="Date" placeholder="Date" required value="<?php echo isset($_POST['Date']) ? htmlspecialchars($_POST['Date']) : ''; ?>"><br>
            <input type="time" name="StartTime" placeholder="StartTime" required value="<?php echo isset($_POST['StartTime']) ? htmlspecialchars($_POST['StartTime']) : ''; ?>"><br>
            <select name="Duration" id="dropdown">
                <option value="00 Hours 30 Minutes">00 Hours 30 Minutes</option>
                <option value="01 Hour 00 Minutes">01 Hour 00 Minutes</option>
                <option value="01 Hour 30 Minutes">01 Hour 30 Minutes</option>
                <option value="02 Hours 00 Minutes">02 Hours 00 Minutes</option>
                <option value="02 Hours 30 Minutes">02 Hours 30 Minutes</option>
                <option value="03 Hours 00 Minutes">03 Hours 00 Minutes</option>
            </select><br>
            <input type="submit" class="button" name="RegisterSlot" value="Register">
        </form>
    </div>

    <div id="remove" class="tab-content">
        <h1>Remove a Slot</h1>
        <?php
            if($restatus==-2){
                echo "<p style='font-style:oblique; color:red; text-align:center;'>There is atleast 1 Exam allotted in this Slot</p>";
            }else if($restatus==-1){
                echo "<p style='font-style:oblique; color:red; text-align:center;'>Slot Does Not Exist</p>";
            }else if($restatus==0){
                echo "<p style='font-style:oblique; color:grey; text-align:center;'>Fill in the Details</p>";
            }else if($restatus==1){
                echo "<p style='font-style:oblique; color:green; text-align:center;'>Slot Successfully Removed</p>";
            }
        ?>
        <form action="" method="post">
            <input type="text" name="SlotID" placeholder="Slot ID" required ><br>
            <input type="submit" class="button" name="RemoveSlot" value="Remove">
        </form>
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
            if(isset($_POST['RegisterSlot'])){
                echo"
                <script>
                    const tabs = document.querySelectorAll('.tab-content');
                    tabs.forEach(tab => tab.classList.remove('active'));
                    document.getElementById('register').classList.add('active');
                </script>";
            }else if(isset($_POST['RemoveSlot'])){
                echo"
                <script>
                    const tabs = document.querySelectorAll('.tab-content');
                    tabs.forEach(tab => tab.classList.remove('active'));
                    document.getElementById('remove').classList.add('active');
                </script>";
            }
        }
    ?>
</body>
</html>