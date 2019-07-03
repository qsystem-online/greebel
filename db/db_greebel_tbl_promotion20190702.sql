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
/*Table structure for table `mspromo` */

CREATE TABLE `mspromo` (
  `fin_promo_id` int(10) NOT NULL AUTO_INCREMENT,
  `fst_promo_name` varchar(100) DEFAULT NULL,
  `fdt_start` date DEFAULT NULL,
  `fdt_end` date DEFAULT NULL,
  `fin_promo_item_id` int(10) DEFAULT NULL,
  `fin_promo_qty` int(5) DEFAULT NULL,
  `fin_promo_unit` varchar(100) DEFAULT NULL,
  `fin_cashback` decimal(12,2) DEFAULT NULL,
  `fst_other_prize` varchar(100) DEFAULT NULL,
  `fbl_qty_gabungan` tinyint(1) DEFAULT NULL,
  `fin_qty_gabungan` int(5) DEFAULT NULL,
  `fst_satuan_gabungan` varchar(100) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_promo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `mspromoitems` */

CREATE TABLE `mspromoitems` (
  `fin_id` int(10) NOT NULL AUTO_INCREMENT,
  `fin_promo_id` int(4) DEFAULT NULL,
  `fin_item_id` int(10) DEFAULT NULL,
  `fin_qty` int(5) DEFAULT NULL,
  `fst_unit` varchar(100) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `mspromoitemscustomer` */

CREATE TABLE `mspromoitemscustomer` (
  `fin_id` int(10) NOT NULL AUTO_INCREMENT,
  `fin_promo_id` int(4) DEFAULT NULL,
  `fin_customer_id` int(10) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
