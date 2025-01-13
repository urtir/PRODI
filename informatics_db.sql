/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.4.32-MariaDB-log : Database - informatics_db
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

/*Table structure for table `comments` */

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `comments` */

insert  into `comments`(`id`,`post_id`,`user_id`,`content`,`created_at`) values 
(11,1,3,'Thanks for sharing! The Python lab sessions were really helpful.','2024-12-29 18:38:59'),
(12,1,4,'Which Python course did you take first?','2024-12-29 18:38:59'),
(13,2,2,'I\'m interested in joining the database project team!','2024-12-30 18:38:59'),
(14,2,4,'Count me in for the database project. I have experience with MySQL.','2024-12-30 18:38:59'),
(15,3,2,'The React workshop sounds great! When are you planning to hold it?','2024-12-31 18:38:59'),
(16,4,3,'Thanks for the update, admin!','2025-01-01 18:38:59'),
(17,5,4,'Let\'s form a study group for the competition. I\'m in!','2025-01-02 18:38:59'),
(18,6,2,'Which company did you intern at? Would love to hear more!','2025-01-04 18:38:59'),
(19,7,3,'Will the tech talk be recorded?','2025-01-05 18:38:59'),
(20,8,4,'Interested in the IoT project. Let\'s discuss!','2025-01-06 18:38:59'),
(21,8,4,'halo made','2025-01-07 18:40:09'),
(22,11,3,'gampang bang, cari sponsor aja kaya BANK DKI','2025-01-07 18:41:59');

/*Table structure for table `contact_messages` */

DROP TABLE IF EXISTS `contact_messages`;

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('unread','read') DEFAULT 'unread',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `contact_messages` */

insert  into `contact_messages`(`id`,`name`,`email`,`subject`,`message`,`created_at`,`status`) values 
(1,'Rizqullah Aryaputra','risqullaharya16@gmail.com','halo','halo','2025-01-08 07:40:29','unread');

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `events` */

