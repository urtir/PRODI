/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - informatics_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`informatics_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `informatics_db`;

/*Table structure for table `awards` */

DROP TABLE IF EXISTS `awards`;

CREATE TABLE `awards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `recipient` varchar(255) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `awards` */

insert  into `awards`(`id`,`title`,`recipient`,`year`,`description`) values 
(1,'Best Research Paper','Dr. John Smith',2024,'AI in Healthcare Research'),
(2,'Teaching Excellence','Dr. Sarah Johnson',2023,'Outstanding Teaching Performance'),
(3,'Innovation Award','Dr. Michael Chen',2024,'Network Security Breakthrough'),
(4,'Research Grant','Dr. Emily Brown',2023,'Software Engineering Project'),
(5,'Industry Collaboration','Dr. David Wilson',2024,'Corporate Partnership Success'),
(6,'Academic Achievement','Dr. Lisa Anderson',2023,'Machine Learning Advances'),
(7,'Community Service','Dr. Robert Taylor',2024,'Educational Outreach'),
(8,'International Recognition','Dr. Maria Garcia',2023,'Global Research Impact'),
(9,'Department Excellence','Dr. James Lee',2024,'Department Leadership'),
(10,'Student Choice','Dr. Anna White',2023,'Student Mentorship');

/*Table structure for table `courses` */

DROP TABLE IF EXISTS `courses`;

CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `courses` */

insert  into `courses`(`id`,`code`,`name`,`credits`,`description`,`image_url`,`semester`) values 
(1,'CS101','Introduction to Programming',3,'Basic programming concepts using Python','course_1.jpg',1),
(2,'CS102','Data Structures',4,'Fundamental data structures and algorithms','course_2.jpg',2),
(3,'CS201','Database Systems',3,'Database design and SQL','course_3.jpg',3),
(4,'CS202','Web Development',4,'Full-stack web development','course_4.jpg',4),
(5,'CS301','Artificial Intelligence',3,'AI concepts and applications','course_5.jpg',5),
(6,'CS302','Computer Networks',3,'Network protocols and architecture','course_6.jpg',5),
(7,'CS401','Software Engineering',4,'Software development lifecycle','course_7.jpg',6),
(8,'CS402','Mobile Computing',3,'Mobile app development','course_8.jpg',6),
(9,'CS403','Cloud Computing',3,'Cloud services and deployment','course_9.jpg',7),
(10,'CS404','Cybersecurity',4,'Security principles and practices','course_10.jpg',7);

/*Table structure for table `events` */

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `long_description` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `image_url2` varchar(255) DEFAULT NULL,
  `image_url3` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `events` */

