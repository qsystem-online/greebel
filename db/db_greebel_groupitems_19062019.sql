/*
SQLyog Ultimate v10.42 
MySQL - 5.5.5-10.1.13-MariaDB : Database - db_greebel
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

/*Table structure for table `msgroupitems` */

DROP TABLE IF EXISTS `msgroupitems`;

CREATE TABLE `msgroupitems` (
  `ItemGroupId` int(10) NOT NULL AUTO_INCREMENT,
  `ItemGroupName` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ItemGroupId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `msgroupitems` */

insert  into `msgroupitems`(`ItemGroupId`,`ItemGroupName`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'STATIONARY','A',0,'0000-00-00 00:00:00',1,'2019-05-08 20:36:21'),(2,'FANCY','A',0,'0000-00-00 00:00:00',1,'2019-05-08 20:37:44'),(3,'PROMO','A',1,'2019-05-08 20:37:34',NULL,NULL);

/*Table structure for table `msmaingroupitems` */

DROP TABLE IF EXISTS `msmaingroupitems`;

CREATE TABLE `msmaingroupitems` (
  `ItemMainGroupId` int(10) NOT NULL AUTO_INCREMENT,
  `ItemMainGroupName` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ItemMainGroupId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `msmaingroupitems` */

insert  into `msmaingroupitems`(`ItemMainGroupId`,`ItemMainGroupName`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'GREEBEL','A',0,'0000-00-00 00:00:00',NULL,NULL),(2,'UMUM','A',0,'0000-00-00 00:00:00',1,'2019-05-08 20:10:31'),(3,'PARKO','A',1,'2019-05-08 17:31:02',NULL,NULL);

/*Table structure for table `mssubgroupitems` */

DROP TABLE IF EXISTS `mssubgroupitems`;

CREATE TABLE `mssubgroupitems` (
  `ItemSubGroupId` int(10) NOT NULL AUTO_INCREMENT,
  `ItemSubGroupName` varchar(100) NOT NULL,
  `ItemGroupId` int(10) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ItemSubGroupId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `mssubgroupitems` */

insert  into `mssubgroupitems`(`ItemSubGroupId`,`ItemSubGroupName`,`ItemGroupId`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'PENCIL',2,'A',0,'0000-00-00 00:00:00',1,'2019-05-13 14:20:11'),(2,'CRAYON',1,'A',0,'0000-00-00 00:00:00',NULL,NULL),(3,'FANCY',2,'A',0,'0000-00-00 00:00:00',NULL,NULL),(4,'PROMO SBY',3,'A',1,'2019-05-09 09:54:47',1,'2019-05-13 12:34:57'),(5,'PROMO JKT',3,'A',1,'2019-05-09 09:55:04',1,'2019-05-13 14:19:52');

/*Table structure for table `msunits` */

DROP TABLE IF EXISTS `msunits`;

CREATE TABLE `msunits` (
  `RecId` int(10) NOT NULL AUTO_INCREMENT,
  `Unit` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RecId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `msunits` */

insert  into `msunits`(`RecId`,`Unit`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'KG','A',0,'0000-00-00 00:00:00',1,'2019-05-08 20:36:21'),(2,'PCS','A',0,'0000-00-00 00:00:00',1,'2019-05-08 20:37:44'),(3,'SET','A',1,'2019-05-08 20:37:34',NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