insert  into `events`(`id`,`title`,`description`,`long_description`,`date`,`image_url`,`image_url2`,`image_url3`,`created_at`) values 
(1,'Tech Conference 2024','Akhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johan','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\nHighlights:\n- Professional speakers from tech industry\n- Hands-on workshops\n- Networking opportunities\n- Certificate of participation\n\nSchedule:\n09:00 - Registration\n10:00 - Opening Ceremony\n11:00 - Keynote Speech\n12:00 - Lunch Break\n13:00 - Workshop Sessions\n16:00 - Closing','2024-03-15','conf1.jpg',NULL,NULL,'2024-12-05 20:54:07'),
(2,'Coding Workshop','Akhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johan','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\nHighlights:\n- Professional speakers from tech industry\n- Hands-on workshops\n- Networking opportunities\n- Certificate of participation\n\nSchedule:\n09:00 - Registration\n10:00 - Opening Ceremony\n11:00 - Keynote Speech\n12:00 - Lunch Break\n13:00 - Workshop Sessions\n16:00 - Closing','2024-03-20','workshop1.jpg',NULL,NULL,'2024-12-05 20:54:07'),
(3,'AI Symposium','Akhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johan','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\nHighlights:\n- Professional speakers from tech industry\n- Hands-on workshops\n- Networking opportunities\n- Certificate of participation\n\nSchedule:\n09:00 - Registration\n10:00 - Opening Ceremony\n11:00 - Keynote Speech\n12:00 - Lunch Break\n13:00 - Workshop Sessions\n16:00 - Closing','2024-04-01','ai_symp.jpg',NULL,NULL,'2024-12-05 20:54:07'),
(4,'Hackathon 2024','Akhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johan',NULL,'2024-04-15','hackathon.jpg',NULL,NULL,'2024-12-05 20:54:07'),
(5,'Data Science Seminar','Akhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johan',NULL,'2024-05-01','datasci.jpg',NULL,NULL,'2024-12-05 20:54:07'),
(6,'Game Dev Meetup','Akhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johan',NULL,'2024-05-15','gamedev.jpg',NULL,NULL,'2024-12-05 20:54:07'),
(7,'IoT Workshop','Akhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johan',NULL,'2024-06-01','iot.jpg',NULL,NULL,'2024-12-05 20:54:07'),
(8,'Security Conference','Akhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johan',NULL,'2024-06-15','security.jpg',NULL,NULL,'2024-12-05 20:54:07'),
(9,'Mobile Dev Talk','Akhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johan',NULL,'2024-07-01','mobile.jpg',NULL,NULL,'2024-12-05 20:54:07'),
(10,'Tech Career Fair','Akhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johan',NULL,'2024-07-15','career.jpg',NULL,NULL,'2024-12-05 20:54:07'),
(11,'Sidang Skripsi Johan1','Akhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johanAkhirnya Johan Lulus dari Unhan teman-teman setelah bertahun tahun mencoba lulus di matkul filsafat pertahanan. selamat johan','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\r\n\r\nHighlights:\r\n- Professional speakers from tech industry\r\n- Hands-on workshops\r\n- Networking opportunities\r\n- Certificate of participation\r\n\r\nSchedule:\r\n09:00 - Registration\r\n10:00 - Opening Ceremony\r\n11:00 - Keynote Speech\r\n12:00 - Lunch Break\r\n13:00 - Workshop Sessions\r\n16:00 - Closing','2024-12-07','johan.jpg',NULL,NULL,'2024-12-06 07:33:34'),
(12,'Seminar Artificial Intelligence','Seminar mengenai perkembangan terkini dalam bidang AI dan implementasinya.','Seminar ini akan membahas secara mendalam tentang perkembangan terbaru dalam bidang Artificial Intelligence, termasuk:\n\n    - Machine Learning dan Deep Learning terkini\n    - Implementasi AI dalam industri\n    - Ethical AI dan dampaknya terhadap masyarakat\n    - Hands-on workshop dengan tools AI modern\n    - Networking dengan para praktisi AI\n    \n    Pembicara tamu dari berbagai perusahaan teknologi terkemuka akan hadir untuk berbagi pengalaman dan insight mendalam tentang industri AI.','2024-03-15','ai-seminar.jpg','ai-workshop.jpg','ai-networking.jpg','2024-12-09 16:42:06'),
(13,'Workshop Data Science','Workshop praktis mengenai analisis data dan visualisasi menggunakan Python.','Workshop Data Science ini dirancang untuk memberikan pengalaman hands-on dalam:\n\n    - Pengolahan data menggunakan Pandas\n    - Visualisasi data dengan Matplotlib dan Seaborn\n    - Analisis statistik dasar hingga lanjutan\n    - Machine Learning dengan scikit-learn\n    - Project-based learning dengan dataset real\n    \n    Peserta akan mendapatkan sertifikat resmi dan akses ke materi pembelajaran online selama 3 bulan.','2024-03-20','data-science.jpg','python-workshop.jpg','data-viz.jpg','2024-12-09 16:42:06'),
(14,'Hackathon Informatika 2024','Kompetisi programming 48 jam untuk mahasiswa informatika.','Hackathon Informatika 2024 adalah kompetisi programming intensif selama 48 jam yang akan menantang peserta untuk:\n\n    - Mengembangkan solusi inovatif untuk masalah real\n    - Bekerja dalam tim multidisiplin\n    - Menggunakan teknologi terkini\n    - Mentoring dari praktisi industri\n    - Kesempatan magang di perusahaan partner\n    \n    Total hadiah senilai Rp 50.000.000 dan kesempatan inkubasi startup.','2024-04-01','hackathon.jpg','coding-competition.jpg','team-collaboration.jpg','2024-12-09 16:42:06'),
(15,'Guest Lecture: Cyber Security','Kuliah tamu tentang keamanan siber oleh pakar industri.','Kuliah tamu ini akan membahas topik-topik kritis dalam keamanan siber:\n\n    - Trend terbaru dalam cyber threats\n    - Best practices dalam security\n    - Penetration testing dan ethical hacking\n    - Career path dalam cyber security\n    - Live demonstration keamanan sistem\n    \n    Dipresentasikan oleh pakar keamanan siber dengan pengalaman lebih dari 15 tahun di industri.','2024-04-15','cyber-security.jpg','ethical-hacking.jpg','security-demo.jpg','2024-12-09 16:42:06'),
(16,'Lomba Web Development','Kompetisi pengembangan website untuk mahasiswa.','Kompetisi Web Development ini akan menguji kemampuan peserta dalam:\n\n    - Front-end development (HTML5, CSS3, JavaScript)\n    - Back-end programming\n    - Database design\n    - UI/UX design\n    - Web security implementation\n    \n    Proyek akan dinilai berdasarkan kreativitas, fungsionalitas, dan kualitas kode.','2024-04-30','web-dev.jpg','coding-contest.jpg','ui-design.jpg','2024-12-09 16:42:06'),
(17,'Industrial Visit: Tech Company','Kunjungan industri ke perusahaan teknologi terkemuka.','Kunjungan industri ini memberikan kesempatan kepada mahasiswa untuk:\n\n    - Melihat langsung operasional perusahaan teknologi\n    - Interaksi dengan para profesional IT\n    - Understanding of industrial best practices\n    - Potential internship opportunities\n    - Networking dengan alumni yang bekerja di industri\n    \n    Termasuk sesi Q&A dan sharing session dengan tim development.','2024-05-15','industry-visit.jpg','tech-company.jpg','networking.jpg','2024-12-09 16:42:06');