insert  into `events`(`id`,`title`,`description`,`long_description`,`date`,`image_url`,`image_url2`,`image_url3`,`created_at`) values 
(1,'Tech Conference 2024','Annual technology conference with industry experts','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\nHighlights:\n- Professional speakers from tech industry\n- Hands-on workshops\n- Networking opportunities\n- Certificate of participation\n\nSchedule:\n09:00 - Registration\n10:00 - Opening Ceremony\n11:00 - Keynote Speech\n12:00 - Lunch Break\n13:00 - Workshop Sessions\n16:00 - Closing','2024-03-15','conf1.jpg',NULL,NULL,'2024-12-05 23:54:07'),
(2,'Coding Workshop','Learn Python programming basics','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\nHighlights:\n- Professional speakers from tech industry\n- Hands-on workshops\n- Networking opportunities\n- Certificate of participation\n\nSchedule:\n09:00 - Registration\n10:00 - Opening Ceremony\n11:00 - Keynote Speech\n12:00 - Lunch Break\n13:00 - Workshop Sessions\n16:00 - Closing','2024-03-20','workshop1.jpg',NULL,NULL,'2024-12-05 23:54:07'),
(3,'AI Symposium','Latest developments in artificial intelligence','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\nHighlights:\n- Professional speakers from tech industry\n- Hands-on workshops\n- Networking opportunities\n- Certificate of participation\n\nSchedule:\n09:00 - Registration\n10:00 - Opening Ceremony\n11:00 - Keynote Speech\n12:00 - Lunch Break\n13:00 - Workshop Sessions\n16:00 - Closing','2024-04-01','ai_symp.jpg',NULL,NULL,'2024-12-05 23:54:07'),
(4,'Hackathon 2024','24-hour coding competition',NULL,'2024-04-15','hackathon.jpg',NULL,NULL,'2024-12-05 23:54:07'),
(5,'Data Science Seminar','Big data analytics workshop',NULL,'2024-05-01','datasci.jpg',NULL,NULL,'2024-12-05 23:54:07'),
(6,'Game Dev Meetup','Gaming industry networking event',NULL,'2024-05-15','gamedev.jpg',NULL,NULL,'2024-12-05 23:54:07'),
(7,'IoT Workshop','Internet of Things practical session',NULL,'2024-06-01','iot.jpg',NULL,NULL,'2024-12-05 23:54:07'),
(8,'Security Conference','Cybersecurity best practices',NULL,'2024-06-15','security.jpg',NULL,NULL,'2024-12-05 23:54:07'),
(9,'Mobile Dev Talk','Mobile app development trends',NULL,'2024-07-01','mobile.jpg',NULL,NULL,'2024-12-05 23:54:07'),
(10,'Tech Career Fair','Meet top tech companies',NULL,'2024-07-15','career.jpg',NULL,NULL,'2024-12-05 23:54:07'),
(11,'Sidang Skripsi Johan1','Akhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johan','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\r\n\r\nHighlights:\r\n- Professional speakers from tech industry\r\n- Hands-on workshops\r\n- Networking opportunities\r\n- Certificate of participation\r\n\r\nSchedule:\r\n09:00 - Registration\r\n10:00 - Opening Ceremony\r\n11:00 - Keynote Speech\r\n12:00 - Lunch Break\r\n13:00 - Workshop Sessions\r\n16:00 - Closing','2024-12-07','johan.jpg',NULL,NULL,'2024-12-06 10:33:34');

/*Table structure for table `lecturers` */

DROP TABLE IF EXISTS `lecturers`;

