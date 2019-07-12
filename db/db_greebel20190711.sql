/*
SQLyog Ultimate v10.42 
MySQL - 5.5.5-10.2.25-MariaDB : Database - u5538790_greebel
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `config` */

DROP TABLE IF EXISTS `config`;

CREATE TABLE `config` (
  `fin_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fst_key` varchar(256) DEFAULT NULL,
  `fst_value` varchar(256) DEFAULT NULL,
  `fst_notes` text DEFAULT NULL,
  `fbl_active` tinyint(1) DEFAULT NULL,
  KEY `fin_id` (`fin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `config` */

insert  into `config`(`fin_id`,`fst_key`,`fst_value`,`fst_notes`,`fbl_active`) values (1,'document_folder','d:\\edoc_storage\\',NULL,1),(2,'document_max_size','102400','maximal doc size (kilobyte)',1),(3,'salesorder_prefix','SO','Prefix penomoran sales order',1),(4,'sales_department_id','2','Sales Department',1),(5,'main_glaccount_separator','','Separator antara maingroup glaccount',1),(6,'parent_glaccount_separator','.','Separator parent group glaccount',1),(7,'percent_ppn','10','PPn Percentage',1),(9,'photo_items_location','/uploads/items/','File Location untuk image Item',1);

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

/*Table structure for table `glaccountgroups` */

DROP TABLE IF EXISTS `glaccountgroups`;

CREATE TABLE `glaccountgroups` (
  `GLAccountGroupId` int(10) NOT NULL AUTO_INCREMENT,
  `GLAccountMainGroupId` int(10) DEFAULT NULL,
  `GLAccountGroupName` varchar(100) DEFAULT NULL,
  `DefaultPost` enum('D','C') DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`GLAccountGroupId`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `glaccountgroups` */

insert  into `glaccountgroups`(`GLAccountGroupId`,`GLAccountMainGroupId`,`GLAccountGroupName`,`DefaultPost`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,NULL,'Assets',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL),(2,NULL,'Liabilities',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL),(3,NULL,'Equity',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL),(4,NULL,'Income',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL),(5,NULL,'Cost Of Sales',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL),(6,NULL,'Expenses',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL),(7,NULL,'Other Income',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL),(8,NULL,'Other Expense',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `glaccountmaingroups` */

insert  into `glaccountmaingroups`(`GLAccountMainGroupId`,`GLAccountMainGroupName`,`GLAccountMainPrefix`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'Assets','1','A',1,'0000-00-00 00:00:00',NULL,NULL),(2,'Liabilities','2','A',1,'0000-00-00 00:00:00',NULL,NULL),(3,'Equity','3','A',1,'0000-00-00 00:00:00',NULL,NULL),(4,'Income','4','A',1,'0000-00-00 00:00:00',NULL,NULL),(5,'Cost Of Sales','5','A',1,'0000-00-00 00:00:00',NULL,NULL),(6,'Expenses','6','A',1,'0000-00-00 00:00:00',NULL,NULL),(7,'Other Income','7','A',1,'0000-00-00 00:00:00',NULL,NULL),(8,'Other Expense','8','A',1,'0000-00-00 00:00:00',NULL,NULL);

/*Table structure for table `glaccounts` */

DROP TABLE IF EXISTS `glaccounts`;

CREATE TABLE `glaccounts` (
  `GLAccountCode` varchar(100) NOT NULL,
  `GLAccountMainGroupId` int(10) NOT NULL,
  `GLAccountName` varchar(256) NOT NULL,
  `GLAccountLevel` enum('HD','DT','DK') NOT NULL COMMENT 'Pilihan HD(Header). DT(Detail), DK(DetailKasBank)',
  `ParentGLAccountCode` varchar(100) DEFAULT NULL COMMENT 'Rekening Induk (hanya perlu diisi jika GLAccountLevel = Detail atau Detail Kasbank',
  `DefaultPost` enum('D','C') DEFAULT NULL,
  `fin_seq_no` int(5) DEFAULT NULL,
  `MinUserLevelAccess` int(10) NOT NULL AUTO_INCREMENT,
  `CurrCode` varchar(10) NOT NULL,
  `isAllowInCashBankModule` tinyint(1) NOT NULL DEFAULT 0,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) NOT NULL,
  `fdt_update_datetime` datetime NOT NULL,
  PRIMARY KEY (`GLAccountCode`),
  KEY `MinUserLevelAccess` (`MinUserLevelAccess`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `glaccounts` */

insert  into `glaccounts`(`GLAccountCode`,`GLAccountMainGroupId`,`GLAccountName`,`GLAccountLevel`,`ParentGLAccountCode`,`DefaultPost`,`fin_seq_no`,`MinUserLevelAccess`,`CurrCode`,`isAllowInCashBankModule`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values ('111',1,'Aktiva Lancar','HD',NULL,'D',NULL,5,'IDR',0,'A',12,'2019-06-26 11:39:09',0,'0000-00-00 00:00:00'),('111.001',1,'Kas','DT','111','D',0,5,'IDR',0,'A',12,'2019-06-26 13:43:05',0,'0000-00-00 00:00:00'),('111.0122',1,'BANK','DK','111','C',1,4,'IDR',1,'D',4,'2019-07-10 17:01:37',0,'0000-00-00 00:00:00'),('112',1,'Aktiva Tetap','HD',NULL,'D',0,5,'IDR',0,'A',12,'2019-06-26 14:18:44',0,'0000-00-00 00:00:00');

/*Table structure for table `glledger` */

DROP TABLE IF EXISTS `glledger`;

CREATE TABLE `glledger` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_branch_id` int(11) DEFAULT NULL,
  `fst_account_code` varchar(100) DEFAULT NULL,
  `fdt_trx_datetime` datetime DEFAULT NULL,
  `fst_trx_sourcecode` varchar(5) DEFAULT NULL,
  `fin_trx_id` int(11) DEFAULT NULL,
  `fst_reference` text DEFAULT NULL,
  `fdc_debit` decimal(12,2) DEFAULT NULL,
  `fdc_credit` decimal(12,2) DEFAULT NULL,
  `fst_orgi_curr_code` varchar(10) DEFAULT NULL,
  `fdc_orgi_rate` decimal(12,2) DEFAULT NULL,
  `fst_no_ref_bank` varchar(20) DEFAULT NULL,
  `fst_profit_cost_center_code` varchar(3) DEFAULT NULL,
  `fin_relation_id` int(11) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_input_id` int(11) DEFAULT NULL,
  `fdt_input_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `glledger` */

/*Table structure for table `menus` */

DROP TABLE IF EXISTS `menus`;

CREATE TABLE `menus` (
  `fin_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fin_order` int(11) NOT NULL,
  `fst_order` varchar(100) DEFAULT NULL,
  `fst_menu_name` varchar(256) NOT NULL,
  `fst_caption` varchar(256) NOT NULL,
  `fst_icon` varchar(256) NOT NULL,
  `fst_type` enum('HEADER','TREEVIEW','','') NOT NULL DEFAULT 'HEADER',
  `fst_link` text DEFAULT NULL,
  `fin_parent_id` int(11) NOT NULL,
  `fbl_active` tinyint(1) NOT NULL,
  UNIQUE KEY `fin_id` (`fin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;

/*Data for the table `menus` */

insert  into `menus`(`fin_id`,`fin_order`,`fst_order`,`fst_menu_name`,`fst_caption`,`fst_icon`,`fst_type`,`fst_link`,`fin_parent_id`,`fbl_active`) values (1,1,'10','dashboard','Dashboard','<i class=\"fa fa-dashboard\"></i>','TREEVIEW','welcome/advanced_element',0,1),(2,2,'20','master','Master Data','','HEADER',NULL,0,1),(23,0,'20.10','master_accounting','Master Accounting','','TREEVIEW',NULL,0,1),(24,0,'20.10.10','gl_account','GL Account','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','gl/GLAccounts',23,1),(25,0,'20.20','master_operasional','Master Operasional','','TREEVIEW',NULL,0,1),(26,0,'20.20.10','master_item','Barang Dagangan','','TREEVIEW',NULL,25,1),(27,0,'20.20.10.10','item_group','Items Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/msgroupitems\r\n',26,1),(28,0,'20.20.10.20','item_main_group','Main Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/msmaingroupitems',26,1),(29,0,'20.20.10.30','item_sub_group','Sub Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/mssubgroupitems',26,1),(30,0,'20.20.10.40','items','Items','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/msitems',26,1),(31,0,'20.20.20','master_currency','Kurs / Mata Uang','','TREEVIEW','master/currency',25,1),(32,0,'20.20.30','relation','Relations','','TREEVIEW',NULL,25,1),(33,0,'20.20.30.10','relation_group','Groups','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/msrelationgroups',32,1),(34,0,'20.20.30.20','relation_customer_vendor','Customer / Vendor','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/msrelations',32,1),(35,0,'20.20.30.30','membership','Membership','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/msmemberships',32,1),(36,0,'20.20.40','warehouse','Gudang','','TREEVIEW','master/mswarehouse',25,1),(37,0,'20.20.50','pricing','Master Prices','','TREEVIEW',NULL,25,1),(38,0,'20.20.50.10','pricing_group','Pricing Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/mscustpricinggroups',37,1),(39,0,'20.20.50.20','prices','Prices','<i class=\"fa fa-circle-o\"></i>','TREEVIEW',NULL,37,1),(40,0,'20.20.50.30','discount','Discount','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/discounts',37,1),(41,0,'20.30','master_system','Master System','','TREEVIEW',NULL,0,1),(42,0,'20.20.60','promo','Master Promo','','TREEVIEW','master/promotion',25,1),(43,0,'20.30.10','branch','Cabang','','TREEVIEW',NULL,41,1),(44,0,'20.30.20','department','Departemen','','TREEVIEW',NULL,41,1),(45,0,'20.30.30','user_group','User Group','','TREEVIEW',NULL,41,1),(46,0,'20.30.40','user','User','','TREEVIEW','user',41,1),(47,0,'30','transaction','Transaksi','','HEADER',NULL,0,1),(48,0,'30.10','purchase','Pembelian','','TREEVIEW',NULL,0,1),(49,0,'30.20','sales','Penjualan','','TREEVIEW',NULL,0,1),(51,0,'30.20.10','sales_order','Sales Order','','TREEVIEW','tr/sales_order',49,1),(52,0,'30.20.20','delivery_order','Delivery Order','','TREEVIEW','tr/delivery_order',49,1),(53,0,'20.20.70','sales_area','Sales Area','','TREEVIEW',NULL,25,1),(54,0,'20.20.70.10','sales_area_national','National','','TREEVIEW','master/sales_area/national',53,1),(55,0,'20.20.70.20','sales_area_regional','Regional','','TREEVIEW','master/sales_area/regional',53,1),(56,0,'20.20.70.30','sales_area_area','Area','','TREEVIEW','master/sales_area/area',53,1),(57,0,'20.20.80','verification','Verifikasi','','TREEVIEW',NULL,25,1),(58,0,'20.20.30.25','member_group','Member Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/membergroup',32,1),(59,0,'30.20.11','unhold_so','Unhold Sales Order','','TREEVIEW','tr/sales_order/unhold',49,1);

/*Table structure for table `msbranches` */

DROP TABLE IF EXISTS `msbranches`;

CREATE TABLE `msbranches` (
  `fin_branch_id` int(5) NOT NULL AUTO_INCREMENT,
  `fst_branch_name` varchar(100) DEFAULT NULL,
  `fst_address` text DEFAULT NULL,
  `CountryId` int(5) DEFAULT NULL,
  `AreaCode` varchar(13) DEFAULT NULL,
  `fst_postalcode` varchar(10) DEFAULT NULL,
  `fst_branch_phone` varchar(20) DEFAULT NULL,
  `fst_notes` text DEFAULT NULL,
  `fbl_is_hq` bit(1) DEFAULT NULL COMMENT 'Hanya boleh ada 1 HQ di table cabang',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `msbranches` */

insert  into `msbranches`(`fin_branch_id`,`fst_branch_name`,`fst_address`,`CountryId`,`AreaCode`,`fst_postalcode`,`fst_branch_phone`,`fst_notes`,`fbl_is_hq`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'Jakarta','Jakarta',1,'31.73.01','14450','08128042742','Oke','','A',0,'2019-06-10 11:35:00',12,'2019-07-11 08:47:22'),(2,'SURABAYA','',1,'35.78.05','','','CABANG 1','\0','A',12,'2019-07-11 08:48:08',12,'2019-07-11 08:53:38');

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

insert  into `mscurrencies`(`CurrCode`,`CurrName`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values ('IDR','Rupiah','A',1,'0000-00-00 00:00:00',NULL,NULL);

/*Table structure for table `mscurrenciesratedetails` */

DROP TABLE IF EXISTS `mscurrenciesratedetails`;

CREATE TABLE `mscurrenciesratedetails` (
  `recid` bigint(20) NOT NULL AUTO_INCREMENT,
  `CurrCode` varchar(10) NOT NULL,
  `Date` date NOT NULL,
  `ExchangeRate2IDR` decimal(9,2) NOT NULL DEFAULT 0.00,
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
  `PercentOfPriceList` decimal(5,2) NOT NULL DEFAULT 100.00,
  `DifferenceInAmount` decimal(12,5) NOT NULL DEFAULT 0.00000,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`CustPricingGroupId`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `mscustpricinggroups` */

insert  into `mscustpricinggroups`(`CustPricingGroupId`,`CustPricingGroupName`,`PercentOfPriceList`,`DifferenceInAmount`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'Bayan Group',90.00,0.00000,'A',1,'2019-05-02 17:10:22',1,'2019-05-03 13:43:53'),(2,'Naver Corp.',2.00,0.00000,'A',1,'2019-05-02 17:22:01',4,'2019-05-29 15:44:33'),(3,'Dupta',10.00,0.00000,'A',1,'2019-05-02 17:53:36',4,'2019-05-02 18:10:19'),(4,'Megalitikum',0.00,30.00000,'A',4,'2019-05-02 18:07:24',4,'2019-05-10 09:53:09'),(5,'Testing',2.00,0.00000,'A',4,'2019-05-03 09:01:41',4,'2019-05-14 12:33:02'),(6,'Yukioi',0.00,15.00000,'A',4,'2019-05-03 09:23:48',4,'2019-05-03 09:24:37'),(7,'Test1',2.00,0.00000,'A',4,'2019-05-15 13:03:19',NULL,NULL);

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

/*Table structure for table `msitembomdetails` */

DROP TABLE IF EXISTS `msitembomdetails`;

CREATE TABLE `msitembomdetails` (
  `recid` int(10) NOT NULL AUTO_INCREMENT,
  `ItemId` int(10) DEFAULT NULL,
  `ItemIdBOM` int(10) DEFAULT NULL,
  `unit` varchar(100) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`recid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `msitembomdetails` */

insert  into `msitembomdetails`(`recid`,`ItemId`,`ItemIdBOM`,`unit`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,1,2,'PACK','A',1,'2019-07-10 19:28:39',NULL,NULL);

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
  `fst_name_on_pos` varchar(50) DEFAULT NULL COMMENT 'Nama Item yang akan di gunakan di System POS',
  `VendorItemName` varchar(256) DEFAULT NULL,
  `ItemMainGroupId` int(11) DEFAULT NULL,
  `ItemGroupId` int(11) DEFAULT NULL,
  `ItemSubGroupId` int(11) DEFAULT NULL,
  `ItemTypeId` enum('1','2','3','4','5') DEFAULT '4' COMMENT '1=Raw Material, 2=Semi Finished Material, 3=Supporting Material, 4=Ready Product, 5=Logistic',
  `StandardVendorId` int(11) DEFAULT NULL,
  `OptionalVendorId` int(11) DEFAULT NULL,
  `isBatchNumber` tinyint(1) DEFAULT 0,
  `isSerialNumber` tinyint(1) DEFAULT 0,
  `ScaleForBOM` smallint(6) DEFAULT 1,
  `StorageRackInfo` varchar(256) DEFAULT NULL,
  `Memo` text DEFAULT NULL,
  `MaxItemDiscount` varchar(256) DEFAULT NULL,
  `MinBasicUnitAvgCost` decimal(10,2) DEFAULT 0.00 COMMENT 'Opsional, jika di isi maka bisa dihasilkan Alert report barang-barang yang perhitungan harga rata2 dibawah Minimal',
  `MaxBasicUnitAvgCost` decimal(10,2) DEFAULT 0.00 COMMENT 'Opsional, jika di isi maka bisa dihasilkan Alert report barang-barang yang perhitungan harga rata2 diatas Maximal',
  `fst_sni_no` varchar(50) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ItemId`),
  UNIQUE KEY `idx_itemcode` (`ItemCode`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `msitems` */

insert  into `msitems`(`ItemId`,`ItemCode`,`ItemName`,`fst_name_on_pos`,`VendorItemName`,`ItemMainGroupId`,`ItemGroupId`,`ItemSubGroupId`,`ItemTypeId`,`StandardVendorId`,`OptionalVendorId`,`isBatchNumber`,`isSerialNumber`,`ScaleForBOM`,`StorageRackInfo`,`Memo`,`MaxItemDiscount`,`MinBasicUnitAvgCost`,`MaxBasicUnitAvgCost`,`fst_sni_no`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'AB1230','Testing','','Test Vendor',1,2,3,'4',NULL,NULL,1,1,1,'',NULL,'2.5',10000.00,12000.00,'','A',0,'2019-06-10 14:04:16',1,'2019-07-10 19:28:38'),(2,'AB2250','Silver Queen',NULL,'Choco',2,1,2,'4',1,1,2,2,2,NULL,'Pre Order','5',50.00,200.00,NULL,'A',0,'2019-06-11 16:16:10',NULL,NULL),(3,'PR001','Promo Item',NULL,'Promo Item',1,1,2,'4',1,1,1,1,1,NULL,NULL,'5',1.00,1.00,NULL,'A',1,'2019-06-25 22:47:56',NULL,NULL);

/*Table structure for table `msitemspecialpricinggroupdetails` */

DROP TABLE IF EXISTS `msitemspecialpricinggroupdetails`;

CREATE TABLE `msitemspecialpricinggroupdetails` (
  `RecId` int(10) NOT NULL AUTO_INCREMENT,
  `ItemId` int(10) NOT NULL,
  `Unit` varchar(100) NOT NULL,
  `PricingGroupId` int(11) NOT NULL,
  `SellingPrice` decimal(12,2) NOT NULL DEFAULT 0.00,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RecId`,`ItemId`,`Unit`,`PricingGroupId`,`SellingPrice`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `msitemspecialpricinggroupdetails` */

insert  into `msitemspecialpricinggroupdetails`(`RecId`,`ItemId`,`Unit`,`PricingGroupId`,`SellingPrice`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (4,1,'pack',1,11000.00,'A',1,'2019-07-10 19:28:39',NULL,NULL);

/*Table structure for table `msitemunitdetails` */

DROP TABLE IF EXISTS `msitemunitdetails`;

CREATE TABLE `msitemunitdetails` (
  `RecId` int(10) NOT NULL AUTO_INCREMENT,
  `ItemId` int(10) NOT NULL,
  `Unit` varchar(100) NOT NULL,
  `isBasicUnit` tinyint(1) NOT NULL DEFAULT 0,
  `Conv2BasicUnit` decimal(12,2) NOT NULL DEFAULT 1.00,
  `isSelling` tinyint(1) DEFAULT 0,
  `isBuying` tinyint(1) NOT NULL DEFAULT 0,
  `isRetail` tinyint(1) DEFAULT 0 COMMENT 'Unit di gunakan di POS, Cuma boleh 1',
  `isProductionOutput` tinyint(1) NOT NULL DEFAULT 0,
  `PriceList` decimal(12,2) NOT NULL DEFAULT 0.00,
  `HET` decimal(12,2) DEFAULT 0.00,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RecId`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `msitemunitdetails` */

insert  into `msitemunitdetails`(`RecId`,`ItemId`,`Unit`,`isBasicUnit`,`Conv2BasicUnit`,`isSelling`,`isBuying`,`isRetail`,`isProductionOutput`,`PriceList`,`HET`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,2,'PACK',1,1.00,1,1,0,0,7000.00,0.00,'A',1,'2019-06-24 13:56:05',NULL,NULL),(2,2,'BOX',0,40.00,1,0,0,0,280000.00,0.00,'A',1,'2019-06-24 13:56:48',NULL,NULL),(9,1,'PACK',1,1.00,1,0,0,0,10000.00,0.00,'A',1,'2019-07-10 19:28:38',NULL,NULL),(10,1,'BOX',0,20.00,1,0,0,0,200000.00,0.00,'A',1,'2019-07-10 19:28:39',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `msmembergroups` */

insert  into `msmembergroups`(`fin_member_group_id`,`fst_member_group_name`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'SILVER','A',1,'2019-07-10 17:35:42',NULL,NULL),(2,'GOLD','A',4,'2019-07-11 15:04:28',4,'2019-07-11 15:10:39'),(3,'PLATINUM','A',4,'2019-07-11 15:07:59',4,'2019-07-11 15:10:24'),(4,'DIAMOND','A',4,'2019-07-11 16:23:51',NULL,NULL);

/*Table structure for table `msmemberships` */

DROP TABLE IF EXISTS `msmemberships`;

CREATE TABLE `msmemberships` (
  `RecId` int(10) NOT NULL AUTO_INCREMENT,
  `MemberNo` varchar(100) DEFAULT NULL,
  `RelationId` int(5) DEFAULT NULL,
  `MemberGroupId` int(5) DEFAULT NULL,
  `NameOnCard` varchar(256) DEFAULT NULL,
  `ExpiryDate` date DEFAULT NULL,
  `MemberDiscount` decimal(5,2) DEFAULT 0.00,
  `fst_active` enum('A','S','D') NOT NULL DEFAULT 'A',
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RecId`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `msmemberships` */

insert  into `msmemberships`(`RecId`,`MemberNo`,`RelationId`,`MemberGroupId`,`NameOnCard`,`ExpiryDate`,`MemberDiscount`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'12345abcd',1,1,'Testing','2019-05-13',2.00,'A',4,'2019-05-10 12:31:43',4,'2019-05-10 15:05:58'),(2,'123456abcds',15,2,'Naver1','2019-05-17',2.50,'A',4,'2019-05-10 13:32:30',4,'2019-05-21 16:52:02'),(3,'007Bond',16,3,'James Bonding','2019-05-15',5.00,'A',4,'2019-05-10 15:10:53',4,'2019-05-10 15:14:25'),(4,'008Bind',13,2,'Bindeng Banget','2019-05-17',2.00,'A',4,'2019-05-13 11:04:13',4,'2019-05-13 11:06:22'),(5,'ABCD23',16,1,'Tester','2019-05-28',1.50,'A',4,'2019-05-21 09:02:55',4,'2019-07-11 16:43:39'),(6,'E5430a',24,2,'Sri Wahyuni','2019-05-28',2.00,'A',4,'2019-05-21 09:43:16',4,'2019-07-11 16:22:25'),(7,'KomaxG470',16,3,'Bindeng','2019-05-27',5.00,'A',4,'2019-05-21 14:11:56',4,'2019-07-11 16:47:38'),(8,'V1.0.0',9,3,'Testing','2020-05-24',5.00,'A',4,'2019-05-21 15:58:44',4,'2019-07-11 15:32:09'),(9,'LAX-MRX302',16,3,'Cihuy Uhuy','2019-07-31',5.00,'A',4,'2019-07-11 16:45:45',4,'2019-07-11 18:21:02');

/*Table structure for table `mspromo` */

DROP TABLE IF EXISTS `mspromo`;

CREATE TABLE `mspromo` (
  `fin_promo_id` int(10) NOT NULL AUTO_INCREMENT,
  `fst_promo_type` enum('POS','OFFICE','ALL') DEFAULT NULL,
  `fst_promo_name` varchar(100) DEFAULT NULL,
  `fdt_start` date DEFAULT NULL,
  `fdt_end` date DEFAULT NULL,
  `fbl_disc_per_item` tinyint(1) DEFAULT 0,
  `fin_promo_item_id` int(10) DEFAULT NULL,
  `fin_promo_qty` float(12,2) DEFAULT NULL COMMENT 'bila ini di isi fdc_min_total_purchase harus 0',
  `fin_promo_unit` varchar(100) DEFAULT NULL COMMENT 'bila ini di isi fdc_min_total_purchase harus 0',
  `fin_cashback` decimal(12,2) DEFAULT 0.00,
  `fst_other_prize` varchar(100) DEFAULT NULL,
  `fdc_other_prize_in_value` decimal(12,2) DEFAULT 0.00,
  `fbl_promo_gabungan` tinyint(1) DEFAULT NULL,
  `fbl_qty_gabungan` tinyint(1) DEFAULT NULL,
  `fin_qty_gabungan` float(12,2) DEFAULT NULL,
  `fst_satuan_gabungan` varchar(100) DEFAULT NULL,
  `fdc_min_total_purchase` decimal(12,2) DEFAULT 0.00 COMMENT 'bila ini di isi fin_promo_qty harus 0',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_promo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `mspromo` */

insert  into `mspromo`(`fin_promo_id`,`fst_promo_type`,`fst_promo_name`,`fdt_start`,`fdt_end`,`fbl_disc_per_item`,`fin_promo_item_id`,`fin_promo_qty`,`fin_promo_unit`,`fin_cashback`,`fst_other_prize`,`fdc_other_prize_in_value`,`fbl_promo_gabungan`,`fbl_qty_gabungan`,`fin_qty_gabungan`,`fst_satuan_gabungan`,`fdc_min_total_purchase`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'OFFICE','Testing Promo','2019-06-01','2019-07-31',0,3,3.12,'pack',0.00,'',0.00,1,0,1.00,'box',0.00,'A',1,'2019-06-25 22:39:16',1,'2019-07-10 19:20:18'),(2,'OFFICE','Testing Devi','2019-06-01','2019-07-31',0,3,3.12,'PACK',50000.00,'Payung Cantik',100000.00,1,0,40.00,'PACK',0.00,'A',1,'0000-00-00 00:00:00',NULL,NULL),(3,'OFFICE','PROMO JULI 2019','2019-07-01','2019-07-31',0,2,5.00,'PACK',0.00,'',0.00,NULL,0,100.00,'PACK',0.00,'A',1,'2019-07-10 19:32:10',NULL,NULL);

/*Table structure for table `mspromodiscperitems` */

DROP TABLE IF EXISTS `mspromodiscperitems`;

CREATE TABLE `mspromodiscperitems` (
  `fin_id` int(10) NOT NULL DEFAULT 0,
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
  `fdt_update_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mspromodiscperitems` */

/*Table structure for table `mspromoitems` */

DROP TABLE IF EXISTS `mspromoitems`;

CREATE TABLE `mspromoitems` (
  `fin_id` int(10) NOT NULL AUTO_INCREMENT,
  `fin_promo_id` int(4) DEFAULT NULL,
  `fst_item_type` enum('ITEM','SUB GROUP') DEFAULT NULL,
  `fin_item_id` int(10) DEFAULT NULL,
  `fin_qty` float(12,2) DEFAULT NULL,
  `fst_unit` varchar(100) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

/*Data for the table `mspromoitems` */

insert  into `mspromoitems`(`fin_id`,`fin_promo_id`,`fst_item_type`,`fin_item_id`,`fin_qty`,`fst_unit`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (13,1,'ITEM',2,10.00,'pack','A',1,'2019-07-10 19:20:18',NULL,NULL),(14,1,'ITEM',1,10.00,'pack','A',1,'2019-07-10 19:20:18',NULL,NULL),(15,1,'ITEM',1,10.00,'PACK','A',1,'2019-07-10 19:20:18',NULL,NULL),(16,1,'SUB GROUP',3,20.00,'PACK','A',1,'2019-07-10 19:20:18',NULL,NULL),(17,3,'SUB GROUP',2,10.00,'PACK','A',1,'2019-07-10 19:32:10',NULL,NULL),(18,3,'ITEM',1,10.00,'PACK','A',1,'2019-07-10 19:32:10',NULL,NULL),(19,2,'ITEM',1,10.00,'PACK','A',1,'2019-07-11 12:36:58',NULL,NULL),(20,2,'ITEM',2,20.00,'PACK','A',1,'2019-07-11 12:37:23',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Data for the table `mspromoitemscustomer` */

insert  into `mspromoitemscustomer`(`fin_id`,`fin_promo_id`,`fst_participant_type`,`fin_customer_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (11,1,'RELATION',2,'A',1,'2019-07-10 19:20:19',NULL,NULL),(12,1,'MEMBER GROUP',1,'A',1,'2019-07-10 19:20:19',NULL,NULL),(13,1,'RELATION GROUP',1,'A',1,'2019-07-10 19:20:19',NULL,NULL),(14,3,'RELATION',13,NULL,1,'2019-07-10 19:32:10',NULL,NULL),(15,3,'RELATION',16,NULL,1,'2019-07-10 19:32:10',NULL,NULL),(16,3,'RELATION GROUP',1,NULL,1,'2019-07-10 19:32:10',NULL,NULL);

/*Table structure for table `msrelationcontactdetails` */

DROP TABLE IF EXISTS `msrelationcontactdetails`;

CREATE TABLE `msrelationcontactdetails` (
  `RecId` int(10) NOT NULL AUTO_INCREMENT,
  `RelationId` int(5) DEFAULT NULL,
  `ContactName` varchar(100) NOT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `EmailAddress` varchar(100) DEFAULT NULL,
  `Notes` text DEFAULT NULL,
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
  `Notes` text DEFAULT NULL,
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
  `fin_parent_id` int(5) DEFAULT NULL COMMENT 'Untuk keperluan invoice akan dilakukan ke parent id',
  `BusinessType` enum('P','C') DEFAULT NULL COMMENT 'P=Personal, C=Corporate',
  `fin_sales_area_id` int(11) DEFAULT NULL COMMENT 'Menentukan Area sales',
  `fin_sales_id` int(11) DEFAULT NULL COMMENT 'Sales Untuk customer ini',
  `RelationName` varchar(256) DEFAULT NULL,
  `Gender` enum('M','F') NOT NULL COMMENT 'Only BusinessType = Personal',
  `BirthDate` date DEFAULT NULL COMMENT 'Only BusinessType = Personal',
  `BirthPlace` text DEFAULT NULL COMMENT 'Only BusinessType = Personal',
  `Address` text DEFAULT NULL,
  `fst_shipping_address` text DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Fax` varchar(20) DEFAULT NULL,
  `PostalCode` varchar(10) DEFAULT NULL,
  `CountryId` int(5) DEFAULT NULL,
  `AreaCode` varchar(13) DEFAULT NULL,
  `CustPricingGroupid` int(5) DEFAULT NULL COMMENT 'Hanya perlu diisi jika, RelationType=1',
  `fin_credit_limit` decimal(12,2) DEFAULT NULL COMMENT 'digunakan untuk type customer sebagai batas credit limit',
  `NIK` varchar(100) DEFAULT NULL,
  `NPWP` varchar(100) DEFAULT NULL,
  `RelationNotes` text DEFAULT NULL COMMENT 'pilihan dari MsRelationNotes, bisa pilih lebih dari satu, id pilihannya disimpan sebagai string dengan comma, notes yg muncul dalam pilihan ini di filter sesuai RelationType, tipe Customer hanya muncul notes printout SJ dan Faktur, tipe Supplier/Vendor hanya muncul notes printout PO',
  `fin_warehouse_id` int(11) DEFAULT NULL,
  `fin_terms_payment` int(5) DEFAULT NULL,
  `fin_top_komisi` int(5) DEFAULT NULL,
  `fin_top_plus_komisi` int(5) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RelationId`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

/*Data for the table `msrelations` */

insert  into `msrelations`(`RelationId`,`RelationGroupId`,`RelationType`,`fin_parent_id`,`BusinessType`,`fin_sales_area_id`,`fin_sales_id`,`RelationName`,`Gender`,`BirthDate`,`BirthPlace`,`Address`,`fst_shipping_address`,`Phone`,`Fax`,`PostalCode`,`CountryId`,`AreaCode`,`CustPricingGroupid`,`fin_credit_limit`,`NIK`,`NPWP`,`RelationNotes`,`fin_warehouse_id`,`fin_terms_payment`,`fin_top_komisi`,`fin_top_plus_komisi`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,2,'2',0,'P',1,11,'Testing','M','1981-06-13','Jakarta','Jakarta','Jakarta Timur','0812 8888 8888','0812 8888 8888','12340',1,'31.75.01.1003',1,30000000.00,'360311234500004','1234567890','Test Notes',3,12,10,8,'A',4,'2019-05-08 12:24:49',4,'2019-07-11 11:27:22'),(2,1,'2,3',2,'P',3,NULL,'Coba Lagi2','F','1981-06-13','Depok','Tangerang','TANGERANG','0812 9999 9999','0812 9999 9999','15560',1,'1',NULL,400000000.00,NULL,'12345678912','Test Lagi',NULL,NULL,NULL,NULL,'A',4,'2019-05-08 12:33:30',4,'2019-05-15 13:08:12'),(9,4,'1,2,3',NULL,'P',1,16,'Ummay','M','1980-02-12','Tangerang','Perum Puri Permata Blok K no. 18 Cipondoh\r\nCipondoh Makmur','Perum Puri Permata Blok K no. 18 Cipondoh\r\nCipondoh Makmur','0819 8888 999','0819 8888 999','15510',1,'1',1,90000000.00,NULL,'123456789014','Coba Test Lagi\r\nTest Notes\r\nUji Coba Ke-4\r\n',3,30,15,7,'A',4,'2019-05-08 14:46:21',4,'2019-05-23 10:15:21'),(13,2,'1,2',9,'P',1,11,'Mocca','M','1981-06-13','Jakarta','Tangerang','TANGERANG','0819 9999 000','0819 9999 000','15560',1,'1',4,100000000.00,NULL,'123456789015','Uji Coba Ke-4',3,20,12,8,'A',4,'2019-05-09 09:31:18',4,'2019-05-10 10:19:47'),(15,2,'12,3',NULL,'P',1,11,'Lolita12','F','1980-09-18','Jakarta','Jakarta','JAKARTA','0813 9898 009','0813 9898 009','12340',1,'2',2,200000000.00,NULL,'1234567890151ab','Test Lagi',3,10,8,6,'A',4,'2019-05-09 15:46:36',4,'2019-05-15 14:27:23'),(16,3,'2',NULL,'C',2,11,'Minions','','2019-07-11','','Jakarta',NULL,'0818 8888 0909','0818 8888 090','12430',1,'31.75.01.1002',3,4000000000.00,'360311128800006','12345678901413','Test Notes\r\nCoba Test Lagi\r\n',3,14,12,8,'A',4,'2019-05-10 09:15:52',4,'2019-07-11 13:36:42'),(19,3,'2,3',NULL,'C',3,11,'Mocca','','2019-07-11','','Tangerang',NULL,'0817 8888 990','0817 8888 990','15540',1,'31.75.02.1004',5,2500000000.00,'','123456789015','Coba Test Lagi\r\n',1,10,8,6,'A',4,'2019-05-14 12:22:19',4,'2019-07-11 13:40:31'),(20,1,'2,1',9,'P',2,11,'Ummay','F','1980-07-10','Jakarta','Kebayoran Baru','TANGERANG','0817 0089 922','0817 0089 922','12340',1,'31.72.01.1001',2,5000000000.00,NULL,'123456789014ac','Test Notes\r\nTest Notes\r\nTest Notes\r\nTest Notes\r\n',3,12,10,8,'A',4,'2019-05-15 13:25:35',4,'2019-05-21 16:25:00'),(21,1,'3,1',NULL,'P',1,11,'Mocca','F','1980-07-17','Jakarta','Tangerang','BOGOR','0818 8888 123','0818 8888 123','15540',1,'31.70.02.1004',3,75000000.00,NULL,'123456789013156','Test Notes\r\nTest Notes\r\nUji Coba Ke-4\r\n',3,14,12,8,'A',4,'2019-05-15 14:35:48',4,'2019-05-21 16:26:51'),(22,1,'1,2,3',22,'C',2,11,'Lolita12','','1970-01-01','','Jakarta','JAKARTA','0818 8888 0909','0818 8888 0909','15540',1,'13.05.06.2006',1,30000000.00,'','123456789013ac','Uji Coba Ke-4\r\n',3,12,10,8,'A',4,'2019-05-15 14:45:09',4,'2019-07-11 13:19:51'),(23,1,'2,1,3',9,'C',1,11,'Coba Lagi2','','1970-01-01','','Bogor','BOGOR','','','12430',1,'13.05.05.2002',1,0.00,NULL,'1234567890654','Coba Test Lagi\r\n',3,14,12,8,'A',4,'2019-05-15 14:51:16',4,'2019-05-15 15:02:12'),(24,1,'2,3',NULL,'C',2,11,'Pikachu','','1970-01-01','','Bekasi','BEKASI','0817 0089 882','0817 0089 882','12430',1,'31.73.05.2001',2,0.00,NULL,'12345678912987','Uji Coba Ke-4\r\nUji Coba Ke-4\r\nTest Lagi\r\n',3,12,10,5,'A',4,'2019-05-15 15:18:41',4,'2019-05-21 16:28:41'),(25,2,'2,3',NULL,'C',2,11,'Mocca1','','1970-01-01','','Tangerang','CIKARANG','0813 1212 0098','0813 1212 0098','15540',1,'31.72.02.1001',0,300000000.00,'','1234567890180','Coba Test Lagi\r\nTest Notes\r\nTest Lagi\r\n',3,12,10,8,'A',4,'2019-05-21 09:08:26',4,'2019-07-11 13:17:24'),(26,1,'1',9,'P',1,11,'Nano Nani','F','1980-07-17','Jakarta','DEPOK','JAKARTA','0818 8888 1010','0818 8888 1010','14450',1,'31.74.07.1001',2,20000000.00,'360311234567890','12345678901001','Test Notes\r\nCoba Test Lagi\r\n',3,12,10,8,'A',4,'2019-06-10 13:52:05',4,'2019-07-11 16:21:40'),(28,1,'1',9,'C',1,11,'Minion','','1970-01-01','','Jakarta','CIKARANG','0818 8888 0909','0818 8888 0909','12430',1,'31.74.07.1001',1,300000000.00,'','123456789013','Uji Coba Ke-4\r\n',3,12,10,8,'A',4,'2019-07-11 13:11:07',4,'2019-07-11 16:13:24');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `mssalesarea` */

insert  into `mssalesarea`(`fin_sales_area_id`,`fst_name`,`fin_sales_regional_id`,`fin_sales_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'JAKARTA SELATAN',3,15,'A',0,'2019-07-06 17:49:17',4,'2019-07-11 18:28:43'),(2,'BOGOR',1,14,'A',4,'2019-07-08 18:44:05',4,'2019-07-11 18:28:10'),(3,'SEMARANG',2,16,'A',4,'2019-07-08 18:58:48',4,'2019-07-11 18:28:25');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `mssalesnational` */

insert  into `mssalesnational`(`fin_sales_national_id`,`fst_name`,`fin_sales_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'INDONESIA',13,'A',0,'2019-07-06 17:42:26',4,'2019-07-11 18:27:21'),(2,'SINGAPORE',14,'A',0,'2019-07-10 12:39:01',4,'2019-07-11 18:27:37'),(3,'MALAYSIA',15,'A',0,'2019-07-10 12:39:24',4,'2019-07-11 18:27:51');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `mssalesregional` */

insert  into `mssalesregional`(`fin_sales_regional_id`,`fst_name`,`fin_sales_national_id`,`fin_sales_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'JAWA BARAT',1,14,'A',0,'2019-07-06 17:44:30',4,'2019-07-11 18:26:05'),(2,'JAWA TENGAH',1,14,'A',0,'2019-07-06 17:45:06',4,'2019-07-11 18:26:19'),(3,'JAWA TIMUR',1,14,'A',0,'2019-07-06 17:48:38',4,'2019-07-11 18:26:36'),(4,'DKI JAKARTA',1,15,'A',0,'2019-07-10 10:40:22',4,'2019-07-11 18:26:50'),(5,'BANTEN',1,15,'A',4,'2019-07-11 18:25:31',4,'2019-07-11 18:27:03'),(6,'BALI',1,15,'A',4,'2019-07-11 18:25:51',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `msunits` */

insert  into `msunits`(`RecId`,`Unit`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'KG','A',0,'0000-00-00 00:00:00',1,'2019-05-08 20:36:21'),(2,'PCS','A',0,'0000-00-00 00:00:00',1,'2019-05-08 20:37:44'),(3,'SET','A',1,'2019-05-08 20:37:34',NULL,NULL),(4,'PACK','A',1,'2019-07-08 18:20:44',NULL,NULL),(5,'BOX','A',1,'2019-07-08 18:20:55',NULL,NULL);

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
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `msverification` */

insert  into `msverification`(`fin_rec_id`,`fst_controller`,`fst_verification_type`,`fin_department_id`,`fin_user_group_id`,`fin_seqno`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'SO','CreditLimit',2,3,0,'A',0,'2019-07-07 06:33:07',NULL,NULL);

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

/*Table structure for table `trinventory` */

DROP TABLE IF EXISTS `trinventory`;

CREATE TABLE `trinventory` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_warehouse_id` int(11) DEFAULT NULL,
  `fdt_trx_datetime` datetime DEFAULT NULL,
  `fst_trx_code` varchar(5) DEFAULT NULL,
  `fin_trx_id` int(11) DEFAULT NULL,
  `fst_referensi` varchar(100) DEFAULT NULL,
  `fin_item_id` int(11) DEFAULT NULL,
  `fst_unit` varchar(100) DEFAULT NULL,
  `fin_qty_in` float(12,2) DEFAULT NULL,
  `fin_qty_out` float(12,2) DEFAULT NULL,
  `fdc_avg_cost` decimal(12,2) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `trinventory` */

/*Table structure for table `trsalesorder` */

DROP TABLE IF EXISTS `trsalesorder`;

CREATE TABLE `trsalesorder` (
  `fin_salesorder_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_salesorder_no` varchar(20) DEFAULT NULL COMMENT 'FORMAT: XXXYYMM/99999, XXX=Prefix Transaksi (taruh di _Config), YY=TAHUN, MM=BULAN, 99999=Urutan Nomor transaksi (bisa per-tahun, bisa per-bulan, tergantung di_config)',
  `fdt_salesorder_date` date DEFAULT NULL,
  `fin_relation_id` int(11) DEFAULT NULL COMMENT 'hanya bisa pilih RelationType = Customer"',
  `fin_terms_payment` int(5) DEFAULT NULL COMMENT 'term pembayaran by default dari data relation',
  `fin_warehouse_id` int(5) DEFAULT NULL,
  `fin_sales_id` int(5) DEFAULT NULL COMMENT 'Ambil dari master user, dengan kode departement sesuai _Config ("SLS"), cukup salah satu dari 3 field ini yg harus diisi, sales itu level line worker, sales superviser itu Supervisor, sales manager itu middle management',
  `fin_sales_spv_id` int(5) DEFAULT NULL,
  `fin_sales_mgr_id` int(5) DEFAULT NULL,
  `fst_curr_code` varchar(10) DEFAULT NULL,
  `fdc_exchange_rate_idr` decimal(12,2) DEFAULT NULL,
  `fst_shipping_address` text DEFAULT NULL,
  `fst_memo` text DEFAULT NULL,
  `fbl_is_hold` bit(1) DEFAULT b'0' COMMENT 'Sales Order di hold sementara (tidak bisa di proses lebih lanjut)',
  `fin_unhold_id` int(11) DEFAULT NULL,
  `fdt_unhold_datetime` datetime DEFAULT NULL,
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

insert  into `trsalesorder`(`fin_salesorder_id`,`fst_salesorder_no`,`fdt_salesorder_date`,`fin_relation_id`,`fin_terms_payment`,`fin_warehouse_id`,`fin_sales_id`,`fin_sales_spv_id`,`fin_sales_mgr_id`,`fst_curr_code`,`fdc_exchange_rate_idr`,`fst_shipping_address`,`fst_memo`,`fbl_is_hold`,`fin_unhold_id`,`fdt_unhold_datetime`,`fbl_is_vat_include`,`fdc_vat_percent`,`fdc_vat_amount`,`fdc_disc_percent`,`fdc_disc_amount`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (18,'SLS1905/00001','2019-05-31',9,NULL,3,11,14,16,NULL,NULL,NULL,'delivery',NULL,1,'2019-07-11 17:05:44','',2.00,0.00,NULL,NULL,'A',4,'2019-05-31 12:21:55',4,'2019-07-11 17:10:34'),(19,'SLS1905/00002','2019-05-31',1,NULL,1,11,15,5,NULL,NULL,NULL,'delivery','',NULL,NULL,'',2.00,194.00,NULL,NULL,'A',4,'2019-05-31 12:22:23',4,'2019-07-11 17:16:57'),(20,'SLS1905/00003','2019-05-31',16,NULL,3,13,15,16,NULL,NULL,NULL,'Pre Order',NULL,NULL,NULL,'',2.50,907.65,NULL,NULL,'A',NULL,NULL,4,'2019-07-11 17:12:50'),(21,'SLS1905/00004','2019-05-31',13,NULL,3,11,14,5,NULL,NULL,NULL,'Tambahan Testing Ulang',NULL,NULL,NULL,'',2.00,453.00,NULL,NULL,'A',4,'2019-05-31 12:23:42',4,'2019-07-11 18:01:38'),(22,'SLS1905/00007','2019-05-31',24,NULL,0,9,9,9,NULL,NULL,NULL,'delivery aaa','',NULL,NULL,'\0',0.00,20.00,2.00,0.00,'A',4,'2019-05-31 12:49:32',NULL,NULL),(23,'SLS1905/00008','2019-05-31',2,NULL,0,9,9,9,NULL,NULL,NULL,'delivery',NULL,NULL,NULL,'',2.00,0.00,0.00,300.00,'A',4,'2019-05-31 12:55:14',4,'2019-05-31 13:47:48'),(24,'SO1906/00001','2019-06-30',15,NULL,0,5,5,5,NULL,NULL,NULL,'delivery abc','\0',NULL,NULL,'',2.00,0.00,0.00,20.00,'A',4,'2019-06-30 13:40:50',NULL,NULL);

/*Table structure for table `trsalesorderdetails` */

DROP TABLE IF EXISTS `trsalesorderdetails`;

CREATE TABLE `trsalesorderdetails` (
  `rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_salesorder_id` int(11) DEFAULT NULL COMMENT 'ref: > trsalesorder.fin_salesorder_id',
  `fin_item_id` int(11) DEFAULT NULL COMMENT 'ref: > msitems.ItemId',
  `fst_custom_item_name` varchar(100) DEFAULT NULL,
  `fst_unit` varchar(100) DEFAULT NULL,
  `fdc_qty` decimal(10,2) DEFAULT NULL,
  `fdc_price` decimal(10,2) DEFAULT NULL,
  `fst_disc_item` varchar(100) DEFAULT NULL COMMENT 'Discount Item bertingkat berupa string, misal 10+5+2',
  `fdc_disc_amount` decimal(12,2) DEFAULT NULL,
  `fst_memo_item` text DEFAULT NULL,
  `fin_promo_id` int(11) DEFAULT NULL COMMENT 'Bila terisi merupakan item promo',
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `trsalesorderdetails` */

insert  into `trsalesorderdetails`(`rec_id`,`fin_salesorder_id`,`fin_item_id`,`fst_custom_item_name`,`fst_unit`,`fdc_qty`,`fdc_price`,`fst_disc_item`,`fdc_disc_amount`,`fst_memo_item`,`fin_promo_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,18,1,NULL,NULL,2.00,200.00,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `trsuratjalan` */

DROP TABLE IF EXISTS `trsuratjalan`;

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
  `fst_sj_memo` text DEFAULT NULL,
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

/*Data for the table `trsuratjalan` */

/*Table structure for table `trsuratjalandetail` */

DROP TABLE IF EXISTS `trsuratjalandetail`;

CREATE TABLE `trsuratjalandetail` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_sj_id` int(11) DEFAULT NULL,
  `fin_item_id` int(11) DEFAULT NULL,
  `fin_qty` int(8) DEFAULT NULL,
  `fst_unit` varchar(100) DEFAULT NULL,
  `fin_conversion` decimal(12,2) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `trsuratjalandetail` */

/*Table structure for table `trverification` */

DROP TABLE IF EXISTS `trverification`;

CREATE TABLE `trverification` (
  `RecId` bigint(20) NOT NULL AUTO_INCREMENT,
  `fst_controller` varchar(100) DEFAULT NULL,
  `fin_transaction_id` int(11) DEFAULT NULL,
  `fin_seqno` int(5) DEFAULT NULL,
  `fst_message` text DEFAULT NULL,
  `fin_department_id` int(5) DEFAULT NULL,
  `fin_user_group_id` int(2) DEFAULT NULL,
  `fst_verification_status` enum('NV','RV','VF','RJ') DEFAULT NULL COMMENT 'NV = Need Verification, RV = Ready to verification, VF=Verified, RJ= Rejected',
  `fst_notes` text DEFAULT NULL,
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
  `fst_address` text DEFAULT NULL,
  `fst_phone` varchar(100) DEFAULT NULL,
  `fst_email` varchar(100) DEFAULT NULL,
  `fin_branch_id` int(5) NOT NULL,
  `fin_department_id` bigint(20) NOT NULL,
  `fin_group_id` bigint(20) DEFAULT NULL,
  `fbl_admin` tinyint(1) NOT NULL DEFAULT 0,
  `fst_active` enum('A','S','D') NOT NULL COMMENT 'A->Active;S->Suspend;D->Deleted',
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_insert_id` int(10) NOT NULL,
  `fdt_update_datetime` datetime NOT NULL,
  `fin_update_id` int(10) NOT NULL,
  UNIQUE KEY `fin_id` (`fin_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`fin_user_id`,`fst_username`,`fst_password`,`fst_fullname`,`fst_gender`,`fdt_birthdate`,`fst_birthplace`,`fst_address`,`fst_phone`,`fst_email`,`fin_branch_id`,`fin_department_id`,`fin_group_id`,`fbl_admin`,`fst_active`,`fdt_insert_datetime`,`fin_insert_id`,`fdt_update_datetime`,`fin_update_id`) values (4,'enny06','c50e5b88116a073a72aea201b96bfe8e','Enny Nuraini','F','1979-10-06','Jakarta','Tangerang','08128042742','enny06@yahoo.com',0,0,2,1,'A','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0),(5,'udin123','3af4c9341e31bce1f4262a326285170d','Udin Sedunia','F','1980-06-12','Makasar','Depok','087772721096','udin123@yahoo.com',12,3,1,1,'A','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0),(9,'dimpi80','4aba2f8cbc594d39020a0187f1331670','Dimas Widiastuti','F','1980-09-18','Depok','Depok','081380804521','dimpi80@yahoo.com',2,3,1,0,'A','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0),(11,'anne80','4a094e453e6ee6a8253def63db4d1509','Annie Emma Limahelu','F','1970-01-01','Jakarta','Jatiasih, Bekasi','0813 4562 9825','anne80@yahoo.com',0,1,NULL,1,'A','2019-05-21 10:23:26',4,'2019-05-21 10:25:17',4),(12,'devibong@yahoo.com','06a6077b0cfcb0f4890fb5f2543c43be','Devi Bastian','M','1978-08-26','Pematang Siantar',NULL,NULL,'devibong@yahoo.com',0,0,NULL,0,'A','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0),(13,'sales1','06a6077b0cfcb0f4890fb5f2543c43be','Sales No 1 National','M','1989-07-17','Jakarta',NULL,NULL,NULL,0,2,1,0,'A','2019-07-06 17:37:42',0,'0000-00-00 00:00:00',0),(14,'sales2','06a6077b0cfcb0f4890fb5f2543c43be','Sales No 2 Regional','M','1989-07-17','Jakarta',NULL,NULL,NULL,0,2,1,0,'A','2019-07-06 17:39:31',0,'0000-00-00 00:00:00',0),(15,'sales3','06a6077b0cfcb0f4890fb5f2543c43be','Sales No 3 Area','M','1989-07-17','Jakarta',NULL,NULL,NULL,0,2,1,0,'A','2019-07-06 17:39:31',0,'0000-00-00 00:00:00',0),(16,'sales4','06a6077b0cfcb0f4890fb5f2543c43be','Sales No 4 biasa','M','1989-07-17','Jakarta',NULL,NULL,NULL,0,2,1,0,'A','2019-07-06 17:39:31',0,'0000-00-00 00:00:00',0);

/*Table structure for table `usersgroup` */

DROP TABLE IF EXISTS `usersgroup`;

CREATE TABLE `usersgroup` (
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

/*Data for the table `usersgroup` */

insert  into `usersgroup`(`fin_group_id`,`fst_group_name`,`fin_level`,`fst_active`,`fdt_insert_datetime`,`fin_insert_id`,`fdt_update_datetime`,`fin_update_id`) values (1,'President Director','1','A','2019-04-24 12:59:47',1,'2019-04-26 09:47:43',1),(2,'General Manager','2','A','2019-04-24 13:00:17',1,NULL,NULL),(3,'Supervisor','3','A','2019-04-24 13:00:35',1,NULL,NULL),(4,'Staff','4','A','2019-04-24 13:01:09',1,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