/*Table structure for table `faqs` */

DROP TABLE IF EXISTS `faqs`;

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `question` text NOT NULL,
  `answer` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','answered') DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `idx_faqs_status` (`status`),
  KEY `idx_faqs_user` (`user_id`),
  CONSTRAINT `faqs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `faqs` */

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
  `bio` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `lecturers` */

insert  into `lecturers`(`id`,`name`,`title`,`specialization`,`image_url`,`email`,`education`,`education_details`,`professional_profile`,`experience`,`experience_years`,`experience_location`,`experience_details`,`additional_skills`,`social_linkedin`,`bio`) values 
(1,'Dr. John Smith','Professor','Artificial Intelligence','smith.jpg','john.smith@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(2,'Dr. Sarah Johnson','Associate Professor','Database Systems','johnson.jpg','sarah.j@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(3,'Dr. Michael Chen','Assistant Professor','Computer Networks','chen.jpg','m.chen@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(4,'Dr. Emily Brown','Professor','Software Engineering','brown.jpg','e.brown@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(5,'Dr. David Wilson','Associate Professor','Cybersecurity','wilson.jpg','d.wilson@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(6,'Dr. Lisa Anderson','Professor','Machine Learning','anderson.jpg','l.anderson@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(7,'Dr. Robert Taylor','Assistant Professor','Mobile Computing','taylor.jpg','r.taylor@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(8,'Dr. Maria Garcia','Associate Professor','Data Science','garcia.jpg','m.garcia@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(9,'Dr. James Lee','Professor','Cloud Computing','lee.jpg','j.lee@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(10,'Dr. Anna White','Assistant Professor','Web Technologies','white.jpg','a.white@univ.edu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(11,'Dr. John Smith','Professor of Computer Science','Artificial Intelligence and Machine Learning','john-smith.jpg','john.smith@university.edu','Ph.D. in Computer Science','Ph.D. in Computer Science, MIT (2010)<br>M.S. in Computer Science, Stanford University (2006)<br>B.S. in Computer Engineering, UC Berkeley (2004)','Distinguished researcher in artificial intelligence and machine learning with over 15 years of experience in academic and industry research. Published over 50 papers in top-tier conferences and journals.','Research and Teaching','15+ Years Experience','Silicon Valley & Academia',' Lead AI Research Scientist at Google (2010-2015)<br> Senior Machine Learning Engineer at Microsoft (2015-2018)<br> Professor of Computer Science (2018-present)<br> Published researcher with 50+ peer-reviewed papers','Python, TensorFlow, PyTorch, Deep Learning, Natural Language Processing, Computer Vision, Research Methods, Project Management','https://linkedin.com/in/johnsmith',NULL),
(12,'Dr. Sarah Johnson','Associate Professor','Data Science and Big Data Analytics','sarah-johnson.jpg','sarah.j@university.edu','Ph.D. in Data Science','Ph.D. in Data Science, Harvard University (2012)<br>M.S. in Statistics, Yale University (2008)<br>B.S. in Mathematics, Princeton University (2006)','Expert in big data analytics and statistical modeling with extensive experience in both academic research and industry applications.','Data Science and Analytics','12+ Years Experience','Boston & New York',' Data Scientist at Amazon (2012-2016)<br> Lead Data Analyst at IBM (2016-2019)<br> Associate Professor (2019-present)<br> Author of \"Big Data Analytics in Practice\"','R, Python, Hadoop, Spark, Statistical Analysis, Machine Learning, Data Visualization, SQL, Big Data Technologies','https://linkedin.com/in/sarahjohnson',NULL),
(13,'Prof. Michael Chen','Assistant Professor','Cybersecurity and Network Systems','michael-chen.jpg','m.chen@university.edu','Ph.D. in Computer Security','Ph.D. in Computer Security, Carnegie Mellon University (2015)<br>M.S. in Computer Networks, USC (2011)<br>B.S. in Computer Science, Georgia Tech (2009)','Specialized in network security and cryptography with focus on developing secure systems for enterprise applications.','Security Research','10+ Years Experience','Global Security Research',' Security Engineer at Cisco (2015-2018)<br> Senior Security Researcher at FireEye (2018-2020)<br> Assistant Professor (2020-present)<br> Multiple security patents holder','Network Security, Cryptography, Penetration Testing, Security Auditing, C++, Python, Java, Security Tools Development','https://linkedin.com/in/michaelchen',NULL),
(14,'Dr. John Doe','Professor of Computer Science',NULL,'johndoe.jpg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Dr. Doe has over 20 years of experience...');

/*Table structure for table `posts` */

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `posts` */

insert  into `posts`(`id`,`user_id`,`title`,`content`,`created_at`) values 
(1,2,'Experience in Informatics Programming','Hi everyone! Just wanted to share my experience learning Python in our department. The labs are well-equipped and professors are helpful.','2024-12-28 18:38:57'),
(2,3,'Database Project Group','Looking for team members for the upcoming database project. Need 2-3 people who are interested in MySQL and web development.','2024-12-29 18:38:57'),
(3,4,'Web Development Workshop','Is anyone interested in joining a weekend workshop on React.js? We can share knowledge and practice together.','2024-12-30 18:38:57'),
(4,1,'Important: New Lab Schedule','The computer lab schedule has been updated for this semester. Please check the notice board for details.','2024-12-31 18:38:57'),
(5,2,'Programming Competition','Who is joining the upcoming coding competition? We could form study groups to prepare together.','2025-01-01 18:38:57'),
(6,3,'Software Engineering Notes','Sharing my notes from last semester\'s Software Engineering course. Hope it helps the juniors!','2025-01-02 18:38:57'),
(7,4,'Internship Experience','Just completed my summer internship at a local tech company. Happy to share insights with anyone interested.','2025-01-03 18:38:57'),
(8,1,'Department Event: Tech Talk','Next week we will have industry experts talking about AI and Machine Learning. Don\'t miss it!','2025-01-04 18:38:57'),
(9,2,'Study Resources','Found some great online resources for Data Structures. Check them out: [links removed]','2025-01-05 18:38:57'),
(10,3,'Final Year Project Ideas','Brainstorming for final year project topics. Anyone interested in collaborating on an IoT project?','2025-01-06 18:38:57'),
(11,2,'WOI GIMANA SIH BANG CARA MASUK INFORMATIKA???','gua pen masuk infor tapi gua gada basic mancing','2025-01-07 18:41:02');

/*Table structure for table `private_messages` */

DROP TABLE IF EXISTS `private_messages`;

CREATE TABLE `private_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('unread','read') DEFAULT 'unread',
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  CONSTRAINT `private_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  CONSTRAINT `private_messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `private_messages` */