CREATE TABLE `lecturers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `education_details` text DEFAULT NULL,
  `professional_profile` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `experience_years` varchar(50) DEFAULT NULL,
  `experience_location` varchar(100) DEFAULT NULL,
  `experience_details` text DEFAULT NULL,
  `additional_skills` text DEFAULT NULL,
  `social_linkedin` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `lecturers` */

insert  into `lecturers`(`id`,`name`,`title`,`specialization`,`image_url`,`email`,`education`,`education_details`,`professional_profile`,`experience`,`experience_years`,`experience_location`,`experience_details`,`additional_skills`,`social_linkedin`) values 
(1,'Dr. John Smith','Professor','Artificial Intelligence','smith.jpg','john.smith@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(2,'Dr. Sarah Johnson','Associate Professor','Database Systems','johnson.jpg','sarah.j@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(3,'Dr. Michael Chen','Assistant Professor','Computer Networks','chen.jpg','m.chen@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(4,'Dr. Emily Brown','Professor','Software Engineering','brown.jpg','e.brown@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(5,'Dr. David Wilson','Associate Professor','Cybersecurity','wilson.jpg','d.wilson@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(6,'Dr. Lisa Anderson','Professor','Machine Learning','anderson.jpg','l.anderson@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(7,'Dr. Robert Taylor','Assistant Professor','Mobile Computing','taylor.jpg','r.taylor@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(8,'Dr. Maria Garcia','Associate Professor','Data Science','garcia.jpg','m.garcia@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(9,'Dr. James Lee','Professor','Cloud Computing','lee.jpg','j.lee@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(10,'Dr. Anna White','Assistant Professor','Web Technologies','white.jpg','a.white@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(11,'Dr. John Smith','Professor of Computer Science','Artificial Intelligence and Machine Learning','john-smith.jpg','john.smith@university.edu','Ph.D. in Computer Science','Ph.D. in Computer Science, MIT (2010)<br>M.S. in Computer Science, Stanford University (2006)<br>B.S. in Computer Engineering, UC Berkeley (2004)','Distinguished researcher in artificial intelligence and machine learning with over 15 years of experience in academic and industry research. Published over 50 papers in top-tier conferences and journals.','Research and Teaching','15+ Years Experience','Silicon Valley & Academia',' Lead AI Research Scientist at Google (2010-2015)<br> Senior Machine Learning Engineer at Microsoft (2015-2018)<br> Professor of Computer Science (2018-present)<br> Published researcher with 50+ peer-reviewed papers','Python, TensorFlow, PyTorch, Deep Learning, Natural Language Processing, Computer Vision, Research Methods, Project Management','https://linkedin.com/in/johnsmith'),
(12,'Dr. Sarah Johnson','Associate Professor','Data Science and Big Data Analytics','sarah-johnson.jpg','sarah.j@university.edu','Ph.D. in Data Science','Ph.D. in Data Science, Harvard University (2012)<br>M.S. in Statistics, Yale University (2008)<br>B.S. in Mathematics, Princeton University (2006)','Expert in big data analytics and statistical modeling with extensive experience in both academic research and industry applications.','Data Science and Analytics','12+ Years Experience','Boston & New York',' Data Scientist at Amazon (2012-2016)<br> Lead Data Analyst at IBM (2016-2019)<br> Associate Professor (2019-present)<br> Author of \"Big Data Analytics in Practice\"','R, Python, Hadoop, Spark, Statistical Analysis, Machine Learning, Data Visualization, SQL, Big Data Technologies','https://linkedin.com/in/sarahjohnson'),
(13,'Prof. Michael Chen','Assistant Professor','Cybersecurity and Network Systems','michael-chen.jpg','m.chen@university.edu','Ph.D. in Computer Security','Ph.D. in Computer Security, Carnegie Mellon University (2015)<br>M.S. in Computer Networks, USC (2011)<br>B.S. in Computer Science, Georgia Tech (2009)','Specialized in network security and cryptography with focus on developing secure systems for enterprise applications.','Security Research','10+ Years Experience','Global Security Research',' Security Engineer at Cisco (2015-2018)<br> Senior Security Researcher at FireEye (2018-2020)<br> Assistant Professor (2020-present)<br> Multiple security patents holder','Network Security, Cryptography, Penetration Testing, Security Auditing, C++, Python, Java, Security Tools Development','https://linkedin.com/in/michaelchen');

/*Table structure for table `research` */

DROP TABLE IF EXISTS `research`;

CREATE TABLE `research` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `researchers` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `research` */

insert  into `research`(`id`,`title`,`description`,`year`,`researchers`,`status`) values 
(1,'AI in Healthcare','Implementing AI for medical diagnosis',2024,'Dr. Smith, Dr. Johnson','Ongoing'),
(2,'Smart City Development','IoT integration in urban planning',2024,'Dr. Chen, Dr. Brown','Ongoing'),
(3,'Quantum Computing','Quantum algorithms research',2023,'Dr. Wilson, Dr. Anderson','Completed'),
(4,'Blockchain Security','Enhanced cryptographic protocols',2024,'Dr. Taylor, Dr. Garcia','Ongoing'),
(5,'5G Networks','Next-gen mobile networks',2023,'Dr. Lee, Dr. White','Completed'),
(6,'Green Computing','Sustainable IT solutions',2024,'Dr. Smith, Dr. Lee','Ongoing'),
(7,'Cloud Security','Advanced cloud protection',2023,'Dr. Wilson, Dr. White','Completed'),
(8,'Big Data Analytics','Real-time data processing',2024,'Dr. Johnson, Dr. Garcia','Ongoing'),
(9,'AR/VR Education','Immersive learning systems',2024,'Dr. Brown, Dr. Taylor','Ongoing'),
(10,'Machine Learning Ethics','Ethical AI development',2023,'Dr. Anderson, Dr. Chen','Completed');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
