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

/*Table structure for table `glaccountgroups` */

DROP TABLE IF EXISTS `glaccountgroups`;

CREATE TABLE `glaccountgroups` (
  `GLAccountGroupId` int(10) NOT NULL AUTO_INCREMENT,
  `GLAccountGroupName` varchar(100) DEFAULT NULL,
  `GLAccountMainPrefix` varchar(20) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`GLAccountGroupId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `glaccountgroups` */

/*Table structure for table `glaccounts` */

DROP TABLE IF EXISTS `glaccounts`;

CREATE TABLE `glaccounts` (
  `GLAccountCode` varchar(100) NOT NULL,
  `GLAccountMainGroupId` int(10) NOT NULL,
  `GLAccountName` varchar(256) NOT NULL,
  `GLAccountLevel` enum('HD','DT') NOT NULL COMMENT 'Pilihan HD(Header). DT(Detail), DK(DetailKasBank)',
  `ParentGLAccountCode` varchar(100) DEFAULT NULL COMMENT 'Rekening Induk (hanya perlu diisi jika GLAccountLevel = Detail atau Detail Kasbank',
  `DefaultPost` enum('D','C') DEFAULT NULL,
  `fin_seq_no` int(5) DEFAULT NULL,
  `MinUserLevelAccess` int(10) NOT NULL AUTO_INCREMENT,
  `CurrCode` varchar(10) NOT NULL,
  `isAllowInCashBankModule` tinyint(1) NOT NULL DEFAULT '0',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) NOT NULL,
  `fdt_update_datetime` datetime NOT NULL,
  PRIMARY KEY (`GLAccountCode`),
  KEY `MinUserLevelAccess` (`MinUserLevelAccess`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `glaccounts` */

/*Table structure for table `msbranches` */

DROP TABLE IF EXISTS `msbranches`;

CREATE TABLE `msbranches` (
  `fin_branch_id` int(5) NOT NULL AUTO_INCREMENT,
  `fst_branch_name` varchar(100) DEFAULT NULL,
  `fst_address` text,
  `fst_postalcode` varchar(10) DEFAULT NULL,
  `fin_country_id` int(5) DEFAULT NULL,
  `fin_province_id` int(5) DEFAULT NULL,
  `fin_district_id` int(5) DEFAULT NULL,
  `fin_subdistrict_id` int(5) DEFAULT NULL,
  `fst_branch_phone` varchar(20) DEFAULT NULL,
  `fst_notes` text,
  `fbl_is_hq` bit(1) DEFAULT NULL COMMENT 'Hanya boleh ada 1 HQ di table cabang',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msbranches` */

/*Table structure for table `trsalesorder` */

DROP TABLE IF EXISTS `trsalesorder`;

CREATE TABLE `trsalesorder` (
  `fin_salesorder_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_salesorder_no` varchar(20) DEFAULT NULL COMMENT 'FORMAT: XXXYYMM/99999<br>XXX=Prefix Transaksi (taruh di _Config)<br>YY=TAHUN<br>MM=BULAN<br>99999=Urutan Nomor transaksi (bisa per-tahun, bisa per-bulan, tergantung di_config)',
  `fdt_salesorder_date` date DEFAULT NULL,
  `fin_relation_id` int(11) DEFAULT NULL COMMENT 'ref: > msrelations.RelationId,note:"hanya bisa pilih RelationType = Customer"',
  `fin_warehouse_id` int(5) DEFAULT NULL COMMENT 'ref: > mswarehouse.fin_warehouse_id',
  `fin_sales_id` int(5) DEFAULT NULL COMMENT 'ref: > users.fin_user_id || Ambil dari master user, dengan kode departement sesuai _Config ("SLS"), cukup salah satu dari 3 field ini yg harus diisi, sales itu level line worker, sales superviser itu Supervisor, sales manager itu middle management',
  `fin_sales_spv_id` int(5) DEFAULT NULL COMMENT 'ref: > users.fin_user_id',
  `fin_sales_mgr_id` int(5) DEFAULT NULL COMMENT 'ref: > users.fin_user_id',
  `fst_memo` text,
  `fbl_is_hold` bit(1) DEFAULT b'0' COMMENT 'note:"Sales Order di hold sementara (tidak bisa di proses lebih lanjut)',
  `fbl_is_vat_include` bit(1) DEFAULT b'1' COMMENT 'note:"Apakah harga sudah termasuk pajak, jika iya, maka PPN di hitung dari DPP (karna subtotal sudah trmsk PPn)',
  `fdc_vat_percent` decimal(5,2) DEFAULT NULL,
  `fdc_vat_amount` decimal(5,2) DEFAULT NULL,
  `fdc_disc_percent` decimal(5,2) DEFAULT NULL,
  `fdc_disc_amount` decimal(5,2) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_salesorder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `trsalesorder` */

/*Table structure for table `trsalesorderdetails` */

DROP TABLE IF EXISTS `trsalesorderdetails`;

CREATE TABLE `trsalesorderdetails` (
  `rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_salesorder_id` int(11) DEFAULT NULL COMMENT 'ref: > trsalesorder.fin_salesorder_id',
  `fin_item_id` int(11) DEFAULT NULL COMMENT 'ref: > msitems.ItemId',
  `fdc_qty` decimal(10,2) DEFAULT NULL,
  `fdc_price` decimal(10,2) DEFAULT NULL,
  `fst_disc_item` varchar(100) DEFAULT NULL COMMENT 'Discount Item bertingkat berupa string, misal 10+5+2',
  `fdc_disc_amount` decimal(12,2) DEFAULT NULL,
  `fst_memo_item` text,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`rec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `trsalesorderdetails` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