insert  into `private_messages`(`id`,`sender_id`,`receiver_id`,`subject`,`message`,`created_at`,`status`) values 
(1,3,2,'halo','halo ido','2025-01-08 07:37:44','unread'),
(2,3,2,'','woiiii','2025-01-08 08:39:07','unread'),
(3,2,3,'','gimana jere','2025-01-08 08:43:09','unread'),
(4,2,3,'','apakabar jere','2025-01-08 08:43:22','unread'),
(5,3,2,'','woiii ido','2025-01-08 08:44:40','unread'),
(6,3,1,'','dsad','2025-01-08 08:51:57','unread');

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

/*Table structure for table `research_news` */

DROP TABLE IF EXISTS `research_news`;

CREATE TABLE `research_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `DATE` date NOT NULL,
  `TEXT` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `research_news` */

insert  into `research_news`(`id`,`DATE`,`TEXT`,`created_at`) values 
(11,'2024-12-10','New research on AI advancements in healthcare has been published.','2024-12-11 06:36:15'),
(12,'2024-11-05','Climate change research shows alarming trends in global temperature rise.','2024-12-11 06:36:15'),
(13,'2024-10-01','New findings in the field of quantum computing reveal breakthrough technologies.','2024-12-11 06:36:15'),
(14,'2024-09-15','The latest study on renewable energy sources suggests a bright future for solar power.','2024-12-11 06:36:15');

/*Table structure for table `researches` */

DROP TABLE IF EXISTS `researches`;

CREATE TABLE `researches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `researches` */

