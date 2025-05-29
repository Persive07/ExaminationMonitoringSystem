-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 29, 2025 at 07:13 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `DA215_Lab4`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `Username` varchar(100) NOT NULL,
  `Password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Admin`
--

INSERT INTO `Admin` (`Username`, `Password`) VALUES
('user123', 'pass123');

-- --------------------------------------------------------

--
-- Table structure for table `Booking_Records`
--

CREATE TABLE `Booking_Records` (
  `BookingID` varchar(10) NOT NULL,
  `StudentID` varchar(10) NOT NULL,
  `ExamID` varchar(10) NOT NULL,
  `SlotID` varchar(10) NOT NULL,
  `ExamStatus` text NOT NULL DEFAULT 'Not Given',
  `TotalScore` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Booking_Records`
--

INSERT INTO `Booking_Records` (`BookingID`, `StudentID`, `ExamID`, `SlotID`, `ExamStatus`, `TotalScore`) VALUES
('AM770ZP520', '230100999', 'CR285DU002', 'GA996ML567', 'Paused', 0),
('AQ284IA786', '1232178123', 'FQ111XO966', 'AI042SU017', 'Given', 27),
('BM878BE386', '870701441', 'FQ111XO966', 'EM468AI080', 'Given', 17),
('IG129QP998', '1232178123', 'CR285DU002', 'LU059GA792', 'Given', -2),
('JQ013TP925', '230150007', 'CR285DU002', 'LU059GA792', 'Given', 2),
('KV593OV646', '1922174382', 'CR285DU002', 'LU059GA792', 'Given', 50),
('NJ070KD567', '2187872491', 'CR285DU002', 'GA996ML567', 'Given', 26),
('RD953SK827', '2301500070', 'FQ111XO966', 'EM468AI080', 'Given', 30),
('TW071FP127', '2187872491', 'FQ111XO966', 'AI042SU017', 'Given', 19),
('VG619UP123', '8765678911', 'CR285DU002', 'LU059GA792', 'Given', 8),
('VS884JC332', '1922174382', 'BW596JC996', 'CA494TE514', 'Given', 0),
('WK536HG724', '870701441', 'CR285DU002', 'GA996ML567', 'Given', 2),
('ZB687XL863', '23010101', 'CR285DU002', 'LU059GA792', 'Given', 48),
('ZC131UU528', '2388889201', 'CR285DU002', 'LU059GA792', 'Given', 46),
('ZG363WY998', '2301500070', 'CR285DU002', 'LU059GA792', 'Given', 29);

-- --------------------------------------------------------

--
-- Table structure for table `Exam_Records`
--

CREATE TABLE `Exam_Records` (
  `ExamID` varchar(10) NOT NULL,
  `ProfID` varchar(10) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Fees` int(6) NOT NULL,
  `StudentsRegistered` int(11) NOT NULL DEFAULT 0,
  `AvailableSlots` int(11) NOT NULL DEFAULT 0,
  `Questions` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Exam_Records`
--

INSERT INTO `Exam_Records` (`ExamID`, `ProfID`, `Name`, `Fees`, `StudentsRegistered`, `AvailableSlots`, `Questions`) VALUES
('BW596JC996', '2310500010', 'DSA', 2999, 1, 1, 0),
('CR285DU002', '2310500010', 'Introduction to Computer Science', 7999, 10, 2, 14),
('FQ111XO966', '1021009201', 'Introduction to Database and Management Systems', 1499, 4, 2, 15),
('PS772BX936', '2310500010', 'DBMS', 999, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Exam_Slots`
--

CREATE TABLE `Exam_Slots` (
  `ExamID` varchar(10) NOT NULL,
  `SlotID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Exam_Slots`
--

INSERT INTO `Exam_Slots` (`ExamID`, `SlotID`) VALUES
('BW596JC996', 'CA494TE514'),
('CR285DU002', 'GA996ML567'),
('CR285DU002', 'LU059GA792'),
('FQ111XO966', 'AI042SU017'),
('FQ111XO966', 'EM468AI080'),
('PS772BX936', 'GA996ML567');

-- --------------------------------------------------------

--
-- Table structure for table `Images`
--

CREATE TABLE `Images` (
  `ImageID` int(11) NOT NULL,
  `Image_Path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Images`
--

INSERT INTO `Images` (`ImageID`, `Image_Path`) VALUES
(1, 'images/image1.jpg'),
(2, 'images/image2.jpg'),
(3, 'images/image3.jpg'),
(4, 'images/image4.jpg'),
(5, 'images/image5.jpg'),
(6, 'images/image6.jpg'),
(7, 'images/image7.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `Prof_Records`
--

CREATE TABLE `Prof_Records` (
  `ProfID` varchar(10) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `PhoneNo` varchar(10) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Department` varchar(100) NOT NULL,
  `ExamsConducting` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Prof_Records`
--

INSERT INTO `Prof_Records` (`ProfID`, `FirstName`, `LastName`, `PhoneNo`, `Email`, `Department`, `ExamsConducting`, `Username`, `Password`) VALUES
('1021009201', 'Clement', 'Frank', '8989898911', 'cfrank@iitg.ac.in', 'Data Science & AI', 1, 'user4', '$2y$10$QP16WAKcsOSOi0JzoUr20.KZd4o3Hz9TrHFQVIwPT9LNq3P.i3SDm'),
('2310010301', 'Ethan', 'Walker', '1729989833', 'ewalk@iitg.ac.in', 'Data Science & AI', 0, 'user3', '$2y$10$DLEpjswtXiu6oFGyK0fLMOOY0CbwNEw8V95vHUz68X6i7SzHoq9/G'),
('2310500010', 'John', 'Doe', '1319087629', 'jdoe@iitg.ac.in', 'Computer Science', 3, 'user1', '$2y$10$XAReHpXuQ8ISNSCkLG6kZesfTAEp7FAoCws9EnnKATmJjDbTcKldm'),
('2310500022', 'James', 'Carter', '1382738475', 'jcart@iitg.ac.in', 'Mathematics', 0, 'user2', '$2y$10$CM1E9GV8YNI3Q7jyGib6f.dxtN0ptu7VIpNySJaazCpOa9PKrr/JC');

-- --------------------------------------------------------

--
-- Table structure for table `Question_Records`
--

CREATE TABLE `Question_Records` (
  `QuestionID` varchar(10) NOT NULL,
  `Topic` varchar(100) NOT NULL,
  `Question` text NOT NULL,
  `Difficulty` enum('Easy','Medium','Hard') NOT NULL,
  `OptionA` varchar(100) NOT NULL,
  `OptionB` varchar(100) NOT NULL,
  `OptionC` varchar(100) NOT NULL,
  `OptionD` varchar(100) NOT NULL,
  `Answer` text NOT NULL,
  `CorrectSubmissions` int(11) NOT NULL DEFAULT 0,
  `WrongSubmissions` int(11) NOT NULL DEFAULT 0,
  `ExamID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Question_Records`
--

INSERT INTO `Question_Records` (`QuestionID`, `Topic`, `Question`, `Difficulty`, `OptionA`, `OptionB`, `OptionC`, `OptionD`, `Answer`, `CorrectSubmissions`, `WrongSubmissions`, `ExamID`) VALUES
('BW125VQ089', 'Transactions & Concurrency', 'Which of the following properties ensures a transaction is completed fully or not at all?', 'Easy', 'Atomicity', 'Consistency', ' Isolation', 'Durability', 'A', 0, 0, 'FQ111XO966'),
('CE582OY502', 'SQL Queries', 'What will be the output of the following SQL query? SELECT COUNT(*) FROM Students WHERE age > 18;', 'Medium', 'Returns the total number of students', 'Returns the number of students older than 18', 'Returns the age of the oldest student', 'Returns a syntax error', 'B', 0, 0, 'FQ111XO966'),
('CR535RL355', 'Programming', 'Which of the following is a low-level programming language ?', 'Medium', 'Assembly', 'Java', 'Python', 'C++', 'A', 0, 0, 'CR285DU002'),
('EB503AP789', 'Relational Model', ' Which normal form ensures that there are no partial dependencies?', 'Medium', '1NF', '2NF', '3NF', 'BCNF', 'B', 0, 0, 'FQ111XO966'),
('ES284QQ998', 'Hardware', 'Find the Binary Equivalent of Decimal Number 13', 'Hard', '0010', '0011', '1011', '1101', 'D', 0, 0, 'CR285DU002'),
('FF711WO664', 'Hardware', 'The main function of the ALU in a CPU is to perform : ', 'Medium', 'Data Storage', 'Data Transmission', 'Arithmetic and Logical Operations', 'Graphics Rendering', 'C', 0, 0, 'CR285DU002'),
('FJ101ZZ542', 'Programming', 'In programming, what does the term “recursion” mean ?', 'Hard', 'A function that calls itself', 'A function with a loop', 'A function that executes in parallel', 'A function without parameters', 'A', 0, 0, 'CR285DU002'),
('GJ769RY471', 'SQL Queries', 'Which SQL clause is used to filter records?', 'Easy', 'WHERE', 'ORDER BY', 'GROUP BY', 'HAVING', 'A', 0, 0, 'FQ111XO966'),
('HF994ZG330', 'Indexing & Optimization', 'What is the main purpose of an index in a database?', 'Easy', 'To store records permanently', 'To speed up queries', 'To ensure ACID properties', 'To enforce constraints', 'B', 0, 0, 'FQ111XO966'),
('IT780AR349', 'NoSQL & New Trends', 'Which data model does MongoDB use?', 'Medium', 'Relational', 'Key-Value', 'Document-based', 'Columnar', 'C', 0, 0, 'FQ111XO966'),
('ME838VD139', 'Hardware', '1 GB equals', 'Easy', '1000 MB', '1000 KB', '1024 MB', '1024 KB', 'C', 0, 0, 'CR285DU002'),
('NA360CW850', 'Hardware', 'Find the Decimal Equivalent of the Binary Number 1010', 'Hard', '8', '9', '10', '11', 'C', 0, 0, 'CR285DU002'),
('NJ388LE160', 'Transactions & Concurrency', 'Which of the following concurrency control techniques uses timestamps?', 'Hard', 'Two-Phase Locking', 'Optimistic Concurrency Control', 'Timestamp Ordering', 'Strict Serializability', 'C', 0, 0, 'FQ111XO966'),
('NR665YO120', 'Indexing & Optimization', 'What is the time complexity of searching in a B+ tree index?', 'Hard', 'O(1)', 'O(n)', 'O(log n)', 'O(n log n)', 'C', 0, 0, 'FQ111XO966'),
('RF761LY305', 'Programming', 'Find the Output of this Python Program : print(\"Hello World\")', 'Medium', 'print(\"Hello World\")', 'HelloWorld', '\"Hello World\"', 'Hello World', 'D', 0, 0, 'CR285DU002'),
('RL926CI218', 'Relational Model', 'In which situation does BCNF not guarantee a lossless decomposition?', 'Hard', 'When there is a composite key', 'When there is a non-trivial functional dependency', 'When a relation has overlapping candidate keys', 'When all attributes depend on the whole primary key', 'C', 0, 0, 'FQ111XO966'),
('RW749OK798', 'Transactions & Concurrency', 'What is a deadlock in DBMS?', 'Medium', 'When a transaction is aborted automatically', 'When two or more transactions wait indefinitely for each other', 'When multiple transactions execute in parallel', 'When a transaction violates consistency', 'B', 0, 0, 'FQ111XO966'),
('RZ948SF831', 'Hardware', 'Which device is used as the secondary storage in a Computer ?', 'Medium', 'Hard Disk Drive', 'RAM', 'USB Flash Drive', 'None', 'A', 0, 0, 'CR285DU002'),
('SR398TC460', 'Programming', 'Which of the following is NOT a compiled language ?', 'Medium', 'C', 'C++', 'Python', 'Rust', 'C', 0, 0, 'CR285DU002'),
('SS494YB568', 'Concurrency Control', 'Q', 'Easy', 'a', 'b', 'c', 'd', 'A', 0, 0, 'PS772BX936'),
('UJ079IL498', 'Hardware', 'Full Form of CPU : ', 'Easy', 'Computer Processing Unit', 'Central Processing Unit', 'Central Peripheral Unit', 'None', 'B', 0, 0, 'CR285DU002'),
('UQ518WP325', 'Hardware', 'If a processor is 32-bit, what does it mean ?', 'Medium', 'It can process 32 bytes at a time', 'It has 32 registers', 'It can process 32 bits at a time', 'It can store only 32-bit numbers', 'C', 0, 0, 'CR285DU002'),
('VJ869CX614', 'Hardware', '1 Byte equals ?', 'Easy', '8 Bits', '1/8 Bits', '1 Bit', 'None of these', 'A', 0, 0, 'CR285DU002'),
('VW637EG107', 'Programming', 'What does HTML stand for ?', 'Easy', 'Hyper Transfer Markup Language', 'High Text Machine Language', 'Home Tool Markup Language', 'Hyper Text Markup Language', 'D', 0, 0, 'CR285DU002'),
('VZ935IU068', 'NoSQL & New Trends', 'Which of the following is an example of a NoSQL database?', 'Easy', 'MySQL', 'PostgreSQL', 'MongoDB', 'Oracle', 'C', 0, 0, 'FQ111XO966'),
('WE744QZ708', 'SQL Queries', 'What is the purpose of the HAVING clause in SQL?', 'Hard', 'It is used to filter grouped records', 'It replaces the WHERE clause', 'It sorts the result set', 'It is used to join tables', 'A', 0, 0, 'FQ111XO966'),
('WH157SC768', 'Indexing & Optimization', 'Which type of index stores pointers to the actual rows?', 'Medium', 'Clustered Index', 'Non-Clustered Index', 'Primary Index', 'Secondary Index', 'B', 0, 0, 'FQ111XO966'),
('XP047UO382', 'Relational Model', 'Which of the following is a valid primary key?', 'Easy', 'A column with duplicate values', 'A column with NULL values', 'A column that uniquely identifies each row', 'A column that can store multiple values', 'C', 0, 0, 'FQ111XO966'),
('XT894SO625', 'NoSQL & New Trends', 'What is BASE in NoSQL databases?', 'Hard', 'Basically Available, Soft state, Eventual consistency', 'Basic, Atomic, Sequential, Efficient', 'Backup, Archive, Storage, Execution', 'Binary Access, Security, Efficiency', 'A', 0, 0, 'FQ111XO966'),
('ZG972ON291', 'Programming', 'Select the Programming language', 'Easy', 'Loop', 'C++', 'Main', 'A++', 'B', 0, 0, 'CR285DU002');

-- --------------------------------------------------------

--
-- Table structure for table `Slot_Records`
--

CREATE TABLE `Slot_Records` (
  `SlotID` varchar(10) NOT NULL,
  `Date` date NOT NULL,
  `StartTime` time NOT NULL,
  `Duration` text NOT NULL,
  `ExamsAllotted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Slot_Records`
--

INSERT INTO `Slot_Records` (`SlotID`, `Date`, `StartTime`, `Duration`, `ExamsAllotted`) VALUES
('AI042SU017', '2025-02-13', '11:30:00', '00 Hours 30 Minutes', 1),
('BE057QR232', '2025-04-26', '09:30:00', '03 Hours 00 Minutes', 0),
('BR541HA437', '2025-02-13', '15:30:00', '00 Hours 30 Minutes', 0),
('CA494TE514', '2025-02-13', '16:30:00', '00 Hours 30 Minutes', 1),
('EM468AI080', '2025-02-13', '15:00:00', '00 Hours 30 Minutes', 1),
('GA996ML567', '2025-02-13', '09:00:00', '00 Hours 30 Minutes', 2),
('LU059GA792', '2025-02-13', '09:30:00', '00 Hours 30 Minutes', 1),
('ML426PL791', '2025-02-13', '14:30:00', '00 Hours 30 Minutes', 0),
('SK458LS239', '2025-02-13', '16:00:00', '00 Hours 30 Minutes', 0),
('SK946IB051', '2025-02-13', '10:30:00', '00 Hours 30 Minutes', 0),
('UP203YV645', '2025-02-13', '14:00:00', '00 Hours 30 Minutes', 0),
('XG944WX484', '2025-02-13', '11:00:00', '00 Hours 30 Minutes', 0),
('XX273RG804', '2025-02-13', '10:00:00', '00 Hours 30 Minutes', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Student_Exam_Dashboard`
--

CREATE TABLE `Student_Exam_Dashboard` (
  `BookingID` varchar(10) NOT NULL,
  `PhotoPath` varchar(255) DEFAULT NULL,
  `TotalTime` time NOT NULL DEFAULT '00:00:00',
  `QuestionIndex` int(11) NOT NULL DEFAULT 0,
  `difficultyCounter` text NOT NULL DEFAULT 'Easy',
  `easyCounter` int(11) NOT NULL DEFAULT 0,
  `mediumCounter` int(11) NOT NULL DEFAULT 0,
  `TotalScore` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Student_Exam_Dashboard`
--

INSERT INTO `Student_Exam_Dashboard` (`BookingID`, `PhotoPath`, `TotalTime`, `QuestionIndex`, `difficultyCounter`, `easyCounter`, `mediumCounter`, `TotalScore`) VALUES
('AM770ZP520', 'images/image3.jpg', '00:00:12', 0, 'Easy', 0, 0, 0),
('AQ284IA786', 'images/image7.jpg', '00:02:03', 5, 'Hard', 2, 2, 27),
('BM878BE386', 'images/image1.jpg', '00:00:51', 0, 'Hard', 2, 2, 17),
('IG129QP998', 'images/image6.jpg', '00:00:08', 1, 'Easy', 0, 0, -2),
('JQ013TP925', 'images/image7.jpg', '00:01:02', 3, 'Hard', 2, 0, 2),
('KV593OV646', 'images/image4.jpg', '00:00:32', 3, 'Hard', 2, 2, 50),
('NJ070KD567', 'images/image3.jpg', '00:00:53', 3, 'Hard', 2, 2, 26),
('RD953SK827', 'images/image6.jpg', '00:02:13', 5, 'Hard', 2, 1, 30),
('TW071FP127', 'images/image3.jpg', '00:00:23', 0, 'Hard', 2, 2, 19),
('VG619UP123', 'images/image3.jpg', '00:00:37', 3, 'Hard', 1, 2, 8),
('VS884JC332', 'images/image3.jpg', '00:00:10', 0, 'Hard', 0, 0, 0),
('WK536HG724', 'images/image7.jpg', '00:00:05', 1, 'Hard', 0, 2, 2),
('ZB687XL863', 'images/image1.jpg', '00:00:49', 3, 'Hard', 2, 2, 48),
('ZC131UU528', 'images/image2.jpg', '00:00:27', 3, 'Hard', 2, 2, 46),
('ZG363WY998', 'images/image6.jpg', '00:00:39', 3, 'Hard', 2, 2, 29);

-- --------------------------------------------------------

--
-- Table structure for table `Student_Exam_Responses`
--

CREATE TABLE `Student_Exam_Responses` (
  `BookingID` varchar(10) NOT NULL,
  `QuestionID` varchar(10) NOT NULL,
  `Responses` varchar(1) DEFAULT NULL,
  `TimeSpent` time NOT NULL,
  `Score` int(11) NOT NULL,
  `TimeStamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Student_Exam_Responses`
--

INSERT INTO `Student_Exam_Responses` (`BookingID`, `QuestionID`, `Responses`, `TimeSpent`, `Score`, `TimeStamp`) VALUES
('AQ284IA786', 'BW125VQ089', 'B', '00:00:28', -2, '2025-02-19 15:33:59'),
('AQ284IA786', 'CE582OY502', 'B', '00:00:12', 6, '2025-02-19 15:34:43'),
('AQ284IA786', 'EB503AP789', 'A', '00:00:37', -1, '2025-02-19 15:35:20'),
('AQ284IA786', 'GJ769RY471', 'A', '00:00:16', 4, '2025-02-19 15:34:15'),
('AQ284IA786', 'HF994ZG330', 'B', '00:00:16', 4, '2025-02-19 15:34:31'),
('AQ284IA786', 'IT780AR349', 'C', '00:00:15', 6, '2025-02-19 15:35:35'),
('AQ284IA786', 'NJ388LE160', 'C', '00:00:08', 10, '2025-02-19 15:35:43'),
('AQ284IA786', 'NR665YO120', 'D', '00:00:29', 0, '2025-02-19 15:36:12'),
('AQ284IA786', 'RL926CI218', 'B', '00:00:05', 0, '2025-02-19 15:36:17'),
('AQ284IA786', 'WE744QZ708', 'B', '00:00:25', 0, '2025-02-19 15:36:42'),
('AQ284IA786', 'XT894SO625', 'D', '00:00:06', 0, '2025-02-19 15:36:48'),
('BM878BE386', 'BW125VQ089', 'B', '00:00:21', -2, '2025-02-19 15:22:45'),
('BM878BE386', 'CE582OY502', 'B', '00:00:08', 6, '2025-02-19 15:22:59'),
('BM878BE386', 'EB503AP789', 'C', '00:00:02', -1, '2025-02-19 15:23:01'),
('BM878BE386', 'GJ769RY471', 'A', '00:00:03', 4, '2025-02-19 15:22:48'),
('BM878BE386', 'HF994ZG330', 'B', '00:00:03', 4, '2025-02-19 15:22:51'),
('BM878BE386', 'IT780AR349', 'C', '00:00:06', 6, '2025-02-19 15:23:07'),
('IG129QP998', 'ME838VD139', 'D', '00:00:08', -2, '2025-02-19 15:33:14'),
('JQ013TP925', 'CR535RL355', 'B', '00:00:22', -1, '2025-04-23 13:43:44'),
('JQ013TP925', 'ES284QQ998', 'C', '00:00:04', 0, '2025-04-23 13:44:18'),
('JQ013TP925', 'FF711WO664', 'A', '00:00:07', -1, '2025-04-23 13:43:51'),
('JQ013TP925', 'FJ101ZZ542', 'C', '00:00:14', 0, '2025-04-23 13:44:32'),
('JQ013TP925', 'ME838VD139', 'C', '00:00:25', 4, '2025-04-23 13:43:18'),
('JQ013TP925', 'NA360CW850', 'D', '00:00:03', 0, '2025-04-23 13:44:35'),
('JQ013TP925', 'RF761LY305', 'C', '00:00:06', -1, '2025-04-23 13:43:57'),
('JQ013TP925', 'RZ948SF831', 'D', '00:00:10', -1, '2025-04-23 13:44:07'),
('JQ013TP925', 'SR398TC460', 'D', '00:00:05', -1, '2025-04-23 13:44:12'),
('JQ013TP925', 'UJ079IL498', 'B', '00:00:04', 4, '2025-04-23 13:43:22'),
('JQ013TP925', 'UQ518WP325', 'D', '00:00:02', -1, '2025-04-23 13:44:14'),
('KV593OV646', 'CR535RL355', 'A', '00:00:06', 6, '2025-02-17 04:42:00'),
('KV593OV646', 'ES284QQ998', 'D', '00:00:06', 10, '2025-02-17 04:42:10'),
('KV593OV646', 'FF711WO664', 'C', '00:00:04', 6, '2025-02-17 04:42:04'),
('KV593OV646', 'FJ101ZZ542', 'A', '00:00:04', 10, '2025-02-17 04:42:14'),
('KV593OV646', 'ME838VD139', 'C', '00:00:06', 4, '2025-02-17 04:41:52'),
('KV593OV646', 'NA360CW850', 'C', '00:00:04', 10, '2025-02-17 04:42:18'),
('KV593OV646', 'UJ079IL498', 'B', '00:00:02', 4, '2025-02-17 04:41:54'),
('NJ070KD567', 'CR535RL355', 'C', '00:00:07', -1, '2025-02-17 04:33:56'),
('NJ070KD567', 'ES284QQ998', 'D', '00:00:05', 10, '2025-02-17 04:34:16'),
('NJ070KD567', 'FF711WO664', 'C', '00:00:03', 6, '2025-02-17 04:33:59'),
('NJ070KD567', 'FJ101ZZ542', 'B', '00:00:04', 0, '2025-02-17 04:34:20'),
('NJ070KD567', 'ME838VD139', 'C', '00:00:08', 4, '2025-02-17 04:33:40'),
('NJ070KD567', 'NA360CW850', 'A', '00:00:05', 0, '2025-02-17 04:34:25'),
('NJ070KD567', 'RF761LY305', 'C', '00:00:09', -1, '2025-02-17 04:34:08'),
('NJ070KD567', 'RZ948SF831', 'A', '00:00:03', 6, '2025-02-17 04:34:11'),
('NJ070KD567', 'UJ079IL498', 'A', '00:00:06', -2, '2025-02-17 04:33:46'),
('NJ070KD567', 'VJ869CX614', 'A', '00:00:03', 4, '2025-02-17 04:33:49'),
('RD953SK827', 'BW125VQ089', 'A', '00:00:25', 4, '2025-02-19 15:05:10'),
('RD953SK827', 'CE582OY502', 'B', '00:00:27', 6, '2025-02-19 15:05:44'),
('RD953SK827', 'EB503AP789', 'A', '00:00:21', -1, '2025-02-19 15:06:05'),
('RD953SK827', 'GJ769RY471', 'A', '00:00:07', 4, '2025-02-19 15:05:17'),
('RD953SK827', 'IT780AR349', 'B', '00:00:11', -1, '2025-02-19 15:06:16'),
('RD953SK827', 'NJ388LE160', 'C', '00:00:17', 10, '2025-02-19 15:06:49'),
('RD953SK827', 'NR665YO120', 'C', '00:00:27', 10, '2025-02-19 15:07:16'),
('RD953SK827', 'RL926CI218', 'A', '00:00:06', 0, '2025-02-19 15:07:22'),
('RD953SK827', 'RW749OK798', 'C', '00:00:07', -1, '2025-02-19 15:06:23'),
('RD953SK827', 'WE744QZ708', 'B', '00:00:03', 0, '2025-02-19 15:07:25'),
('RD953SK827', 'WH157SC768', 'C', '00:00:09', -1, '2025-02-19 15:06:32'),
('RD953SK827', 'XT894SO625', 'B', '00:00:04', 0, '2025-02-19 15:07:29'),
('TW071FP127', 'BW125VQ089', 'A', '00:00:09', 4, '2025-02-19 15:09:53'),
('TW071FP127', 'CE582OY502', 'B', '00:00:04', 6, '2025-02-19 15:10:00'),
('TW071FP127', 'EB503AP789', 'C', '00:00:03', -1, '2025-02-19 15:10:03'),
('TW071FP127', 'GJ769RY471', 'A', '00:00:03', 4, '2025-02-19 15:09:56'),
('TW071FP127', 'IT780AR349', 'C', '00:00:01', 6, '2025-02-19 15:10:04'),
('VG619UP123', 'CR535RL355', 'A', '00:00:04', 6, '2025-02-17 04:44:25'),
('VG619UP123', 'ES284QQ998', 'B', '00:00:06', 0, '2025-02-17 04:44:33'),
('VG619UP123', 'FF711WO664', 'C', '00:00:02', 6, '2025-02-17 04:44:27'),
('VG619UP123', 'FJ101ZZ542', 'B', '00:00:05', 0, '2025-02-17 04:44:38'),
('VG619UP123', 'ME838VD139', 'A', '00:00:03', -2, '2025-02-17 04:44:09'),
('VG619UP123', 'NA360CW850', 'D', '00:00:05', 0, '2025-02-17 04:44:43'),
('VG619UP123', 'UJ079IL498', 'A', '00:00:03', -2, '2025-02-17 04:44:12'),
('VG619UP123', 'VJ869CX614', 'C', '00:00:03', -2, '2025-02-17 04:44:15'),
('VG619UP123', 'VW637EG107', 'A', '00:00:04', -2, '2025-02-17 04:44:19'),
('VG619UP123', 'ZG972ON291', 'B', '00:00:02', 4, '2025-02-17 04:44:21'),
('WK536HG724', 'CR535RL355', 'A', '00:00:16', 6, '2025-02-19 15:24:24'),
('WK536HG724', 'ES284QQ998', 'C', '00:00:04', 0, '2025-02-19 15:24:30'),
('WK536HG724', 'FF711WO664', 'C', '00:00:02', 6, '2025-02-19 15:24:26'),
('WK536HG724', 'ME838VD139', 'A', '00:00:43', -2, '2025-02-19 15:23:50'),
('WK536HG724', 'UJ079IL498', 'D', '00:00:02', -2, '2025-02-19 15:23:52'),
('WK536HG724', 'VJ869CX614', 'C', '00:00:03', -2, '2025-02-19 15:23:55'),
('WK536HG724', 'VW637EG107', 'C', '00:00:06', -2, '2025-02-19 15:24:01'),
('WK536HG724', 'ZG972ON291', 'D', '00:00:07', -2, '2025-02-19 15:24:08'),
('ZB687XL863', 'CR535RL355', 'A', '00:00:12', 6, '2025-04-16 13:00:04'),
('ZB687XL863', 'ES284QQ998', 'D', '00:00:06', 10, '2025-04-16 13:00:13'),
('ZB687XL863', 'FF711WO664', 'C', '00:00:03', 6, '2025-04-16 13:00:07'),
('ZB687XL863', 'FJ101ZZ542', 'A', '00:00:05', 10, '2025-04-16 13:00:18'),
('ZB687XL863', 'ME838VD139', 'C', '00:00:07', 4, '2025-04-16 12:59:39'),
('ZB687XL863', 'NA360CW850', 'C', '00:00:03', 10, '2025-04-16 13:00:21'),
('ZB687XL863', 'UJ079IL498', 'D', '00:00:04', -2, '2025-04-16 12:59:43'),
('ZB687XL863', 'VJ869CX614', 'A', '00:00:09', 4, '2025-04-16 12:59:52'),
('ZC131UU528', 'CR535RL355', 'A', '00:00:02', 6, '2025-02-17 04:46:10'),
('ZC131UU528', 'ES284QQ998', 'D', '00:00:03', 10, '2025-02-17 04:46:15'),
('ZC131UU528', 'FF711WO664', 'C', '00:00:02', 6, '2025-02-17 04:46:12'),
('ZC131UU528', 'FJ101ZZ542', 'A', '00:00:03', 10, '2025-02-17 04:46:18'),
('ZC131UU528', 'ME838VD139', 'A', '00:00:05', -2, '2025-02-17 04:45:58'),
('ZC131UU528', 'NA360CW850', 'C', '00:00:02', 10, '2025-02-17 04:46:20'),
('ZC131UU528', 'UJ079IL498', 'B', '00:00:02', 4, '2025-02-17 04:46:00'),
('ZC131UU528', 'VJ869CX614', 'B', '00:00:04', -2, '2025-02-17 04:46:04'),
('ZC131UU528', 'VW637EG107', 'D', '00:00:04', 4, '2025-02-17 04:46:08'),
('ZG363WY998', 'CR535RL355', 'C', '00:00:04', -1, '2025-02-17 04:07:11'),
('ZG363WY998', 'ES284QQ998', 'B', '00:00:03', 0, '2025-02-17 04:07:23'),
('ZG363WY998', 'FF711WO664', 'C', '00:00:04', 6, '2025-02-17 04:07:15'),
('ZG363WY998', 'FJ101ZZ542', 'C', '00:00:06', 0, '2025-02-17 04:07:29'),
('ZG363WY998', 'ME838VD139', 'C', '00:00:07', 4, '2025-02-17 04:07:00'),
('ZG363WY998', 'NA360CW850', 'C', '00:00:03', 10, '2025-02-17 04:07:32'),
('ZG363WY998', 'RF761LY305', 'D', '00:00:05', 6, '2025-02-17 04:07:20'),
('ZG363WY998', 'UJ079IL498', 'B', '00:00:07', 4, '2025-02-17 04:07:07');

-- --------------------------------------------------------

--
-- Table structure for table `Student_Records`
--

CREATE TABLE `Student_Records` (
  `StudentID` varchar(10) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `PhoneNo` varchar(10) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `ExamsRegistered` int(11) NOT NULL DEFAULT 0,
  `Username` varchar(100) NOT NULL,
  `Password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Student_Records`
--

INSERT INTO `Student_Records` (`StudentID`, `FirstName`, `LastName`, `PhoneNo`, `Email`, `ExamsRegistered`, `Username`, `Password`) VALUES
('1232178123', 'Collin', 'Pjanic', '9888773451', 'pjan@iitg.ac.in', 2, 'user6', '$2y$10$grCu3/uuYfrf9AMmG11JY.HOd4V/jSH2ODtF1o5wJ0Rt2hQUmcsNy'),
('1922174382', 'Bernie', 'Jerald', '7382928457', 'jer@iitg.ac.in', 2, 'user3', '$2y$10$vlv320.RVTNrP7R.JsJMx.bNkBox3bx2f.NaMOp9bWfxwEVozyD3W'),
('2187872491', 'Richie', 'Richard', '8787909123', 'richard@iitg.ac.in', 2, 'user2', '$2y$10$OBmhI9.ypmjvBfF8jLojB.YN6RY/xbBKPMEW0FtgQ5suBi.HpdaqC'),
('230100999', 'Alex', 'Jacques', '31253125', 'al@itg.ac.in', 1, '1', '$2y$10$YmEHYRlOrGgzI0tj1VLUuut19/ssQnLmnaSkqPA6ZXzteyD0flCFC'),
('23010101', 'David', 'Jones', '9897987987', 'dj@iitg.com', 1, 'user0', '$2y$10$9TTLl7JHxs6oeKnZH5qB.eFwUQwoqdklOL9vQi9drgTlJH5me5fy.'),
('230150007', 'Cherish', 'Billa', '9097908', 'c@c.com', 1, 'user', '$2y$10$v2kwVPJf2j8C2lEQFk9Y6u5sjQ0jc9nM1rSKbd6Sex6QP3MlB6QO2'),
('2301500070', 'Cherish', 'B', '9898989898', 'cherish@iitg.ac.in', 2, 'user1', '$2y$10$OeAIZSFIxygIwhVYQ7HsZux/XObh1AAOJfLv03UprPEfcnXWi1G6S'),
('2388889201', 'Oscar', 'Ham', '9999999999', 'aussie@iitg.ac.in', 1, 'user5', '$2y$10$FGFZsnhuJIdkckyE1Vhu0uL.TiZLRWl31UHQELgZe.fsQLrQ.0Bla'),
('870701441', 'Cherish', 'B', '980918080', 'c@iitg', 2, 'user10', '$2y$10$NCskagji4tFinGAGxtC.yeAOfl7tNd5iaAFtQOTia8KqKXQ5D8Gw6'),
('8765678911', 'Max', 'Bobb', '8787878001', 'bob@iitg.ac.in', 1, 'user4', '$2y$10$qb1BNrDlzPvNLzmvcSrP8uOBCnQUM4Butxh7SCIxUv.imIdFQRg62');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `Booking_Records`
--
ALTER TABLE `Booking_Records`
  ADD PRIMARY KEY (`BookingID`),
  ADD UNIQUE KEY `StudentID` (`StudentID`,`ExamID`,`SlotID`),
  ADD KEY `ExamID` (`ExamID`),
  ADD KEY `SlotID` (`SlotID`);

--
-- Indexes for table `Exam_Records`
--
ALTER TABLE `Exam_Records`
  ADD PRIMARY KEY (`ExamID`),
  ADD UNIQUE KEY `Name` (`Name`),
  ADD KEY `ProfID` (`ProfID`);

--
-- Indexes for table `Exam_Slots`
--
ALTER TABLE `Exam_Slots`
  ADD PRIMARY KEY (`ExamID`,`SlotID`),
  ADD KEY `SlotID` (`SlotID`);

--
-- Indexes for table `Images`
--
ALTER TABLE `Images`
  ADD PRIMARY KEY (`ImageID`);

--
-- Indexes for table `Prof_Records`
--
ALTER TABLE `Prof_Records`
  ADD PRIMARY KEY (`ProfID`),
  ADD UNIQUE KEY `PhoneNo` (`PhoneNo`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `Question_Records`
--
ALTER TABLE `Question_Records`
  ADD PRIMARY KEY (`QuestionID`),
  ADD KEY `ExamID` (`ExamID`);

--
-- Indexes for table `Slot_Records`
--
ALTER TABLE `Slot_Records`
  ADD PRIMARY KEY (`SlotID`),
  ADD UNIQUE KEY `Date` (`Date`,`StartTime`,`Duration`) USING HASH;

--
-- Indexes for table `Student_Exam_Dashboard`
--
ALTER TABLE `Student_Exam_Dashboard`
  ADD PRIMARY KEY (`BookingID`);

--
-- Indexes for table `Student_Exam_Responses`
--
ALTER TABLE `Student_Exam_Responses`
  ADD PRIMARY KEY (`BookingID`,`QuestionID`),
  ADD KEY `QuestionID` (`QuestionID`);

--
-- Indexes for table `Student_Records`
--
ALTER TABLE `Student_Records`
  ADD PRIMARY KEY (`StudentID`),
  ADD UNIQUE KEY `PhoneNo` (`PhoneNo`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Images`
--
ALTER TABLE `Images`
  MODIFY `ImageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Booking_Records`
--
ALTER TABLE `Booking_Records`
  ADD CONSTRAINT `booking_records_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `Student_Records` (`StudentID`),
  ADD CONSTRAINT `booking_records_ibfk_2` FOREIGN KEY (`ExamID`) REFERENCES `Exam_Records` (`ExamID`),
  ADD CONSTRAINT `booking_records_ibfk_3` FOREIGN KEY (`SlotID`) REFERENCES `Slot_Records` (`SlotID`);

--
-- Constraints for table `Exam_Records`
--
ALTER TABLE `Exam_Records`
  ADD CONSTRAINT `exam_records_ibfk_1` FOREIGN KEY (`ProfID`) REFERENCES `Prof_Records` (`ProfID`);

--
-- Constraints for table `Exam_Slots`
--
ALTER TABLE `Exam_Slots`
  ADD CONSTRAINT `exam_slots_ibfk_1` FOREIGN KEY (`ExamID`) REFERENCES `Exam_Records` (`ExamID`),
  ADD CONSTRAINT `exam_slots_ibfk_2` FOREIGN KEY (`SlotID`) REFERENCES `Slot_Records` (`SlotID`);

--
-- Constraints for table `Question_Records`
--
ALTER TABLE `Question_Records`
  ADD CONSTRAINT `question_records_ibfk_1` FOREIGN KEY (`ExamID`) REFERENCES `Exam_Records` (`ExamID`);

--
-- Constraints for table `Student_Exam_Dashboard`
--
ALTER TABLE `Student_Exam_Dashboard`
  ADD CONSTRAINT `student_exam_dashboard_ibfk_1` FOREIGN KEY (`BookingID`) REFERENCES `Booking_Records` (`BookingID`);

--
-- Constraints for table `Student_Exam_Responses`
--
ALTER TABLE `Student_Exam_Responses`
  ADD CONSTRAINT `student_exam_responses_ibfk_1` FOREIGN KEY (`BookingID`) REFERENCES `Booking_Records` (`BookingID`),
  ADD CONSTRAINT `student_exam_responses_ibfk_2` FOREIGN KEY (`QuestionID`) REFERENCES `Question_Records` (`QuestionID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
