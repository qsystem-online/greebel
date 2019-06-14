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

/*Table structure for table `config` */

DROP TABLE IF EXISTS `config`;

CREATE TABLE `config` (
  `fin_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fst_key` varchar(256) DEFAULT NULL,
  `fst_value` varchar(256) DEFAULT NULL,
  `fst_notes` text,
  `fbl_active` tinyint(1) DEFAULT NULL,
  KEY `fin_id` (`fin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `config` */

insert  into `config`(`fin_id`,`fst_key`,`fst_value`,`fst_notes`,`fbl_active`) values (1,'document_folder','d:\\edoc_storage\\',NULL,1),(2,'document_max_size','102400','maximal doc size (kilobyte)',1),(3,'salesorder_prefix','SO','Prefix penomoran sales order',1),(4,'sales_department_id','2','Sales Department',1);

/*Table structure for table `departments` */

DROP TABLE IF EXISTS `departments`;

CREATE TABLE `departments` (
  `fin_department_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fst_department_name` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL COMMENT 'A->Active;S->Suspend;D->Deleted',
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_insert_id` int(10) NOT NULL,
  `fdt_update_datetime` datetime NOT NULL,
  `fin_update_id` int(10) NOT NULL,
  UNIQUE KEY `fin_id` (`fin_department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `departments` */

insert  into `departments`(`fin_department_id`,`fst_department_name`,`fst_active`,`fdt_insert_datetime`,`fin_insert_id`,`fdt_update_datetime`,`fin_update_id`) values (1,'Finance','A','2019-04-18 08:23:34',1,'0000-00-00 00:00:00',0),(2,'Sales','A','2019-04-18 08:23:51',1,'0000-00-00 00:00:00',0),(3,'HRD','A','2019-04-18 08:25:33',1,'0000-00-00 00:00:00',0);

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

/*Table structure for table `master_groups` */

DROP TABLE IF EXISTS `master_groups`;

CREATE TABLE `master_groups` (
  `fin_group_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fst_group_name` varchar(256) NOT NULL,
  `fin_level` enum('0','1','2','3','4','5') NOT NULL COMMENT '0=Top management, 1=Upper management, 2=Middle management, 3=Supervisors, 4=Line workers, 5=public',
  `fst_active` enum('A','S','D') NOT NULL COMMENT 'A->Active;S->Suspend;D->Deleted',
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_insert_id` int(10) NOT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(10) DEFAULT NULL,
  UNIQUE KEY `fin_id` (`fin_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `master_groups` */

insert  into `master_groups`(`fin_group_id`,`fst_group_name`,`fin_level`,`fst_active`,`fdt_insert_datetime`,`fin_insert_id`,`fdt_update_datetime`,`fin_update_id`) values (1,'Presiden Director','1','A','2019-04-24 12:59:47',1,NULL,NULL),(2,'General Manager','2','A','2019-04-24 13:00:17',1,NULL,NULL),(3,'Supervisor','3','A','2019-04-24 13:00:35',1,NULL,NULL),(4,'Staff','4','A','2019-04-24 13:01:09',1,NULL,NULL);

/*Table structure for table `menus` */

DROP TABLE IF EXISTS `menus`;

CREATE TABLE `menus` (
  `fin_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fin_order` int(11) NOT NULL,
  `fst_menu_name` varchar(256) NOT NULL,
  `fst_caption` varchar(256) NOT NULL,
  `fst_icon` varchar(256) NOT NULL,
  `fst_type` enum('HEADER','TREEVIEW','','') NOT NULL DEFAULT 'HEADER',
  `fst_link` text,
  `fin_parent_id` int(11) NOT NULL,
  `fbl_active` tinyint(1) NOT NULL,
  UNIQUE KEY `fin_id` (`fin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `menus` */

insert  into `menus`(`fin_id`,`fin_order`,`fst_menu_name`,`fst_caption`,`fst_icon`,`fst_type`,`fst_link`,`fin_parent_id`,`fbl_active`) values (1,1,'master','Master','','HEADER',NULL,0,1),(2,2,'dashboard','Dashboard','<i class=\"fa fa-dashboard\"></i>','TREEVIEW','welcome/advanced_element',0,1),(3,3,'department','Department','<i class=\"fa fa-dashboard\"></i>','TREEVIEW','department',0,1),(4,4,'group','Groups','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','welcome/general_element',0,1),(5,5,'user','User','<i class=\"fa fa-edit\"></i>','TREEVIEW','user',0,1),(6,1,'user_user','Users','<i class=\"fa fa-files-o\"></i>','TREEVIEW','user',4,1),(7,2,'user_group','Groups','<i class=\"fa fa-edit\"></i>','TREEVIEW','master_groups',4,1),(8,6,'document','Documents','','HEADER',NULL,0,1),(9,7,'list_document','List Document','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','document/add',0,1);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `msbranches` */

insert  into `msbranches`(`fin_branch_id`,`fst_branch_name`,`fst_address`,`fst_postalcode`,`fin_country_id`,`fin_province_id`,`fin_district_id`,`fin_subdistrict_id`,`fst_branch_phone`,`fst_notes`,`fbl_is_hq`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'Jakarta','Jakarta','14450',1,2,51,34,'08128042742','Oke','','A',0,'2019-06-10 11:35:00',NULL,NULL);

/*Table structure for table `mscountries` */

DROP TABLE IF EXISTS `mscountries`;

CREATE TABLE `mscountries` (
  `CountryId` int(5) NOT NULL AUTO_INCREMENT,
  `CountryName` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`CountryId`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `mscountries` */

insert  into `mscountries`(`CountryId`,`CountryName`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'Indonesia','A',4,'2019-05-03 06:05:02',NULL,NULL),(2,'Singapore','A',4,'2019-05-03 18:10:07',NULL,NULL),(3,'Australia','A',4,'2019-05-06 09:41:16',NULL,NULL),(4,'Thailand','A',4,'2019-05-06 15:16:32',NULL,NULL),(5,'Vietnam','A',4,'2019-05-06 15:26:41',NULL,NULL),(7,'Malaysia','A',4,'2019-05-06 16:07:25',NULL,NULL);

/*Table structure for table `mscurrencies` */

DROP TABLE IF EXISTS `mscurrencies`;

CREATE TABLE `mscurrencies` (
  `CurrCode` varchar(10) NOT NULL,
  `CurrName` varchar(100) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`CurrCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `mscurrencies` */

/*Table structure for table `mscurrenciesratedetails` */

DROP TABLE IF EXISTS `mscurrenciesratedetails`;

CREATE TABLE `mscurrenciesratedetails` (
  `recid` bigint(20) NOT NULL AUTO_INCREMENT,
  `CurrCode` varchar(10) NOT NULL,
  `Date` date NOT NULL,
  `ExchangeRate2IDR` decimal(9,2) NOT NULL DEFAULT '0.00',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`recid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `mscurrenciesratedetails` */

/*Table structure for table `mscustpricinggroups` */

DROP TABLE IF EXISTS `mscustpricinggroups`;

CREATE TABLE `mscustpricinggroups` (
  `CustPricingGroupId` int(10) NOT NULL AUTO_INCREMENT,
  `CustPricingGroupName` varchar(100) NOT NULL,
  `PercentOfPriceList` decimal(5,2) NOT NULL DEFAULT '100.00',
  `DifferenceInAmount` decimal(12,5) NOT NULL DEFAULT '0.00000',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`CustPricingGroupId`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `mscustpricinggroups` */

insert  into `mscustpricinggroups`(`CustPricingGroupId`,`CustPricingGroupName`,`PercentOfPriceList`,`DifferenceInAmount`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'Bayan Group',12.00,0.00000,'A',1,'2019-05-02 17:10:22',1,'2019-05-03 13:43:53'),(2,'Naver Corp.',2.00,0.00000,'A',1,'2019-05-02 17:22:01',4,'2019-05-29 15:44:33'),(3,'Dupta',10.00,0.00000,'A',1,'2019-05-02 17:53:36',4,'2019-05-02 18:10:19'),(4,'Megalitikum',0.00,30.00000,'A',4,'2019-05-02 18:07:24',4,'2019-05-10 09:53:09'),(5,'Testing',2.00,0.00000,'A',4,'2019-05-03 09:01:41',4,'2019-05-14 12:33:02'),(6,'Yukioi',0.00,15.00000,'A',4,'2019-05-03 09:23:48',4,'2019-05-03 09:24:37'),(7,'Test1',2.00,0.00000,'A',4,'2019-05-15 13:03:19',NULL,NULL);

/*Table structure for table `msdistricts` */

DROP TABLE IF EXISTS `msdistricts`;

CREATE TABLE `msdistricts` (
  `DistrictId` int(5) NOT NULL AUTO_INCREMENT,
  `ProvinceId` bigint(20) NOT NULL,
  `DistrictName` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`DistrictId`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

/*Data for the table `msdistricts` */

insert  into `msdistricts`(`DistrictId`,`ProvinceId`,`DistrictName`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,7,'Kabupaten Aceh Barat','A',4,'2019-05-06 10:43:52',NULL,NULL),(2,7,'Kabupaten Aceh Barat Daya','A',4,'2019-05-06 10:44:09',NULL,NULL),(3,7,'Kabupaten Aceh Besar','A',4,'2019-05-06 10:44:33',NULL,NULL),(4,7,'Kabupaten Aceh Jaya','A',4,'2019-05-06 10:44:57',NULL,NULL),(5,7,'Kabupaten Aceh Selatan','A',4,'2019-05-06 10:45:22',NULL,NULL),(6,7,'Kabupaten Aceh Singkil','A',4,'2019-05-06 10:45:45',NULL,NULL),(7,7,'Kabupaten Aceh Tamiang','A',4,'2019-05-06 10:47:35',NULL,NULL),(8,7,'Kabupaten Aceh Tengah','A',4,'2019-05-06 10:48:06',NULL,NULL),(9,7,'Kabupaten Aceh Tenggara','A',4,'2019-05-06 10:48:33',NULL,NULL),(10,7,'Kabupaten Aceh Timur','A',4,'2019-05-06 10:48:55',NULL,NULL),(11,7,'Kabupaten Aceh Utara','A',4,'2019-05-06 10:49:15',NULL,NULL),(12,7,'Kabupaten Bener Meriah','A',4,'2019-05-06 10:49:52',NULL,NULL),(13,7,'Kabupaten Bireuen','A',4,'2019-05-06 10:50:28',NULL,NULL),(14,7,'Kabupaten Gayo Lues','A',4,'2019-05-06 10:50:58',NULL,NULL),(15,7,'Kabupaten Pidie','A',4,'2019-05-06 10:51:20',NULL,NULL),(16,7,'Kabupaten Nagan Raya','A',4,'2019-05-06 10:51:48',NULL,NULL),(17,7,'Kabupaten Pidie Jaya','A',4,'2019-05-06 10:52:08',NULL,NULL),(18,7,'Kabupaten Simeulue','A',4,'2019-05-06 10:52:27',NULL,NULL),(19,7,'Kota Banda Aceh','A',4,'2019-05-06 10:52:55',NULL,NULL),(20,7,'Kota Langsa','A',4,'2019-05-06 10:54:11',NULL,NULL),(21,7,'Kota Lhokseumawe','A',4,'2019-05-06 10:54:33',NULL,NULL),(22,7,'Kota Sabang','A',4,'2019-05-06 10:54:57',NULL,NULL),(23,7,'Kota Subulussalam','A',4,'2019-05-06 10:55:22',NULL,NULL),(24,9,'Kabupaten Asahan','A',4,'2019-05-06 10:57:11',NULL,NULL),(25,9,'Kabupaten Batu Bara','A',4,'2019-05-06 10:57:33',NULL,NULL),(26,9,'Kabupaten Dairi','A',4,'2019-05-06 10:57:54',NULL,NULL),(27,9,'Kabupaten Deli Serdang','A',4,'2019-05-06 10:58:15',NULL,NULL),(28,9,'Kabupaten Humbang Hasundutan','A',4,'2019-05-06 10:59:47',NULL,NULL),(29,9,'Kabupaten Karo','A',4,'2019-05-06 11:00:06',NULL,NULL),(30,9,'Kabupaten Labuhanbatu','A',4,'2019-05-06 11:00:26',NULL,NULL),(31,9,'Kabupaten Labuhanbatu Selatan','A',4,'2019-05-06 11:00:54',NULL,NULL),(32,9,'Kabupaten Labuhanbatu Utara','A',4,'2019-05-06 11:01:06',NULL,NULL),(33,9,'Kabupaten Langkat','A',4,'2019-05-06 11:01:26',NULL,NULL),(34,9,'Kabupaten Mandailing Natal','A',4,'2019-05-06 11:01:47',NULL,NULL),(35,9,'Kabupaten Nias','A',4,'2019-05-06 11:02:07',NULL,NULL),(36,9,'Kabupaten Nias Barat','A',4,'2019-05-06 11:02:22',NULL,NULL),(37,9,'Kabupaten Nias Selatan','A',4,'2019-05-06 11:02:47',NULL,NULL),(38,9,'Kabupaten Nias Utara','A',4,'2019-05-06 11:03:06',NULL,NULL),(39,9,'Kabupaten Padang Lawas','A',4,'2019-05-06 11:16:54',NULL,NULL),(40,9,'Kabupaten Padang Lawas Utara','A',4,'2019-05-06 11:17:28',NULL,NULL),(41,1,'Serang','A',4,'2019-05-07 09:14:52',NULL,NULL),(42,1,'Cilegon','A',4,'2019-05-07 09:15:16',NULL,NULL),(43,1,'Labuan','A',4,'2019-05-07 09:15:40',NULL,NULL),(44,1,'Pandeglang','A',4,'2019-05-07 09:16:17',NULL,NULL),(45,1,'Tangerang','A',4,'2019-05-07 09:16:44',NULL,NULL),(47,1,'Kabupaten Tangerang','A',4,'2019-05-07 09:17:38',NULL,NULL),(48,2,'Jakarta Barat','A',4,'2019-05-07 09:18:20',NULL,NULL),(49,2,'Jakarta Timur','A',4,'2019-05-07 09:18:41',NULL,NULL),(50,2,'Jakarta Selatan','A',4,'2019-05-07 09:19:02',NULL,NULL),(51,2,'Jakarta Utara','A',4,'2019-05-07 09:19:23',NULL,NULL),(52,9,'Alor Setar','A',4,'2019-05-07 16:55:46',NULL,NULL),(53,13,'Johor Bahru','A',4,'2019-05-07 16:56:44',NULL,NULL),(54,10,'Kota Bahru','A',4,'2019-05-07 16:58:22',NULL,NULL);

/*Table structure for table `msitembomdetails` */

DROP TABLE IF EXISTS `msitembomdetails`;

CREATE TABLE `msitembomdetails` (
  `recid` int(10) NOT NULL AUTO_INCREMENT,
  `ItemCode` varchar(100) DEFAULT NULL,
  `ItemCodeBOM` varchar(100) DEFAULT NULL,
  `unit` varchar(100) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`recid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msitembomdetails` */

/*Table structure for table `msitemdiscounts` */

DROP TABLE IF EXISTS `msitemdiscounts`;

CREATE TABLE `msitemdiscounts` (
  `RecId` int(5) NOT NULL AUTO_INCREMENT,
  `ItemDiscount` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RecId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `msitemdiscounts` */

insert  into `msitemdiscounts`(`RecId`,`ItemDiscount`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'0','A',4,'2019-06-14 10:27:41',NULL,NULL),(2,'10','A',4,'2019-06-14 10:27:45',NULL,NULL),(3,'10+2.5','A',4,'2019-06-14 10:27:47',NULL,NULL),(4,'10+5','A',4,'2019-06-14 10:28:03',NULL,NULL),(5,'10+5+2.5','A',4,'2019-06-14 10:29:19',NULL,NULL);

/*Table structure for table `msitems` */

DROP TABLE IF EXISTS `msitems`;

CREATE TABLE `msitems` (
  `ItemId` int(10) NOT NULL AUTO_INCREMENT,
  `ItemCode` varchar(100) DEFAULT NULL,
  `ItemName` varchar(256) DEFAULT NULL,
  `VendorItemName` varchar(256) DEFAULT NULL,
  `ItemMainGroupId` int(11) DEFAULT NULL,
  `ItemGroupId` int(11) DEFAULT NULL,
  `itemSubGroupId` int(11) DEFAULT NULL,
  `ItemTypeId` enum('1','2','3','4','5') DEFAULT '4' COMMENT '1=Raw Material, 2=Semi Finished Material, 3=Supporting Material, 4=Ready Product, 5=Logistic',
  `StandardVendorId` int(11) DEFAULT NULL,
  `OptionalVendorId` int(11) DEFAULT NULL,
  `isBatchNumber` tinyint(1) DEFAULT '0',
  `isSerialNumber` tinyint(1) DEFAULT '0',
  `ScaleForBOM` smallint(6) DEFAULT '1',
  `StorageRackInfo` varchar(256) DEFAULT NULL,
  `Memo` text,
  `MaxItemDiscount` varchar(256) DEFAULT NULL,
  `MinBasicUnitAvgCost` decimal(10,0) DEFAULT '0' COMMENT 'Opsional, jika di isi maka bisa dihasilkan Alert report barang-barang yang perhitungan harga rata2 dibawah Minimal',
  `MaxBasicUnitAvgCost` decimal(10,0) DEFAULT '0' COMMENT 'Opsional, jika di isi maka bisa dihasilkan Alert report barang-barang yang perhitungan harga rata2 diatas Maximal',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ItemId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `msitems` */

insert  into `msitems`(`ItemId`,`ItemCode`,`ItemName`,`VendorItemName`,`ItemMainGroupId`,`ItemGroupId`,`itemSubGroupId`,`ItemTypeId`,`StandardVendorId`,`OptionalVendorId`,`isBatchNumber`,`isSerialNumber`,`ScaleForBOM`,`StorageRackInfo`,`Memo`,`MaxItemDiscount`,`MinBasicUnitAvgCost`,`MaxBasicUnitAvgCost`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'AB1230','Testing','Test Vendor',1,2,3,'4',1,1,1,1,1,NULL,'Delivery','2.5',10,120,'A',0,'2019-06-10 14:04:16',NULL,NULL),(2,'AB2250','Silver Queen','Choco',2,1,2,'4',1,1,2,2,2,NULL,'Pre Order','5',50,200,'A',0,'2019-06-11 16:16:10',NULL,NULL);

/*Table structure for table `msitemspecialpricinggroupdetails` */

DROP TABLE IF EXISTS `msitemspecialpricinggroupdetails`;

CREATE TABLE `msitemspecialpricinggroupdetails` (
  `RecId` int(10) NOT NULL AUTO_INCREMENT,
  `ItemCode` varchar(100) NOT NULL,
  `Unit` varchar(100) NOT NULL,
  `PricingGroupId` int(11) NOT NULL,
  `SellingPrice` decimal(12,2) NOT NULL DEFAULT '0.00',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RecId`,`ItemCode`,`Unit`,`PricingGroupId`,`SellingPrice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msitemspecialpricinggroupdetails` */

/*Table structure for table `msitemunitdetails` */

DROP TABLE IF EXISTS `msitemunitdetails`;

CREATE TABLE `msitemunitdetails` (
  `RecId` int(10) NOT NULL AUTO_INCREMENT,
  `ItemCode` varchar(100) NOT NULL,
  `Unit` varchar(100) NOT NULL,
  `isBasicUnit` tinyint(1) NOT NULL DEFAULT '0',
  `Conv2BasicUnit` decimal(12,2) NOT NULL DEFAULT '1.00',
  `isSelling` tinyint(1) DEFAULT '0',
  `isBuying` tinyint(1) NOT NULL DEFAULT '0',
  `isProductionOutput` tinyint(1) NOT NULL DEFAULT '0',
  `PriceList` decimal(12,2) NOT NULL DEFAULT '0.00',
  `HET` decimal(12,2) DEFAULT '0.00',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RecId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msitemunitdetails` */

/*Table structure for table `msmemberships` */

DROP TABLE IF EXISTS `msmemberships`;

CREATE TABLE `msmemberships` (
  `RecId` int(10) NOT NULL AUTO_INCREMENT,
  `MemberNo` varchar(100) DEFAULT NULL,
  `RelationId` int(5) DEFAULT NULL,
  `MemberGroupId` int(5) DEFAULT NULL,
  `NameOnCard` varchar(256) DEFAULT NULL,
  `ExpiryDate` date DEFAULT NULL,
  `MemberDiscount` decimal(5,2) DEFAULT '0.00',
  `fst_active` enum('A','S','D') NOT NULL DEFAULT 'A',
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RecId`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `msmemberships` */

insert  into `msmemberships`(`RecId`,`MemberNo`,`RelationId`,`MemberGroupId`,`NameOnCard`,`ExpiryDate`,`MemberDiscount`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'12345abcd',1,1,'Testing','2019-05-13',2.00,'A',4,'2019-05-10 12:31:43',4,'2019-05-10 15:05:58'),(2,'123456abcds',15,4,'Naver1','2019-05-17',2.50,'A',4,'2019-05-10 13:32:30',4,'2019-05-21 16:52:02'),(3,'007Bond',16,3,'James Bonding','2019-05-15',5.00,'A',4,'2019-05-10 15:10:53',4,'2019-05-10 15:14:25'),(4,'008Bind',13,2,'Bindeng Banget','2019-05-17',2.00,'A',4,'2019-05-13 11:04:13',4,'2019-05-13 11:06:22'),(5,'ABCD23',16,3,'Tester','2019-05-28',1.50,'A',4,'2019-05-21 09:02:55',NULL,NULL),(6,'E5430a',24,2,'Sri Wahyuni','2019-05-28',2.00,'A',4,'2019-05-21 09:43:16',NULL,NULL),(7,'KomaxG470',16,2,'Bindeng','2019-05-27',5.00,'A',4,'2019-05-21 14:11:56',NULL,NULL),(8,'V1.0.0',13,3,'Testing','2019-05-24',5.00,'A',4,'2019-05-21 15:58:44',NULL,NULL);

/*Table structure for table `msprovinces` */

DROP TABLE IF EXISTS `msprovinces`;

CREATE TABLE `msprovinces` (
  `ProvinceId` int(5) NOT NULL AUTO_INCREMENT,
  `CountryId` int(5) NOT NULL,
  `ProvinceName` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ProvinceId`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `msprovinces` */

insert  into `msprovinces`(`ProvinceId`,`CountryId`,`ProvinceName`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,1,'Banten','A',0,'0000-00-00 00:00:00',NULL,NULL),(2,1,'Daerah Khusus Ibukota Jakarta','A',4,'2019-05-03 18:12:00',4,'2019-05-06 11:20:39'),(3,1,'Jawa Barat','A',4,'2019-05-06 10:17:40',NULL,NULL),(4,1,'Jawa Tengah','A',4,'2019-05-06 10:18:19',NULL,NULL),(5,1,'Jawa Timur','A',4,'2019-05-06 10:18:32',NULL,NULL),(6,1,'Sumatera Utara','A',4,'2019-05-06 10:56:02',NULL,NULL),(7,1,'Daerah Istimewa Aceh','A',4,'2019-05-06 10:56:34',NULL,NULL),(8,1,'Daerah Istimewa Yogyakarta','A',4,'2019-05-06 11:19:42',NULL,NULL),(9,7,'Kedah','A',4,'2019-05-07 16:50:39',NULL,NULL),(10,7,'Kelantan','A',4,'2019-05-07 16:51:01',NULL,NULL),(11,7,'Melaka','A',4,'2019-05-07 16:51:27',NULL,NULL),(12,7,'Negeri Sembilan','A',4,'2019-05-07 16:51:44',NULL,NULL),(13,7,'Johor','A',4,'2019-05-07 16:52:18',NULL,NULL),(14,7,'Pahang','A',4,'2019-05-07 16:52:40',NULL,NULL);

/*Table structure for table `msrelationcontactdetails` */

DROP TABLE IF EXISTS `msrelationcontactdetails`;

CREATE TABLE `msrelationcontactdetails` (
  `RecId` int(10) NOT NULL AUTO_INCREMENT,
  `RelationId` int(5) DEFAULT NULL,
  `ContactName` varchar(100) NOT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `EmailAddress` varchar(100) DEFAULT NULL,
  `Notes` text,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RecId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msrelationcontactdetails` */

/*Table structure for table `msrelationgroups` */

DROP TABLE IF EXISTS `msrelationgroups`;

CREATE TABLE `msrelationgroups` (
  `RelationGroupId` int(5) NOT NULL AUTO_INCREMENT,
  `RelationGroupName` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RelationGroupId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `msrelationgroups` */

insert  into `msrelationgroups`(`RelationGroupId`,`RelationGroupName`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'Customer','A',1,'2019-05-02 17:22:40',1,'2019-05-03 15:15:53'),(2,'Supplier/Vendor1','A',1,'2019-05-02 17:45:28',4,'2019-05-10 09:52:33'),(3,'Ekspedisi1','A',1,'2019-05-02 17:45:40',4,'2019-05-10 10:25:30'),(4,'Total1','A',4,'2019-05-03 09:36:27',4,'2019-05-03 09:45:34'),(5,'Dropshipper','A',4,'2019-05-21 16:22:01',NULL,NULL);

/*Table structure for table `msrelationprintoutnotes` */

DROP TABLE IF EXISTS `msrelationprintoutnotes`;

CREATE TABLE `msrelationprintoutnotes` (
  `NoteId` int(5) NOT NULL AUTO_INCREMENT,
  `Notes` text,
  `PrintOut` varchar(100) DEFAULT NULL COMMENT 'SJ, FAKTUR, PO',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`NoteId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `msrelationprintoutnotes` */

insert  into `msrelationprintoutnotes`(`NoteId`,`Notes`,`PrintOut`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'Test Notes',NULL,'A',4,'0000-00-00 00:00:00',NULL,NULL),(2,'Test Lagi',NULL,'A',4,'0000-00-00 00:00:00',NULL,NULL),(3,'Coba Test Lagi',NULL,'A',4,'0000-00-00 00:00:00',NULL,NULL),(4,'Uji Coba Ke-4',NULL,'A',4,'0000-00-00 00:00:00',NULL,NULL);

/*Table structure for table `msrelations` */

DROP TABLE IF EXISTS `msrelations`;

CREATE TABLE `msrelations` (
  `RelationId` int(5) NOT NULL AUTO_INCREMENT,
  `RelationGroupId` int(5) DEFAULT NULL,
  `RelationType` varchar(100) DEFAULT NULL COMMENT '1=Customer, 2=Supplier/Vendor, 3=Expedisi (boleh pilih lebih dari satu, simpan sebagai string dengan comma), Customer,Supplier/Vendor dan Expedisi define sebagai array di Constanta system supaya suatu saat bisa ditambah',
  `BusinessType` enum('P','C') DEFAULT NULL COMMENT 'P=Personal, C=Corporate',
  `RelationName` varchar(256) DEFAULT NULL,
  `Gender` enum('M','F') NOT NULL COMMENT 'Only BusinessType = Personal',
  `BirthDate` date DEFAULT NULL COMMENT 'Only BusinessType = Personal',
  `BirthPlace` text COMMENT 'Only BusinessType = Personal',
  `Address` text,
  `Phone` varchar(20) DEFAULT NULL,
  `Fax` varchar(20) DEFAULT NULL,
  `PostalCode` varchar(10) DEFAULT NULL,
  `CountryId` int(5) DEFAULT NULL,
  `ProvinceId` int(5) DEFAULT NULL,
  `DistrictId` int(5) DEFAULT NULL,
  `SubDistrictId` int(5) DEFAULT NULL,
  `CustPricingGroupid` int(5) DEFAULT NULL COMMENT 'Hanya perlu diisi jika, RelationType=1',
  `NPWP` varchar(100) DEFAULT NULL,
  `RelationNotes` text COMMENT 'pilihan dari MsRelationNotes, bisa pilih lebih dari satu, id pilihannya disimpan sebagai string dengan comma, notes yg muncul dalam pilihan ini di filter sesuai RelationType, tipe Customer hanya muncul notes printout SJ dan Faktur, tipe Supplier/Vendor hanya muncul notes printout PO',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RelationId`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

/*Data for the table `msrelations` */

insert  into `msrelations`(`RelationId`,`RelationGroupId`,`RelationType`,`BusinessType`,`RelationName`,`Gender`,`BirthDate`,`BirthPlace`,`Address`,`Phone`,`Fax`,`PostalCode`,`CountryId`,`ProvinceId`,`DistrictId`,`SubDistrictId`,`CustPricingGroupid`,`NPWP`,`RelationNotes`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,2,'2','P','Testing','M','1981-06-13','Jakarta','Jakarta','0812 8888 8888','0812 8888 8888','12340',1,2,50,31,NULL,'1234567890','Test Notes','A',4,'2019-05-08 12:24:49',NULL,NULL),(2,1,'2,3','P','Coba Lagi2','F','1981-06-13','Depok','Tangerang','0812 9999 9999','0812 9999 9999','15560',1,1,47,28,NULL,'12345678912','Test Lagi','A',4,'2019-05-08 12:33:30',4,'2019-05-15 13:08:12'),(9,4,'1,2,3','P','Ummay','M','1980-02-12','Tangerang','Tangerang','0819 8888 999','0819 8888 999','15510',1,1,45,1,NULL,'123456789014','Coba Test Lagi\r\nTest Notes\r\nUji Coba Ke-4\r\n','A',4,'2019-05-08 14:46:21',4,'2019-05-23 10:15:21'),(13,2,'1,2','P','Mocca','M','1981-06-13','Jakarta','Tangerang','0819 9999 000','0819 9999 000','15560',1,1,47,28,4,'123456789015','Uji Coba Ke-4','A',4,'2019-05-09 09:31:18',4,'2019-05-10 10:19:47'),(15,2,'1,3','P','Lolita12','F','1980-09-18','Jakarta','Jakarta','0813 9898 009','0813 9898 009','12340',1,2,50,29,NULL,'1234567890151ab','Test Lagi','A',4,'2019-05-09 15:46:36',4,'2019-05-15 14:27:23'),(16,3,'2','C','Minions','','0000-00-00','','Jakarta','0818 8888 0909','0818 8888 090','12430',1,2,50,33,NULL,'12345678901413','','A',4,'2019-05-10 09:15:52',4,'2019-05-14 12:36:05'),(19,3,'2,3','C','Mocca','','0000-00-00','','Tangerang','0817 8888 990','0817 8888 990','15540',1,1,45,1,NULL,'123456789015','','A',4,'2019-05-14 12:22:19',4,'2019-05-14 12:27:08'),(20,1,'2,1','P','Ummay','F','1980-07-10','Jakarta','Kebayoran Baru','0817 0089 922','0817 0089 922','12340',1,2,50,31,NULL,'123456789014ac','Test Notes\r\nTest Notes\r\nTest Notes\r\nTest Notes\r\n','A',4,'2019-05-15 13:25:35',4,'2019-05-21 16:25:00'),(21,1,'3,1','P','Mocca','F','1980-07-17','Jakarta','Tangerang','0818 8888 123','0818 8888 123','15540',1,1,47,27,NULL,'123456789013156','Test Notes\r\nTest Notes\r\nUji Coba Ke-4\r\n','A',4,'2019-05-15 14:35:48',4,'2019-05-21 16:26:51'),(22,1,'1,2,3','C','Lolita12','','1970-01-01','','Jakarta','0818 8888 0909','0818 8888 0909','15540',1,2,50,31,NULL,'123456789013ac','Uji Coba Ke-4\r\n','A',4,'2019-05-15 14:45:09',4,'2019-05-31 16:00:07'),(23,1,'2,1,3','C','Coba Lagi2','','1970-01-01','','','','','12430',1,2,50,30,NULL,'1234567890654','Coba Test Lagi\r\n','A',4,'2019-05-15 14:51:16',4,'2019-05-15 15:02:12'),(24,1,'2,3','C','Pikachu','','1970-01-01','','','0817 0089 882','0817 0089 882','12430',1,2,50,33,NULL,'12345678912987','Uji Coba Ke-4\r\nUji Coba Ke-4\r\nTest Lagi\r\n','A',4,'2019-05-15 15:18:41',4,'2019-05-21 16:28:41'),(25,2,'2,3','C','Mocca1','','1970-01-01','','Tangerang','0813 1212 0098','0813 1212 0098','15540',1,1,45,6,NULL,'1234567890180','Coba Test Lagi\r\nTest Notes\r\nTest Lagi\r\n','A',4,'2019-05-21 09:08:26',4,'2019-05-21 09:09:21'),(26,1,'1','P','Nano Nano','F','1980-07-17','Jakarta','Jakarta','0818 8888 1010','0818 8888 1010','14450',1,2,51,34,NULL,'12345678901001','Test Notes\r\nCoba Test Lagi\r\n','A',4,'2019-06-10 13:52:05',NULL,NULL);

/*Table structure for table `mssubdistricts` */

DROP TABLE IF EXISTS `mssubdistricts`;

CREATE TABLE `mssubdistricts` (
  `SubDistrictId` int(5) NOT NULL AUTO_INCREMENT,
  `DistrictId` int(5) NOT NULL,
  `SubDistrictName` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`SubDistrictId`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

/*Data for the table `mssubdistricts` */

insert  into `mssubdistricts`(`SubDistrictId`,`DistrictId`,`SubDistrictName`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,45,'Cikokol','A',4,'2019-05-07 10:56:24',NULL,NULL),(2,45,'Cipondoh','A',4,'2019-05-07 10:56:48',NULL,NULL),(3,45,'Pinang','A',4,'2019-05-07 10:57:49',NULL,NULL),(4,45,'Tangerang','A',4,'2019-05-07 10:58:24',NULL,NULL),(5,45,'Batuceper','A',4,'2019-05-07 10:58:44',NULL,NULL),(6,45,'Cibodas','A',4,'2019-05-07 10:59:16',NULL,NULL),(7,45,'Ciledug','A',4,'2019-05-07 10:59:32',NULL,NULL),(8,45,'Jatiuwung','A',4,'2019-05-07 10:59:50',NULL,NULL),(9,45,'Karawaci','A',4,'2019-05-07 11:00:25',NULL,NULL),(10,45,'Larangan','A',4,'2019-05-07 11:00:43',NULL,NULL),(11,45,'Periuk','A',4,'2019-05-07 11:01:16',NULL,NULL),(12,45,'Neglasari','A',4,'2019-05-07 11:01:42',NULL,NULL),(13,45,'Karang Tengah','A',4,'2019-05-07 11:02:13',NULL,NULL),(14,45,'Benda','A',4,'2019-05-07 11:02:38',NULL,NULL),(15,47,'Balaraja','A',4,'2019-05-07 11:05:12',NULL,NULL),(16,47,'Cikupa','A',4,'2019-05-07 11:05:42',NULL,NULL),(17,47,'Cisauk','A',4,'2019-05-07 11:06:03',NULL,NULL),(18,47,'Cisoka','A',4,'2019-05-07 11:06:37',NULL,NULL),(19,47,'Curug','A',4,'2019-05-07 11:07:01',NULL,NULL),(20,47,'Gunung Kaler','A',4,'2019-05-07 11:07:27',NULL,NULL),(21,47,'Jambe','A',4,'2019-05-07 11:08:04',NULL,NULL),(22,47,'Jayanti','A',4,'2019-05-07 11:08:20',NULL,NULL),(23,47,'Kelapa Dua','A',4,'2019-05-07 11:08:49',NULL,NULL),(24,47,'Kemiri','A',4,'2019-05-07 11:09:16',NULL,NULL),(25,47,'Kresek','A',4,'2019-05-07 11:09:40',NULL,NULL),(26,47,'Kronjo','A',4,'2019-05-07 11:09:58',NULL,NULL),(27,47,'Rajeg','A',4,'2019-05-07 11:10:25',NULL,NULL),(28,47,'Pasar Kemis','A',4,'2019-05-07 11:10:44',NULL,NULL),(29,50,'Cilandak','A',4,'2019-05-07 11:11:47',NULL,NULL),(30,50,'Jagakarsa','A',4,'2019-05-07 11:12:10',NULL,NULL),(31,50,'Kebayoran Baru','A',4,'2019-05-07 11:12:31',NULL,NULL),(32,50,'Kebayoran Lama','A',4,'2019-05-07 11:12:48',NULL,NULL),(33,50,'Mampang Prapatan','A',4,'2019-05-07 11:13:18',NULL,NULL),(34,51,'Penjaringan','A',0,'2019-06-10 11:33:01',NULL,NULL);

/*Table structure for table `msverification` */

DROP TABLE IF EXISTS `msverification`;

CREATE TABLE `msverification` (
  `RecId` int(10) NOT NULL AUTO_INCREMENT,
  `Controller` varchar(100) DEFAULT NULL,
  `VerificationType` varchar(100) DEFAULT 'default',
  `fin_department_id` int(5) NOT NULL,
  `fin_user_group_id` int(2) NOT NULL,
  `fin_seqno` int(5) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RecId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msverification` */

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `mswarehouse` */

insert  into `mswarehouse`(`fin_warehouse_id`,`fin_branch_id`,`fst_warehouse_name`,`fbl_is_external`,`fbl_is_main`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,1,'Test','','','A',4,'2019-06-10 13:43:49',NULL,NULL),(3,1,'Chocohouse','\0','','A',4,'2019-06-11 17:07:51',NULL,NULL);

/*Table structure for table `trsalesorder` */

DROP TABLE IF EXISTS `trsalesorder`;

CREATE TABLE `trsalesorder` (
  `fin_salesorder_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_salesorder_no` varchar(20) DEFAULT NULL COMMENT 'FORMAT: XXXYYMM/99999, XXX=Prefix Transaksi (taruh di _Config), YY=TAHUN, MM=BULAN, 99999=Urutan Nomor transaksi (bisa per-tahun, bisa per-bulan, tergantung di_config)',
  `fdt_salesorder_date` date DEFAULT NULL,
  `fin_relation_id` int(11) DEFAULT NULL COMMENT 'hanya bisa pilih RelationType = Customer"',
  `fin_warehouse_id` int(5) DEFAULT NULL,
  `fin_sales_id` int(5) DEFAULT NULL COMMENT 'Ambil dari master user, dengan kode departement sesuai _Config ("SLS"), cukup salah satu dari 3 field ini yg harus diisi, sales itu level line worker, sales superviser itu Supervisor, sales manager itu middle management',
  `fin_sales_spv_id` int(5) DEFAULT NULL,
  `fin_sales_mgr_id` int(5) DEFAULT NULL,
  `fst_memo` text,
  `fbl_is_hold` bit(1) DEFAULT b'0' COMMENT 'Sales Order di hold sementara (tidak bisa di proses lebih lanjut)',
  `fbl_is_vat_include` bit(1) DEFAULT b'1' COMMENT 'Apakah harga sudah termasuk pajak, jika iya, maka PPN di hitung dari DPP (karna subtotal sudah trmsk PPn)',
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

/*Data for the table `trsalesorder` */

insert  into `trsalesorder`(`fin_salesorder_id`,`fst_salesorder_no`,`fdt_salesorder_date`,`fin_relation_id`,`fin_warehouse_id`,`fin_sales_id`,`fin_sales_spv_id`,`fin_sales_mgr_id`,`fst_memo`,`fbl_is_hold`,`fbl_is_vat_include`,`fdc_vat_percent`,`fdc_vat_amount`,`fdc_disc_percent`,`fdc_disc_amount`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (18,'SLS1905/00001','2019-05-31',16,0,5,5,5,'delivery','\0','',2.00,0.00,0.00,20.00,'A',4,'2019-05-31 12:21:55',NULL,NULL),(19,'SLS1905/00002','2019-05-31',13,0,11,11,11,'delivery','\0','',0.00,20.00,2.00,0.00,'A',4,'2019-05-31 12:22:23',NULL,NULL),(20,'SLS1905/00003','2019-05-31',NULL,NULL,NULL,NULL,NULL,NULL,'\0','',NULL,NULL,NULL,NULL,'A',NULL,NULL,NULL,NULL),(21,'SLS1905/00004','2019-05-31',15,0,5,5,5,'Coba lagi','\0','',2.00,0.00,0.00,300.00,'A',4,'2019-05-31 12:23:42',NULL,NULL),(22,'SLS1905/00007','2019-05-31',24,0,9,9,9,'delivery aaa','','\0',0.00,20.00,2.00,0.00,'A',4,'2019-05-31 12:49:32',NULL,NULL),(23,'SLS1905/00008','2019-05-31',2,0,9,9,9,'delivery',NULL,'',2.00,0.00,0.00,300.00,'A',4,'2019-05-31 12:55:14',4,'2019-05-31 13:47:48'),(24,'SO1906/00001','2019-06-30',15,0,5,5,5,'delivery abc','\0','',2.00,0.00,0.00,20.00,'A',4,'2019-06-30 13:40:50',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `trsalesorderdetails` */

insert  into `trsalesorderdetails`(`rec_id`,`fin_salesorder_id`,`fin_item_id`,`fdc_qty`,`fdc_price`,`fst_disc_item`,`fdc_disc_amount`,`fst_memo_item`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,18,1,2.00,200.00,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `trverification` */

DROP TABLE IF EXISTS `trverification`;

CREATE TABLE `trverification` (
  `RecId` bigint(20) NOT NULL AUTO_INCREMENT,
  `Controller` varchar(100) DEFAULT NULL,
  `TransactionId` bigint(20) DEFAULT NULL,
  `fin_seqno` int(5) DEFAULT NULL,
  `messages` text,
  `fin_department_id` int(5) DEFAULT NULL,
  `fin_user_group_id` int(2) DEFAULT NULL,
  `VerificationStatus` enum('NV','RV','VF','RJ') DEFAULT NULL COMMENT 'NV = Need Verification, RV = Ready to verification, VF=Verified, RJ= Rejected',
  `Notes` text,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RecId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `trverification` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `fin_user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fst_username` varchar(50) NOT NULL,
  `fst_password` varchar(256) NOT NULL,
  `fst_fullname` varchar(256) NOT NULL,
  `fst_gender` enum('M','F') NOT NULL,
  `fdt_birthdate` date NOT NULL,
  `fst_birthplace` varchar(256) NOT NULL,
  `fst_address` text,
  `fst_phone` varchar(100) DEFAULT NULL,
  `fst_email` varchar(100) DEFAULT NULL,
  `fin_branch_id` int(5) NOT NULL,
  `fin_department_id` bigint(20) NOT NULL,
  `fin_group_id` bigint(20) DEFAULT NULL,
  `fbl_admin` tinyint(1) NOT NULL DEFAULT '0',
  `fst_active` enum('A','S','D') NOT NULL COMMENT 'A->Active;S->Suspend;D->Deleted',
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_insert_id` int(10) NOT NULL,
  `fdt_update_datetime` datetime NOT NULL,
  `fin_update_id` int(10) NOT NULL,
  UNIQUE KEY `fin_id` (`fin_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`fin_user_id`,`fst_username`,`fst_password`,`fst_fullname`,`fst_gender`,`fdt_birthdate`,`fst_birthplace`,`fst_address`,`fst_phone`,`fst_email`,`fin_branch_id`,`fin_department_id`,`fin_group_id`,`fbl_admin`,`fst_active`,`fdt_insert_datetime`,`fin_insert_id`,`fdt_update_datetime`,`fin_update_id`) values (4,'enny06','c50e5b88116a073a72aea201b96bfe8e','Enny Nuraini','F','1979-10-06','Jakarta','Tangerang','08128042742','enny06@yahoo.com',0,0,2,1,'A','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0),(5,'udin123','3af4c9341e31bce1f4262a326285170d','Udin Sedunia','F','1980-06-12','Makasar','Depok','087772721096','udin123@yahoo.com',12,3,1,1,'A','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0),(9,'dimpi80','4aba2f8cbc594d39020a0187f1331670','Dimas Widiastuti','F','1980-09-18','Depok','Depok','081380804521','dimpi80@yahoo.com',2,3,1,0,'A','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0),(11,'anne80','4a094e453e6ee6a8253def63db4d1509','Annie Emma Limahelu','F','1970-01-01','Jakarta','Jatiasih, Bekasi','0813 4562 9825','anne80@yahoo.com',0,1,NULL,1,'A','2019-05-21 10:23:26',4,'2019-05-21 10:25:17',4),(12,'devibong@yahoo.com','06a6077b0cfcb0f4890fb5f2543c43be','Devi Bastian','M','1978-08-26','Pematang Siantar',NULL,NULL,'devibong@yahoo.com',0,0,NULL,0,'A','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
