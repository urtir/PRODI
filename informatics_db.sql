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
  `materials_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `courses` */

insert  into `courses`(`id`,`code`,`name`,`credits`,`description`,`image_url`,`semester`,`materials_url`) values 
(1,'CS101','SDASDASD',4,'DASDASDASD','1736874560_1736794788_event-img_2.jpg',1,'course_materials/CS101/materials_ril.zip'),
(2,'CS102','Data Structures',4,'Fundamental data structures and algorithms','course_2.jpg',2,'course_materials/CS102/materials.zip'),
(3,'CS201','Database Systems',3,'Database design and SQL','course_3.jpg',3,'course_materials/CS201/materials.zip'),
(4,'CS202','Web Development',4,'Full-stack web development','course_4.jpg',4,'course_materials/CS202/materials.zip'),
(5,'CS301','Artificial Intelligence',3,'AI concepts and applications','course_5.jpg',5,'course_materials/CS301/materials.zip'),
(6,'CS302','Computer Networks',3,'Network protocols and architecture','course_6.jpg',5,'course_materials/CS302/materials.zip'),
(7,'CS401','Software Engineering',4,'Software development lifecycle','course_7.jpg',6,'course_materials/CS401/materials.zip'),
(8,'CS402','Mobile Computing',3,'Mobile app development','course_8.jpg',6,'course_materials/CS402/materials.zip'),
(9,'CS403','Cloud Computing',3,'Cloud services and deployment','course_9.jpg',7,'course_materials/CS403/materials.zip'),
(10,'CS404','Cybersecurity',4,'Security principles and practices','course_10.jpg',7,'course_materials/CS404/materials.zip'),
(11,'CINTA101','Cara Mencintai Dalam DIam',6,'Aikwokwoakde','1736794788_event-img_2.jpg',6,'course_materials/CINTA101/materials.zip'),
(12,'sadsadasd','sadasdasd',2,'fafadfdfafad','1736875214_1736874560_1736794788_event-img_2.jpg',2,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
