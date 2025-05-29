-- Creating the Database

CREATE DATABASE `DA215_Lab4`;


-- Admin Table and Functions

CREATE TABLE `Admin` (`Username` VARCHAR(100) UNIQUE NOT NULL ,
                      `Password` TEXT NOT NULL);
                      
INSERT INTO `Admin` (`Username`,`Password`) VALUES ('user123','pass123');

CREATE TABLE `Slot_Records`(`SlotID` VARCHAR(10) PRIMARY KEY NOT NULL , 
                            `Date` DATE NOT NULL ,
                            `StartTime` TIME NOT NULL ,
                            `Duration` TIME NOT NULL ,
                             UNIQUE (`Date`,`StartTime`,`Duration`));
                             


-- Prof Table and Functions

CREATE TABLE `Prof_Records`  (`ProfID` VARCHAR(10) PRIMARY KEY NOT NULL ,
                              `FirstName` VARCHAR(100) NOT NULL ,
                              `LastName` VARCHAR(100) NOT NULL ,
                              `PhoneNo` VARCHAR(10) UNIQUE NOT NULL ,
							  `Email` VARCHAR(100) UNIQUE NOT NULL ,
                              `Department` VARCHAR(100) NOT NULL ,
                              `ExamsConducting` INT NOT NULL ,
                              `Username` VARCHAR(100) UNIQUE NOT NULL ,
                              `Password` TEXT NOT NULL);                           

CREATE TABLE `Exam_Records` (`ExamID` VARCHAR(10) PRIMARY KEY NOT NULL ,
                             `ProfID` VARCHAR(10) NOT NULL ,
                             `Name` VARCHAR(100) UNIQUE NOT NULL , 
                             `Fees` INT(6) NOT NULL ,
                             `StudentsRegistered` INT DEFAULT 0 NOT NULL ,
                              FOREIGN KEY (`ProfID`) REFERENCES `Prof_Records`(`ProfID`));
                              
CREATE TABLE `Question_Records` (`QuestionID` VARCHAR(10) PRIMARY KEY NOT NULL ,
                                 `Topic` VARCHAR(100) NOT NULL ,
                          		 `Question` TEXT NOT NULL ,
                                 `Difficulty` ENUM('Easy', 'Medium', 'Hard') NOT NULL ,
                                 `OptionA` VARCHAR(100) NOT NULL ,
                                 `OptionB` VARCHAR(100) NOT NULL ,
                                 `OptionC` VARCHAR(100) NOT NULL ,
                                 `OptionD` VARCHAR(100) NOT NULL ,
                                 `Answer` VARCHAR(1) NOT NULL , 
                                 `CorrectSubmissions` INT DEFAULT 0 NOT NULL ,
                                 `WrongSubmissions` INT DEFAULT 0 NOT NULL , 
                                 `ExamID` VARCHAR(10) NOT NULL,
                                  FOREIGN KEY(`ExamID`) REFERENCES `Exam_Records`(`ExamID`));

CREATE TABLE `Exam_Slots` (`ExamID` VARCHAR(10) NOT NULL , 
                           `SlotID` VARCHAR(10) NOT NULL ,
                            PRIMARY KEY (`ExamID`,`SlotID`),
                            FOREIGN KEY(`ExamID`) REFERENCES `Exam_Records`(`ExamID`),
                            FOREIGN KEY(`SlotID`) REFERENCES `Slot_Records`(`SlotID`));



-- Student Table and Functions

CREATE TABLE `Student_Records` (`StudentID` VARCHAR(10) PRIMARY KEY NOT NULL , 
                                `FirstName` VARCHAR(100) NOT NULL,
                                `LastName` VARCHAR(100) NOT NULL ,
                                `PhoneNo` VARCHAR(10) UNIQUE NOT NULL ,
								`Email` VARCHAR(100) UNIQUE NOT NULL ,
                               	`ExamsRegistered` INT DEFAULT 0 NOT NULL ,
                                `Username` VARCHAR(100) UNIQUE NOT NULL , 
                           		`Password` TEXT NOT NULL);
                           
CREATE TABLE `Booking_Records` (`BookingID` VARCHAR(10) PRIMARY KEY NOT NULL ,
                                `StudentID` VARCHAR(10) NOT NULL ,
                                `ExamID` VARCHAR(10) NOT NULL ,
                                `SlotID` VARCHAR(10) NOT NULL ,
                                 UNIQUE (`StudentID`,`ExamID`,`SlotID`),
                                 FOREIGN KEY(`StudentID`) REFERENCES `Student_Records`(`StudentID`),
                                 FOREIGN KEY(`ExamID`) REFERENCES `Exam_Records`(`ExamID`),
                                 FOREIGN KEY(`SlotID`) REFERENCES `Slot_Records`(`SlotID`));
                                 
-- During the Exam  

CREATE TABLE `Student_Exam_Responses`  (`BookingID` VARCHAR(10) NOT NULL , 
                             			`QuestionID` VARCHAR(10) NOT NULL ,
                             			`Responses` VARCHAR(1) ,
                                        `TimeSpent` TIME NOT NULL ,
                                       	`Score` INT NOT NULL ,
                                         PRIMARY KEY (`BookingID`,`QuestionID`),
                                         FOREIGN KEY(`BookingID`) REFERENCES `Booking_Records`(`BookingID`),
                                		 FOREIGN KEY(`QuestionID`) REFERENCES `Question_Records`(`QuestionID`));
                        
CREATE TABLE `Student_Exam_Dashboard` (`BookingID` VARCHAR(10) PRIMARY KEY NOT NULL ,
                                       `PhotoName` VARCHAR(255) ,
                                       `PhotoPath` VARCHAR(255) ,
                                       `TotalScore` INT DEFAULT 0 NOT NULL,
                                       `TotalTime` TIME DEFAULT '00:00:00' NOT NULL,
                                       `AvgScore` INT DEFAULT 0 NOT NULL,
                                       `AvgTime` TIME DEFAULT '00:00:00' NOT NULL ,
                                       `CurrentDifficulty` VARCHAR(10) DEFAULT 'Easy' NOT NULL ,
                                       `Percentile` DECIMAL(3,2),
                                        FOREIGN KEY(`BookingID`) REFERENCES `Booking_Records`(`BookingID`));




-- Dummy Input