insert  into `researches`(`id`,`title`,`description`,`image_url`,`details`,`created_at`) values 
(11,'AI in Healthcare','Exploring the applications of AI in improving healthcare systems.','research-img_02.jpg','This research focuses on the role of Artificial Intelligence in diagnosing diseases, optimizing hospital operations, and improving patient care.','2024-12-11 06:36:15'),
(12,'Climate Change Impact','The effects of climate change on global ecosystems and human societies.','research-img_02.jpg','This study examines the rising sea levels, extreme weather events, and the economic consequences of climate change.','2024-12-11 06:36:15'),
(13,'Quantum Computing Breakthrough','New developments in quantum computing could revolutionize various industries.','research-img_02.jpg','Research highlights the potential of quantum computing in solving complex problems in fields like cryptography, AI, and material science.','2024-12-11 06:36:15'),
(14,'Renewable Energy Sources','The future of energy: solar, wind, and other renewable sources.','research-img_02.jpg','This research covers the advancements in renewable energy technologies and their potential to replace fossil fuels in the global energy mix.','2024-12-11 06:36:15');

/*Table structure for table `resources` */

DROP TABLE IF EXISTS `resources`;

CREATE TABLE `resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `resources` */

insert  into `resources`(`id`,`title`,`description`,`image_url`,`created_at`) values 
(4,'Research Paper on AI','A comprehensive paper on the application of AI in various industries.','research-img_02.jpg','2024-12-11 06:36:15'),
(5,'Global Warming Data','A set of global warming data, including temperature changes and CO2 levels.','research-img_02.jpg','2024-12-11 06:36:15'),
(6,'Quantum Computing Journal','A journal featuring the latest research and breakthroughs in quantum computing.','research-img_02.jpg','2024-12-11 06:36:15');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`email`,`password`,`firstname`,`lastname`,`created_at`,`role`) values 
(1,'admin','admin@example.com','$2y$10$5lY.UamUo35SjczdkOJsUOeU5N9ypjJ6zaG2O2FWrY2p/Tz5F1ILC','Admin','User','2025-01-07 17:37:43','admin'),
(2,'idojaya@gmail.com','idojaya@gmail.com','$2y$10$5lY.UamUo35SjczdkOJsUOeU5N9ypjJ6zaG2O2FWrY2p/Tz5F1ILC','Ido','Jaya','2025-01-07 17:44:02','admin'),
(3,'jeremia@gmail.com','jeremia@gmail.com','$2y$10$HDGLUK1G6sITEVBZSx7/tuTzK4JmIg0PyPNp6niV1WZ20SZyHUI1m','Jeremia','Sinaga','2025-01-07 18:05:54','user'),
(4,'made@gmail.com','made@gmail.com','$2y$10$t61IEXJejK7lFgCjKEpVmepbM8okUylR.N3OpX3eHxeOn5GopVJWG','made','made','2025-01-07 18:31:59','user');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
