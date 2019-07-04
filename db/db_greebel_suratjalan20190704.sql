/*
SQLyog Ultimate v10.42 
MySQL - 5.5.5-10.1.37-MariaDB : Database - db_greebel
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `trsuratjalan` */

CREATE TABLE `trsuratjalan` (
  `fin_sj_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_sj_no` varchar(20) DEFAULT NULL,
  `fdt_sj_date` datetime DEFAULT NULL,
  `fin_salesorder_id` int(11) DEFAULT NULL,
  `fin_warehouse_id` int(11) DEFAULT NULL,
  `fst_no_polisi` varchar(10) DEFAULT NULL,
  `fin_driver_id` int(10) DEFAULT NULL,
  `fst_del_add` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `fst_no_reff` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `fst_sj_memo` text,
  `fin_print_no` int(4) DEFAULT NULL,
  `fst_sj_time` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
  `fbl_hold` bit(1) DEFAULT NULL,
  `fin_unhold_id` int(11) DEFAULT NULL,
  `fdt_sj_return` datetime DEFAULT NULL,
  `fst_sj_return_resino` varchar(20) DEFAULT NULL,
  `fin_sj_return_by_id` int(11) DEFAULT NULL,
  `fin_approved_by_id` int(11) DEFAULT NULL,
  `fbl_auto_printed` bit(1) DEFAULT NULL,
  `fst_no_inv_return` varchar(20) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_sj_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `trsuratjalandetail` */

CREATE TABLE `trsuratjalandetail` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_sj_id` int(11) DEFAULT NULL,
  `fin_item_id` int(11) DEFAULT NULL,
  `fin_qty` int(8) DEFAULT NULL,
  `fst_unit` varchar(100) DEFAULT NULL,
  `fin_qty_inv` int(8) DEFAULT NULL,
  `fin_conversion` decimal(12,2) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
