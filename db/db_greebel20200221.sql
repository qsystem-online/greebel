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

/*Table structure for table `preorder` */

DROP TABLE IF EXISTS `preorder`;

CREATE TABLE `preorder` (
  `fin_preorder_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_preorder_code` varchar(20) DEFAULT NULL,
  `fin_item_maingroup_id` int(11) DEFAULT NULL,
  `fin_item_group_id` int(11) DEFAULT NULL,
  `fin_item_subgroup_id` int(11) DEFAULT NULL,
  `fst_preorder_name` varchar(256) DEFAULT NULL,
  `fdt_start_date` date DEFAULT NULL,
  `fdt_end_date` date DEFAULT NULL,
  `fst_curr_code` varchar(10) DEFAULT NULL,
  `fdc_preorder_price` decimal(12,2) DEFAULT NULL,
  `fdc_minimal_deposit` decimal(12,2) DEFAULT NULL,
  `fdt_eta_date` date DEFAULT NULL COMMENT 'ETA: Estimasi Time Arival',
  `fst_item_name` varchar(256) DEFAULT NULL,
  `fst_notes` text DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_preorder_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Data for the table `preorder` */

insert  into `preorder`(`fin_preorder_id`,`fst_preorder_code`,`fin_item_maingroup_id`,`fin_item_group_id`,`fin_item_subgroup_id`,`fst_preorder_name`,`fdt_start_date`,`fdt_end_date`,`fst_curr_code`,`fdc_preorder_price`,`fdc_minimal_deposit`,`fdt_eta_date`,`fst_item_name`,`fst_notes`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (14,'111234',NULL,123,NULL,'KARAKTER SPIDERMAN NEW','2019-11-01','2019-11-30','IDR',95000000.00,30000000.00,'2019-12-05','KARAKTER TANOS INDONESIA','','A',12,'2019-11-19 19:25:37',12,'2019-11-19 19:55:57'),(15,'111235',NULL,123,NULL,'KARAKTER STAR WARS :  THE RISE OF SKYWALKER','2019-12-24','2019-12-29','USD',18.11,10000.00,'2019-12-30','STAR WARS THE BLACK SERIES REY & D-O TOY 6\"','TEST EDIT','A',4,'2019-12-24 11:54:35',4,'2019-12-24 17:32:58');

/*Table structure for table `preorderbranchdetails` */

DROP TABLE IF EXISTS `preorderbranchdetails`;

CREATE TABLE `preorderbranchdetails` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_preorder_id` int(11) DEFAULT NULL,
  `fin_branch_id` int(11) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=latin1;

/*Data for the table `preorderbranchdetails` */

insert  into `preorderbranchdetails`(`fin_rec_id`,`fin_preorder_id`,`fin_branch_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (111,15,4,'A',4,'2019-12-24 17:32:58',NULL,NULL),(110,15,3,'A',4,'2019-12-24 17:32:58',NULL,NULL),(109,15,2,'A',4,'2019-12-24 17:32:58',NULL,NULL),(108,15,1,'A',4,'2019-12-24 17:32:58',NULL,NULL),(99,14,2,'A',12,'2019-11-19 19:55:57',NULL,NULL),(98,14,1,'A',12,'2019-11-19 19:55:57',NULL,NULL);

/*Table structure for table `trcbpayment` */

DROP TABLE IF EXISTS `trcbpayment`;

CREATE TABLE `trcbpayment` (
  `fin_cbpayment_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_cbpayment_no` varchar(25) DEFAULT NULL,
  `fin_kasbank_id` int(11) DEFAULT NULL,
  `fdt_cbpayment_datetime` datetime DEFAULT NULL,
  `fin_supplier_id` int(11) DEFAULT NULL,
  `fst_curr_code` varchar(10) DEFAULT NULL,
  `fdc_exchange_rate_idr` decimal(12,2) DEFAULT NULL,
  `fst_memo` text DEFAULT NULL,
  `fin_branch_id` int(11) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_cbpayment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=latin1;

/*Data for the table `trcbpayment` */

insert  into `trcbpayment`(`fin_cbpayment_id`,`fst_cbpayment_no`,`fin_kasbank_id`,`fdt_cbpayment_datetime`,`fin_supplier_id`,`fst_curr_code`,`fdc_exchange_rate_idr`,`fst_memo`,`fin_branch_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (89,'BCA2/JKT/2019/12/00002',3,'2019-12-27 15:01:09',150,'IDR',1.00,'PO/JKT/2019/12/00002',1,'A',4,'2019-12-27 15:02:02',NULL,NULL),(90,'BCA2/JKT/2019/12/00003',3,'2019-12-27 15:15:27',150,'IDR',1.00,'FB/JKT/2019/12/00002',1,'A',4,'2019-12-27 15:16:26',NULL,NULL),(92,'BCA2/JKT/2019/12/00004',3,'2019-12-27 15:45:39',149,'IDR',1.00,'PO/JKT/2019/12/00007',1,'A',4,'2019-12-27 15:46:23',NULL,NULL),(93,'BCA2/JKT/2019/12/00005',3,'2019-12-27 15:58:56',149,'IDR',1.00,'FB/JKT/2019/12/00003',1,'A',4,'2019-12-27 15:59:52',NULL,NULL),(94,'DKI1/JKT/2019/12/00001',6,'2019-12-27 15:59:54',151,'IDR',1.00,'PO/JKT/2019/12/00003',1,'A',4,'2019-12-27 16:00:51',NULL,NULL),(95,'DKI1/JKT/2019/12/00002',6,'2019-12-27 16:12:12',151,'IDR',1.00,'FB/JKT/2019/12/00004',1,'A',4,'2019-12-27 16:13:13',NULL,NULL),(96,'DKI1/JKT/2019/12/00003',6,'2019-12-27 16:13:44',152,'IDR',1.00,'PO/JKT/2019/12/00004',1,'A',4,'2019-12-27 16:14:43',NULL,NULL),(97,'DKI1/JKT/2019/12/00004',6,'2019-12-27 16:36:45',152,'IDR',1.00,'FB/JKT/2019/12/00005',1,'A',4,'2019-12-27 16:38:06',NULL,NULL),(98,'DKI1/JKT/2019/12/00005',6,'2019-12-27 16:50:07',153,'IDR',1.00,'PO/JKT/2019/12/00005',1,'A',4,'2019-12-27 16:50:59',NULL,NULL),(99,'DKI1/JKT/2019/12/00006',6,'2019-12-27 17:06:01',153,'IDR',1.00,'FB/JKT/2019/12/00006',1,'A',4,'2019-12-27 17:06:51',NULL,NULL),(100,'DKI1/JKT/2019/12/00007',6,'2019-12-27 17:51:26',159,'IDR',1.00,'PO/JKT/2019/12/00006',1,'A',4,'2019-12-27 17:52:45',NULL,NULL),(101,'BCA2/JKT/2019/12/00006',3,'2019-12-27 17:52:47',159,'USD',14250.00,'PO/JKT/2019/12/00008',1,'A',4,'2019-12-27 17:54:04',NULL,NULL),(102,'BCA2/JKT/2019/12/00007',3,'2019-12-27 18:13:26',159,'IDR',1.00,'FB/JKT/2019/12/00007',1,'A',4,'2019-12-27 18:14:29',NULL,NULL),(103,'BCA2/JKT/2019/12/00008',3,'2019-12-27 18:14:31',159,'USD',14250.00,'FB/JKT/2019/12/00008',1,'A',4,'2019-12-27 18:15:37',NULL,NULL),(104,'BCA2/JKT/2020/01/00001',3,'2020-01-02 11:14:15',150,'IDR',1.00,'PO/JKT/2020/01/00001',1,'A',4,'2020-01-02 11:15:11',NULL,NULL),(105,'BCA2/JKT/2020/01/00002',3,'2020-01-02 11:24:20',150,'IDR',1.00,'FB/JKT/2020/01/00001',1,'A',4,'2020-01-02 11:28:59',NULL,NULL),(106,'PC-0/JKT/2020/01/00001',4,'2020-01-02 14:09:28',149,'IDR',1.00,'PO/JKT/2020/01/00002',1,'A',4,'2020-01-02 14:13:39',NULL,NULL),(107,'PC-0/JKT/2020/01/00002',4,'2020-01-02 14:21:37',149,'IDR',1.00,'FB/JKT/2020/01/00002',1,'A',4,'2020-01-02 14:22:31',NULL,NULL),(108,'BCA2/JKT/2020/01/00003',3,'2020-01-03 09:34:17',152,'IDR',1.00,'PO/JKT/2020/01/00003	',1,'A',4,'2020-01-03 09:35:12',NULL,NULL),(109,'BCA2/JKT/2020/01/00004',3,'2020-01-03 09:44:22',152,'IDR',1.00,'FB/JKT/2020/01/00003',1,'A',4,'2020-01-03 09:45:13',NULL,NULL),(110,'PC-0/JKT/2020/01/00003',4,'2020-01-03 10:04:47',150,'IDR',1.00,'PO/JKT/2020/01/00005',1,'A',4,'2020-01-03 10:08:57',NULL,NULL),(111,'PC-0/JKT/2020/01/00004',4,'2020-01-03 10:08:59',150,'IDR',1.00,'PO/JKT/2020/01/00004',1,'A',4,'2020-01-03 10:09:57',NULL,NULL),(112,'PC-0/JKT/2020/01/00005',4,'2020-01-03 10:37:18',150,'IDR',1.00,'FB/JKT/2020/01/00004',1,'A',4,'2020-01-03 10:39:39',NULL,NULL),(113,'PC-0/JKT/2020/01/00006',4,'2020-01-03 10:39:42',150,'IDR',1.00,'FB/JKT/2020/01/00005',1,'A',4,'2020-01-03 10:40:33',NULL,NULL),(114,'DKI1/JKT/2020/01/00001',6,'2020-01-03 10:47:51',151,'IDR',1.00,'PO/JKT/2020/01/00006',1,'A',4,'2020-01-03 10:48:57',NULL,NULL),(115,'BCA2/JKT/2020/01/00005',3,'2020-01-03 11:11:04',151,'IDR',1.00,'FB/JKT/2020/01/00006',1,'A',4,'2020-01-03 11:12:02',NULL,NULL),(116,'DKI1/JKT/2020/01/00002',6,'2020-01-03 13:41:24',149,'IDR',1.00,'PO/JKT/2020/01/00007',1,'A',4,'2020-01-03 13:42:36',NULL,NULL),(117,'DKI1/JKT/2020/01/00003',6,'2020-01-03 14:01:39',149,'IDR',1.00,'FB/JKT/2020/01/00007',1,'A',4,'2020-01-03 14:02:56',NULL,NULL),(118,'BCA2/JKT/2020/01/00006',3,'2020-01-06 09:44:35',150,'IDR',1.00,'PO/JKT/2020/01/00008',1,'A',4,'2020-01-06 09:45:42',NULL,NULL),(119,'BCA2/JKT/2020/01/00007',3,'2020-01-06 10:07:59',150,'IDR',1.00,'FB/JKT/2020/01/00008',1,'A',4,'2020-01-06 10:10:05',NULL,NULL),(120,'BCA2/JKT/2020/01/00008',3,'2020-01-06 16:41:49',149,'IDR',1.00,'PO/JKT/2020/01/00009',1,'A',4,'2020-01-06 16:43:07',NULL,NULL),(121,'BCA2/JKT/2020/01/00009',3,'2020-01-06 16:49:50',149,'IDR',1.00,'FB/JKT/2020/01/00009',1,'A',4,'2020-01-06 16:50:39',NULL,NULL),(122,'BCA2/JKT/2020/01/00010',3,'2020-01-07 11:11:09',151,'IDR',1.00,'PO/JKT/2020/01/00010',1,'A',4,'2020-01-07 11:14:44',NULL,NULL),(123,'BCA2/JKT/2020/01/00011',3,'2020-01-07 11:22:08',151,'IDR',1.00,'FB/JKT/2020/01/00010',1,'A',4,'2020-01-07 11:23:03',NULL,NULL),(124,'BCA2/JKT/2020/01/00012',3,'2020-01-07 13:59:18',150,'IDR',1.00,'PO/JKT/2020/01/00011',1,'A',4,'2020-01-07 14:00:11',NULL,NULL),(125,'BCA2/JKT/2020/01/00013',3,'2020-01-07 14:33:14',150,'IDR',1.00,'FB/JKT/2020/01/00011',1,'A',12,'2020-01-07 14:34:13',12,'2020-01-17 16:53:04');

/*Table structure for table `trcbpaymentitems` */

DROP TABLE IF EXISTS `trcbpaymentitems`;

CREATE TABLE `trcbpaymentitems` (
  `fin_rec_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `fin_cbpayment_id` int(11) DEFAULT NULL,
  `fst_trans_type` enum('LPB_PO','DP_PO','LPB_RETURN') DEFAULT NULL,
  `fin_trans_id` int(11) DEFAULT NULL,
  `fdc_trans_amount` decimal(12,2) DEFAULT NULL,
  `fdc_return_amount` decimal(12,2) DEFAULT NULL,
  `fdc_payment` decimal(12,2) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fdt_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=latin1;

/*Data for the table `trcbpaymentitems` */

insert  into `trcbpaymentitems`(`fin_rec_id`,`fin_cbpayment_id`,`fst_trans_type`,`fin_trans_id`,`fdc_trans_amount`,`fdc_return_amount`,`fdc_payment`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fdt_update_id`,`fdt_update_datetime`) values (92,89,'DP_PO',65,6200000.00,0.00,6200000.00,'A',4,'2019-12-27 15:02:02',NULL,NULL),(93,90,'LPB_PO',42,5927500.00,232650.00,5694850.00,'A',4,'2019-12-27 15:16:26',NULL,NULL),(95,92,'DP_PO',70,6000000.00,0.00,6000000.00,'A',4,'2019-12-27 15:46:23',NULL,NULL),(96,93,'LPB_PO',43,5335500.00,485100.00,4850400.00,'A',4,'2019-12-27 15:59:52',NULL,NULL),(97,94,'DP_PO',66,8000000.00,0.00,8000000.00,'A',4,'2019-12-27 16:00:51',NULL,NULL),(98,95,'LPB_PO',44,7279000.00,11000.00,7268000.00,'A',4,'2019-12-27 16:13:13',NULL,NULL),(99,96,'DP_PO',67,9500000.00,0.00,9500000.00,'A',4,'2019-12-27 16:14:43',NULL,NULL),(100,97,'LPB_PO',45,9696100.00,643500.00,9052600.00,'A',4,'2019-12-27 16:38:06',NULL,NULL),(101,98,'DP_PO',68,7500000.00,0.00,7500000.00,'A',4,'2019-12-27 16:50:59',NULL,NULL),(102,99,'LPB_PO',46,8340000.00,297000.00,8043000.00,'A',4,'2019-12-27 17:06:51',NULL,NULL),(103,100,'DP_PO',69,7500000.00,0.00,7500000.00,'A',4,'2019-12-27 17:52:45',NULL,NULL),(104,101,'DP_PO',71,1600.00,0.00,1600.00,'A',4,'2019-12-27 17:54:04',NULL,NULL),(105,102,'LPB_PO',47,7597500.00,123750.00,7473750.00,'A',4,'2019-12-27 18:14:29',NULL,NULL),(106,103,'LPB_PO',48,1725.00,104.50,1620.50,'A',4,'2019-12-27 18:15:37',NULL,NULL),(107,104,'DP_PO',72,1000000.00,0.00,1000000.00,'A',4,'2020-01-02 11:15:11',NULL,NULL),(108,105,'LPB_PO',49,980000.00,39600.00,940400.00,'A',4,'2020-01-02 11:28:59',NULL,NULL),(109,106,'DP_PO',73,1500.00,0.00,1500.00,'A',4,'2020-01-02 14:13:39',NULL,NULL),(110,107,'LPB_PO',50,700.00,22.00,678.00,'A',4,'2020-01-02 14:22:31',NULL,NULL),(111,108,'DP_PO',74,6000000.00,0.00,6000000.00,'A',4,'2020-01-03 09:35:12',NULL,NULL),(112,109,'LPB_PO',51,5385000.00,113850.00,5271150.00,'A',4,'2020-01-03 09:45:13',NULL,NULL),(113,110,'DP_PO',76,15600.00,0.00,15600.00,'A',4,'2020-01-03 10:08:57',NULL,NULL),(114,111,'DP_PO',75,140000.00,0.00,140000.00,'A',4,'2020-01-03 10:09:57',NULL,NULL),(115,112,'LPB_PO',52,92650.00,9900.00,82750.00,'A',4,'2020-01-03 10:39:39',NULL,NULL),(116,113,'LPB_PO',53,24000.00,19800.00,4200.00,'A',4,'2020-01-03 10:40:33',NULL,NULL),(117,114,'DP_PO',77,2500000.00,0.00,2500000.00,'A',4,'2020-01-03 10:48:57',NULL,NULL),(118,115,'LPB_PO',54,2004500.00,19800.00,1984700.00,'A',4,'2020-01-03 11:12:03',NULL,NULL),(119,116,'DP_PO',78,3000000.00,0.00,3000000.00,'A',4,'2020-01-03 13:42:36',NULL,NULL),(120,117,'LPB_PO',55,1950000.00,49500.00,1900500.00,'A',4,'2020-01-03 14:02:56',NULL,NULL),(121,118,'DP_PO',79,10000000.00,0.00,10000000.00,'A',4,'2020-01-06 09:45:42',NULL,NULL),(122,119,'LPB_PO',56,9360000.00,193600.00,9166400.00,'A',4,'2020-01-06 10:10:05',NULL,NULL),(123,120,'DP_PO',80,4000000.00,0.00,4000000.00,'A',4,'2020-01-06 16:43:07',NULL,NULL),(124,121,'LPB_PO',57,2930000.00,29700.00,2900300.00,'A',4,'2020-01-06 16:50:39',NULL,NULL),(125,122,'DP_PO',81,2000000.00,0.00,2000000.00,'A',4,'2020-01-07 11:14:45',NULL,NULL),(126,123,'LPB_PO',58,1300000.00,16500.00,1283500.00,'A',4,'2020-01-07 11:23:03',NULL,NULL),(127,124,'DP_PO',82,5000000.00,0.00,5000000.00,'A',4,'2020-01-07 14:00:11',NULL,NULL),(128,125,'LPB_PO',59,4350000.00,93500.00,4256500.00,'A',12,'2020-01-17 16:53:04',NULL,NULL);

/*Table structure for table `trcbpaymentitemstype` */

DROP TABLE IF EXISTS `trcbpaymentitemstype`;

CREATE TABLE `trcbpaymentitemstype` (
  `fin_rec_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `fin_cbpayment_id` int(11) DEFAULT NULL,
  `fst_cbpayment_type` enum('TUNAI','TRANSFER','GIRO','GLACCOUNT') DEFAULT NULL,
  `fdc_amount` decimal(12,2) DEFAULT NULL,
  `fst_referensi` varchar(100) DEFAULT NULL,
  `fst_bilyet_no` varchar(15) DEFAULT NULL,
  `fdt_clear_date` date DEFAULT NULL,
  `fst_glaccount_code` varchar(100) DEFAULT NULL,
  `fin_pcc_id` int(11) DEFAULT NULL,
  `fin_pc_divisi_id` int(11) DEFAULT NULL,
  `fin_pc_customer_id` int(11) DEFAULT NULL,
  `fin_pc_project_id` int(11) DEFAULT NULL,
  `fin_relation_id` int(11) DEFAULT NULL,
  `fst_bilyet_status` enum('CLEAR','REJECTED') DEFAULT NULL,
  `fdt_bilyet_clear_datetime` datetime DEFAULT NULL,
  `fin_bilyet_clear_user` int(11) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=latin1;

/*Data for the table `trcbpaymentitemstype` */

insert  into `trcbpaymentitemstype`(`fin_rec_id`,`fin_cbpayment_id`,`fst_cbpayment_type`,`fdc_amount`,`fst_referensi`,`fst_bilyet_no`,`fdt_clear_date`,`fst_glaccount_code`,`fin_pcc_id`,`fin_pc_divisi_id`,`fin_pc_customer_id`,`fin_pc_project_id`,`fin_relation_id`,`fst_bilyet_status`,`fdt_bilyet_clear_datetime`,`fin_bilyet_clear_user`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (89,89,'TRANSFER',6200000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2019-12-27 15:02:02',NULL,NULL),(90,90,'TRANSFER',5694850.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2019-12-27 15:16:26',NULL,NULL),(92,92,'TRANSFER',6000000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2019-12-27 15:46:23',NULL,NULL),(93,93,'TRANSFER',4850400.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2019-12-27 15:59:52',NULL,NULL),(94,94,'TRANSFER',8000000.00,'REK BANK BCA','',NULL,'111.112.008',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2019-12-27 16:00:51',NULL,NULL),(95,95,'TRANSFER',7268000.00,'REK BANK DKI','',NULL,'111.112.008',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2019-12-27 16:13:13',NULL,NULL),(96,96,'TRANSFER',9500000.00,'REK BANK DKI','',NULL,'111.112.008',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2019-12-27 16:14:43',NULL,NULL),(97,97,'TRANSFER',9052600.00,'REK BANK DKI','',NULL,'111.112.008',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2019-12-27 16:38:06',NULL,NULL),(98,98,'TRANSFER',7500000.00,'REK BANK DKI','',NULL,'111.112.008',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2019-12-27 16:50:59',NULL,NULL),(99,99,'TRANSFER',8043000.00,'REK BANK DKI','',NULL,'111.112.008',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2019-12-27 17:06:51',NULL,NULL),(100,100,'TRANSFER',7500000.00,'REK BANK DKI','',NULL,'111.112.008',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2019-12-27 17:52:45',NULL,NULL),(101,101,'TRANSFER',1600.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2019-12-27 17:54:04',NULL,NULL),(102,102,'TRANSFER',7473750.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2019-12-27 18:14:29',NULL,NULL),(103,103,'TRANSFER',1620.50,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2019-12-27 18:15:37',NULL,NULL),(104,104,'TRANSFER',1000000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-02 11:15:11',NULL,NULL),(105,105,'TRANSFER',940400.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-02 11:28:59',NULL,NULL),(106,106,'TUNAI',1500.00,'REK BANK BCA','',NULL,'111.111.001',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-02 14:13:39',NULL,NULL),(107,107,'TUNAI',678.00,'CASH','',NULL,'111.111.001',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-02 14:22:31',NULL,NULL),(108,108,'TRANSFER',6000000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-03 09:35:12',NULL,NULL),(109,109,'TRANSFER',5271150.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-03 09:45:13',NULL,NULL),(110,110,'TUNAI',15600.00,'CASH','',NULL,'111.111.001',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-03 10:08:57',NULL,NULL),(111,111,'TUNAI',140000.00,'CASH','',NULL,'111.111.001',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-03 10:09:57',NULL,NULL),(112,112,'TUNAI',82750.00,'CASH','',NULL,'111.111.001',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-03 10:39:39',NULL,NULL),(113,113,'TUNAI',4200.00,'CASH','',NULL,'111.111.001',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-03 10:40:33',NULL,NULL),(114,114,'TRANSFER',2500000.00,'REK BANK DKI','',NULL,'111.112.008',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-03 10:48:57',NULL,NULL),(115,115,'TRANSFER',1984700.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-03 11:12:03',NULL,NULL),(116,116,'TRANSFER',3000000.00,'REK BANK DKI','',NULL,'111.112.008',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-03 13:42:36',NULL,NULL),(117,117,'TRANSFER',1900500.00,'REK BANK DKI','',NULL,'111.112.008',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-03 14:02:56',NULL,NULL),(118,118,'TRANSFER',10000000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-06 09:45:42',NULL,NULL),(119,119,'TRANSFER',9166400.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-06 10:10:05',NULL,NULL),(120,120,'TRANSFER',4000000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-06 16:43:07',NULL,NULL),(121,121,'TRANSFER',2900300.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-06 16:50:39',NULL,NULL),(122,122,'TRANSFER',2000000.00,'BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-07 11:14:45',NULL,NULL),(123,123,'TRANSFER',1283500.00,'BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-07 11:23:03',NULL,NULL),(124,124,'TRANSFER',5000000.00,'BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,'A',4,'2020-01-07 14:00:11',NULL,NULL),(125,125,'TUNAI',4226500.00,'bca','',NULL,'111.112.004',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'A',12,'2020-01-17 16:53:04',NULL,NULL),(126,125,'GLACCOUNT',30000.00,'tes 123123123','',NULL,'111.114.005',0,4,153,4,153,NULL,NULL,NULL,'A',12,'2020-01-17 16:53:04',NULL,NULL);

/*Table structure for table `trcbpaymentother` */

DROP TABLE IF EXISTS `trcbpaymentother`;

CREATE TABLE `trcbpaymentother` (
  `fin_cbpaymentoth_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_cbpaymentoth_type` enum('Cash','Bank') DEFAULT NULL,
  `fst_cbpaymentoth_no` varchar(25) DEFAULT NULL,
  `fdt_cbpaymentoth_datetime` datetime DEFAULT NULL,
  `fin_kasbank_id` int(11) DEFAULT NULL,
  `fst_give_to` varchar(100) DEFAULT NULL,
  `fst_curr_code` varchar(5) DEFAULT NULL,
  `fdc_exchange_rate_idr` decimal(12,2) DEFAULT NULL,
  `fst_memo` text DEFAULT NULL,
  `fin_branch_id` int(11) DEFAULT NULL,
  `fdc_nominal` decimal(12,2) DEFAULT NULL,
  `fdc_cash_transfer` decimal(12,2) DEFAULT NULL,
  `fdc_bilyet` decimal(12,2) DEFAULT NULL,
  `fst_bilyet_no` varchar(15) DEFAULT NULL,
  `fdt_clear_date` date DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_cbpaymentoth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `trcbpaymentother` */

insert  into `trcbpaymentother`(`fin_cbpaymentoth_id`,`fst_cbpaymentoth_type`,`fst_cbpaymentoth_no`,`fdt_cbpaymentoth_datetime`,`fin_kasbank_id`,`fst_give_to`,`fst_curr_code`,`fdc_exchange_rate_idr`,`fst_memo`,`fin_branch_id`,`fdc_nominal`,`fdc_cash_transfer`,`fdc_bilyet`,`fst_bilyet_no`,`fdt_clear_date`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (3,NULL,'BCA1/JKT/2020/01/00015','2020-01-15 15:21:25',2,'devibastia','IDR',1.00,'test1 ',1,2500000.00,2000000.00,500000.00,'1111111111','2020-01-31','A',12,'2020-01-15 15:27:22',NULL,NULL);

/*Table structure for table `trcbpaymentotheritems` */

DROP TABLE IF EXISTS `trcbpaymentotheritems`;

CREATE TABLE `trcbpaymentotheritems` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_cbpaymentoth_id` int(11) DEFAULT NULL,
  `fst_glaccount_code` varchar(100) DEFAULT NULL,
  `fdc_debit` decimal(12,2) DEFAULT 0.00,
  `fdc_credit` decimal(12,2) DEFAULT 0.00,
  `fst_notes` text DEFAULT NULL,
  `fin_pcc_id` int(11) DEFAULT NULL,
  `fin_pc_divisi_id` int(11) DEFAULT NULL,
  `fin_pc_customer_id` int(11) DEFAULT NULL,
  `fin_pc_project_id` int(11) DEFAULT NULL,
  `fin_relation_id` int(11) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `trcbpaymentotheritems` */

insert  into `trcbpaymentotheritems`(`fin_rec_id`,`fin_cbpaymentoth_id`,`fst_glaccount_code`,`fdc_debit`,`fdc_credit`,`fst_notes`,`fin_pcc_id`,`fin_pc_divisi_id`,`fin_pc_customer_id`,`fin_pc_project_id`,`fin_relation_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (3,3,'111.111.001',2500000.00,0.00,'test memo 1',NULL,NULL,NULL,NULL,NULL,'A',12,'2020-01-15 15:27:22',NULL,NULL);

/*Table structure for table `trcbreceive` */

DROP TABLE IF EXISTS `trcbreceive`;

CREATE TABLE `trcbreceive` (
  `fin_cbreceive_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_cbreceive_no` varchar(25) DEFAULT NULL,
  `fin_kasbank_id` int(11) DEFAULT NULL,
  `fdt_cbreceive_datetime` datetime DEFAULT NULL,
  `fin_customer_id` int(11) DEFAULT NULL,
  `fst_curr_code` varchar(10) DEFAULT NULL,
  `fdc_exchange_rate_idr` decimal(12,2) DEFAULT NULL,
  `fst_memo` text DEFAULT NULL,
  `fin_branch_id` int(11) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_cbreceive_id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=latin1;

/*Data for the table `trcbreceive` */

insert  into `trcbreceive`(`fin_cbreceive_id`,`fst_cbreceive_no`,`fin_kasbank_id`,`fdt_cbreceive_datetime`,`fin_customer_id`,`fst_curr_code`,`fdc_exchange_rate_idr`,`fst_memo`,`fin_branch_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (86,'DKI2/JKT/2019/12/00001',6,'2019-12-27 16:41:28',154,'IDR',1.00,'SO/JKT/2019/12/00001',1,'A',4,'2019-12-27 16:42:28',NULL,NULL),(87,'DKI2/JKT/2019/12/00002',6,'2019-12-27 16:47:52',154,'IDR',1.00,'IV/JKT/2019/12/00001 \r\nSO/JKT/2019/12/00001',1,'A',4,'2019-12-27 16:49:10',NULL,NULL),(89,'DKI2/JKT/2019/12/00003',6,'2019-12-27 17:30:19',157,'IDR',1.00,'SO/JKT/2019/12/00002	',1,'A',4,'2019-12-27 17:31:02',NULL,NULL),(90,'DKI2/JKT/2019/12/00004',6,'2019-12-27 17:36:48',157,'IDR',1.00,'IV/JKT/2019/12/00002 \r\nSO/JKT/2019/12/00002',1,'A',4,'2019-12-27 17:38:18',4,'2019-12-27 17:39:07'),(91,'BCA1/JKT/2019/12/00001',3,'2019-12-30 12:29:27',160,'IDR',1.00,'SO/JKT/2019/12/00003',1,'A',4,'2019-12-30 12:30:20',4,'2020-01-02 10:43:44'),(93,'BCA1/JKT/2020/01/00002',3,'2020-01-02 11:02:21',160,'IDR',1.00,'IV/JKT/2020/01/00001',1,'A',4,'2020-01-02 11:03:49',NULL,NULL),(97,'BCA1/JKT/2020/01/00003',3,'2020-01-02 13:56:13',159,'IDR',1.00,'SO/JKT/2020/01/00001',1,'A',4,'2020-01-02 14:05:05',NULL,NULL),(98,'BCA1/JKT/2020/01/00004',3,'2020-01-02 14:38:27',159,'IDR',1.00,'IV/JKT/2020/01/00002',1,'A',4,'2020-01-02 14:39:32',NULL,NULL),(100,'BCA1/JKT/2020/01/00005',3,'2020-01-06 10:19:42',161,'IDR',1.00,'SO/JKT/2020/01/00004',1,'A',4,'2020-01-06 10:21:04',NULL,NULL),(101,'BCA1/JKT/2020/01/00006',3,'2020-01-06 11:17:48',154,'IDR',1.00,'SO/JKT/2020/01/00003	',1,'A',4,'2020-01-06 11:18:53',NULL,NULL),(102,'PC-0/JKT/2020/01/00001',4,'2020-01-06 11:49:33',154,'IDR',1.00,'IV/JKT/2020/01/00004',1,'A',4,'2020-01-06 11:50:34',NULL,NULL),(104,'BCA1/JKT/2020/01/00007',3,'2020-01-06 13:38:41',160,'IDR',1.00,'SO/JKT/2020/01/00005',1,'A',4,'2020-01-06 13:40:02',NULL,NULL),(105,'BCA1/JKT/2020/01/00008',3,'2020-01-06 13:50:37',157,'IDR',1.00,'SO/JKT/2020/01/00006 \r\nDP LEBIH BESAR',1,'A',4,'2020-01-06 13:51:55',NULL,NULL),(106,'PC-0/JKT/2020/01/00002',4,'2020-01-06 13:58:54',157,'IDR',1.00,'IV/JKT/2020/01/00006',1,'A',4,'2020-01-06 14:00:10',NULL,NULL),(107,'BCA1/JKT/2020/01/00009',3,'2020-01-06 14:30:31',161,'IDR',1.00,'BELUM BISA MENGGUNAKAN SISA DP DARI SO/JKT/2020/01/00004 KLAIM KELEBIHAN BAYAR BELUM BISA \r\nIV/JKT/2020/01/00007',1,'A',4,'2020-01-06 14:32:41',NULL,NULL),(108,'BCA1/JKT/2020/01/00010',3,'2020-01-06 15:01:48',158,'IDR',1.00,'SO/JKT/2020/01/00008',1,'A',4,'2020-01-06 15:02:55',NULL,NULL),(109,'BCA1/JKT/2020/01/00011',3,'2020-01-06 15:13:39',153,'IDR',1.00,'SO/JKT/2020/01/00009',1,'A',4,'2020-01-06 15:14:39',NULL,NULL),(110,'BCA1/JKT/2020/01/00012',3,'2020-01-06 16:51:26',159,'IDR',1.00,'SO/JKT/2020/01/00010	',1,'A',4,'2020-01-06 16:52:17',NULL,NULL),(111,'PC-0/JKT/2020/01/00003',4,'2020-01-06 16:58:19',159,'IDR',1.00,'IV/JKT/2020/01/00010',1,'A',4,'2020-01-06 16:59:22',NULL,NULL),(112,'BCA1/JKT/2020/01/00013',3,'2020-01-07 11:27:40',160,'IDR',1.00,'SO/JKT/2020/01/00011',1,'A',4,'2020-01-07 11:28:28',NULL,NULL),(113,'PC-0/JKT/2020/01/00004',4,'2020-01-07 11:33:28',160,'IDR',1.00,'IV/JKT/2020/01/00011	',1,'A',4,'2020-01-07 11:35:53',NULL,NULL),(114,'BCA1/JKT/2020/01/00014',3,'2020-01-07 15:08:46',153,'IDR',1.00,'SO/JKT/2020/01/00014',1,'A',4,'2020-01-07 15:09:30',NULL,NULL),(126,'ADJ/JKT/2020/01/00011',1,'2020-01-20 12:22:46',158,'IDR',1.00,'',1,'D',12,'2020-01-20 12:39:29',NULL,NULL),(127,'BCA1/JKT/2020/01/00016',2,'2020-01-20 14:37:48',158,'IDR',1.00,'',1,'A',12,'2020-01-20 14:44:17',NULL,NULL);

/*Table structure for table `trcbreceiveitems` */

DROP TABLE IF EXISTS `trcbreceiveitems`;

CREATE TABLE `trcbreceiveitems` (
  `fin_rec_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `fin_cbreceive_id` int(11) NOT NULL,
  `fst_trans_type` enum('DP_SO','INV_SO','RETURN_SO','PAYMENT_OVER','CLAIM_PAYMENT_OVER','CLAIM_PAYMENT_UNKNOWN') NOT NULL,
  `fin_trans_id` int(11) DEFAULT NULL,
  `fdc_trans_amount` decimal(12,2) DEFAULT NULL,
  `fdc_return_amount` decimal(12,2) DEFAULT 0.00,
  `fdc_receive_amount` decimal(12,2) DEFAULT 0.00,
  `fdc_receive_amount_claimed` decimal(12,2) DEFAULT 0.00,
  `fst_memo` text DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fdt_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=latin1;

/*Data for the table `trcbreceiveitems` */

insert  into `trcbreceiveitems`(`fin_rec_id`,`fin_cbreceive_id`,`fst_trans_type`,`fin_trans_id`,`fdc_trans_amount`,`fdc_return_amount`,`fdc_receive_amount`,`fdc_receive_amount_claimed`,`fst_memo`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fdt_update_id`,`fdt_update_datetime`) values (109,86,'DP_SO',167,600000.00,0.00,600000.00,0.00,'UANG MUKA','A',4,'2019-12-27 16:42:28',NULL,NULL),(110,87,'INV_SO',9,555000.00,159060.00,395940.00,0.00,'RETUR 2','A',4,'2019-12-27 16:49:10',NULL,NULL),(112,89,'DP_SO',169,300000.00,0.00,300000.00,0.00,'UANG MUKA','A',4,'2019-12-27 17:31:02',NULL,NULL),(113,90,'INV_SO',11,307200.00,50600.00,256600.00,0.00,'','A',4,'2019-12-27 17:39:07',NULL,NULL),(114,91,'DP_SO',170,450000.00,0.00,450000.00,0.00,'UANG MUKA','A',4,'2020-01-02 10:43:44',NULL,NULL),(116,93,'INV_SO',12,444300.00,9900.00,434400.00,0.00,'PELUNASAN','A',4,'2020-01-02 11:03:49',NULL,NULL),(120,97,'DP_SO',173,900000.00,0.00,900000.00,0.00,'UANG MUKA','A',4,'2020-01-02 14:05:05',NULL,NULL),(121,98,'INV_SO',14,900154.00,136400.00,763754.00,0.00,'PELUNASAN','A',4,'2020-01-02 14:39:32',NULL,NULL),(123,100,'DP_SO',178,10000000.00,0.00,10000000.00,0.00,'UANG MUKA \nGUDANG C22 - JAKARTA 1','A',4,'2020-01-06 10:21:04',NULL,NULL),(124,101,'DP_SO',177,200000.00,0.00,200000.00,0.00,'UANG MUKA','A',4,'2020-01-06 11:18:53',NULL,NULL),(125,102,'INV_SO',16,24402.00,8470.00,15932.00,0.00,'PELUNASAN','A',4,'2020-01-06 11:50:34',NULL,NULL),(127,104,'DP_SO',179,10000000.00,0.00,10000000.00,0.00,'DP LEBIH BESAR DARI TOTAL HARGA PENJUALAN','A',4,'2020-01-06 13:40:02',NULL,NULL),(128,105,'DP_SO',180,10000000.00,0.00,10000000.00,0.00,'DP LEBIH BESAR DARI TOTAL HARGA','A',4,'2020-01-06 13:51:55',NULL,NULL),(129,106,'INV_SO',18,3.00,0.00,3.00,0.00,'','A',4,'2020-01-06 14:00:10',NULL,NULL),(130,107,'INV_SO',19,410852.00,0.00,410852.00,0.00,'BELUM BISA MENGGUNAKAN SISA DP DARI SO/JKT/2020/01/00004 \nKLAIM KELEBIHAN BAYAR BELUM BISA','A',4,'2020-01-06 14:32:41',NULL,NULL),(131,108,'DP_SO',182,5000000.00,0.00,5000000.00,0.00,'DP LEBIH BESAR','A',4,'2020-01-06 15:02:55',NULL,NULL),(132,109,'DP_SO',183,3000000.00,0.00,3000000.00,0.00,'DP LEBIH BESAR LAGI','A',4,'2020-01-06 15:14:39',NULL,NULL),(133,110,'DP_SO',184,200000.00,0.00,200000.00,0.00,'TEST CHK','A',4,'2020-01-06 16:52:17',NULL,NULL),(134,111,'INV_SO',22,156403.00,0.00,156403.00,0.00,'','A',4,'2020-01-06 16:59:22',NULL,NULL),(135,112,'DP_SO',185,8000000.00,0.00,8000000.00,0.00,'','A',4,'2020-01-07 11:28:28',NULL,NULL),(136,113,'INV_SO',23,3.00,0.00,3.00,0.00,'','A',4,'2020-01-07 11:35:53',NULL,NULL),(137,114,'DP_SO',189,1000000.00,0.00,1000000.00,0.00,'','A',4,'2020-01-07 15:09:30',NULL,NULL),(158,126,'DP_SO',188,3000000.00,0.00,3000000.00,0.00,'','D',12,'2020-01-20 12:39:29',NULL,NULL),(159,126,'CLAIM_PAYMENT_UNKNOWN',3974,-200000.00,0.00,-200000.00,0.00,'','D',12,'2020-01-20 12:39:29',NULL,NULL),(160,127,'CLAIM_PAYMENT_UNKNOWN',3974,-200000.00,0.00,-200000.00,0.00,'test','A',12,'2020-01-20 14:44:17',NULL,NULL),(161,127,'DP_SO',188,3000000.00,0.00,500000.00,0.00,'test','A',12,'2020-01-20 14:44:17',NULL,NULL);

/*Table structure for table `trcbreceiveitemstype` */

DROP TABLE IF EXISTS `trcbreceiveitemstype`;

CREATE TABLE `trcbreceiveitemstype` (
  `fin_rec_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `fin_cbreceive_id` int(11) DEFAULT NULL,
  `fst_cbreceive_type` enum('TUNAI','TRANSFER','GIRO','GLACCOUNT') DEFAULT NULL,
  `fdc_amount` decimal(12,2) DEFAULT NULL,
  `fst_referensi` varchar(100) DEFAULT NULL,
  `fst_bilyet_no` varchar(15) DEFAULT NULL,
  `fdt_clear_date` date DEFAULT NULL,
  `fst_glaccount_code` varchar(100) DEFAULT NULL,
  `fin_pcc_id` int(11) DEFAULT NULL,
  `fin_pc_divisi_id` int(11) DEFAULT NULL,
  `fin_pc_customer_id` int(11) DEFAULT NULL,
  `fin_pc_project_id` int(11) DEFAULT NULL,
  `fin_relation_id` int(11) DEFAULT NULL,
  `fst_bilyet_status` enum('CLEAR','REJECTED') DEFAULT NULL,
  `fdt_bilyet_clear_datetime` datetime DEFAULT NULL,
  `fin_bilyet_clear_user` int(11) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=latin1;

/*Data for the table `trcbreceiveitemstype` */

insert  into `trcbreceiveitemstype`(`fin_rec_id`,`fin_cbreceive_id`,`fst_cbreceive_type`,`fdc_amount`,`fst_referensi`,`fst_bilyet_no`,`fdt_clear_date`,`fst_glaccount_code`,`fin_pcc_id`,`fin_pc_divisi_id`,`fin_pc_customer_id`,`fin_pc_project_id`,`fin_relation_id`,`fst_bilyet_status`,`fdt_bilyet_clear_datetime`,`fin_bilyet_clear_user`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (73,86,'TRANSFER',600000.00,'REK BANK DKI','',NULL,'111.112.008',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2019-12-27 16:42:28',NULL,NULL),(74,87,'TRANSFER',395940.00,'REK BANK DKI','',NULL,'111.112.008',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2019-12-27 16:49:10',NULL,NULL),(76,89,'TRANSFER',300000.00,'REK BANK DKI','',NULL,'111.112.008',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2019-12-27 17:31:02',NULL,NULL),(77,90,'TRANSFER',256600.00,'REK BANKM DKI','',NULL,'111.112.008',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2019-12-27 17:39:07',NULL,NULL),(78,91,'TRANSFER',450000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-02 10:43:44',NULL,NULL),(80,93,'TRANSFER',434400.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-02 11:03:49',NULL,NULL),(84,97,'TRANSFER',900000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-02 14:05:05',NULL,NULL),(85,98,'TRANSFER',763754.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-02 14:39:32',NULL,NULL),(87,100,'TRANSFER',10000000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-06 10:21:04',NULL,NULL),(88,101,'TRANSFER',200000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-06 11:18:53',NULL,NULL),(89,102,'TUNAI',15932.00,'CASH','',NULL,'111.111.001',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-06 11:50:34',NULL,NULL),(91,104,'TRANSFER',10000000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-06 13:40:02',NULL,NULL),(92,105,'TRANSFER',10000000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-06 13:51:55',NULL,NULL),(93,106,'TUNAI',3.00,'CASH','',NULL,'111.111.001',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-06 14:00:10',NULL,NULL),(94,107,'TRANSFER',410852.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-06 14:32:41',NULL,NULL),(95,108,'TRANSFER',5000000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-06 15:02:55',NULL,NULL),(96,109,'TRANSFER',3000000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-06 15:14:39',NULL,NULL),(97,110,'TRANSFER',200000.00,'REK BANK BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-06 16:52:17',NULL,NULL),(98,111,'TRANSFER',156403.00,'PC','',NULL,'111.111.001',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-06 16:59:22',NULL,NULL),(99,112,'TRANSFER',8000000.00,'BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-07 11:28:28',NULL,NULL),(100,113,'TUNAI',3.00,'PC','',NULL,'111.111.001',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-07 11:35:53',NULL,NULL),(101,114,'TRANSFER',1000000.00,'BCA','',NULL,'111.112.004',0,0,0,0,NULL,NULL,NULL,NULL,NULL,4,'2020-01-07 15:09:31',NULL,NULL),(112,126,'GLACCOUNT',2800000.00,'test refff','',NULL,'111.114.005',0,2,153,4,151,NULL,NULL,NULL,'D',12,'2020-01-20 12:39:29',NULL,NULL),(113,127,'TUNAI',300000.00,'ref1','',NULL,'111.112.001',0,0,0,0,0,NULL,NULL,NULL,NULL,12,'2020-01-20 14:44:17',NULL,NULL);

/*Table structure for table `trcbreceiveother` */

DROP TABLE IF EXISTS `trcbreceiveother`;

CREATE TABLE `trcbreceiveother` (
  `fin_cbreceiveoth_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_cbreceiveoth_type` enum('Cash','Bank') DEFAULT NULL,
  `fst_cbreceiveoth_no` varchar(25) DEFAULT NULL,
  `fdt_cbreceiveoth_datetime` datetime DEFAULT NULL,
  `fin_kasbank_id` int(11) DEFAULT NULL,
  `fst_receive_from` varchar(100) DEFAULT NULL,
  `fst_curr_code` varchar(5) DEFAULT NULL,
  `fdc_exchange_rate_idr` decimal(12,2) DEFAULT NULL,
  `fst_memo` text DEFAULT NULL,
  `fin_branch_id` int(11) DEFAULT NULL,
  `fdc_nominal` decimal(12,2) DEFAULT NULL,
  `fdc_cash_transfer` decimal(12,2) DEFAULT NULL,
  `fdc_bilyet` decimal(12,2) DEFAULT NULL,
  `fst_bilyet_no` varchar(15) DEFAULT NULL,
  `fdt_clear_date` date DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_cbreceiveoth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `trcbreceiveother` */

insert  into `trcbreceiveother`(`fin_cbreceiveoth_id`,`fst_cbreceiveoth_type`,`fst_cbreceiveoth_no`,`fdt_cbreceiveoth_datetime`,`fin_kasbank_id`,`fst_receive_from`,`fst_curr_code`,`fdc_exchange_rate_idr`,`fst_memo`,`fin_branch_id`,`fdc_nominal`,`fdc_cash_transfer`,`fdc_bilyet`,`fst_bilyet_no`,`fdt_clear_date`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (7,'Cash','BCA1/JKT/2020/01/00015','2020-01-14 18:01:50',6,'devi','IDR',1.00,'ini memo header',1,520000.00,320000.00,200000.00,'1234567890','2020-01-31','D',12,'2020-01-14 18:03:47',12,'2020-01-15 11:45:59');

/*Table structure for table `trcbreceiveotheritems` */

DROP TABLE IF EXISTS `trcbreceiveotheritems`;

CREATE TABLE `trcbreceiveotheritems` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_cbreceiveoth_id` int(11) DEFAULT NULL,
  `fst_glaccount_code` varchar(100) DEFAULT NULL,
  `fdc_debit` decimal(12,2) DEFAULT 0.00,
  `fdc_credit` decimal(12,2) DEFAULT 0.00,
  `fst_notes` text DEFAULT NULL,
  `fin_pcc_id` int(11) DEFAULT NULL,
  `fin_pc_divisi_id` int(11) DEFAULT NULL,
  `fin_pc_customer_id` int(11) DEFAULT NULL,
  `fin_pc_project_id` int(11) DEFAULT NULL,
  `fin_relation_id` int(11) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `trcbreceiveotheritems` */

insert  into `trcbreceiveotheritems`(`fin_rec_id`,`fin_cbreceiveoth_id`,`fst_glaccount_code`,`fdc_debit`,`fdc_credit`,`fst_notes`,`fin_pcc_id`,`fin_pc_divisi_id`,`fin_pc_customer_id`,`fin_pc_project_id`,`fin_relation_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (6,7,'111.114.005',0.00,500000.00,'test',NULL,0,NULL,NULL,149,'D',12,'2020-01-15 11:45:59',NULL,NULL),(10,7,'612.111.007',0.00,20000.00,'test 2',1,NULL,154,4,NULL,'D',12,'2020-01-15 11:45:59',NULL,NULL);

/*Table structure for table `trchequeflow` */

DROP TABLE IF EXISTS `trchequeflow`;

CREATE TABLE `trchequeflow` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_source_type` enum('CBD_OUT','CBD_IN','CBO_OUT','CBO_IN') DEFAULT NULL,
  `fin_trx_id` int(11) DEFAULT NULL,
  `fin_trx_detail_id` int(11) DEFAULT NULL,
  `fst_type` enum('IN','OUT') DEFAULT NULL,
  `fin_relation_id` int(11) DEFAULT NULL,
  `fst_cheque_no` varchar(100) DEFAULT NULL,
  `fdt_clear_date` date DEFAULT NULL,
  `fst_cheque_status` enum('OPEN','BOUNCE','CLEARED') DEFAULT NULL,
  `fst_notes` text DEFAULT NULL,
  `fdc_amount` decimal(12,2) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `trchequeflow` */

/*Table structure for table `trinvoice` */

DROP TABLE IF EXISTS `trinvoice`;

CREATE TABLE `trinvoice` (
  `fin_inv_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_inv_no` varchar(20) DEFAULT NULL,
  `fdt_inv_datetime` datetime DEFAULT NULL,
  `fin_relation_id` int(11) DEFAULT NULL,
  `fin_salesorder_id` int(11) DEFAULT NULL,
  `fin_sj_id` int(11) DEFAULT NULL,
  `fin_branch_id` int(11) DEFAULT NULL,
  `fin_warehouse_id` int(11) DEFAULT NULL,
  `fst_inv_memo` text DEFAULT NULL,
  `fst_curr_code` varchar(10) DEFAULT NULL,
  `fdc_exchange_rate_idr` decimal(12,2) DEFAULT 0.00,
  `fbl_is_vat_include` tinyint(1) DEFAULT 0,
  `fin_terms_payment` int(5) DEFAULT NULL,
  `fdt_payment_due_date` date DEFAULT NULL COMMENT 'Tanggal Jatuh Tempo Tagihan',
  `fin_sales_id` int(11) DEFAULT NULL,
  `fdc_subttl` decimal(12,2) DEFAULT 0.00 COMMENT 'total Sebelum disc dan pajak',
  `fdc_dpp_amount` decimal(12,2) DEFAULT 0.00,
  `fdc_disc_amount` decimal(12,2) DEFAULT 0.00,
  `fdc_ppn_percent` decimal(12,2) DEFAULT 0.00,
  `fdc_ppn_amount` decimal(12,2) DEFAULT 0.00,
  `fdc_downpayment_claim` decimal(12,2) DEFAULT 0.00,
  `fdc_total` decimal(12,2) DEFAULT 0.00 COMMENT '-disc + ppn - DP Claimed',
  `fdc_total_paid` decimal(12,2) DEFAULT 0.00 COMMENT 'Total tagihan yang sudah di bayar',
  `fdc_total_return` decimal(12,2) DEFAULT 0.00 COMMENT 'total return dr tagihan ini',
  `fst_reff_no` varchar(100) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_inv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

/*Data for the table `trinvoice` */

insert  into `trinvoice`(`fin_inv_id`,`fst_inv_no`,`fdt_inv_datetime`,`fin_relation_id`,`fin_salesorder_id`,`fin_sj_id`,`fin_branch_id`,`fin_warehouse_id`,`fst_inv_memo`,`fst_curr_code`,`fdc_exchange_rate_idr`,`fbl_is_vat_include`,`fin_terms_payment`,`fdt_payment_due_date`,`fin_sales_id`,`fdc_subttl`,`fdc_dpp_amount`,`fdc_disc_amount`,`fdc_ppn_percent`,`fdc_ppn_amount`,`fdc_downpayment_claim`,`fdc_total`,`fdc_total_paid`,`fdc_total_return`,`fst_reff_no`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (9,'IV/JKT/2019/12/00001','2019-12-27 16:43:44',154,167,NULL,1,1,'SO/JKT/2019/12/00001 \r\nFAKTUR PENJUALAN','IDR',1.00,0,30,NULL,22,1050000.00,1050000.00,0.00,10.00,105000.00,600000.00,555000.00,395940.00,159060.00,'','A',4,'2019-12-27 16:44:44',NULL,NULL),(11,'IV/JKT/2019/12/00002','2019-12-27 17:32:51',157,169,NULL,1,1,'SJ/JKT/2019/12/00007','IDR',1.00,0,7,NULL,19,552000.00,552000.00,0.00,10.00,55200.00,300000.00,307200.00,256600.00,50600.00,'','A',4,'2019-12-27 17:33:24',NULL,NULL),(12,'IV/JKT/2020/01/00001','2020-01-02 10:57:16',160,170,NULL,1,2,'','IDR',1.00,0,30,NULL,19,813000.00,813000.00,0.00,10.00,81300.00,450000.00,444300.00,434400.00,9900.00,'','A',4,'2020-01-02 10:57:41',NULL,NULL),(14,'IV/JKT/2020/01/00002','2020-01-02 14:30:23',159,173,NULL,1,2,'SJ/JKT/2020/01/00004','IDR',1.00,0,30,NULL,23,1636504.00,1636500.00,4.00,10.00,163650.00,900000.00,900154.00,763754.00,136400.00,'','A',4,'2020-01-02 14:31:06',NULL,NULL),(15,'IV/JKT/2020/01/00003','2020-01-06 10:32:25',161,178,NULL,1,1,'UANG MUKA LEBIH BESAR DARI TOTAL HARGA YANG HARUS DIBAYARKAN \r\nSJ/JKT/2020/01/00011','IDR',1.00,0,30,NULL,23,3060003.00,3060000.00,3.00,10.00,306000.00,3366003.00,0.00,0.00,0.00,'','A',4,'2020-01-06 10:33:29',4,'2020-01-06 14:02:16'),(16,'IV/JKT/2020/01/00004','2020-01-06 11:38:38',154,177,NULL,1,1,'SJ/JKT/2020/01/00012','IDR',1.00,0,30,NULL,22,204002.00,204000.00,2.00,10.00,20400.00,200000.00,24402.00,15932.00,8470.00,'','A',4,'2020-01-06 11:39:24',NULL,NULL),(17,'IV/JKT/2020/01/00005','2020-01-06 13:43:16',160,179,NULL,1,1,'TEST DP LEBIH BESAR DARI TOTAL HARGA PENJUALAN','IDR',1.00,0,30,NULL,19,4305003.00,4305000.00,3.00,10.00,430500.00,4735503.00,0.00,0.00,0.00,'1','A',4,'2020-01-06 13:44:17',4,'2020-01-06 14:11:16'),(18,'IV/JKT/2020/01/00006','2020-01-06 13:53:27',157,180,NULL,1,1,'DP LEBIH BESAR','IDR',1.00,0,7,NULL,19,4305003.00,4305000.00,3.00,10.00,430500.00,4735500.00,3.00,3.00,0.00,'1','A',4,'2020-01-06 13:54:43',NULL,NULL),(19,'IV/JKT/2020/01/00007','2020-01-06 14:20:50',161,181,NULL,1,1,'SUDAH ADA SISA DP DENGAN NOMOR SO/JKT/2020/01/00004','IDR',1.00,0,30,NULL,23,373502.00,373500.00,2.00,10.00,37350.00,0.00,410852.00,410852.00,0.00,'1','A',4,'2020-01-06 14:21:53',NULL,NULL),(20,'IV/JKT/2020/01/00008','2020-01-06 15:04:25',158,182,NULL,1,1,'TEST DP LEBIH BESAR LAGI','IDR',1.00,0,7,NULL,22,3198003.00,3198000.00,3.00,10.00,319800.00,3517803.00,0.00,0.00,0.00,'1','A',4,'2020-01-06 15:05:27',NULL,NULL),(21,'IV/JKT/2020/01/00009','2020-01-06 15:16:06',153,183,NULL,1,1,'SJ/JKT/2020/01/00017','IDR',1.00,0,30,NULL,23,490403.00,490400.00,3.00,10.00,49040.00,539443.00,0.00,0.00,0.00,'1','A',4,'2020-01-06 15:17:36',NULL,NULL),(22,'IV/JKT/2020/01/00010','2020-01-06 16:54:25',159,184,NULL,1,1,'','IDR',1.00,0,30,NULL,23,324003.00,324000.00,3.00,10.00,32400.00,200000.00,156403.00,156403.00,0.00,'','A',4,'2020-01-06 16:54:45',NULL,NULL),(23,'IV/JKT/2020/01/00011','2020-01-07 11:30:19',160,185,NULL,1,1,'CITRA GARDEN 2 EXT BLOK BD 1A NO: 1 \r\nKALIDERES, PEGADUNGAN \r\nJAKARTA BARAT','IDR',1.00,0,30,NULL,19,3984003.00,3984000.00,3.00,10.00,398400.00,4382400.00,3.00,3.00,0.00,'','A',4,'2020-01-07 11:30:55',NULL,NULL),(24,'IV/JKT/2020/01/00012','2020-01-07 15:10:46',153,189,NULL,1,1,'PEMBAYARAN','IDR',1.00,0,30,NULL,23,354003.00,354000.00,3.00,10.00,35400.00,389403.00,0.00,0.00,0.00,'','A',4,'2020-01-07 15:12:26',4,'2020-01-07 15:23:00');

/*Table structure for table `trinvoicedetails` */

DROP TABLE IF EXISTS `trinvoicedetails`;

CREATE TABLE `trinvoicedetails` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_inv_id` int(11) DEFAULT NULL COMMENT 'ref: > trsalesorder.fin_salesorder_id',
  `fin_sj_id` int(11) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

/*Data for the table `trinvoicedetails` */

insert  into `trinvoicedetails`(`fin_rec_id`,`fin_inv_id`,`fin_sj_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (10,9,55,'A',4,'2019-12-27 16:44:44',NULL,NULL),(12,11,58,'A',4,'2019-12-27 17:33:24',NULL,NULL),(13,12,61,'A',4,'2020-01-02 10:57:41',NULL,NULL),(15,14,65,'A',4,'2020-01-02 14:31:06',NULL,NULL),(17,16,74,'A',4,'2020-01-06 11:39:24',NULL,NULL),(19,18,76,'A',4,'2020-01-06 13:54:43',NULL,NULL),(20,15,72,'A',4,'2020-01-06 14:02:16',NULL,NULL),(21,17,75,'A',4,'2020-01-06 14:11:16',NULL,NULL),(22,19,77,'A',4,'2020-01-06 14:21:53',NULL,NULL),(23,20,78,'A',4,'2020-01-06 15:05:27',NULL,NULL),(24,21,79,'A',4,'2020-01-06 15:17:36',NULL,NULL),(25,22,81,'A',4,'2020-01-06 16:54:45',NULL,NULL),(26,23,83,'A',4,'2020-01-07 11:30:55',NULL,NULL),(28,24,85,'A',4,'2020-01-07 15:23:00',NULL,NULL);

/*Table structure for table `trinvoiceitems` */

DROP TABLE IF EXISTS `trinvoiceitems`;

CREATE TABLE `trinvoiceitems` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_inv_id` int(11) DEFAULT NULL,
  `fin_item_id` int(11) DEFAULT NULL,
  `fst_custom_item_name` varchar(100) DEFAULT NULL,
  `fst_unit` varchar(100) DEFAULT NULL,
  `fdb_qty` double(12,2) DEFAULT NULL,
  `fdb_qty_return` double(12,2) DEFAULT 0.00,
  `fdc_price` decimal(12,2) DEFAULT NULL,
  `fst_disc_item` varchar(100) DEFAULT NULL COMMENT 'Discount Item bertingkat berupa string, misal 10+5+2',
  `fdc_disc_amount_per_item` decimal(12,2) DEFAULT 0.00,
  `fst_memo_item` text DEFAULT NULL,
  `fin_promo_id` int(11) DEFAULT NULL COMMENT 'Bila terisi merupakan item promo',
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8;

/*Data for the table `trinvoiceitems` */

insert  into `trinvoiceitems`(`fin_rec_id`,`fin_inv_id`,`fin_item_id`,`fst_custom_item_name`,`fst_unit`,`fdb_qty`,`fdb_qty_return`,`fdc_price`,`fst_disc_item`,`fdc_disc_amount_per_item`,`fst_memo_item`,`fin_promo_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (28,9,63,'GREEBEL 3736 PENCIL SUPER LEAD','SET',12.00,2.00,72300.00,'0',0.00,NULL,0,'A',4,'2019-12-27 16:44:44',NULL,NULL),(29,9,68,'GREEBEL PAKET UJIAN PINTAR','SET',12.00,0.00,15200.00,'0',0.00,NULL,0,'A',4,'2019-12-27 16:44:44',NULL,NULL),(32,11,68,'GREEBEL PAKET UJIAN PINTAR','SET',12.00,1.00,26000.00,'0',0.00,NULL,0,'A',4,'2019-12-27 17:33:24',NULL,NULL),(33,11,72,'MASTER BOX GREEBEL GUNTING GB-SC-02','PCS',12.00,1.00,20000.00,'0',0.00,NULL,0,'A',4,'2019-12-27 17:33:24',NULL,NULL),(34,12,62,'GREEBEL 7018 PENCIL 2B (12 PCS/SET)','SET',5.00,0.00,54000.00,'0',0.00,NULL,0,'A',4,'2020-01-02 10:57:41',NULL,NULL),(35,12,63,'GREEBEL 3736 PENCIL SUPER LEAD','SET',4.00,0.00,124500.00,'0',0.00,NULL,0,'A',4,'2020-01-02 10:57:41',NULL,NULL),(36,12,187,'GREEBEL 7026 HB HEXAGONAL PENCIL (PER PCS)','PCS',10.00,2.00,4500.00,'0',0.00,NULL,0,'A',4,'2020-01-02 10:57:41',NULL,NULL),(43,14,63,'GREEBEL 3736 PENCIL SUPER LEAD','SET',5.00,0.00,124500.00,'0',0.00,NULL,0,'A',4,'2020-01-02 14:31:06',NULL,NULL),(44,14,98,'GREEBEL 7206 PENCIL BI COLOR','SET',12.00,0.00,22500.00,'0',0.00,NULL,0,'A',4,'2020-01-02 14:31:06',NULL,NULL),(45,14,191,'GREEBEL 6712 - 3.7 MM WATER COLOUR PENCIL','SET',12.00,2.00,62000.00,'0',0.00,NULL,0,'A',4,'2020-01-02 14:31:06',NULL,NULL),(46,14,0,'Piring cantik','PCS',1.00,0.00,1.00,'100',1.00,NULL,12,'A',4,'2020-01-02 14:31:06',NULL,NULL),(47,14,101,'COLOURING BOOK NOT FOR SALE','PCS',1.00,0.00,1.00,'100',1.00,NULL,12,'A',4,'2020-01-02 14:31:06',NULL,NULL),(48,14,0,'PAYUNG CANTIK','PCS',1.00,0.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-02 14:31:06',NULL,NULL),(49,14,186,'CORRECTION PEN W 004','LSN',1.00,0.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-02 14:31:06',NULL,NULL),(54,16,91,'GREEBEL GLUE STICK 8G - 2 PCS/SET','SET',12.00,0.00,9300.00,'0',0.00,NULL,0,'A',4,'2020-01-06 11:39:24',NULL,NULL),(55,16,107,'GREEBEL 7018 2 PCS + ERASER GBB-141240 2 PCS','SET',12.00,1.00,7700.00,'0',0.00,NULL,0,'A',4,'2020-01-06 11:39:24',NULL,NULL),(56,16,0,'PAYUNG CANTIK','PCS',1.00,0.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 11:39:24',NULL,NULL),(57,16,186,'CORRECTION PEN W 004','LSN',1.00,0.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 11:39:24',NULL,NULL),(63,18,63,'GREEBEL 3736 PENCIL SUPER LEAD','SET',10.00,0.00,124500.00,'0',0.00,NULL,0,'A',4,'2020-01-06 13:54:43',NULL,NULL),(64,18,118,'GREEBEL ARTIST OIL PASTEL 36C','SET',12.00,0.00,255000.00,'0',0.00,NULL,0,'A',4,'2020-01-06 13:54:43',NULL,NULL),(65,18,0,'PAYUNG CANTIK','PCS',1.00,1.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 13:54:43',NULL,NULL),(66,18,186,'CORRECTION PEN W 004','LSN',1.00,0.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 13:54:43',NULL,NULL),(67,18,0,'WINE GLASS','PCS',1.00,0.00,1.00,'100',1.00,NULL,14,'A',4,'2020-01-06 13:54:43',NULL,NULL),(68,15,118,'GREEBEL ARTIST OIL PASTEL 36C','SET',12.00,0.00,255000.00,'0',0.00,NULL,0,'A',4,'2020-01-06 14:02:16',NULL,NULL),(69,15,0,'PAYUNG CANTIK','PCS',1.00,0.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 14:02:16',NULL,NULL),(70,15,186,'CORRECTION PEN W 004','LSN',1.00,1.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 14:02:16',NULL,NULL),(71,15,0,'WINE GLASS','PCS',1.00,1.00,1.00,'100',1.00,NULL,14,'A',4,'2020-01-06 14:02:16',NULL,NULL),(72,17,63,'GREEBEL 3736 PENCIL SUPER LEAD','SET',10.00,0.00,124500.00,'0',0.00,NULL,0,'A',4,'2020-01-06 14:11:16',NULL,NULL),(73,17,118,'GREEBEL ARTIST OIL PASTEL 36C','SET',12.00,0.00,255000.00,'0',0.00,NULL,0,'A',4,'2020-01-06 14:11:16',NULL,NULL),(74,17,0,'PAYUNG CANTIK','PCS',1.00,0.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 14:11:16',NULL,NULL),(75,17,186,'CORRECTION PEN W 004','LSN',1.00,0.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 14:11:16',NULL,NULL),(76,17,0,'WINE GLASS','PCS',1.00,1.00,1.00,'100',1.00,NULL,14,'A',4,'2020-01-06 14:11:16',NULL,NULL),(77,19,63,'GREEBEL 3736 PENCIL SUPER LEAD','SET',3.00,0.00,124500.00,'0',0.00,NULL,0,'A',4,'2020-01-06 14:21:53',NULL,NULL),(78,19,0,'PAYUNG CANTIK','PCS',1.00,0.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 14:21:53',NULL,NULL),(79,19,186,'CORRECTION PEN W 004','LSN',1.00,1.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 14:21:53',NULL,NULL),(80,20,62,'GREEBEL 7018 PENCIL 2B (12 PCS/SET)','SET',12.00,0.00,54000.00,'0',0.00,NULL,0,'A',4,'2020-01-06 15:05:27',NULL,NULL),(81,20,118,'GREEBEL ARTIST OIL PASTEL 36C','SET',10.00,0.00,255000.00,'0',0.00,NULL,0,'A',4,'2020-01-06 15:05:27',NULL,NULL),(82,20,0,'PAYUNG CANTIK','PCS',1.00,0.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 15:05:27',NULL,NULL),(83,20,186,'CORRECTION PEN W 004','LSN',1.00,0.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 15:05:27',NULL,NULL),(84,20,0,'WINE GLASS','PCS',1.00,0.00,1.00,'100',1.00,NULL,14,'A',4,'2020-01-06 15:05:27',NULL,NULL),(85,21,64,'GREEBEL PENCIL BAG MICA 2520','PCS',12.00,0.00,4200.00,'0',0.00,NULL,0,'A',4,'2020-01-06 15:17:36',NULL,NULL),(86,21,68,'GREEBEL PAKET UJIAN PINTAR','SET',10.00,0.00,26000.00,'0',0.00,NULL,0,'A',4,'2020-01-06 15:17:36',NULL,NULL),(87,21,107,'GREEBEL 7018 2 PCS + ERASER GBB-141240 2 PCS','SET',12.00,0.00,15000.00,'0',0.00,NULL,0,'A',4,'2020-01-06 15:17:36',NULL,NULL),(88,21,0,'PAYUNG CANTIK','PCS',1.00,0.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 15:17:36',NULL,NULL),(89,21,186,'CORRECTION PEN W 004','LSN',1.00,0.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 15:17:36',NULL,NULL),(90,21,0,'WINE GLASS','PCS',1.00,1.00,1.00,'100',1.00,NULL,14,'A',4,'2020-01-06 15:17:36',NULL,NULL),(91,22,98,'GREEBEL 7206 PENCIL BI COLOR','SET',12.00,0.00,22500.00,'0',0.00,NULL,0,'A',4,'2020-01-06 16:54:46',NULL,NULL),(92,22,175,'GREEBEL 7019 PENCIL 2B (PER PCS)','PCS',12.00,0.00,4500.00,'0',0.00,NULL,0,'A',4,'2020-01-06 16:54:46',NULL,NULL),(93,22,0,'PAYUNG CANTIK','PCS',1.00,1.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 16:54:46',NULL,NULL),(94,22,186,'CORRECTION PEN W 004','LSN',1.00,1.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-06 16:54:46',NULL,NULL),(95,22,0,'WINE GLASS','PCS',1.00,0.00,1.00,'100',1.00,NULL,14,'A',4,'2020-01-06 16:54:46',NULL,NULL),(96,23,107,'GREEBEL 7018 2 PCS + ERASER GBB-141240 2 PCS','SET',12.00,0.00,15000.00,'0',0.00,NULL,0,'A',4,'2020-01-07 11:30:55',NULL,NULL),(97,23,118,'GREEBEL ARTIST OIL PASTEL 36C','SET',12.00,0.00,255000.00,'0',0.00,NULL,0,'A',4,'2020-01-07 11:30:55',NULL,NULL),(98,23,191,'GREEBEL 6712 - 3.7 MM WATER COLOUR PENCIL','SET',12.00,0.00,62000.00,'0',0.00,NULL,0,'A',4,'2020-01-07 11:30:55',NULL,NULL),(99,23,0,'PAYUNG CANTIK','PCS',1.00,1.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-07 11:30:55',NULL,NULL),(100,23,186,'CORRECTION PEN W 004','LSN',1.00,1.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-07 11:30:55',NULL,NULL),(101,23,0,'WINE GLASS','PCS',1.00,1.00,1.00,'100',1.00,NULL,14,'A',4,'2020-01-07 11:30:55',NULL,NULL),(107,24,91,'GREEBEL GLUE STICK 8G - 2 PCS/SET','SET',12.00,0.00,14500.00,'0',0.00,NULL,0,'A',4,'2020-01-07 15:23:00',NULL,NULL),(108,24,107,'GREEBEL 7018 2 PCS + ERASER GBB-141240 2 PCS','SET',12.00,0.00,15000.00,'0',0.00,NULL,0,'A',4,'2020-01-07 15:23:00',NULL,NULL),(109,24,0,'PAYUNG CANTIK','PCS',1.00,0.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-07 15:23:01',NULL,NULL),(110,24,186,'CORRECTION PEN W 004','LSN',1.00,1.00,1.00,'100',1.00,NULL,13,'A',4,'2020-01-07 15:23:01',NULL,NULL),(111,24,0,'WINE GLASS','PCS',1.00,0.00,1.00,'100',1.00,NULL,14,'A',4,'2020-01-07 15:23:01',NULL,NULL);

/*Table structure for table `trlpbgudang` */

DROP TABLE IF EXISTS `trlpbgudang`;

CREATE TABLE `trlpbgudang` (
  `fin_lpbgudang_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_lpbgudang_no` varchar(25) DEFAULT NULL,
  `fin_warehouse_id` int(11) DEFAULT NULL,
  `fdt_lpbgudang_datetime` datetime DEFAULT NULL,
  `fst_lpb_type` enum('PO','SO_RETURN') DEFAULT NULL,
  `fin_trans_id` int(11) DEFAULT NULL,
  `fst_trans_no` varchar(25) DEFAULT NULL,
  `fin_relation_id` int(11) DEFAULT NULL,
  `fst_memo` text DEFAULT NULL,
  `fin_branch_id` int(11) DEFAULT NULL,
  `fin_lpbpurchase_id` int(11) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) unsigned DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_lpbgudang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=latin1;

/*Data for the table `trlpbgudang` */

insert  into `trlpbgudang`(`fin_lpbgudang_id`,`fst_lpbgudang_no`,`fin_warehouse_id`,`fdt_lpbgudang_datetime`,`fst_lpb_type`,`fin_trans_id`,`fst_trans_no`,`fin_relation_id`,`fst_memo`,`fin_branch_id`,`fin_lpbpurchase_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (63,'GUD/JKT/2019/12/00002',1,'2019-12-27 15:02:13','PO',65,'PO/JKT/2019/12/00002',150,'',1,42,'A',4,'2019-12-27 15:05:45',NULL,NULL),(64,'GUD/JKT/2019/12/00003',1,'2019-12-27 15:46:35','PO',70,'PO/JKT/2019/12/00007',149,'OKE',1,43,'A',4,'2019-12-27 15:48:48',NULL,NULL),(65,'GUD/JKT/2019/12/00004',1,'2019-12-27 16:01:49','PO',66,'PO/JKT/2019/12/00003',151,'OK',1,44,'A',4,'2019-12-27 16:05:29',NULL,NULL),(66,'GUD/JKT/2019/12/00005',2,'2019-12-27 16:14:53','PO',67,'PO/JKT/2019/12/00004',152,'OK',1,45,'A',4,'2019-12-27 16:28:47',NULL,NULL),(67,'GUD/JKT/2019/12/00006',1,'2019-12-27 16:46:41','SO_RETURN',7,'SRT/JKT/2019/12/00001',154,'SO/JKT/2019/12/00001',1,NULL,'A',4,'2019-12-27 16:47:36',NULL,NULL),(68,'GUD/JKT/2019/12/00007',2,'2019-12-27 16:51:23','PO',68,'PO/JKT/2019/12/00005',153,'OK',1,46,'A',4,'2019-12-27 16:58:58',NULL,NULL),(69,'GUD/JKT/2019/12/00008',1,'2019-12-27 17:34:54','SO_RETURN',8,'SRT/JKT/2019/12/00002',157,'BARANG RUSAK',1,NULL,'A',4,'2019-12-27 17:36:09',NULL,NULL),(70,'GUD/JKT/2019/12/00009',2,'2019-12-27 17:54:47','PO',69,'PO/JKT/2019/12/00006',159,'OK',1,47,'A',4,'2019-12-27 17:59:21',NULL,NULL),(71,'GUD/JKT/2019/12/00010',1,'2019-12-27 17:59:23','PO',71,'PO/JKT/2019/12/00008',159,'IMPORT',1,48,'A',4,'2019-12-27 18:02:29',NULL,NULL),(72,'GUD/JKT/2020/01/00001',2,'2020-01-02 10:59:06','SO_RETURN',9,'SRT/JKT/2020/01/00001',160,'',1,NULL,'A',4,'2020-01-02 11:02:07',NULL,NULL),(73,'GUD/JKT/2020/01/00002',2,'2020-01-02 11:15:39','PO',72,'PO/JKT/2020/01/00001',150,'OKE',1,49,'A',4,'2020-01-02 11:17:43',NULL,NULL),(74,'GUD/JKT/2020/01/00003',2,'2020-01-02 12:15:45','SO_RETURN',10,'SRT/JKT/2020/01/00002',159,'',1,NULL,'D',4,'2020-01-02 12:16:29',NULL,NULL),(75,'GUD/JKT/2020/01/00004',2,'2020-01-02 14:16:05','PO',73,'PO/JKT/2020/01/00002',149,'OKE',1,50,'A',4,'2020-01-02 14:16:37',NULL,NULL),(76,'GUD/JKT/2020/01/00005',2,'2020-01-02 14:36:02','SO_RETURN',11,'SRT/JKT/2020/01/00002',159,'PENGEMBALIAN',1,NULL,'A',4,'2020-01-02 14:38:05',NULL,NULL),(77,'GUD/JKT/2020/01/00006',1,'2020-01-03 09:35:54','PO',74,'PO/JKT/2020/01/00003',152,'LENGKAP',1,51,'A',4,'2020-01-03 09:38:57',4,'2020-01-03 09:39:30'),(78,'GUD/JKT/2020/01/00007',1,'2020-01-03 10:10:13','PO',75,'PO/JKT/2020/01/00004',150,'TUKARAN YANG DIRETUR',1,52,'A',4,'2020-01-03 10:19:03',NULL,NULL),(79,'GUD/JKT/2020/01/00008',2,'2020-01-03 10:19:05','PO',76,'PO/JKT/2020/01/00005',150,'TUKERAN YANG RETUR',1,53,'A',4,'2020-01-03 10:20:12',NULL,NULL),(80,'GUD/JKT/2020/01/00009',1,'2020-01-03 10:49:17','PO',77,'PO/JKT/2020/01/00006',151,'C22 - JAKARTA 1 PT.005 - WIGUNA INDO NITAMA, PT',1,54,'A',4,'2020-01-03 11:01:08',NULL,NULL),(81,'GUD/JKT/2020/01/00010',1,'2020-01-03 13:42:47','PO',78,'PO/JKT/2020/01/00007',149,'C22 - JAKARTA',1,55,'A',4,'2020-01-03 13:48:32',NULL,NULL),(82,'GUD/JKT/2020/01/00011',1,'2020-01-06 09:45:53','PO',79,'PO/JKT/2020/01/00008',150,'OKE',1,56,'A',4,'2020-01-06 09:48:48',NULL,NULL),(83,'GUD/JKT/2020/01/00012',1,'2020-01-06 11:42:38','SO_RETURN',12,'SRT/JKT/2020/01/00003',154,'RUSAK',1,NULL,'A',4,'2020-01-06 11:46:06',NULL,NULL),(85,'GUD/JKT/2020/01/00013',1,'2020-01-06 13:58:00','SO_RETURN',14,'SRT/JKT/2020/01/00004',157,'PAYUNG RUSAK',1,NULL,'A',4,'2020-01-06 13:58:43',NULL,NULL),(86,'GUD/JKT/2020/01/00014',1,'2020-01-06 14:03:59','SO_RETURN',15,'SRT/JKT/2020/01/00005',161,'REJECT',1,NULL,'A',4,'2020-01-06 14:05:06',NULL,NULL),(87,'GUD/JKT/2020/01/00015',1,'2020-01-06 14:12:35','SO_RETURN',16,'SRT/JKT/2020/01/00006',160,'RETAK',1,NULL,'A',4,'2020-01-06 14:13:08',NULL,NULL),(88,'GUD/JKT/2020/01/00016',1,'2020-01-06 14:24:44','SO_RETURN',17,'SRT/JKT/2020/01/00007',161,'TEST SISA DP DARI NOMOR SO/JKT/2020/01/00004',1,NULL,'A',4,'2020-01-06 14:26:22',NULL,NULL),(89,'GUD/JKT/2020/01/00017',1,'2020-01-06 16:43:17','PO',80,'PO/JKT/2020/01/00009',149,'',1,57,'A',4,'2020-01-06 16:44:37',NULL,NULL),(90,'GUD/JKT/2020/01/00018',1,'2020-01-06 16:55:58','SO_RETURN',19,'SRT/JKT/2020/01/00009',159,'',1,NULL,'A',4,'2020-01-06 16:57:53',NULL,NULL),(91,'GUD/JKT/2020/01/00019',1,'2020-01-07 11:15:22','PO',81,'PO/JKT/2020/01/00010',151,'',1,58,'A',4,'2020-01-07 11:16:01',NULL,NULL),(92,'GUD/JKT/2020/01/00020',1,'2020-01-07 11:32:21','SO_RETURN',20,'SRT/JKT/2020/01/00010',160,'',1,NULL,'A',4,'2020-01-07 11:33:20',NULL,NULL),(93,'GUD/JKT/2020/01/00021',1,'2020-01-07 14:00:39','PO',82,'PO/JKT/2020/01/00011',150,'C22 - JAKARTA 1',1,59,'A',4,'2020-01-07 14:01:47',NULL,NULL),(94,'GUD/JKT/2020/01/00022',1,'2020-01-07 15:24:37','SO_RETURN',21,'SRT/JKT/2020/01/00011',153,'',1,NULL,'A',4,'2020-01-07 15:25:18',NULL,NULL);

/*Table structure for table `trlpbgudangitems` */

DROP TABLE IF EXISTS `trlpbgudangitems`;

CREATE TABLE `trlpbgudangitems` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_lpbgudang_id` int(11) DEFAULT NULL,
  `fin_trans_detail_id` int(11) DEFAULT NULL,
  `fin_item_id` int(11) DEFAULT NULL,
  `fst_custom_item_name` varchar(100) DEFAULT NULL,
  `fdb_qty` double(12,2) DEFAULT NULL,
  `fst_unit` varchar(10) DEFAULT NULL,
  `fdc_m3` decimal(12,2) DEFAULT NULL,
  `fst_batch_number` varchar(100) DEFAULT NULL,
  `fst_serial_number_list` text DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=225 DEFAULT CHARSET=latin1;

/*Data for the table `trlpbgudangitems` */

insert  into `trlpbgudangitems`(`fin_rec_id`,`fin_lpbgudang_id`,`fin_trans_detail_id`,`fin_item_id`,`fst_custom_item_name`,`fdb_qty`,`fst_unit`,`fdc_m3`,`fst_batch_number`,`fst_serial_number_list`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (127,63,242,84,'RING CLIP BOARD GREEBEL',150.00,'PCS',2.00,'A4-RCBG','[]','A',4,'2019-12-27 15:05:45',NULL,NULL),(128,63,236,72,'MASTER BOX GREEBEL GUNTING GB-SC-02',100.00,'PCS',2.00,'A4-MBGG/SC02','[]','A',4,'2019-12-27 15:05:45',NULL,NULL),(129,63,238,80,'PENCIL CASE PAKET UJIAN',100.00,'PCS',2.50,'A4-PCPU','[]','A',4,'2019-12-27 15:05:45',NULL,NULL),(130,63,241,83,'PENGGARIS GB PELITA MAS',100.00,'PCS',2.50,'A4-PGPM','[]','A',4,'2019-12-27 15:05:45',NULL,NULL),(131,63,243,85,'NAIL 3,8X8X8MM',150.00,'PCS',1.50,'A4-NAIL','[]','A',4,'2019-12-27 15:05:45',NULL,NULL),(132,63,239,81,'GREEBEL SHARPENER 102 (JAR)',100.00,'PCS',1.50,'A4-GS102','[]','A',4,'2019-12-27 15:05:46',NULL,NULL),(133,63,234,70,'GREEBEL OIL PASTEL PP - 24C - 2',100.00,'SET',2.00,'A4-GOP','[]','A',4,'2019-12-27 15:05:46',NULL,NULL),(134,63,235,71,'GREEBEL SHARPENER 103 (COLOR BOX)',100.00,'PCS',2.50,'A4-GS103','[]','A',4,'2019-12-27 15:05:46',NULL,NULL),(135,63,237,73,'GREEBEL 7018 PENCIL 2B (PER PCS)',100.00,'PCS',2.50,'A4-GP7018','[]','A',4,'2019-12-27 15:05:46',NULL,NULL),(136,63,240,82,'GREEBEL ERASER GBW-120630',100.00,'PCS',2.00,'A4-GEGBW','[]','A',4,'2019-12-27 15:05:46',NULL,NULL),(137,64,282,68,'GREEBEL PAKET UJIAN PINTAR',100.00,'SET',2.50,'A4-GPUP','[]','A',4,'2019-12-27 15:48:48',NULL,NULL),(138,64,280,65,'CRAYON TABUNG ZC 012',100.00,'TAB',3.00,'A4-CTZC/012','[]','A',4,'2019-12-27 15:48:48',NULL,NULL),(139,64,281,67,'GREEBEL WATER GLUE 35 ML',10.00,'BOX',2.00,'A4-GWG/35ML','[]','A',4,'2019-12-27 15:48:48',NULL,NULL),(140,64,277,62,'GREEBEL 7018 PENCIL 2B (12 PCS/SET)',100.00,'SET',2.00,'A4-GP7018','[]','A',4,'2019-12-27 15:48:48',NULL,NULL),(141,64,278,63,'GREEBEL 3736 PENCIL SUPER LEAD',100.00,'SET',3.00,'A4-GPSL/3736','[]','A',4,'2019-12-27 15:48:48',NULL,NULL),(142,64,279,64,'GREEBEL PENCIL BAG MICA 2520',100.00,'PCS',2.00,'A4-GPBM/2520','[]','A',4,'2019-12-27 15:48:48',NULL,NULL),(143,65,244,86,'GREEBEL STATIONERY 4 IN 1',100.00,'SET',3.00,'A4-GS4IN1','[]','A',4,'2019-12-27 16:05:29',NULL,NULL),(144,65,248,91,'GREEBEL GLUE STICK 8G - 2 PCS/SET',100.00,'SET',2.00,'A4-GGS/8G','[]','A',4,'2019-12-27 16:05:29',NULL,NULL),(145,65,250,94,'GREEBEL PAKET TAB 2017',100.00,'SET',3.00,'A4-GPT2017','[]','A',4,'2019-12-27 16:05:29',NULL,NULL),(146,65,245,87,'GREEBEL SHARPENER 102 (5000 PCS/CTN)',4.00,'CTN',3.50,'A4-GS102','[]','A',4,'2019-12-27 16:05:29',NULL,NULL),(147,65,246,88,'GREEBEL ERASER GBW-120640',100.00,'PCS',2.00,'A4-GEGBW','[]','A',4,'2019-12-27 16:05:29',NULL,NULL),(148,65,249,92,'GREEBEL GLUE STICK 8G',100.00,'PCS',2.00,'A4-GGS/8G','[]','A',4,'2019-12-27 16:05:29',NULL,NULL),(149,65,247,90,'KERTAS BARCODE',300.00,'PCS',1.50,'A4-KBRCODE','[]','A',4,'2019-12-27 16:05:29',NULL,NULL),(150,66,255,104,'PENCIL LEAD OM-11 BLACK',85.00,'GRS',2.00,'A4-PLOM/11B','[]','A',4,'2019-12-27 16:28:47',NULL,NULL),(151,66,258,174,'GREEBEL STATIONERY SET 2-7019 + 120630 + 102',100.00,'SET',3.00,'A4-GSS7019','[]','A',4,'2019-12-27 16:28:47',NULL,NULL),(152,66,251,95,'GREEBEL OIL PASTEL PP - 12C',100.00,'SET',2.00,'A4-GOP','[]','A',4,'2019-12-27 16:28:47',NULL,NULL),(153,66,252,96,'GREEBEL ERASER GBB-141820',100.00,'PCS',2.50,'A4-GEGBB','[]','A',4,'2019-12-27 16:28:47',NULL,NULL),(154,66,253,97,'GREEBEL GLUE STICK 21G',100.00,'PCS',1.50,'A4-GGS/21G','[]','A',4,'2019-12-27 16:28:47',NULL,NULL),(155,66,257,187,'GREEBEL 7026 HB HEXAGONAL PENCIL (PER PCS)',100.00,'PCS',2.00,'A4-GHP/7026','[]','A',4,'2019-12-27 16:28:47',NULL,NULL),(156,66,254,98,'GREEBEL 7206 PENCIL BI COLOR',100.00,'SET',2.00,'A4-GPBC/7206','[]','A',4,'2019-12-27 16:28:47',NULL,NULL),(157,66,256,191,'GREEBEL 6712 - 3.7 MM WATER COLOUR PENCIL',100.00,'SET',2.00,'A4-GWCP/6712','[]','A',4,'2019-12-27 16:28:47',NULL,NULL),(158,67,11,63,'GREEBEL 3736 PENCIL SUPER LEAD',2.00,'SET',3.00,'A4-GPSL/3736','[]','A',4,'2019-12-27 16:47:36',NULL,NULL),(159,68,266,72,'MASTER BOX GREEBEL GUNTING GB-SC-02',100.00,'PCS',2.00,'A4-MBGG/SC02','[]','A',4,'2019-12-27 16:58:58',NULL,NULL),(160,68,268,80,'PENCIL CASE PAKET UJIAN',100.00,'PCS',2.50,'A4-PCPU','[]','A',4,'2019-12-27 16:58:58',NULL,NULL),(161,68,263,68,'GREEBEL PAKET UJIAN PINTAR',100.00,'SET',2.50,'A4-GPUP','[]','A',4,'2019-12-27 16:58:58',NULL,NULL),(162,68,262,65,'CRAYON TABUNG ZC 012',100.00,'TAB',3.00,'A4-CTZC/012','[]','A',4,'2019-12-27 16:58:58',NULL,NULL),(163,68,264,70,'GREEBEL OIL PASTEL PP - 24C - 2',100.00,'SET',2.00,'A4-GOP','[]','A',4,'2019-12-27 16:58:58',NULL,NULL),(164,68,265,71,'GREEBEL SHARPENER 103 (COLOR BOX)',100.00,'PCS',2.50,'A4-GS103','[]','A',4,'2019-12-27 16:58:58',NULL,NULL),(165,68,267,73,'GREEBEL 7018 PENCIL 2B (PER PCS)',100.00,'PCS',2.50,'A4-GP7018','[]','A',4,'2019-12-27 16:58:58',NULL,NULL),(166,68,259,62,'GREEBEL 7018 PENCIL 2B (12 PCS/SET)',100.00,'SET',2.00,'A4-GP7018','[]','A',4,'2019-12-27 16:58:58',NULL,NULL),(167,68,260,63,'GREEBEL 3736 PENCIL SUPER LEAD',100.00,'SET',3.00,'A4-GPSL/3736','[]','A',4,'2019-12-27 16:58:58',NULL,NULL),(168,68,261,64,'GREEBEL PENCIL BAG MICA 2520',100.00,'PCS',2.00,'A4-GPBM/2520','[]','A',4,'2019-12-27 16:58:58',NULL,NULL),(169,69,13,72,'MASTER BOX GREEBEL GUNTING GB-SC-02',1.00,'PCS',2.00,'A4-MBGG/SC02','[]','A',4,'2019-12-27 17:36:09',NULL,NULL),(170,69,12,68,'GREEBEL PAKET UJIAN PINTAR',1.00,'SET',2.50,'A4-GPUP','[]','A',4,'2019-12-27 17:36:09',NULL,NULL),(171,70,272,84,'RING CLIP BOARD GREEBEL',100.00,'PCS',2.00,'A4-RCBG','[]','A',4,'2019-12-27 17:59:21',NULL,NULL),(172,70,273,86,'GREEBEL STATIONERY 4 IN 1',80.00,'SET',3.00,'A4-GS4IN1','[]','A',4,'2019-12-27 17:59:21',NULL,NULL),(173,70,275,91,'GREEBEL GLUE STICK 8G - 2 PCS/SET',100.00,'SET',2.00,'A4-GGS/8G','[]','A',4,'2019-12-27 17:59:21',NULL,NULL),(174,70,271,83,'PENGGARIS GB PELITA MAS',100.00,'PCS',2.50,'A4-PGPM','[]','A',4,'2019-12-27 17:59:21',NULL,NULL),(175,70,276,94,'GREEBEL PAKET TAB 2017',100.00,'SET',3.00,'A4-GPT2017','[]','A',4,'2019-12-27 17:59:21',NULL,NULL),(176,70,269,81,'GREEBEL SHARPENER 102 (JAR)',100.00,'PCS',1.50,'A4-GS102','[]','A',4,'2019-12-27 17:59:21',NULL,NULL),(177,70,274,88,'GREEBEL ERASER GBW-120640',100.00,'PCS',2.00,'A4-GEGBW','[]','A',4,'2019-12-27 17:59:21',NULL,NULL),(178,70,270,82,'GREEBEL ERASER GBW-120630',100.00,'PCS',2.00,'A4-GEGBW','[]','A',4,'2019-12-27 17:59:21',NULL,NULL),(179,71,283,107,'GREEBEL 7018 2 PCS + ERASER GBB-141240 2 PCS',100.00,'SET',3.00,'A4-GP7018/EGBB/240','[]','A',4,'2019-12-27 18:02:29',NULL,NULL),(180,71,284,62,'GREEBEL 7018 PENCIL 2B (12 PCS/SET)',100.00,'SET',2.00,'A4-GP7018','[]','A',4,'2019-12-27 18:02:29',NULL,NULL),(181,71,285,64,'GREEBEL PENCIL BAG MICA 2520',100.00,'PCS',2.00,'A4-GPBM/2520','[]','A',4,'2019-12-27 18:02:29',NULL,NULL),(182,72,14,187,'GREEBEL 7026 HB HEXAGONAL PENCIL (PER PCS)',2.00,'PCS',2.00,'A4-GHP/7026','[]','A',4,'2020-01-02 11:02:07',NULL,NULL),(183,73,286,186,'CORRECTION PEN W 004',100.00,'LSN',2.00,'A4-CPW/004','[]','A',4,'2020-01-02 11:17:43',NULL,NULL),(184,74,15,187,'GREEBEL 7026 HB HEXAGONAL PENCIL (PER PCS)',1.00,'PCS',2.00,'A4-GHP/7026','[]','D',4,'2020-01-02 12:16:29',NULL,NULL),(185,75,287,101,'COLOURING BOOK NOT FOR SALE',200.00,'PCS',2.00,'A4-CBNFS','[]','A',4,'2020-01-02 14:16:37',NULL,NULL),(186,76,16,191,'GREEBEL 6712 - 3.7 MM WATER COLOUR PENCIL',2.00,'SET',2.00,'A4-GWCP/6712','[]','A',4,'2020-01-02 14:38:05',NULL,NULL),(187,77,290,94,'GREEBEL PAKET TAB 2017',100.00,'SET',3.00,'A4-GPT2017','[]','A',4,'2020-01-03 09:39:30',NULL,NULL),(188,77,288,107,'GREEBEL 7018 2 PCS + ERASER GBB-141240 2 PCS',100.00,'SET',3.00,'A4-GP7018/EGBB/240','[]','A',4,'2020-01-03 09:39:30',NULL,NULL),(189,77,291,68,'GREEBEL PAKET UJIAN PINTAR',100.00,'SET',2.50,'A4-GPUP','[]','A',4,'2020-01-03 09:39:30',NULL,NULL),(190,77,289,186,'CORRECTION PEN W 004',100.00,'LSN',2.00,'A4-CPW/004','[]','A',4,'2020-01-03 09:39:30',NULL,NULL),(191,78,300,84,'RING CLIP BOARD GREEBEL',2.00,'PCS',2.00,'A4-RCBG','[]','A',4,'2020-01-03 10:19:03',NULL,NULL),(192,78,294,72,'MASTER BOX GREEBEL GUNTING GB-SC-02',2.00,'PCS',2.00,'A4-MBGG/SC02','[]','A',4,'2020-01-03 10:19:03',NULL,NULL),(193,78,296,80,'PENCIL CASE PAKET UJIAN',2.00,'PCS',2.50,'A4-PCPU','[]','A',4,'2020-01-03 10:19:03',NULL,NULL),(194,78,299,83,'PENGGARIS GB PELITA MAS',2.00,'PCS',2.50,'A4-PGPM','[]','A',4,'2020-01-03 10:19:03',NULL,NULL),(195,78,301,85,'NAIL 3,8X8X8MM',2.00,'PCS',1.50,'A4-NAIL','[]','A',4,'2020-01-03 10:19:03',NULL,NULL),(196,78,297,81,'GREEBEL SHARPENER 102 (JAR)',2.00,'PCS',1.50,'A4-GS102','[]','A',4,'2020-01-03 10:19:03',NULL,NULL),(197,78,292,70,'GREEBEL OIL PASTEL PP - 24C - 2',2.00,'SET',2.00,'A4-GOP','[]','A',4,'2020-01-03 10:19:03',NULL,NULL),(198,78,293,71,'GREEBEL SHARPENER 103 (COLOR BOX)',2.00,'PCS',2.50,'A4-GS103','[]','A',4,'2020-01-03 10:19:03',NULL,NULL),(199,78,295,73,'GREEBEL 7018 PENCIL 2B (PER PCS)',2.00,'PCS',2.50,'A4-GP7018','[]','A',4,'2020-01-03 10:19:03',NULL,NULL),(200,78,298,82,'GREEBEL ERASER GBW-120630',2.00,'PCS',2.00,'A4-GEGBW','[]','A',4,'2020-01-03 10:19:03',NULL,NULL),(201,79,302,186,'CORRECTION PEN W 004',2.00,'LSN',2.00,'A4-CPW/004','[]','A',4,'2020-01-03 10:20:12',NULL,NULL),(202,80,305,69,'PAPAN UJIAN A4 BIRU',100.00,'PCS',3.00,'A4-PUA4/B','[]','A',4,'2020-01-03 11:01:08',NULL,NULL),(203,80,303,90,'KERTAS BARCODE',100.00,'PCS',1.50,'A4-KBRCODE','[]','A',4,'2020-01-03 11:01:08',NULL,NULL),(204,80,304,64,'GREEBEL PENCIL BAG MICA 2520',100.00,'PCS',2.00,'A4-GPBM/2520','[]','A',4,'2020-01-03 11:01:08',NULL,NULL),(205,81,306,177,'GREEBEL BALLPEN TECHNOLINE 0.5 BLACK',200.00,'PCS',2.00,'A4-GBTB/0.5','[]','A',4,'2020-01-03 13:48:32',NULL,NULL),(206,82,307,118,'GREEBEL ARTIST OIL PASTEL 36C',200.00,'SET',3.00,'A4-GAOP/36C','[]','A',4,'2020-01-06 09:48:48',NULL,NULL),(207,83,17,107,'GREEBEL 7018 2 PCS + ERASER GBB-141240 2 PCS',1.00,'SET',3.00,'A4-GP7018/EGBB/240','[]','A',4,'2020-01-06 11:46:06',NULL,NULL),(209,85,19,0,'PAYUNG CANTIK',1.00,'PCS',1.00,'','[]','A',4,'2020-01-06 13:58:43',NULL,NULL),(210,86,20,186,'CORRECTION PEN W 004',1.00,'LSN',2.00,'A4-CPW/004','[]','A',4,'2020-01-06 14:05:06',NULL,NULL),(211,87,21,0,'WINE GLASS',1.00,'PCS',3.00,'','[]','A',4,'2020-01-06 14:13:08',NULL,NULL),(212,88,22,186,'CORRECTION PEN W 004',1.00,'LSN',2.00,'A4-CPW/004','[]','A',4,'2020-01-06 14:26:22',NULL,NULL),(213,88,23,0,'WINE GLASS',1.00,'PCS',3.00,'','[]','A',4,'2020-01-06 14:26:22',NULL,NULL),(214,89,308,175,'GREEBEL 7019 PENCIL 2B (PER PCS)',200.00,'PCS',3.00,'A4-GP7019','[]','A',4,'2020-01-06 16:44:37',NULL,NULL),(215,89,309,98,'GREEBEL 7206 PENCIL BI COLOR',200.00,'SET',3.00,'A4-GP7206/BIC','[]','A',4,'2020-01-06 16:44:37',NULL,NULL),(216,90,26,186,'CORRECTION PEN W 004',1.00,'LSN',2.00,'A4-CPW/004','[]','A',4,'2020-01-06 16:57:53',NULL,NULL),(217,90,25,0,'PAYUNG CANTIK',1.00,'PCS',1.00,'','[]','A',4,'2020-01-06 16:57:53',NULL,NULL),(218,91,310,191,'GREEBEL 6712 - 3.7 MM WATER COLOUR PENCIL',200.00,'SET',2.00,'A4-GWCP/6712','[]','A',4,'2020-01-07 11:16:01',NULL,NULL),(219,92,28,186,'CORRECTION PEN W 004',1.00,'LSN',2.00,'A4-CPW/004','[]','A',4,'2020-01-07 11:33:20',NULL,NULL),(220,92,27,0,'PAYUNG CANTIK',1.00,'PCS',0.00,'','[]','A',4,'2020-01-07 11:33:20',NULL,NULL),(221,92,29,0,'WINE GLASS',1.00,'PCS',0.00,'','[]','A',4,'2020-01-07 11:33:20',NULL,NULL),(222,93,312,174,'GREEBEL STATIONERY SET 2-7019 + 120630 + 102',100.00,'SET',3.00,'A4-GSS7019','[]','A',4,'2020-01-07 14:01:47',NULL,NULL),(223,93,311,181,'GREEBEL SHARPENER 102 (2PCS/PACK)',100.00,'PACK',1.50,'A4-GS102','[]','A',4,'2020-01-07 14:01:47',NULL,NULL),(224,94,30,186,'CORRECTION PEN W 004',1.00,'LSN',2.00,'A4-CPW/004','[]','A',4,'2020-01-07 15:25:18',NULL,NULL);

/*Table structure for table `trlpbpurchase` */

DROP TABLE IF EXISTS `trlpbpurchase`;

CREATE TABLE `trlpbpurchase` (
  `fin_lpbpurchase_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_lpbpurchase_no` varchar(25) DEFAULT NULL,
  `fdt_lpbpurchase_datetime` datetime DEFAULT NULL,
  `fin_po_id` int(11) DEFAULT NULL,
  `fin_supplier_id` int(11) DEFAULT NULL,
  `fin_term` int(5) DEFAULT NULL,
  `fst_curr_code` varchar(10) DEFAULT NULL,
  `fdc_exchange_rate_idr` decimal(12,2) DEFAULT 0.00,
  `fdc_subttl` decimal(12,2) DEFAULT 0.00 COMMENT 'ttl before disc',
  `fdc_disc_amount` decimal(12,2) DEFAULT 0.00,
  `fdc_ppn_percent` decimal(12,2) DEFAULT 0.00,
  `fdc_ppn_amount` decimal(12,2) DEFAULT 0.00,
  `fdc_downpayment_claim` decimal(12,2) DEFAULT 0.00 COMMENT 'ttl nilai dp yang di claim',
  `fdc_total` decimal(12,2) DEFAULT 0.00 COMMENT 'ttl after disc + ppn - DP Claimed',
  `fdc_total_paid` decimal(12,2) DEFAULT 0.00 COMMENT 'ttl yang sudah dibayar',
  `fdc_total_return` decimal(12,2) DEFAULT 0.00 COMMENT 'ttl return tidak boleh melebih fdc_total - fdc_total_paid',
  `fst_memo` text DEFAULT NULL,
  `fin_branch_id` int(11) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_lpbpurchase_id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;

/*Data for the table `trlpbpurchase` */

insert  into `trlpbpurchase`(`fin_lpbpurchase_id`,`fst_lpbpurchase_no`,`fdt_lpbpurchase_datetime`,`fin_po_id`,`fin_supplier_id`,`fin_term`,`fst_curr_code`,`fdc_exchange_rate_idr`,`fdc_subttl`,`fdc_disc_amount`,`fdc_ppn_percent`,`fdc_ppn_amount`,`fdc_downpayment_claim`,`fdc_total`,`fdc_total_paid`,`fdc_total_return`,`fst_memo`,`fin_branch_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (42,'FB/JKT/2019/12/00002','2019-12-27 15:07:02',65,150,30,'IDR',1.00,12250000.00,1225000.00,10.00,1102500.00,6200000.00,5927500.00,5694850.00,232650.00,'GUD/JKT/2019/12/00002',1,'A',4,'2019-12-27 15:07:39',NULL,NULL),(43,'FB/JKT/2019/12/00003','2019-12-27 15:48:59',70,149,30,'IDR',1.00,11450000.00,1145000.00,10.00,1030500.00,6000000.00,5335500.00,4850400.00,485100.00,'GUD/JKT/2019/12/00003',1,'A',4,'2019-12-27 15:49:29',NULL,NULL),(44,'FB/JKT/2019/12/00004','2019-12-27 16:05:39',66,151,30,'IDR',1.00,15450000.00,1560000.00,10.00,1389000.00,8000000.00,7279000.00,7268000.00,11000.00,'GUD/JKT/2019/12/00004',1,'A',4,'2019-12-27 16:06:13',NULL,NULL),(45,'FB/JKT/2019/12/00005','2019-12-27 16:28:57',67,152,30,'IDR',1.00,19390000.00,1939000.00,10.00,1745100.00,9500000.00,9696100.00,9052600.00,643500.00,'PO/JKT/2019/12/00004',1,'A',4,'2019-12-27 16:29:48',NULL,NULL),(46,'FB/JKT/2019/12/00006','2019-12-27 16:59:08',68,153,30,'IDR',1.00,16000000.00,1600000.00,10.00,1440000.00,7500000.00,8340000.00,8043000.00,297000.00,'PO/JKT/2019/12/00005',1,'A',4,'2019-12-27 16:59:36',NULL,NULL),(47,'FB/JKT/2019/12/00007','2019-12-27 18:02:39',69,159,30,'IDR',1.00,15250000.00,1525000.00,10.00,1372500.00,7500000.00,7597500.00,7473750.00,123750.00,'GUD/JKT/2019/12/00009',1,'A',4,'2019-12-27 18:03:15',NULL,NULL),(48,'FB/JKT/2019/12/00008','2019-12-27 18:03:17',71,159,30,'USD',14250.00,3500.00,175.00,0.00,0.00,1600.00,1725.00,1620.50,104.50,'GUD/JKT/2019/12/00010',1,'A',4,'2019-12-27 18:03:49',NULL,NULL),(49,'FB/JKT/2020/01/00001','2020-01-02 11:18:00',72,150,30,'IDR',1.00,2000000.00,200000.00,10.00,180000.00,1000000.00,980000.00,940400.00,39600.00,'GUD/JKT/2020/01/00002',1,'A',4,'2020-01-02 11:18:37',NULL,NULL),(50,'FB/JKT/2020/01/00002','2020-01-02 14:16:47',73,149,20,'IDR',1.00,2000.00,0.00,10.00,200.00,1500.00,700.00,678.00,22.00,'GUD/JKT/2020/01/00004',1,'A',4,'2020-01-02 14:17:19',NULL,NULL),(51,'FB/JKT/2020/01/00003','2020-01-03 09:39:42',74,152,30,'IDR',1.00,11500000.00,1150000.00,10.00,1035000.00,6000000.00,5385000.00,5271150.00,113850.00,'GUD/JKT/2020/01/00006',1,'A',4,'2020-01-03 09:40:16',NULL,NULL),(52,'FB/JKT/2020/01/00004','2020-01-03 10:20:22',75,150,30,'IDR',1.00,235000.00,23500.00,10.00,21150.00,140000.00,92650.00,82750.00,9900.00,'',1,'A',4,'2020-01-03 10:20:39',NULL,NULL),(53,'FB/JKT/2020/01/00005','2020-01-03 10:20:40',76,150,10,'IDR',1.00,40000.00,4000.00,10.00,3600.00,15600.00,24000.00,4200.00,19800.00,'',1,'A',4,'2020-01-03 10:20:58',NULL,NULL),(54,'FB/JKT/2020/01/00006','2020-01-03 11:01:25',77,151,30,'IDR',1.00,4550000.00,455000.00,10.00,409500.00,2500000.00,2004500.00,1984700.00,19800.00,'GUD/JKT/2020/01/00009',1,'A',4,'2020-01-03 11:02:13',NULL,NULL),(55,'FB/JKT/2020/01/00007','2020-01-03 13:49:47',78,149,30,'IDR',1.00,5000000.00,500000.00,10.00,450000.00,3000000.00,1950000.00,1900500.00,49500.00,'GUD/JKT/2020/01/00010',1,'A',4,'2020-01-03 13:50:45',NULL,NULL),(56,'FB/JKT/2020/01/00008','2020-01-06 09:49:51',79,150,30,'IDR',1.00,17600000.00,0.00,10.00,1760000.00,10000000.00,9360000.00,9166400.00,193600.00,'GUD/JKT/2020/01/00011',1,'A',4,'2020-01-06 09:50:28',NULL,NULL),(57,'FB/JKT/2020/01/00009','2020-01-06 16:44:49',80,149,30,'IDR',1.00,7000000.00,700000.00,10.00,630000.00,4000000.00,2930000.00,2900300.00,29700.00,'',1,'A',4,'2020-01-06 16:45:14',NULL,NULL),(58,'FB/JKT/2020/01/00010','2020-01-07 11:16:20',81,151,30,'IDR',1.00,3000000.00,0.00,10.00,300000.00,2000000.00,1300000.00,1283500.00,16500.00,'',1,'A',4,'2020-01-07 11:16:39',NULL,NULL),(59,'FB/JKT/2020/01/00011','2020-01-07 14:04:54',82,150,30,'IDR',1.00,8500000.00,0.00,10.00,850000.00,5000000.00,4350000.00,4256500.00,93500.00,'GUD/JKT/2020/01/00021',1,'A',4,'2020-01-07 14:05:34',NULL,NULL);

/*Table structure for table `trlpbpurchasedetails` */

DROP TABLE IF EXISTS `trlpbpurchasedetails`;

CREATE TABLE `trlpbpurchasedetails` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_lpbpurchase_id` int(11) DEFAULT NULL,
  `fin_lpbgudang_id` int(11) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;

/*Data for the table `trlpbpurchasedetails` */

insert  into `trlpbpurchasedetails`(`fin_rec_id`,`fin_lpbpurchase_id`,`fin_lpbgudang_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (61,42,63,'A',4,'2019-12-27 15:07:39',NULL,NULL),(62,43,64,'A',4,'2019-12-27 15:49:29',NULL,NULL),(63,44,65,'A',4,'2019-12-27 16:06:13',NULL,NULL),(64,45,66,'A',4,'2019-12-27 16:29:48',NULL,NULL),(65,46,68,'A',4,'2019-12-27 16:59:36',NULL,NULL),(66,47,70,'A',4,'2019-12-27 18:03:15',NULL,NULL),(67,48,71,'A',4,'2019-12-27 18:03:49',NULL,NULL),(68,49,73,'A',4,'2020-01-02 11:18:37',NULL,NULL),(69,50,75,'A',4,'2020-01-02 14:17:19',NULL,NULL),(70,51,77,'A',4,'2020-01-03 09:40:16',NULL,NULL),(71,52,78,'A',4,'2020-01-03 10:20:39',NULL,NULL),(72,53,79,'A',4,'2020-01-03 10:20:58',NULL,NULL),(73,54,80,'A',4,'2020-01-03 11:02:13',NULL,NULL),(74,55,81,'A',4,'2020-01-03 13:50:45',NULL,NULL),(75,56,82,'A',4,'2020-01-06 09:50:28',NULL,NULL),(76,57,89,'A',4,'2020-01-06 16:45:14',NULL,NULL),(77,58,91,'A',4,'2020-01-07 11:16:39',NULL,NULL),(78,59,93,'A',4,'2020-01-07 14:05:34',NULL,NULL);

/*Table structure for table `trlpbpurchaseitems` */

DROP TABLE IF EXISTS `trlpbpurchaseitems`;

CREATE TABLE `trlpbpurchaseitems` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_lpbpurchase_id` int(11) DEFAULT NULL,
  `fin_item_id` int(11) DEFAULT NULL,
  `fst_custom_item_name` varchar(100) DEFAULT NULL,
  `fst_unit` varchar(100) DEFAULT NULL,
  `fdb_qty` double(12,2) DEFAULT NULL,
  `fdb_qty_return` double(12,2) DEFAULT 0.00,
  `fdc_price` decimal(12,2) DEFAULT NULL,
  `fst_disc_item` varchar(100) DEFAULT NULL COMMENT 'Discount Item bertingkat berupa string, misal 10+5+2',
  `fdc_disc_amount_per_item` decimal(12,2) DEFAULT 0.00,
  `fst_memo_item` text DEFAULT NULL,
  `fin_promo_id` int(11) DEFAULT NULL COMMENT 'Bila terisi merupakan item promo',
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=utf8;

/*Data for the table `trlpbpurchaseitems` */

insert  into `trlpbpurchaseitems`(`fin_rec_id`,`fin_lpbpurchase_id`,`fin_item_id`,`fst_custom_item_name`,`fst_unit`,`fdb_qty`,`fdb_qty_return`,`fdc_price`,`fst_disc_item`,`fdc_disc_amount_per_item`,`fst_memo_item`,`fin_promo_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (106,42,70,'GREEBEL OIL PASTEL PP - 24C - 2','SET',100.00,2.00,15000.00,'10',1500.00,NULL,NULL,'A',4,'2019-12-27 15:07:39',NULL,NULL),(107,42,71,'GREEBEL SHARPENER 103 (COLOR BOX)','PCS',100.00,2.00,18000.00,'10',1800.00,NULL,NULL,'A',4,'2019-12-27 15:07:39',NULL,NULL),(108,42,72,'MASTER BOX GREEBEL GUNTING GB-SC-02','PCS',100.00,2.00,12000.00,'10',1200.00,NULL,NULL,'A',4,'2019-12-27 15:07:39',NULL,NULL),(109,42,73,'GREEBEL 7018 PENCIL 2B (PER PCS)','PCS',100.00,2.00,5000.00,'10',500.00,NULL,NULL,'A',4,'2019-12-27 15:07:39',NULL,NULL),(110,42,80,'PENCIL CASE PAKET UJIAN','PCS',100.00,2.00,20000.00,'10',2000.00,NULL,NULL,'A',4,'2019-12-27 15:07:39',NULL,NULL),(111,42,81,'GREEBEL SHARPENER 102 (JAR)','PCS',100.00,2.00,15000.00,'10',1500.00,NULL,NULL,'A',4,'2019-12-27 15:07:39',NULL,NULL),(112,42,82,'GREEBEL ERASER GBW-120630','PCS',100.00,2.00,12500.00,'10',1250.00,NULL,NULL,'A',4,'2019-12-27 15:07:39',NULL,NULL),(113,42,83,'PENGGARIS GB PELITA MAS','PCS',100.00,2.00,10000.00,'10',1000.00,NULL,NULL,'A',4,'2019-12-27 15:07:39',NULL,NULL),(114,42,84,'RING CLIP BOARD GREEBEL','PCS',150.00,2.00,5000.00,'10',500.00,NULL,NULL,'A',4,'2019-12-27 15:07:39',NULL,NULL),(115,42,85,'NAIL 3,8X8X8MM','PCS',150.00,2.00,5000.00,'10',500.00,NULL,NULL,'A',4,'2019-12-27 15:07:39',NULL,NULL),(116,43,62,'GREEBEL 7018 PENCIL 2B (12 PCS/SET)','SET',100.00,0.00,20000.00,'10',2000.00,NULL,NULL,'A',4,'2019-12-27 15:49:29',NULL,NULL),(117,43,63,'GREEBEL 3736 PENCIL SUPER LEAD','SET',100.00,0.00,10000.00,'10',1000.00,NULL,NULL,'A',4,'2019-12-27 15:49:29',NULL,NULL),(118,43,64,'GREEBEL PENCIL BAG MICA 2520','PCS',100.00,0.00,15000.00,'10',1500.00,NULL,NULL,'A',4,'2019-12-27 15:49:29',NULL,NULL),(119,43,65,'CRAYON TABUNG ZC 012','TAB',100.00,0.00,15000.00,'10',1500.00,NULL,NULL,'A',4,'2019-12-27 15:49:29',NULL,NULL),(120,43,67,'GREEBEL WATER GLUE 35 ML','BOX',10.00,2.00,245000.00,'10',24500.00,NULL,NULL,'A',4,'2019-12-27 15:49:29',NULL,NULL),(121,43,68,'GREEBEL PAKET UJIAN PINTAR','SET',100.00,0.00,30000.00,'10',3000.00,NULL,NULL,'A',4,'2019-12-27 15:49:29',NULL,NULL),(122,44,86,'GREEBEL STATIONERY 4 IN 1','SET',100.00,0.00,50000.00,'10',5000.00,NULL,NULL,'A',4,'2019-12-27 16:06:13',NULL,NULL),(123,44,87,'GREEBEL SHARPENER 102 (5000 PCS/CTN)','CTN',4.00,0.00,625000.00,'10',62500.00,NULL,NULL,'A',4,'2019-12-27 16:06:13',NULL,NULL),(124,44,88,'GREEBEL ERASER GBW-120640','PCS',100.00,0.00,10000.00,'10',1000.00,NULL,NULL,'A',4,'2019-12-27 16:06:13',NULL,NULL),(125,44,90,'KERTAS BARCODE','PCS',300.00,25.00,500.00,'20',100.00,NULL,NULL,'A',4,'2019-12-27 16:06:13',NULL,NULL),(126,44,91,'GREEBEL GLUE STICK 8G - 2 PCS/SET','SET',100.00,0.00,15000.00,'10',1500.00,NULL,NULL,'A',4,'2019-12-27 16:06:13',NULL,NULL),(127,44,92,'GREEBEL GLUE STICK 8G','PCS',100.00,0.00,8000.00,'10',800.00,NULL,NULL,'A',4,'2019-12-27 16:06:13',NULL,NULL),(128,44,94,'GREEBEL PAKET TAB 2017','SET',100.00,0.00,45000.00,'10',4500.00,NULL,NULL,'A',4,'2019-12-27 16:06:13',NULL,NULL),(129,45,95,'GREEBEL OIL PASTEL PP - 12C','SET',100.00,0.00,50000.00,'10',5000.00,NULL,NULL,'A',4,'2019-12-27 16:29:48',NULL,NULL),(130,45,96,'GREEBEL ERASER GBB-141820','PCS',100.00,0.00,10000.00,'10',1000.00,NULL,NULL,'A',4,'2019-12-27 16:29:48',NULL,NULL),(131,45,97,'GREEBEL GLUE STICK 21G','PCS',100.00,0.00,20000.00,'10',2000.00,NULL,NULL,'A',4,'2019-12-27 16:29:48',NULL,NULL),(132,45,98,'GREEBEL 7206 PENCIL BI COLOR','SET',100.00,0.00,12000.00,'10',1200.00,NULL,NULL,'A',4,'2019-12-27 16:29:48',NULL,NULL),(133,45,104,'PENCIL LEAD OM-11 BLACK','GRS',85.00,0.00,14000.00,'10',1400.00,NULL,NULL,'A',4,'2019-12-27 16:29:48',NULL,NULL),(134,45,174,'GREEBEL STATIONERY SET 2-7019 + 120630 + 102','SET',100.00,10.00,65000.00,'10',6500.00,NULL,NULL,'A',4,'2019-12-27 16:29:48',NULL,NULL),(135,45,187,'GREEBEL 7026 HB HEXAGONAL PENCIL (PER PCS)','PCS',100.00,0.00,10000.00,'10',1000.00,NULL,NULL,'A',4,'2019-12-27 16:29:48',NULL,NULL),(136,45,191,'GREEBEL 6712 - 3.7 MM WATER COLOUR PENCIL','SET',100.00,0.00,15000.00,'10',1500.00,NULL,NULL,'A',4,'2019-12-27 16:29:48',NULL,NULL),(137,46,62,'GREEBEL 7018 PENCIL 2B (12 PCS/SET)','SET',100.00,0.00,20000.00,'10',2000.00,NULL,NULL,'A',4,'2019-12-27 16:59:36',NULL,NULL),(138,46,63,'GREEBEL 3736 PENCIL SUPER LEAD','SET',100.00,0.00,10000.00,'10',1000.00,NULL,NULL,'A',4,'2019-12-27 16:59:36',NULL,NULL),(139,46,64,'GREEBEL PENCIL BAG MICA 2520','PCS',100.00,0.00,15000.00,'10',1500.00,NULL,NULL,'A',4,'2019-12-27 16:59:36',NULL,NULL),(140,46,65,'CRAYON TABUNG ZC 012','TAB',100.00,0.00,15000.00,'10',1500.00,NULL,NULL,'A',4,'2019-12-27 16:59:36',NULL,NULL),(141,46,68,'GREEBEL PAKET UJIAN PINTAR','SET',100.00,10.00,30000.00,'10',3000.00,NULL,NULL,'A',4,'2019-12-27 16:59:36',NULL,NULL),(142,46,70,'GREEBEL OIL PASTEL PP - 24C - 2','SET',100.00,0.00,15000.00,'10',1500.00,NULL,NULL,'A',4,'2019-12-27 16:59:36',NULL,NULL),(143,46,71,'GREEBEL SHARPENER 103 (COLOR BOX)','PCS',100.00,0.00,18000.00,'10',1800.00,NULL,NULL,'A',4,'2019-12-27 16:59:36',NULL,NULL),(144,46,72,'MASTER BOX GREEBEL GUNTING GB-SC-02','PCS',100.00,0.00,12000.00,'10',1200.00,NULL,NULL,'A',4,'2019-12-27 16:59:36',NULL,NULL),(145,46,73,'GREEBEL 7018 PENCIL 2B (PER PCS)','PCS',100.00,0.00,5000.00,'10',500.00,NULL,NULL,'A',4,'2019-12-27 16:59:36',NULL,NULL),(146,46,80,'PENCIL CASE PAKET UJIAN','PCS',100.00,0.00,20000.00,'10',2000.00,NULL,NULL,'A',4,'2019-12-27 16:59:36',NULL,NULL),(147,47,81,'GREEBEL SHARPENER 102 (JAR)','PCS',100.00,0.00,15000.00,'10',1500.00,NULL,NULL,'A',4,'2019-12-27 18:03:15',NULL,NULL),(148,47,82,'GREEBEL ERASER GBW-120630','PCS',100.00,2.00,12500.00,'10',1250.00,NULL,NULL,'A',4,'2019-12-27 18:03:15',NULL,NULL),(149,47,83,'PENGGARIS GB PELITA MAS','PCS',100.00,0.00,10000.00,'10',1000.00,NULL,NULL,'A',4,'2019-12-27 18:03:15',NULL,NULL),(150,47,84,'RING CLIP BOARD GREEBEL','PCS',100.00,0.00,5000.00,'10',500.00,NULL,NULL,'A',4,'2019-12-27 18:03:15',NULL,NULL),(151,47,86,'GREEBEL STATIONERY 4 IN 1','SET',80.00,2.00,50000.00,'10',5000.00,NULL,NULL,'A',4,'2019-12-27 18:03:15',NULL,NULL),(152,47,88,'GREEBEL ERASER GBW-120640','PCS',100.00,0.00,10000.00,'10',1000.00,NULL,NULL,'A',4,'2019-12-27 18:03:15',NULL,NULL),(153,47,91,'GREEBEL GLUE STICK 8G - 2 PCS/SET','SET',100.00,0.00,15000.00,'10',1500.00,NULL,NULL,'A',4,'2019-12-27 18:03:15',NULL,NULL),(154,47,94,'GREEBEL PAKET TAB 2017','SET',100.00,0.00,45000.00,'10',4500.00,NULL,NULL,'A',4,'2019-12-27 18:03:15',NULL,NULL),(155,48,62,'GREEBEL 7018 PENCIL 2B (12 PCS/SET)','SET',100.00,0.00,10.00,'5',0.50,NULL,NULL,'A',4,'2019-12-27 18:03:49',NULL,NULL),(156,48,64,'GREEBEL PENCIL BAG MICA 2520','PCS',100.00,0.00,15.00,'5',0.75,NULL,NULL,'A',4,'2019-12-27 18:03:49',NULL,NULL),(157,48,107,'GREEBEL 7018 2 PCS + ERASER GBB-141240 2 PCS','SET',100.00,10.00,10.00,'5',0.50,NULL,NULL,'A',4,'2019-12-27 18:03:49',NULL,NULL),(158,49,186,'CORRECTION PEN W 004','LSN',100.00,2.00,20000.00,'10',2000.00,NULL,NULL,'A',4,'2020-01-02 11:18:37',NULL,NULL),(159,50,101,'COLOURING BOOK NOT FOR SALE','PCS',200.00,2.00,10.00,'0',0.00,NULL,NULL,'A',4,'2020-01-02 14:17:19',NULL,NULL),(160,51,68,'GREEBEL PAKET UJIAN PINTAR','SET',100.00,1.00,30000.00,'10',3000.00,NULL,NULL,'A',4,'2020-01-03 09:40:16',NULL,NULL),(161,51,94,'GREEBEL PAKET TAB 2017','SET',100.00,1.00,45000.00,'10',4500.00,NULL,NULL,'A',4,'2020-01-03 09:40:16',NULL,NULL),(162,51,107,'GREEBEL 7018 2 PCS + ERASER GBB-141240 2 PCS','SET',100.00,1.00,20000.00,'10',2000.00,NULL,NULL,'A',4,'2020-01-03 09:40:16',NULL,NULL),(163,51,186,'CORRECTION PEN W 004','LSN',100.00,1.00,20000.00,'10',2000.00,NULL,NULL,'A',4,'2020-01-03 09:40:16',NULL,NULL),(164,52,70,'GREEBEL OIL PASTEL PP - 24C - 2','SET',2.00,0.00,15000.00,'10',1500.00,NULL,NULL,'A',4,'2020-01-03 10:20:39',NULL,NULL),(165,52,71,'GREEBEL SHARPENER 103 (COLOR BOX)','PCS',2.00,0.00,18000.00,'10',1800.00,NULL,NULL,'A',4,'2020-01-03 10:20:39',NULL,NULL),(166,52,72,'MASTER BOX GREEBEL GUNTING GB-SC-02','PCS',2.00,0.00,12000.00,'10',1200.00,NULL,NULL,'A',4,'2020-01-03 10:20:39',NULL,NULL),(167,52,73,'GREEBEL 7018 PENCIL 2B (PER PCS)','PCS',2.00,0.00,5000.00,'10',500.00,NULL,NULL,'A',4,'2020-01-03 10:20:39',NULL,NULL),(168,52,80,'PENCIL CASE PAKET UJIAN','PCS',2.00,0.00,20000.00,'10',2000.00,NULL,NULL,'A',4,'2020-01-03 10:20:39',NULL,NULL),(169,52,81,'GREEBEL SHARPENER 102 (JAR)','PCS',2.00,0.00,15000.00,'10',1500.00,NULL,NULL,'A',4,'2020-01-03 10:20:39',NULL,NULL),(170,52,82,'GREEBEL ERASER GBW-120630','PCS',2.00,0.00,12500.00,'10',1250.00,NULL,NULL,'A',4,'2020-01-03 10:20:39',NULL,NULL),(171,52,83,'PENGGARIS GB PELITA MAS','PCS',2.00,0.00,10000.00,'10',1000.00,NULL,NULL,'A',4,'2020-01-03 10:20:39',NULL,NULL),(172,52,84,'RING CLIP BOARD GREEBEL','PCS',2.00,1.00,5000.00,'10',500.00,NULL,NULL,'A',4,'2020-01-03 10:20:39',NULL,NULL),(173,52,85,'NAIL 3,8X8X8MM','PCS',2.00,1.00,5000.00,'10',500.00,NULL,NULL,'A',4,'2020-01-03 10:20:39',NULL,NULL),(174,53,186,'CORRECTION PEN W 004','LSN',2.00,1.00,20000.00,'10',2000.00,NULL,NULL,'A',4,'2020-01-03 10:20:58',NULL,NULL),(175,54,64,'GREEBEL PENCIL BAG MICA 2520','PCS',100.00,1.00,20000.00,'10',2000.00,NULL,NULL,'A',4,'2020-01-03 11:02:13',NULL,NULL),(176,54,69,'PAPAN UJIAN A4 BIRU','PCS',100.00,0.00,25000.00,'10',2500.00,NULL,NULL,'A',4,'2020-01-03 11:02:13',NULL,NULL),(177,54,90,'KERTAS BARCODE','PCS',100.00,0.00,500.00,'10',50.00,NULL,NULL,'A',4,'2020-01-03 11:02:13',NULL,NULL),(178,55,177,'GREEBEL BALLPEN TECHNOLINE 0.5 BLACK','PCS',200.00,2.00,25000.00,'10',2500.00,NULL,NULL,'A',4,'2020-01-03 13:50:45',NULL,NULL),(179,56,118,'GREEBEL ARTIST OIL PASTEL 36C','SET',200.00,2.00,88000.00,'0',0.00,NULL,NULL,'A',4,'2020-01-06 09:50:28',NULL,NULL),(180,57,98,'GREEBEL 7206 PENCIL BI COLOR','SET',200.00,1.00,30000.00,'10',3000.00,NULL,NULL,'A',4,'2020-01-06 16:45:14',NULL,NULL),(181,57,175,'GREEBEL 7019 PENCIL 2B (PER PCS)','PCS',200.00,0.00,5000.00,'10',500.00,NULL,NULL,'A',4,'2020-01-06 16:45:14',NULL,NULL),(182,58,191,'GREEBEL 6712 - 3.7 MM WATER COLOUR PENCIL','SET',200.00,1.00,15000.00,'0',0.00,NULL,NULL,'A',4,'2020-01-07 11:16:39',NULL,NULL),(183,59,174,'GREEBEL STATIONERY SET 2-7019 + 120630 + 102','SET',100.00,1.00,65000.00,'0',0.00,NULL,NULL,'A',4,'2020-01-07 14:05:34',NULL,NULL),(184,59,181,'GREEBEL SHARPENER 102 (2PCS/PACK)','PACK',100.00,1.00,20000.00,'0',0.00,NULL,NULL,'A',4,'2020-01-07 14:05:35',NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
