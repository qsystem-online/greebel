/*
SQLyog Enterprise v10.42 
MySQL - 5.5.5-10.1.38-MariaDB : Database - db_greebel
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_greebel` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `db_greebel`;

/*Table structure for table `glaccountmaingroups` */

DROP TABLE IF EXISTS `glaccountmaingroups`;

CREATE TABLE `glaccountmaingroups` (
  `GLAccountMainGroupId` int(10) NOT NULL AUTO_INCREMENT,
  `GLAccountMainGroupName` varchar(100) DEFAULT NULL,
  `GLAccountMainPrefix` varchar(20) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`GLAccountMainGroupId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `glaccountmaingroups` */

/*Table structure for table `mswarehouse` */

DROP TABLE IF EXISTS `mswarehouse`;

CREATE TABLE `mswarehouse` (
  `fin_warehouse_id` int(5) NOT NULL AUTO_INCREMENT,
  `fin_branch_id` int(5) DEFAULT NULL,
  `fst_warehouse_name` varchar(100) DEFAULT NULL,
  `fbl_is_external` bit(1) DEFAULT NULL COMMENT 'Apakah Gudang External? Gudang External adalah gudang titipan customer, tidak masuk sebagai aset perusahaan',
  `fbl_is_main` bit(1) DEFAULT NULL COMMENT 'Gudang Utama (gudang default)',
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `mswarehouse` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
