/*
SQLyog Ultimate v10.42 
MySQL - 5.5.5-10.2.30-MariaDB : Database - u5538790_greebel
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`u5538790_greebel` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `u5538790_greebel`;

/*Table structure for table `mslinebusiness` */

DROP TABLE IF EXISTS `mslinebusiness`;

CREATE TABLE `mslinebusiness` (
  `fin_linebusiness_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_linebusiness_name` varchar(100) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_linebusiness_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `mslinebusiness` */

insert  into `mslinebusiness`(`fin_linebusiness_id`,`fst_linebusiness_name`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'ALAT TULIS KANTOR','A',12,'2019-11-19 11:10:10',NULL,NULL),(2,'SPAREPART MESIN','A',12,'2019-11-19 11:10:33',12,'2019-11-19 11:15:04'),(3,'PLASTIK','A',12,'2019-11-19 11:11:45',NULL,NULL),(4,'KIMIA','A',NULL,'2019-11-19 13:14:22',NULL,NULL),(5,'ALAT GAMBAR','A',4,'2019-11-20 10:25:13',NULL,NULL),(6,'PERALATAN MANCING','A',4,'2019-11-20 10:25:36',NULL,NULL),(7,'ALAT OLAHRAGA','A',4,'2019-11-20 10:26:11',NULL,NULL),(8,'AKSESORIS WANITA','A',4,'2019-11-20 10:37:40',NULL,NULL);

/*Table structure for table `msmaingroupitems` */

DROP TABLE IF EXISTS `msmaingroupitems`;

CREATE TABLE `msmaingroupitems` (
  `fin_item_maingroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_item_maingroup_name` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_item_maingroup_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `msmaingroupitems` */

insert  into `msmaingroupitems`(`fin_item_maingroup_id`,`fst_item_maingroup_name`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'GREEBEL','A',0,'0000-00-00 00:00:00',4,'2019-07-16 15:17:07'),(2,'UMUM','A',0,'0000-00-00 00:00:00',1,'2019-05-08 20:10:31'),(3,'PARKO','A',1,'2019-05-08 17:31:02',NULL,NULL),(4,'FIBER','A',4,'2019-07-16 15:17:29',NULL,NULL);

/*Table structure for table `msmembergroups` */

DROP TABLE IF EXISTS `msmembergroups`;

CREATE TABLE `msmembergroups` (
  `fin_member_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_member_group_name` varchar(100) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL DEFAULT 'A',
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_member_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `msmembergroups` */

insert  into `msmembergroups`(`fin_member_group_id`,`fst_member_group_name`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'SILVER','A',1,'2019-07-10 17:35:42',NULL,NULL),(2,'GOLD','A',4,'2019-07-11 15:04:28',4,'2019-07-11 15:10:39'),(3,'PLATINUM','A',4,'2019-07-11 15:07:59',4,'2019-07-11 15:10:24'),(4,'DIAMOND','A',4,'2019-07-11 16:23:51',NULL,NULL),(5,'PEARL','D',4,'2019-07-12 18:01:20',NULL,NULL),(6,'BLACK DIAMOND','A',4,'2019-11-13 10:19:44',NULL,NULL);

/*Table structure for table `msmemberships` */

DROP TABLE IF EXISTS `msmemberships`;

CREATE TABLE `msmemberships` (
  `fin_rec_id` int(10) NOT NULL AUTO_INCREMENT,
  `fst_member_no` varchar(100) DEFAULT NULL,
  `fin_relation_id` int(5) DEFAULT NULL,
  `fin_member_group_id` int(5) DEFAULT NULL,
  `fst_name_on_card` varchar(256) DEFAULT NULL,
  `fdt_expiry_date` date DEFAULT NULL,
  `fdc_member_discount_percent` decimal(5,2) DEFAULT 0.00,
  `fst_active` enum('A','S','D') NOT NULL DEFAULT 'A',
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `msmemberships` */

insert  into `msmemberships`(`fin_rec_id`,`fst_member_no`,`fin_relation_id`,`fin_member_group_id`,`fst_name_on_card`,`fdt_expiry_date`,`fdc_member_discount_percent`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (12,'3D001',149,2,'CV 3D SOLUTION','2025-11-13',15.00,'A',4,'2019-11-13 10:16:20',4,'2019-12-05 10:38:31'),(13,'FER002',152,3,'PT. FERRINDO ABADI CEMERLANG','2025-11-13',45.00,'A',4,'2019-11-13 13:39:57',NULL,NULL),(14,'AST001',150,4,'PT. ASTRINDO PRIMA MOBILINDO','2025-12-31',50.00,'A',4,'2019-12-09 11:20:33',4,'2019-12-09 11:21:28');

/*Table structure for table `msprofitcostcenter` */

DROP TABLE IF EXISTS `msprofitcostcenter`;

CREATE TABLE `msprofitcostcenter` (
  `fin_pcc_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_pcc_name` varchar(100) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_pcc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `msprofitcostcenter` */

insert  into `msprofitcostcenter`(`fin_pcc_id`,`fst_pcc_name`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'GREEBEL','A',4,'2019-09-19 17:21:25',4,'2019-12-05 08:59:33'),(2,'PRODUKSI','A',4,'2019-10-24 16:08:51',4,'2019-11-13 11:44:29'),(3,'PROMOTION','A',4,'2019-11-06 18:46:48',4,'2019-11-13 11:45:04'),(4,'UMUM','A',4,'2019-11-13 11:26:53',4,'2019-11-13 11:45:25');

/*Table structure for table `msprojects` */

DROP TABLE IF EXISTS `msprojects`;

CREATE TABLE `msprojects` (
  `fin_project_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_project_name` varchar(100) DEFAULT NULL,
  `fdt_project_start` date DEFAULT NULL,
  `fdt_project_end` date DEFAULT NULL,
  `fst_memo` text DEFAULT NULL,
  `fin_branch_id` int(11) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fdt_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `msprojects` */

insert  into `msprojects`(`fin_project_id`,`fst_project_name`,`fdt_project_start`,`fdt_project_end`,`fst_memo`,`fin_branch_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fdt_update_id`,`fdt_update_datetime`) values (1,'Testing','2019-11-07','2019-11-07','Test Edit',1,'D',4,'2019-11-06 14:27:15',NULL,'2019-11-07 15:43:12'),(2,'MIDNIGHT SALE ','2019-11-11','2019-11-23','CUCI GUDANG',1,'A',4,'2019-11-07 10:15:37',NULL,'2019-11-26 08:44:47'),(3,'PROMO 12-12','2019-12-02','2019-12-31','PROMO BULANAN',1,'A',4,'2019-11-08 12:49:05',NULL,'2019-11-26 08:46:13'),(4,'SALE AKHIR TAHUN','2019-12-27','2019-12-31','CUCI GUDANG AKHIR TAHUN',1,'A',4,'2019-12-13 17:21:31',NULL,'2019-12-13 17:21:59'),(5,'BACK TO SCHOOL 2020','2020-01-01','2020-01-12','SEMESTER AKHIR',1,'A',4,'2020-01-06 09:56:53',NULL,NULL);

/*Table structure for table `mspromo` */

DROP TABLE IF EXISTS `mspromo`;

CREATE TABLE `mspromo` (
  `fin_promo_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_list_branch_id` varchar(100) DEFAULT NULL COMMENT 'Multiselect branch yang ikut serta promo ini',
  `fst_promo_type` enum('POS','OFFICE','ALL') DEFAULT NULL,
  `fst_promo_name` varchar(100) DEFAULT NULL,
  `fin_priority` int(5) DEFAULT 0,
  `fdt_start` date DEFAULT NULL,
  `fdt_end` date DEFAULT NULL,
  `fbl_disc_per_item` tinyint(1) DEFAULT 0,
  `fin_promo_item_id` int(10) DEFAULT NULL,
  `fdb_promo_qty` double(12,2) DEFAULT NULL,
  `fst_promo_unit` varchar(100) DEFAULT NULL,
  `fdc_cashback` decimal(12,2) DEFAULT 0.00,
  `fst_other_prize` varchar(100) DEFAULT NULL,
  `fdc_other_prize_in_value` decimal(12,2) DEFAULT 0.00,
  `fbl_promo_gabungan` tinyint(1) DEFAULT NULL,
  `fbl_qty_gabungan` tinyint(1) DEFAULT NULL,
  `fdb_qty_gabungan` double(12,2) unsigned DEFAULT NULL,
  `fst_unit_gabungan` varchar(100) DEFAULT NULL,
  `fdc_min_total_purchase` decimal(12,2) DEFAULT 0.00 COMMENT 'bila ini di isi fin_promo_qty harus 0',
  `fbl_is_multiples_prize` tinyint(1) DEFAULT 0 COMMENT 'Untuk qty gabungan bila field ini true maka hadiah berlaku untuk kelipatan',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_promo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Data for the table `mspromo` */

insert  into `mspromo`(`fin_promo_id`,`fst_list_branch_id`,`fst_promo_type`,`fst_promo_name`,`fin_priority`,`fdt_start`,`fdt_end`,`fbl_disc_per_item`,`fin_promo_item_id`,`fdb_promo_qty`,`fst_promo_unit`,`fdc_cashback`,`fst_other_prize`,`fdc_other_prize_in_value`,`fbl_promo_gabungan`,`fbl_qty_gabungan`,`fdb_qty_gabungan`,`fst_unit_gabungan`,`fdc_min_total_purchase`,`fbl_is_multiples_prize`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (11,'1','OFFICE','Big Sale Akhir Tahun',2,'2019-11-18','2019-11-30',1,86,2.00,'SET',0.00,'',0.00,0,0,5.00,'SET',500000.00,0,'A',4,'2019-11-07 14:18:32',4,'2019-12-09 11:50:14'),(12,'1,2,4,9','OFFICE','PROMO AKHIR TAHUN',1,'2019-11-01','2020-01-02',0,101,1.00,'PCS',50000.00,'Piring cantik',20000.00,0,0,5.00,'BOX',200000.00,0,'A',4,'2019-11-13 10:58:13',4,'2019-12-11 09:20:05'),(13,'1,2,3','OFFICE','PROMO AWAL TAHUN 2020',0,'2020-01-01','2020-01-12',0,186,1.00,'LSN',50000.00,'PAYUNG CANTIK',20000.00,1,0,5.00,'BOX',300000.00,1,'A',4,'2019-12-06 15:32:26',NULL,NULL),(14,'1','OFFICE','PROMO JANUARY 2020',0,'2020-01-06','2020-01-18',0,191,0.00,'SET',0.00,'WINE GLASS',1.00,1,0,5.00,'SET',500000.00,0,'A',4,'2019-12-20 14:47:07',NULL,NULL);

/*Table structure for table `mspromodiscperitems` */

DROP TABLE IF EXISTS `mspromodiscperitems`;

CREATE TABLE `mspromodiscperitems` (
  `fin_id` int(10) NOT NULL AUTO_INCREMENT,
  `fin_promo_id` int(4) DEFAULT NULL,
  `fin_item_id` int(10) DEFAULT NULL,
  `fin_qty` float(12,2) DEFAULT NULL,
  `fst_unit` varchar(100) DEFAULT NULL,
  `fdc_disc_persen` double(12,2) DEFAULT NULL,
  `fdc_disc_value` double(12,2) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=latin1;

/*Data for the table `mspromodiscperitems` */

insert  into `mspromodiscperitems`(`fin_id`,`fin_promo_id`,`fin_item_id`,`fin_qty`,`fst_unit`,`fdc_disc_persen`,`fdc_disc_value`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (4,5,5,10.00,'PACK',5.00,0.00,'A',12,'2019-07-23 16:54:24',NULL,NULL),(5,5,1,10.00,'PACK',5.00,0.00,'A',12,'2019-07-23 16:54:24',NULL,NULL),(6,5,2,4.00,'BOX',10.00,230000.00,'A',12,'2019-07-23 16:54:24',NULL,NULL),(28,7,5,10.00,'PACK',10.00,0.00,'A',12,'2019-07-23 22:14:22',NULL,NULL),(29,8,5,100.00,'PACK',10.00,0.00,'A',4,'2019-07-24 09:47:32',NULL,NULL),(31,9,5,100.00,'PACK',10.00,0.00,'A',4,'2019-07-24 09:58:05',NULL,NULL),(32,9,1,10.00,'PACK',0.00,100000.00,'A',4,'2019-07-24 09:58:05',NULL,NULL),(57,10,1,2.00,'PACK',5.00,0.00,'A',12,'2019-07-26 19:36:49',NULL,NULL),(64,3,5,10.00,'SET',20.00,0.00,'A',4,'2019-08-27 11:28:09',NULL,NULL),(65,3,4,3.00,'KG',10.00,0.00,'A',4,'2019-08-27 11:28:09',NULL,NULL),(66,3,5,10.00,'SET',0.00,2000.00,'A',4,'2019-08-27 11:28:09',NULL,NULL),(68,6,2,10.00,'PACK',5.00,0.00,'A',12,'2019-10-21 14:57:34',NULL,NULL),(86,13,63,2.00,'SET',0.00,0.00,'A',4,'2019-12-06 15:32:27',NULL,NULL),(87,13,82,2.00,'PCS',0.00,0.00,'A',4,'2019-12-06 15:32:27',NULL,NULL),(88,13,176,2.00,'SET',0.00,0.00,'A',4,'2019-12-06 15:32:27',NULL,NULL),(89,13,68,2.00,'SET',0.00,0.00,'A',4,'2019-12-06 15:32:27',NULL,NULL),(94,11,63,5.00,'SET',10.00,0.00,'A',4,'2019-12-09 11:50:14',NULL,NULL),(95,11,107,3.00,'SET',0.00,1000.00,'A',4,'2019-12-09 11:50:14',NULL,NULL),(100,12,107,12.00,'SET',5.00,0.00,'A',4,'2019-12-11 09:20:05',NULL,NULL),(101,12,98,12.00,'SET',0.00,5000.00,'A',4,'2019-12-11 09:20:05',NULL,NULL),(102,12,177,12.00,'PCS',5.00,0.00,'A',4,'2019-12-11 09:20:05',NULL,NULL),(103,12,68,12.00,'PCS',0.00,10000.00,'A',4,'2019-12-11 09:20:05',NULL,NULL),(104,14,187,2.00,'PCS',5.00,0.00,'A',4,'2019-12-20 14:47:07',NULL,NULL),(105,14,177,2.00,'PCS',0.00,1000.00,'A',4,'2019-12-20 14:47:07',NULL,NULL);

/*Table structure for table `mspromoitems` */

DROP TABLE IF EXISTS `mspromoitems`;

CREATE TABLE `mspromoitems` (
  `fin_rec_id` int(10) NOT NULL AUTO_INCREMENT,
  `fin_promo_id` int(4) DEFAULT NULL,
  `fst_item_type` enum('ITEM','SUB GROUP') DEFAULT NULL,
  `fin_item_id` int(10) DEFAULT NULL,
  `fdb_qty` double(12,2) DEFAULT NULL,
  `fst_unit` varchar(100) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=179 DEFAULT CHARSET=latin1;

/*Data for the table `mspromoitems` */

insert  into `mspromoitems`(`fin_rec_id`,`fin_promo_id`,`fst_item_type`,`fin_item_id`,`fdb_qty`,`fst_unit`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (19,2,'ITEM',1,10.00,'PACK','A',1,'2019-07-11 12:36:58',NULL,NULL),(20,2,'ITEM',2,20.00,'PACK','A',1,'2019-07-11 12:37:23',NULL,NULL),(28,5,'ITEM',4,NULL,'KG','A',12,'2019-07-23 16:54:23',NULL,NULL),(63,7,'ITEM',5,10.00,'PACK','A',12,'2019-07-23 22:14:21',NULL,NULL),(64,7,'ITEM',1,10.00,'PACK','A',12,'2019-07-23 22:14:22',NULL,NULL),(65,8,'ITEM',5,100.00,'PACK','A',4,'2019-07-24 09:47:31',NULL,NULL),(66,8,'',2,100.00,'PACK','A',4,'2019-07-24 09:47:31',NULL,NULL),(69,9,'ITEM',5,100.00,'PACK','A',4,'2019-07-24 09:58:04',NULL,NULL),(70,9,'',2,100.00,'PACK','A',4,'2019-07-24 09:58:04',NULL,NULL),(89,10,'ITEM',5,3.00,'SET','A',12,'2019-07-26 19:36:49',NULL,NULL),(94,3,'ITEM',2,10.00,'BOX','A',4,'2019-08-27 11:28:08',NULL,NULL),(97,6,'ITEM',2,10.00,'PACK','A',12,'2019-10-21 14:57:33',NULL,NULL),(98,6,'',2,10.00,'PACK','A',12,'2019-10-21 14:57:34',NULL,NULL),(121,1,'',3,120.45,'PACK','A',12,'2019-11-20 11:10:40',NULL,NULL),(122,1,'ITEM',73,40.00,'CTN','A',12,'2019-11-20 11:10:40',NULL,NULL),(123,1,'ITEM',107,40.00,'SET','A',12,'2019-11-20 11:10:40',NULL,NULL),(148,13,'ITEM',177,2.00,'PCS','A',4,'2019-12-06 15:32:26',NULL,NULL),(149,13,'ITEM',86,2.00,'SET','A',4,'2019-12-06 15:32:26',NULL,NULL),(155,11,'ITEM',63,2.00,'SET','A',4,'2019-12-09 11:50:14',NULL,NULL),(156,11,'ITEM',107,2.00,'SET','A',4,'2019-12-09 11:50:14',NULL,NULL),(157,11,'ITEM',185,2.00,'LSN','A',4,'2019-12-09 11:50:14',NULL,NULL),(158,11,'ITEM',103,3.00,'KG','A',4,'2019-12-09 11:50:14',NULL,NULL),(159,11,'ITEM',115,3.00,'PCS','A',4,'2019-12-09 11:50:14',NULL,NULL),(160,11,'SUB GROUP',107,5.00,'SET','A',4,'2019-12-09 11:50:14',NULL,NULL),(168,12,'ITEM',107,20.00,'SET','A',4,'2019-12-11 09:20:05',NULL,NULL),(169,12,'ITEM',68,20.00,'SET','A',4,'2019-12-11 09:20:05',NULL,NULL),(170,12,'ITEM',174,20.00,'SET','A',4,'2019-12-11 09:20:05',NULL,NULL),(171,12,'ITEM',83,20.00,'PCS','A',4,'2019-12-11 09:20:05',NULL,NULL),(172,12,'ITEM',69,20.00,'PCS','A',4,'2019-12-11 09:20:05',NULL,NULL),(173,12,'SUB GROUP',123,2.00,'SET','A',4,'2019-12-11 09:20:05',NULL,NULL),(174,12,'SUB GROUP',101,2.00,'PACK','A',4,'2019-12-11 09:20:05',NULL,NULL),(175,14,'ITEM',179,2.00,'PCS','A',4,'2019-12-20 14:47:07',NULL,NULL),(176,14,'ITEM',187,2.00,'PCS','A',4,'2019-12-20 14:47:07',NULL,NULL),(177,14,'SUB GROUP',101,2.00,'SET','A',4,'2019-12-20 14:47:07',NULL,NULL),(178,14,'SUB GROUP',115,2.00,'SET','A',4,'2019-12-20 14:47:07',NULL,NULL);

/*Table structure for table `mspromoitemscustomer` */

DROP TABLE IF EXISTS `mspromoitemscustomer`;

CREATE TABLE `mspromoitemscustomer` (
  `fin_id` int(10) NOT NULL AUTO_INCREMENT,
  `fin_promo_id` int(4) DEFAULT NULL,
  `fst_participant_type` enum('RELATION','MEMBER GROUP','RELATION GROUP') DEFAULT NULL COMMENT 'RELATION > MSRELATION.RelationId; MEMBER GROUP > MSMEMBERSHIPS.MemberGroupId; RELATION GROUP > MSRELATIONS.RelationGroupId',
  `fin_customer_id` int(10) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=latin1;

/*Data for the table `mspromoitemscustomer` */

insert  into `mspromoitemscustomer`(`fin_id`,`fin_promo_id`,`fst_participant_type`,`fin_customer_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (38,5,'RELATION',13,'A',12,'2019-07-23 16:54:23',NULL,NULL),(39,5,'MEMBER GROUP',3,'A',12,'2019-07-23 16:54:23',NULL,NULL),(40,5,'RELATION GROUP',6,'A',12,'2019-07-23 16:54:24',NULL,NULL),(69,7,'RELATION',38,'A',12,'2019-07-23 22:14:22',NULL,NULL),(70,8,'RELATION',38,NULL,4,'2019-07-24 09:47:32',NULL,NULL),(71,8,'MEMBER GROUP',2,NULL,4,'2019-07-24 09:47:32',NULL,NULL),(72,8,'RELATION GROUP',5,NULL,4,'2019-07-24 09:47:32',NULL,NULL),(76,9,'RELATION',38,'A',4,'2019-07-24 09:58:04',NULL,NULL),(77,9,'MEMBER GROUP',2,'A',4,'2019-07-24 09:58:04',NULL,NULL),(78,9,'RELATION GROUP',5,'A',4,'2019-07-24 09:58:05',NULL,NULL),(88,10,'RELATION',83,NULL,12,'2019-07-26 19:36:49',NULL,NULL),(94,3,'RELATION GROUP',1,'A',4,'2019-08-27 11:28:09',NULL,NULL),(96,6,'RELATION',44,'A',12,'2019-10-21 14:57:34',NULL,NULL),(97,6,'MEMBER GROUP',2,'A',12,'2019-10-21 14:57:34',NULL,NULL),(119,1,'MEMBER GROUP',1,'A',12,'2019-11-20 11:10:40',NULL,NULL),(120,1,'RELATION GROUP',1,'A',12,'2019-11-20 11:10:40',NULL,NULL),(121,1,'RELATION',141,'A',12,'2019-11-20 11:10:40',NULL,NULL),(134,13,'RELATION',149,NULL,4,'2019-12-06 15:32:26',NULL,NULL),(135,13,'MEMBER GROUP',2,NULL,4,'2019-12-06 15:32:26',NULL,NULL),(136,13,'RELATION GROUP',3,NULL,4,'2019-12-06 15:32:27',NULL,NULL),(145,11,'RELATION GROUP',1,'A',4,'2019-12-09 11:50:14',NULL,NULL),(155,12,'RELATION',154,'A',4,'2019-12-11 09:20:05',NULL,NULL),(156,12,'RELATION',157,'A',4,'2019-12-11 09:20:05',NULL,NULL),(157,12,'RELATION',155,'A',4,'2019-12-11 09:20:05',NULL,NULL),(158,12,'RELATION',158,'A',4,'2019-12-11 09:20:05',NULL,NULL),(159,12,'RELATION',156,'A',4,'2019-12-11 09:20:05',NULL,NULL),(160,12,'MEMBER GROUP',1,'A',4,'2019-12-11 09:20:05',NULL,NULL),(161,12,'MEMBER GROUP',2,'A',4,'2019-12-11 09:20:05',NULL,NULL),(162,12,'MEMBER GROUP',3,'A',4,'2019-12-11 09:20:05',NULL,NULL),(163,12,'RELATION GROUP',1,'A',4,'2019-12-11 09:20:05',NULL,NULL),(164,14,'RELATION',162,NULL,4,'2019-12-20 14:47:07',NULL,NULL),(165,14,'RELATION',151,NULL,4,'2019-12-20 14:47:07',NULL,NULL),(166,14,'MEMBER GROUP',2,NULL,4,'2019-12-20 14:47:07',NULL,NULL),(167,14,'RELATION GROUP',1,NULL,4,'2019-12-20 14:47:07',NULL,NULL);

/*Table structure for table `msrelationgroups` */

DROP TABLE IF EXISTS `msrelationgroups`;

CREATE TABLE `msrelationgroups` (
  `fin_relation_group_id` int(5) NOT NULL AUTO_INCREMENT,
  `fst_relation_group_name` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_relation_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `msrelationgroups` */

insert  into `msrelationgroups`(`fin_relation_group_id`,`fst_relation_group_name`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'CUSTOMER','A',1,'2019-05-02 17:22:40',4,'2019-12-05 09:54:41'),(2,'SUPPLIER/VENDOR','A',1,'2019-05-02 17:45:28',4,'2019-11-13 10:09:23'),(3,'EKSPEDISI','A',1,'2019-05-02 17:45:40',4,'2019-05-10 10:25:30');

/*Table structure for table `msrelationprintoutnotes` */

DROP TABLE IF EXISTS `msrelationprintoutnotes`;

CREATE TABLE `msrelationprintoutnotes` (
  `fin_note_id` int(5) NOT NULL AUTO_INCREMENT,
  `fst_notes` text DEFAULT NULL,
  `fst_print_out` varchar(100) DEFAULT NULL COMMENT 'SJ, FAKTUR, PO',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_note_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `msrelationprintoutnotes` */

insert  into `msrelationprintoutnotes`(`fin_note_id`,`fst_notes`,`fst_print_out`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'Test Notes',NULL,'A',4,'0000-00-00 00:00:00',NULL,NULL),(2,'Test Lagi',NULL,'A',4,'0000-00-00 00:00:00',NULL,NULL),(3,'Coba Test Lagi',NULL,'A',4,'0000-00-00 00:00:00',NULL,NULL),(4,'Uji Coba Ke-4',NULL,'A',4,'0000-00-00 00:00:00',NULL,NULL);

/*Table structure for table `msrelations` */

DROP TABLE IF EXISTS `msrelations`;

CREATE TABLE `msrelations` (
  `fin_relation_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_branch_id` int(11) DEFAULT NULL,
  `fin_relation_group_id` int(5) DEFAULT NULL,
  `fst_relation_type` varchar(100) DEFAULT NULL COMMENT '1=Customer, 2=Supplier/Vendor, 3=Expedisi (boleh pilih lebih dari satu, simpan sebagai string dengan comma), Customer,Supplier/Vendor dan Expedisi define sebagai array di Constanta system supaya suatu saat bisa ditambah',
  `fin_parent_id` int(5) DEFAULT NULL COMMENT 'Untuk keperluan invoice akan dilakukan ke parent id',
  `fst_business_type` enum('P','C') DEFAULT NULL COMMENT 'P=Personal, C=Corporate',
  `fst_linebusiness_id` varchar(25) DEFAULT NULL COMMENT 'multiselect line of bisniss',
  `fin_sales_area_id` int(11) DEFAULT NULL COMMENT 'Menentukan Area sales',
  `fin_sales_id` int(11) DEFAULT NULL COMMENT 'Sales Untuk customer ini',
  `fst_relation_name` varchar(256) DEFAULT NULL,
  `fst_gender` enum('M','F') NOT NULL COMMENT 'Only BusinessType = Personal',
  `fdt_birth_date` date DEFAULT NULL COMMENT 'Only BusinessType = Personal',
  `fst_birth_place` text DEFAULT NULL COMMENT 'Only BusinessType = Personal',
  `fst_address` text DEFAULT NULL,
  `fst_phone` varchar(20) DEFAULT NULL,
  `fst_fax` varchar(20) DEFAULT NULL,
  `fst_postal_code` varchar(10) DEFAULT NULL,
  `fin_country_id` int(5) DEFAULT NULL,
  `fst_area_code` varchar(13) DEFAULT NULL,
  `fin_cust_pricing_group_id` int(5) DEFAULT NULL COMMENT 'Hanya perlu diisi jika, RelationType=1',
  `fdc_credit_limit` decimal(12,2) DEFAULT NULL COMMENT 'digunakan untuk type customer sebagai batas credit limit',
  `fst_nik` varchar(100) DEFAULT NULL,
  `fst_npwp` varchar(100) DEFAULT NULL,
  `fst_relation_notes` text DEFAULT NULL COMMENT 'pilihan dari MsRelationNotes, bisa pilih lebih dari satu, id pilihannya disimpan sebagai string dengan comma, notes yg muncul dalam pilihan ini di filter sesuai RelationType, tipe Customer hanya muncul notes printout SJ dan Faktur, tipe Supplier/Vendor hanya muncul notes printout PO',
  `fin_warehouse_id` int(11) DEFAULT NULL,
  `fin_terms_payment` int(5) DEFAULT NULL,
  `fin_top_komisi` int(5) DEFAULT NULL COMMENT 'top:Term Of Payment',
  `fin_top_plus_komisi` int(5) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_relation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8;

/*Data for the table `msrelations` */

insert  into `msrelations`(`fin_relation_id`,`fin_branch_id`,`fin_relation_group_id`,`fst_relation_type`,`fin_parent_id`,`fst_business_type`,`fst_linebusiness_id`,`fin_sales_area_id`,`fin_sales_id`,`fst_relation_name`,`fst_gender`,`fdt_birth_date`,`fst_birth_place`,`fst_address`,`fst_phone`,`fst_fax`,`fst_postal_code`,`fin_country_id`,`fst_area_code`,`fin_cust_pricing_group_id`,`fdc_credit_limit`,`fst_nik`,`fst_npwp`,`fst_relation_notes`,`fin_warehouse_id`,`fin_terms_payment`,`fin_top_komisi`,`fin_top_plus_komisi`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (149,1,2,'2',0,'C','5,1,4,3',3,0,'3D001 - 3D SOLUTION, CV','','1970-01-01','','CITRA BUSINESS PARK, JKB \r\nJL. PETA BARAT, KALIDERES, JAKARTA BARAT 11840','021 - 28063920','021 - 28063920','11840',1,'31.73.06.1001',0,0.00,'','123','Test Notes\r\n',1,30,15,7,'A',4,'2019-11-08 15:53:55',4,'2019-12-05 10:29:52'),(150,1,2,'2',0,'C','7,6,2',3,0,'AST001 - ASTRINDO PRIMA MOBILINDO, PT','','1970-01-01','','JL. LINGKAR LUAR BARAT NO. 1 RT.012/011, \r\nCENGKARENG, JAKARTA BARAT','021 - 559555000','021 - 29031566','11730',1,'31.73.01.1001',0,0.00,'','123','Test Notes\r\n',1,14,0,0,'A',4,'2019-11-08 16:03:08',4,'2019-12-05 17:13:53'),(151,1,2,'2',0,'C','5,7,1,6',3,0,'PT.005 - WIGUNA INDO NITAMA, PT','','1970-01-01','','KOMPLEK MUTIARA TAMAN PALEM BLOKA A17-21, \r\nCENGKARENG JAKARTA 11730','021 - 54350289','021 - 55952338','11730',1,'31.73.01.1001',0,0.00,'','123','Test Notes\r\n',1,30,0,0,'A',4,'2019-11-08 16:11:24',4,'2019-12-09 10:48:11'),(152,1,2,'2',0,'C','5,1,4,6,3',3,22,'FER002 - FERRINDO ABADI CEMERLANG, PT   ','','1970-01-01','','JL PETA UTARA NO. 2 A, PEGADUNGAN, \r\nKALIDERES, JAKARTA','021 - 5402948','021 - 5402948','11830',1,'31.73.06.1005',0,0.00,'','123','Test Notes\r\n',1,30,15,7,'A',4,'2019-11-08 16:17:11',4,'2019-12-10 17:02:07'),(153,1,2,'1,2',0,'C','5,1,4,3',4,23,'OMM001 - OMMINDO SUKSES ABADI','','1970-01-01','','KOMPLEK RUKO PANGERAN JAYAKARTA CENTER \r\nBLOK E2 NO 14, \r\nMANGGA DUA SELATAN, SAWAH BESAR, JAKPUS','021 - 22682202','021 - 22682202','10730',1,'31.71.02.1005',1,100000000.00,'','123','Test Notes\r\n',12,30,45,7,'A',4,'2019-11-08 16:22:27',4,'2019-11-27 09:47:37'),(154,1,1,'1',0,'C','1,3',3,22,'ABA008 - ABANG ADEK (MUWARDI)','','1970-01-01','','Jl. Muwardi II No.14 Grogol - JKB','021 - 5602041','021 - 5602041','11450',1,'31.73.02.1001',3,11000000.00,'','123','Test Notes\r\n',1,30,45,7,'A',4,'2019-11-11 16:31:33',4,'2019-11-27 09:08:31'),(155,9,1,'1',0,'C','1,4,3',9,21,'AL006  -  AL-AMIN (TANGERANG)','','1970-01-01','','JL . KARET RAYA BLOK 77 E NO. 03, \r\nPERUM I KARAWACI \r\nTANGERANG','-','-','15115',1,'36.71.07.1001',3,10000000.00,'','123','Test Notes\r\n',1,30,15,0,'A',4,'2019-11-11 16:47:22',4,'2019-11-27 08:57:14'),(156,9,1,'1',0,'C','5,1,4',9,21,'STR023 - STRADA SANTA MARIA II (PO)','','1970-01-01','','PASAR BARU TANGGERANG \r\nJL.KS TUBUN NO.1 PASAR BARU, \r\nKARAWACI TANGGERANG','-','-','15112',1,'36.71.07.1015',4,10000000.00,'','123','Test Lagi\r\n',2,7,0,0,'A',4,'2019-11-11 16:56:39',4,'2019-11-27 09:06:21'),(157,1,1,'1',0,'C','1,4,3',2,19,'AIS004 - AISYIYAH 86 TK','','1970-01-01','','JL. FLAMBOYAN BLOK P NO. 16, \r\nCIPAYUNG JAKARTA TIMUR','0856 - 9482 - 7582','-','13840',1,'31.75.10.1001',1,5000000.00,'','123','Test Lagi\r\n',2,7,7,7,'A',4,'2019-11-11 17:15:08',4,'2019-11-27 09:12:22'),(158,1,1,'1',0,'C','5,1,3',5,22,'AL040  -  AL MUSTAQIM TK','','1970-01-01','','JL. TANGGUH RAYA KOMP. KODAMAR \r\nKELAPA GADING \r\nJAKARTA UTARA','0857 - 7779 - 4713','-','14250',1,'31.72.06.1003',1,5000000.00,'','123','Test Notes\r\n',1,7,7,7,'A',4,'2019-11-11 17:22:26',4,'2019-11-27 09:16:07'),(159,1,2,'1,2',0,'C','8,5,7,1,4,6,3,2',4,23,'SIS001 - SIS INTERNATIONAL','','1970-01-01','','2718 DAELIM ACROTEL 13 EONJU RO 30 GIL GANGNAM GU, \r\nSEOUL, SOUTH KOREA','82-2-5719547','82-2-5719544','135-100',1,'31.71.07.1003',1,100000000.00,'','1234','Test Notes\r\nTest Lagi\r\n',12,30,45,7,'A',4,'2019-11-13 09:30:10',4,'2019-12-10 12:36:40'),(160,1,1,'1',0,'C','1,3',3,19,'BLO001 - BLOSSOM ART','','1970-01-01','','CITRA GARDEN 2 EXT BLOK BD 1A NO : 1 \r\nJAKARTA BARAT\r\nKONTAK IBU SYHIRVANA','0815 - 1907 - 6689','-','11830',1,'31.73.06.1005',1,4000000.00,'','123','Test Notes\r\n',2,30,30,7,'A',4,'2019-11-20 08:37:08',4,'2019-12-09 10:43:15'),(161,1,1,'1,2',0,'C','1,4,3',3,23,'AHA004 - AHAD MART UIN','','1970-01-01','','JL.IR.H.JUANDA NO.8-9 \r\nCIPUTAT \r\nTANGERANG SELATAN \r\nBP.BADAR','021 - 7491167','021 - 7491167','15412',1,'36.74.04.1003',1,15000000.00,'','1230023230','Test Notes\r\n',2,30,30,7,'A',4,'2019-11-20 09:51:44',4,'2019-12-09 10:34:18'),(162,1,2,'2',0,'C','7',9,0,'SS001 - SERBA SEPEDA, PT','',NULL,'','Green Lake City, Rukan Columbus No. A-7, \r\nCipondoh (perbatasan Jakarta Barat - Tangerang), RT.002/RW.009, \r\nKetapang, Cipondoh, Tangerang City, Banten 15147','(021) 84979791','(021) 84979791','15147',1,'36.71.05.1007',0,100000000.00,'','1230023230','Test Notes\r\nTest Lagi\r\n',1,30,7,7,'A',4,'2019-12-20 11:14:34',NULL,NULL);

/*Table structure for table `mssalesarea` */

DROP TABLE IF EXISTS `mssalesarea`;

CREATE TABLE `mssalesarea` (
  `fin_sales_area_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_name` varchar(100) DEFAULT NULL,
  `fin_sales_regional_id` int(11) NOT NULL,
  `fin_sales_id` int(11) NOT NULL COMMENT 'diambil dr master user department sales',
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_sales_area_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `mssalesarea` */

insert  into `mssalesarea`(`fin_sales_area_id`,`fst_name`,`fin_sales_regional_id`,`fin_sales_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'JAKARTA SELATAN',4,15,'A',0,'2019-07-06 17:49:17',4,'2019-11-07 17:02:14'),(2,'JAKARTA TIMUR',4,20,'D',4,'2019-07-08 18:44:05',4,'2019-11-27 10:11:09'),(3,'JAKARTA BARAT',4,19,'A',4,'2019-07-08 18:58:48',4,'2019-11-27 10:07:19'),(4,'JAKARTA PUSAT',4,23,'A',4,'2019-07-16 16:35:26',4,'2019-11-27 10:07:43'),(5,'JAKARTA UTARA',4,22,'A',4,'2019-08-16 15:36:37',4,'2019-11-27 10:10:42'),(6,'DEPOK',1,20,'D',4,'2019-11-07 17:14:14',4,'2019-11-29 10:27:29'),(7,'KOTA CILEGON',5,15,'A',4,'2019-11-13 11:00:21',4,'2019-11-29 10:22:58'),(8,'KOTA SERANG',5,15,'D',4,'2019-11-13 11:01:17',4,'2019-11-29 10:26:56'),(9,'KOTA TANGERANG',5,21,'A',4,'2019-11-13 11:02:27',4,'2019-11-27 10:06:20'),(10,'TANGERANG SELATAN',5,21,'A',4,'2019-11-13 11:03:35',4,'2019-11-27 10:11:44'),(11,'KAB. TANGERANG',5,21,'A',4,'2019-11-13 11:04:16',4,'2019-11-27 10:12:06');

/*Table structure for table `mssalesnational` */

DROP TABLE IF EXISTS `mssalesnational`;

CREATE TABLE `mssalesnational` (
  `fin_sales_national_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_name` varchar(100) DEFAULT NULL,
  `fin_sales_id` int(11) DEFAULT NULL COMMENT 'diambil dr master user department sales',
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_sales_national_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `mssalesnational` */

insert  into `mssalesnational`(`fin_sales_national_id`,`fst_name`,`fin_sales_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'INDONESIA',13,'A',0,'2019-07-06 17:42:26',4,'2019-07-11 18:27:21'),(2,'SINGAPORE',12,'A',0,'2019-07-10 12:39:01',4,'2019-11-29 10:38:58'),(3,'MALAYSIA',12,'A',0,'2019-07-10 12:39:24',4,'2019-11-29 10:39:17'),(4,'THAILAND',17,'D',4,'2019-08-16 15:29:43',NULL,NULL),(5,'THAILAND',13,'A',4,'2019-11-13 11:16:48',NULL,NULL);

/*Table structure for table `mssalesregional` */

DROP TABLE IF EXISTS `mssalesregional`;

CREATE TABLE `mssalesregional` (
  `fin_sales_regional_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_name` varchar(100) DEFAULT NULL,
  `fin_sales_national_id` int(11) NOT NULL,
  `fin_sales_id` int(11) NOT NULL COMMENT 'diambil dr master user department sales',
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_sales_regional_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `mssalesregional` */

insert  into `mssalesregional`(`fin_sales_regional_id`,`fst_name`,`fin_sales_national_id`,`fin_sales_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'DKI JAKARTA',1,14,'A',0,'2019-07-06 17:44:30',4,'2019-11-29 10:31:16'),(2,'DKI JAKARTA',1,14,'D',0,'2019-07-06 17:45:06',4,'2019-11-07 17:12:54'),(3,'DKI JAKARTA',1,14,'A',0,'2019-07-06 17:48:38',4,'2019-11-29 10:31:36'),(4,'DKI JAKARTA',1,15,'D',0,'2019-07-10 10:40:22',4,'2019-07-11 18:26:50'),(5,'BANTEN',1,15,'D',4,'2019-07-11 18:25:31',4,'2019-07-11 18:27:03'),(6,'BALI',1,15,'A',4,'2019-07-11 18:25:51',NULL,NULL),(7,'SUMATERA',1,17,'D',4,'2019-08-16 15:09:22',NULL,NULL),(8,'BANTEN',1,14,'A',4,'2019-11-13 11:08:45',4,'2019-11-29 10:32:17'),(9,'JAWA BARAT',1,14,'A',4,'2019-11-13 11:09:59',4,'2019-11-29 10:32:38'),(10,'JAWA TENGAH',1,15,'A',4,'2019-11-13 11:13:23',4,'2019-11-29 10:33:20'),(11,'JAWA TIMUR',1,15,'A',4,'2019-11-13 11:14:13',4,'2019-11-29 10:33:38');

/*Table structure for table `msshippingaddress` */

DROP TABLE IF EXISTS `msshippingaddress`;

CREATE TABLE `msshippingaddress` (
  `fin_shipping_address_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_name` varchar(100) DEFAULT NULL,
  `fin_relation_id` int(11) DEFAULT NULL,
  `fst_area_code` varchar(13) DEFAULT NULL,
  `fst_shipping_address` text DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_shipping_address_id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=latin1;

/*Data for the table `msshippingaddress` */

insert  into `msshippingaddress`(`fin_shipping_address_id`,`fst_name`,`fin_relation_id`,`fst_area_code`,`fst_shipping_address`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (101,'AL AMIN',155,'36.71.07.1001','JL . KARET RAYA BLOK 77 E NO. 03 \nPERUM I KARAWACI TANGERANG','A',4,'2019-11-27 08:57:14',NULL,NULL),(102,'STR023 - STRADA SANTA MARIA II (PO)',156,'36.71.07.1015','PASAR BARU TANGGERANG \nJL.KS TUBUN NO.1 PASAR BARU, \nKARAWACI TANGGERANG','A',4,'2019-11-27 09:06:21',NULL,NULL),(103,'ABA008 - ABANG ADEK (MUWARDI)',154,'31.73.02.1001','Jl. Muwardi II No.14 Grogol - JKB','A',4,'2019-11-27 09:08:31',NULL,NULL),(104,'AIS005 - AISYIYAH 86 TK (OB)',157,'31.75.10.1001','JL. FLAMBOYAN BLOK P NO. 16, \nCIPAYUNG JAKARTA TIMUR','A',4,'2019-11-27 09:12:22',NULL,NULL),(105,'AL040  -  AL MUSTAQIM TK',158,'31.72.06.1003','JL. FLAMBOYAN BLOK P NO. 16 \nCIPAYUNG JAKARTA TIMUR','A',4,'2019-11-27 09:16:08',NULL,NULL),(109,'OMM001 - OMMINDO SUKSES ABADI',153,'31.71.02.1005','KOMPLEK RUKO PANGERAN JAYAKARTA CENTER \nBLOK E2 NO 14, \nMANGGA DUA SELATAN, SAWAH BESAR, JAKPUS','A',4,'2019-11-27 09:47:37',NULL,NULL),(114,'3D001 - 3D SOLUTION, CV',149,'31.73.06.1001','CITRA BUSINESS PARK \nJL. PETA BARAT, KALIDERES , JAKBAR 11840','A',4,'2019-12-05 10:29:52',NULL,NULL),(115,'AST001 - ASTRINDO PRIMA MOBILINDO, PT',150,'31.73.01.1001','JL. LINGKAR LUAR BARAT NO. 1 RT.012/011, \nCENGKARENG, JAKARTA BARAT','A',4,'2019-12-05 17:13:53',NULL,NULL),(116,'AHA004 - AHAD MART UIN',161,'36.74.04.1003','JL.IR.H.JUANDA NO.8-9 CIPUTAT TANGERANG SELATAN BP.BADAR','A',4,'2019-12-09 10:34:18',NULL,NULL),(117,'BLO001 - BLOSSOM ART',160,'31.73.06.1005','CITRA GARDEN 2 EXT BLOK BD 1A NO: 1 \nKALIDERES, PEGADUNGAN \nJAKARTA BARAT','A',4,'2019-12-09 10:43:15',NULL,NULL),(118,'PT.005 - WIGUNA INDO NITAMA, PT',151,'31.73.01.1001','KOMPLEK MUTIARA TAMAN PALEM BLOKA A17-21, \nCENGKARENG JAKARTA 11730','A',4,'2019-12-09 10:48:11',NULL,NULL),(126,'SIS001 - SIS INTERNATIONAL',159,'31.71.07.1003','2718 DAELIM ACROTEL 13 EONJU RO 30 GIL GANGNAM GU, \nSEOUL, SOUTH KOREA','A',4,'2019-12-10 12:36:40',NULL,NULL),(127,'FER002 - FERRINDO ABADI CEMERLANG, PT   ',152,'31.73.06.1005','JL PETA UTARA NO. 2 A, PEGADUNGAN, \nKALIDERES, JAKARTA','A',4,'2019-12-10 17:02:07',NULL,NULL),(128,'SS001 - SERBA SEPEDA, PT',162,'36.71.05.1007','Green Lake City, Rukan Columbus No. A-7, \nCipondoh (perbatasan Jakarta Barat - Tangerang), RT.002/RW.009, \nKetapang, Cipondoh, Tangerang City, Banten 15147','A',4,'2019-12-20 11:14:34',NULL,NULL);

/*Table structure for table `msunits` */

DROP TABLE IF EXISTS `msunits`;

CREATE TABLE `msunits` (
  `fin_rec_id` int(10) NOT NULL AUTO_INCREMENT,
  `fst_unit` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `msunits` */

insert  into `msunits`(`fin_rec_id`,`fst_unit`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'BOX','A',0,'0000-00-00 00:00:00',1,'2019-05-08 20:36:21'),(2,'CRD','A',0,'0000-00-00 00:00:00',1,'2019-05-08 20:37:44'),(3,'CTN','A',1,'2019-05-08 20:37:34',NULL,NULL),(4,'GRAM','A',1,'2019-07-08 18:20:44',NULL,NULL),(5,'GRS','A',1,'2019-07-08 18:20:55',NULL,NULL),(6,'INNER','A',1,'2019-11-08 08:53:00',NULL,NULL),(7,'KG','A',1,'2019-11-08 09:47:52',NULL,NULL),(8,'LBR','A',1,'2019-11-08 09:49:10',NULL,NULL),(9,'LSN','A',1,'2019-11-08 11:56:26',NULL,NULL),(10,'PACK','A',0,'2019-11-08 15:36:40',NULL,NULL),(11,'PCS','A',0,'2019-11-08 15:36:44',NULL,NULL),(12,'RTG','A',0,'2019-11-08 15:36:48',NULL,NULL),(13,'SET','A',0,'2019-11-08 15:36:52',NULL,NULL),(14,'TAB','A',0,'2019-11-08 15:36:55',NULL,NULL);

/*Table structure for table `msverification` */

DROP TABLE IF EXISTS `msverification`;

CREATE TABLE `msverification` (
  `fin_rec_id` int(10) NOT NULL AUTO_INCREMENT,
  `fst_controller` varchar(100) DEFAULT NULL,
  `fst_verification_type` varchar(100) DEFAULT 'default',
  `fin_department_id` int(5) NOT NULL,
  `fin_user_group_id` int(2) NOT NULL,
  `fin_seqno` int(5) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fst_model` varchar(100) DEFAULT NULL,
  `fst_method` varchar(100) DEFAULT NULL,
  `fst_show_record_method` varchar(256) DEFAULT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `msverification` */

insert  into `msverification`(`fin_rec_id`,`fst_controller`,`fst_verification_type`,`fin_department_id`,`fin_user_group_id`,`fin_seqno`,`fst_active`,`fst_model`,`fst_method`,`fst_show_record_method`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'SO','CreditLimit',2,3,0,'A','trsalesorder_model','approved','show_transaction',1,'2019-07-07 06:33:07',NULL,NULL),(2,'SO','QtyOutStock',2,3,0,'A','trsalesorder_model','approved','show_transaction',1,'2019-07-11 19:47:03',NULL,NULL),(3,'PO','default',2,3,0,'A','trpo_model','approved','show_transaction',1,'2019-08-12 09:47:12',NULL,NULL),(5,'SO','MaxDisc',2,3,0,'A','trsalesorder_model','approved','show_transaction',1,'2019-07-11 19:47:03',NULL,NULL),(6,'SO','OverDueDateTolerance',2,3,0,'A','trsalesorder_model','approved','show_transaction',1,'2019-11-30 01:22:37',NULL,NULL);

/*Table structure for table `mswarehouse` */

DROP TABLE IF EXISTS `mswarehouse`;

CREATE TABLE `mswarehouse` (
  `fin_warehouse_id` int(5) NOT NULL AUTO_INCREMENT,
  `fin_branch_id` int(5) DEFAULT NULL,
  `fst_warehouse_name` varchar(100) DEFAULT NULL,
  `fbl_is_external` tinyint(1) DEFAULT NULL COMMENT 'Apakah Gudang External? Gudang External adalah gudang titipan customer, tidak masuk sebagai aset perusahaan',
  `fbl_is_main` tinyint(1) DEFAULT NULL COMMENT 'Gudang Utama (gudang default)',
  `fbl_logistic` tinyint(1) DEFAULT NULL,
  `fst_delivery_address` text DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_warehouse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `mswarehouse` */

insert  into `mswarehouse`(`fin_warehouse_id`,`fin_branch_id`,`fst_warehouse_name`,`fbl_is_external`,`fbl_is_main`,`fbl_logistic`,`fst_delivery_address`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,1,'C22',1,1,0,'JAKARTA 1','A',4,'2019-06-10 13:43:49',12,'2019-12-05 11:05:11'),(2,1,'PR1',1,0,0,'Jakarta 2','A',4,'2019-07-22 13:24:55',12,'2019-12-05 11:05:27'),(3,2,'SBY',1,0,0,'Jakarta 3','A',4,'2019-06-11 17:07:51',12,'2019-09-27 11:41:44'),(4,3,'MDN01',1,0,0,'Jakarta 4','A',4,'2019-07-16 15:29:22',4,'2019-11-11 17:32:09'),(6,4,'DPS01',1,0,1,'DENPASAR','A',4,'2019-07-22 14:24:07',4,'2019-11-19 10:37:16'),(7,4,'Gudang Buleleng',1,0,0,'Denpasar, Bali','D',4,'2019-08-16 13:13:07',NULL,NULL),(8,4,'Gudang Buleleng',1,0,0,'BALI','D',4,'2019-08-16 13:23:20',NULL,NULL),(9,6,'CLG1',1,0,1,'CILEGON 1','A',4,'2019-11-13 10:34:30',4,'2019-11-19 10:33:27'),(10,7,'SRG1',1,0,1,'SERANG 1','A',4,'2019-11-13 11:51:16',4,'2019-11-19 10:31:44'),(11,8,'BDG001',1,0,1,'BANDUNG 1','A',4,'2019-11-19 10:27:39',4,'2019-12-09 11:30:15'),(12,1,'Logistic Jakarta',0,0,1,'JAKARTA','A',12,'2019-11-19 10:44:42',4,'2019-12-09 11:26:54'),(13,9,'TNG',1,0,1,'TANGERANG','A',4,'2019-12-10 12:52:40',NULL,NULL),(14,10,'TNGSL',1,0,0,'','A',4,'2019-12-10 16:47:56',NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
