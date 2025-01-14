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

/*Table structure for table `lecturers` */

DROP TABLE IF EXISTS `lecturers`;

CREATE TABLE `lecturers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `perguruan_tinggi` varchar(255) DEFAULT NULL,
  `program_studi` varchar(255) DEFAULT NULL,
  `jabatan_fungsional` varchar(100) DEFAULT NULL,
  `pendidikan_terakhir` varchar(100) DEFAULT NULL,
  `status_ikatan_kerja` varchar(100) DEFAULT NULL,
  `status_aktivitas` enum('Aktif','Tidak Aktif') DEFAULT NULL,
  `riwayat_pendidikan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`riwayat_pendidikan`)),
  `riwayat_mengajar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`riwayat_mengajar`)),
  `penelitian` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`penelitian`)),
  `pengabdian_masyarakat` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`pengabdian_masyarakat`)),
  `publikasi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`publikasi`)),
  `hki_paten` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`hki_paten`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status_aktivitas`),
  KEY `idx_jabatan` (`jabatan_fungsional`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `lecturers` */

insert  into `lecturers`(`id`,`nama`,`foto`,`jenis_kelamin`,`perguruan_tinggi`,`program_studi`,`jabatan_fungsional`,`pendidikan_terakhir`,`status_ikatan_kerja`,`status_aktivitas`,`riwayat_pendidikan`,`riwayat_mengajar`,`penelitian`,`pengabdian_masyarakat`,`publikasi`,`hki_paten`,`created_at`,`updated_at`) values 
(1,'Dr. Budi Santoso2','1736854059_sem.jpg','Laki-laki','Universitas Indonesia','Teknik Informatika123','Lektor Kepala','S3','Dosen Tetap','Aktif','[{\"perguruan_tinggi\":\"Stanford University123\",\"gelar\":\"Ph.D\",\"jenjang\":\"S3\",\"tahun\":\"2015\",\"deskripsi\":\"Specialization in Machine Learning and Computer Vision\"},{\"perguruan_tinggi\":\"TU Delft\",\"gelar\":\"M.Sc\",\"jenjang\":\"S2\",\"tahun\":\"2012\",\"deskripsi\":\"Focus on Artificial Intelligence and Data Science\"},{\"perguruan_tinggi\":\"Institut Teknologi Bandung\",\"gelar\":\"S.T.\",\"jenjang\":\"S1\",\"tahun\":\"2010\",\"deskripsi\":\"Computer Engineering with honors\"}]','[{\"tahun\":\"2022\\/2023\",\"mata_kuliah\":\"Advanced Machine Learning\",\"institusi\":\"UI\",\"deskripsi\":\"Graduate level course on deep learning and neural networks\"},{\"tahun\":\"2022\\/2023\",\"mata_kuliah\":\"Computer Vision\",\"institusi\":\"UI\",\"deskripsi\":\"Image processing and recognition systems\"},{\"tahun\":\"2021\\/2022\",\"mata_kuliah\":\"Artificial Intelligence\",\"institusi\":\"UI\",\"deskripsi\":\"Fundamental concepts in AI and expert systems\"}]','[{\"judul\":\"Deep Learning for Medical Imaging\",\"tahun\":\"2023\",\"deskripsi\":\"Research on automated diagnosis using CNN architectures\"},{\"judul\":\"AI in Healthcare\",\"tahun\":\"2022\",\"deskripsi\":\"Developing intelligent systems for patient care\"},{\"judul\":\"Computer Vision Applications\",\"tahun\":\"2021\",\"deskripsi\":\"Real-time object detection and tracking\"}]','[{\"judul\":\"AI Workshop for Hospitals\",\"tahun\":\"2023\",\"deskripsi\":\"Training medical staff on AI diagnostic tools\"},{\"judul\":\"Machine Learning Bootcamp\",\"tahun\":\"2022\",\"deskripsi\":\"Community training on ML fundamentals\"},{\"judul\":\"Computer Vision Workshop\",\"tahun\":\"2021\",\"deskripsi\":\"Teaching image processing techniques\"}]','[{\"judul\":\"Deep Learning in Healthcare\",\"jurnal\":\"Nature Digital Medicine\",\"tahun\":\"2023\",\"deskripsi\":\"Survey of AI applications in medical diagnosis\"},{\"judul\":\"Neural Networks for Medical Imaging\",\"jurnal\":\"IEEE Transactions on Medical Imaging\",\"tahun\":\"2022\",\"deskripsi\":\"Novel CNN architecture for diagnosis\"},{\"judul\":\"Computer Vision in Medicine\",\"jurnal\":\"Medical Image Analysis\",\"tahun\":\"2021\",\"deskripsi\":\"Automated disease detection\"}]','[{\"judul\":\"Medical Diagnosis System\",\"tahun\":\"2023\",\"deskripsi\":\"AI-powered diagnostic platform\"},{\"judul\":\"Neural Network Architecture\",\"tahun\":\"2022\",\"deskripsi\":\"Novel deep learning framework\"},{\"judul\":\"Image Recognition System\",\"tahun\":\"2021\",\"deskripsi\":\"Computer vision platform\"}]','2025-01-14 14:49:56','2025-01-14 16:23:16');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
