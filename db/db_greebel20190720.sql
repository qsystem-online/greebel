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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `config` */

insert  into `config`(`fin_id`,`fst_key`,`fst_value`,`fst_notes`,`fbl_active`) values (1,'document_folder','d:\\edoc_storage\\',NULL,1),(2,'document_max_size','102400','maximal doc size (kilobyte)',1),(3,'salesorder_prefix','SO','Prefix penomoran sales order',1),(4,'sales_department_id','2','Sales Department',1),(5,'main_glaccount_separator','','Separator antara maingroup glaccount',1),(6,'parent_glaccount_separator','.','Separator parent group glaccount',1),(7,'percent_ppn','10','PPn Percentage',1),(9,'photo_items_location','/uploads/items/','File Location untuk image Item',1),(10,'delete_jurnal','1','0:Balik jurnal ; 1:Delete Jurnal ',1),(11,'lock_transaction_date','2019-05-01','Setiap transaksi dibawah tgl lock tidak dapat ditambah, rubah ataupun di hapus',1),(12,'closing_transaction_date','2019-05-01','Setiap transaksi dibawah tgl lock tidak dapat ditambah, rubah ataupun di hapus\r\nPada saat proses closing otomatis tgl lock_transaction_date di set \r\nsesuai tgl closing_transaction_date',1);

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
  `fin_glaccount_group_id` int(10) NOT NULL AUTO_INCREMENT,
  `fin_glaccount_maingroup_id` int(10) DEFAULT NULL,
  `fst_glaccount_group_name` varchar(100) DEFAULT NULL,
  `fst_default_post` enum('D','C') DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_glaccount_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `glaccountgroups` */

insert  into `glaccountgroups`(`fin_glaccount_group_id`,`fin_glaccount_maingroup_id`,`fst_glaccount_group_name`,`fst_default_post`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,NULL,'Assets',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL),(2,NULL,'Liabilities',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL),(3,NULL,'Equity',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL),(4,NULL,'Income',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL),(5,NULL,'Cost Of Sales',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL),(6,NULL,'Expenses',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL),(7,NULL,'Other Income',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL),(8,NULL,'Other Expense',NULL,'A',1,'0000-00-00 00:00:00',NULL,NULL);

/*Table structure for table `glaccountmaingroups` */

DROP TABLE IF EXISTS `glaccountmaingroups`;

CREATE TABLE `glaccountmaingroups` (
  `fin_glaccount_maingroup_id` int(10) NOT NULL AUTO_INCREMENT,
  `fst_glaccount_maingroup_name` varchar(100) DEFAULT NULL,
  `fst_glaccount_main_prefix` varchar(20) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_glaccount_maingroup_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `glaccountmaingroups` */

insert  into `glaccountmaingroups`(`fin_glaccount_maingroup_id`,`fst_glaccount_maingroup_name`,`fst_glaccount_main_prefix`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'Assets','1','A',1,'0000-00-00 00:00:00',NULL,NULL),(2,'Liabilities','2','A',1,'0000-00-00 00:00:00',NULL,NULL),(3,'Equity','3','A',1,'0000-00-00 00:00:00',NULL,NULL),(4,'Income','4','A',1,'0000-00-00 00:00:00',NULL,NULL),(5,'Cost Of Sales','5','A',1,'0000-00-00 00:00:00',NULL,NULL),(6,'Expenses','6','A',1,'0000-00-00 00:00:00',NULL,NULL),(7,'Other Income','7','A',1,'0000-00-00 00:00:00',NULL,NULL),(8,'Other Expense','8','A',1,'0000-00-00 00:00:00',NULL,NULL);

/*Table structure for table `glaccounts` */

DROP TABLE IF EXISTS `glaccounts`;

CREATE TABLE `glaccounts` (
  `fst_glaccount_code` varchar(100) NOT NULL,
  `fin_glaccount_maingroup_id` int(10) NOT NULL,
  `fst_glaccount_name` varchar(256) NOT NULL,
  `fst_glaccount_level` enum('HD','DT','DK') NOT NULL COMMENT 'Pilihan HD(Header). DT(Detail), DK(DetailKasBank)',
  `fst_parent_glaccount_code` varchar(100) DEFAULT NULL COMMENT 'Rekening Induk (hanya perlu diisi jika GLAccountLevel = Detail atau Detail Kasbank',
  `fst_default_post` enum('D','C') DEFAULT NULL,
  `fin_seq_no` int(5) DEFAULT NULL,
  `fin_min_user_level_access` int(10) NOT NULL AUTO_INCREMENT,
  `fst_curr_code` varchar(10) NOT NULL,
  `fbl_is_allow_in_cash_bank_module` tinyint(1) NOT NULL DEFAULT 0,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) NOT NULL,
  `fdt_update_datetime` datetime NOT NULL,
  PRIMARY KEY (`fst_glaccount_code`),
  KEY `MinUserLevelAccess` (`fin_min_user_level_access`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `glaccounts` */

insert  into `glaccounts`(`fst_glaccount_code`,`fin_glaccount_maingroup_id`,`fst_glaccount_name`,`fst_glaccount_level`,`fst_parent_glaccount_code`,`fst_default_post`,`fin_seq_no`,`fin_min_user_level_access`,`fst_curr_code`,`fbl_is_allow_in_cash_bank_module`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values ('111',1,'Aktiva Lancar','HD',NULL,'D',1,5,'IDR',1,'A',12,'2019-06-26 11:39:09',4,'2019-07-15 14:08:40'),('111.000125',1,'Kas','DK','111','C',2,5,'IDR',1,'A',4,'2019-07-15 14:38:00',0,'0000-00-00 00:00:00'),('112',1,'Aktiva Tetap','HD',NULL,'D',0,5,'IDR',1,'A',12,'2019-06-26 14:18:44',0,'0000-00-00 00:00:00');

/*Table structure for table `glledger` */

DROP TABLE IF EXISTS `glledger`;

CREATE TABLE `glledger` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_trx_sourcecode` varchar(5) DEFAULT NULL,
  `fin_trx_id` int(11) DEFAULT NULL,
  `fin_branch_id` int(11) DEFAULT NULL,
  `fdt_trx_datetime` datetime DEFAULT NULL,
  `fst_account_code` varchar(100) DEFAULT NULL,
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

/*Table structure for table `logtransactions` */

DROP TABLE IF EXISTS `logtransactions`;

CREATE TABLE `logtransactions` (
  `fin_rec_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fst_table_name` varchar(100) DEFAULT NULL,
  `transaction_type` enum('INSERT','UPDATE','DELETE') DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `logtransactions` */

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
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;

/*Data for the table `menus` */

insert  into `menus`(`fin_id`,`fin_order`,`fst_order`,`fst_menu_name`,`fst_caption`,`fst_icon`,`fst_type`,`fst_link`,`fin_parent_id`,`fbl_active`) values (1,1,'10','dashboard','Dashboard','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','welcome/advanced_element',0,1),(2,2,'20','master','Master Data','<i class=\"fa fa-dashboard\"></i>','HEADER',NULL,0,1),(23,0,'20.10','master_accounting','Master Accounting','<i class=\"fa fa-bank\"></i>','TREEVIEW',NULL,0,1),(24,0,'20.10.10','gl_account','GL Account','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','gl/glaccount\r\n',23,1),(25,0,'20.20','master_operasional','Master Operasional','<i class=\"fa fa-car\"></i>','TREEVIEW',NULL,0,1),(26,0,'20.20.10','master_item','Barang Dagangan','<i class=\"fa fa-dropbox\"></i>','TREEVIEW',NULL,25,1),(27,0,'20.20.10.10','item_group','Items Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/group_item\r\n\r\n',26,1),(28,0,'20.20.10.20','item_main_group','Main Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/maingroup_item\r\n',26,1),(29,0,'20.20.10.30','item_sub_group','Sub Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/subgroup_item',26,1),(30,0,'20.20.10.40','items','Items','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/item\r\n',26,1),(31,0,'20.20.20','master_currency','Kurs / Mata Uang','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/currency',25,1),(32,0,'20.20.30','relation','Relations','<i class=\"fa fa-dashboard\"></i>','TREEVIEW',NULL,25,1),(33,0,'20.20.30.10','relation_group','Groups','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/relation_group\r\n',32,1),(34,0,'20.20.30.20','relation_customer_vendor','Customer / Vendor','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/relation\r\n',32,1),(35,0,'20.20.30.30','membership','Membership','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/membership',32,1),(36,0,'20.20.40','warehouse','Gudang','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/warehouse',25,1),(37,0,'20.20.50','pricing','Master Prices','<i class=\"fa fa-dashboard\"></i>','TREEVIEW',NULL,25,1),(38,0,'20.20.50.10','pricing_group','Pricing Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/cust_pricing_group',37,1),(39,0,'20.20.50.20','prices','Prices','<i class=\"fa fa-dashboard\"></i>','TREEVIEW',NULL,37,1),(40,0,'20.20.50.30','discount','Discount','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/discount',37,1),(41,0,'20.30','master_system','Master System','<i class=\"fa fa-dashboard\"></i>','TREEVIEW',NULL,0,1),(42,0,'20.20.60','promo','Master Promo','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/promotion',25,1),(43,0,'20.30.10','branch','Cabang','<i class=\"fa fa-dashboard\"></i>','TREEVIEW',NULL,41,1),(44,0,'20.30.20','department','Departemen','<i class=\"fa fa-dashboard\"></i>','TREEVIEW',NULL,41,1),(45,0,'20.30.30','user_group','User Group','<i class=\"fa fa-dashboard\"></i>','TREEVIEW',NULL,41,1),(46,0,'20.30.40','user','User','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','user',41,1),(47,0,'30','transaction','Transaksi','<i class=\"fa fa-dashboard\"></i>','HEADER',NULL,0,1),(48,0,'30.10','purchase','Pembelian','<i class=\"fa fa-dashboard\"></i>','TREEVIEW',NULL,0,1),(49,0,'30.20','sales','Penjualan','<i class=\"fa fa-dashboard\"></i>','TREEVIEW',NULL,0,1),(51,0,'30.20.10','sales_order','Sales Order','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','tr/sales_order',49,1),(52,0,'30.20.20','delivery_order','Delivery Order','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','tr/delivery_order',49,1),(53,0,'20.20.70','sales_area','Sales Area','<i class=\"fa fa-dashboard\"></i>','TREEVIEW',NULL,25,1),(54,0,'20.20.70.10','sales_area_national','National','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/sales_area/national',53,1),(55,0,'20.20.70.20','sales_area_regional','Regional','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/sales_area/regional',53,1),(56,0,'20.20.70.30','sales_area_area','Area','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/sales_area/area',53,1),(57,0,'20.20.80','verification','Verifikasi','<i class=\"fa fa-dashboard\"></i>','TREEVIEW',NULL,25,1),(58,0,'20.20.30.25','member_group','Member Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/member_group',32,1),(59,0,'30.20.11','unhold_so','Unhold Sales Order','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','tr/sales_order/unhold',49,1),(60,0,'30.20.50','sales_preorder','Pre Order','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','tr/sales_preorder',49,1);

/*Table structure for table `msarea` */

DROP TABLE IF EXISTS `msarea`;

CREATE TABLE `msarea` (
  `fst_kode` varchar(13) NOT NULL,
  `fst_nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msarea` */


/*Table structure for table `msbranches` */

DROP TABLE IF EXISTS `msbranches`;

CREATE TABLE `msbranches` (
  `fin_branch_id` int(5) NOT NULL AUTO_INCREMENT,
  `fst_branch_name` varchar(100) DEFAULT NULL,
  `fst_address` text DEFAULT NULL,
  `fin_country_id` int(5) DEFAULT NULL,
  `fst_area_code` varchar(13) DEFAULT NULL,
  `fst_postalcode` varchar(10) DEFAULT NULL,
  `fst_branch_phone` varchar(20) DEFAULT NULL,
  `fst_notes` text DEFAULT NULL,
  `fbl_is_hq` tinyint(1) DEFAULT NULL COMMENT 'Hanya boleh ada 1 HQ di table cabang',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `msbranches` */

insert  into `msbranches`(`fin_branch_id`,`fst_branch_name`,`fst_address`,`fin_country_id`,`fst_area_code`,`fst_postalcode`,`fst_branch_phone`,`fst_notes`,`fbl_is_hq`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'JAKARTA','Jakarta',1,'31.73.01','14450','08128042742','Oke',1,'A',0,'2019-06-10 11:35:00',4,'2019-07-15 16:21:05'),(2,'SURABAYA','SURABAYA',1,'35.78.05','60262','0812 8877 9999','CABANG 1',0,'A',12,'2019-07-11 08:48:08',4,'2019-07-15 16:20:11'),(3,'MEDAN','MEDAN',1,'12.71.01','20215','0811 1234 9876','KACAB 1',0,'A',4,'2019-07-15 16:41:23',4,'2019-07-15 16:41:55');

/*Table structure for table `msconfigjurnal` */

DROP TABLE IF EXISTS `msconfigjurnal`;

CREATE TABLE `msconfigjurnal` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_key` varchar(256) DEFAULT NULL,
  `fst_glaccount_code` varchar(100) DEFAULT NULL,
  `fbl_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `msconfigjurnal` */

insert  into `msconfigjurnal`(`fin_rec_id`,`fst_key`,`fst_glaccount_code`,`fbl_active`) values (1,'SO_PIUTANG','111.111.111',1),(2,'SO_DP','111.222.111',1);

/*Table structure for table `mscountries` */

DROP TABLE IF EXISTS `mscountries`;

CREATE TABLE `mscountries` (
  `fin_country_id` int(5) NOT NULL AUTO_INCREMENT,
  `fst_country_name` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `mscountries` */

insert  into `mscountries`(`fin_country_id`,`fst_country_name`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'Indonesia','A',4,'2019-05-03 06:05:02',NULL,NULL),(2,'Singapore','A',4,'2019-05-03 18:10:07',NULL,NULL),(3,'Australia','A',4,'2019-05-06 09:41:16',NULL,NULL),(4,'Thailand','A',4,'2019-05-06 15:16:32',NULL,NULL),(5,'Vietnam','A',4,'2019-05-06 15:26:41',NULL,NULL),(7,'Malaysia','A',4,'2019-05-06 16:07:25',NULL,NULL);

/*Table structure for table `mscurrencies` */

DROP TABLE IF EXISTS `mscurrencies`;

CREATE TABLE `mscurrencies` (
  `fst_curr_code` varchar(10) NOT NULL,
  `fst_curr_name` varchar(100) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fst_curr_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `mscurrencies` */

insert  into `mscurrencies`(`fst_curr_code`,`fst_curr_name`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values ('IDR','Rupiah','A',1,'0000-00-00 00:00:00',NULL,NULL),('KRW','Won Korea','A',4,'2019-07-15 15:13:43',4,'2019-07-15 15:14:22');

/*Table structure for table `mscurrenciesratedetails` */

DROP TABLE IF EXISTS `mscurrenciesratedetails`;

CREATE TABLE `mscurrenciesratedetails` (
  `fin_rec_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fst_curr_code` varchar(10) NOT NULL,
  `fdt_date` date NOT NULL,
  `fdc_exchange_rate_to_idr` decimal(12,2) NOT NULL DEFAULT 0.00,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `mscurrenciesratedetails` */

insert  into `mscurrenciesratedetails`(`fin_rec_id`,`fst_curr_code`,`fdt_date`,`fdc_exchange_rate_to_idr`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'0','2019-07-15',14000.00,'A',4,'2019-07-15 15:08:10',NULL,NULL),(4,'KRW','1970-01-01',850.00,'A',4,'2019-07-15 15:14:22',NULL,NULL);

/*Table structure for table `mscustpricinggroups` */

DROP TABLE IF EXISTS `mscustpricinggroups`;

CREATE TABLE `mscustpricinggroups` (
  `fin_cust_pricing_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_cust_pricing_group_name` varchar(100) NOT NULL,
  `fdc_percent_of_price_list` decimal(12,2) NOT NULL DEFAULT 100.00,
  `fdc_difference_in_amount` decimal(12,5) NOT NULL DEFAULT 0.00000,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_cust_pricing_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `mscustpricinggroups` */

insert  into `mscustpricinggroups`(`fin_cust_pricing_group_id`,`fst_cust_pricing_group_name`,`fdc_percent_of_price_list`,`fdc_difference_in_amount`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'Bayan Group',90.00,0.00000,'A',1,'2019-05-02 17:10:22',1,'2019-05-03 13:43:53'),(2,'Naver Corp.',2.00,0.00000,'A',1,'2019-05-02 17:22:01',4,'2019-05-29 15:44:33'),(3,'Dupta',10.00,0.00000,'A',1,'2019-05-02 17:53:36',4,'2019-05-02 18:10:19'),(4,'Megalitikum',0.00,30.00000,'A',4,'2019-05-02 18:07:24',4,'2019-05-10 09:53:09'),(5,'Testing',2.00,0.00000,'A',4,'2019-05-03 09:01:41',4,'2019-05-14 12:33:02'),(6,'Yukioi',0.00,15.00000,'A',4,'2019-05-03 09:23:48',4,'2019-05-03 09:24:37'),(7,'Test1',2.00,0.00000,'A',4,'2019-05-15 13:03:19',NULL,NULL),(8,'Enigma Tbk',2.00,0.00000,'A',4,'2019-07-12 17:53:58',NULL,NULL);

/*Table structure for table `msgroupitems` */

DROP TABLE IF EXISTS `msgroupitems`;

CREATE TABLE `msgroupitems` (
  `fin_item_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_item_group_name` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_item_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `msgroupitems` */

insert  into `msgroupitems`(`fin_item_group_id`,`fst_item_group_name`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'STATIONARY','A',0,'0000-00-00 00:00:00',1,'2019-05-08 20:36:21'),(2,'FANCY','A',0,'0000-00-00 00:00:00',1,'2019-05-08 20:37:44'),(3,'PROMO','A',1,'2019-05-08 20:37:34',NULL,NULL);

/*Table structure for table `msitembomdetails` */

DROP TABLE IF EXISTS `msitembomdetails`;

CREATE TABLE `msitembomdetails` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_item_id` int(11) DEFAULT NULL,
  `fin_item_id_bom` int(10) DEFAULT NULL COMMENT '*BOM:Bill Of Material',
  `fst_unit` varchar(100) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `msitembomdetails` */

insert  into `msitembomdetails`(`fin_rec_id`,`fin_item_id`,`fin_item_id_bom`,`fst_unit`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,1,2,'PACK','A',1,'2019-07-10 19:28:39',NULL,NULL),(3,4,4,'KG','A',4,'2019-07-16 15:00:59',NULL,NULL);

/*Table structure for table `msitemdiscounts` */

DROP TABLE IF EXISTS `msitemdiscounts`;

CREATE TABLE `msitemdiscounts` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_item_discount` varchar(100) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `msitemdiscounts` */

insert  into `msitemdiscounts`(`fin_rec_id`,`fst_item_discount`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'0','A',4,'2019-06-14 10:27:41',NULL,NULL),(2,'10','A',4,'2019-06-14 10:27:45',NULL,NULL),(3,'10+2.5','A',4,'2019-06-14 10:27:47',NULL,NULL),(4,'10+5','A',4,'2019-06-14 10:28:03',NULL,NULL),(5,'10+5+2.5','A',4,'2019-06-14 10:29:19',NULL,NULL),(6,'2.5','A',4,'2019-07-14 23:04:15',NULL,NULL),(7,'5','A',4,'2019-07-15 16:59:17',NULL,NULL),(8,'20','A',12,'2019-07-17 14:41:51',NULL,NULL),(9,'25','A',12,'2019-07-17 14:42:03',12,'2019-07-17 14:46:01'),(10,'20','D',12,'2019-07-17 14:42:44',NULL,NULL),(11,'20','D',12,'2019-07-17 14:43:22',NULL,NULL),(12,'20','D',12,'2019-07-17 14:44:40',NULL,NULL);

/*Table structure for table `msitems` */

DROP TABLE IF EXISTS `msitems`;

CREATE TABLE `msitems` (
  `fin_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_item_code` varchar(100) DEFAULT NULL,
  `fst_item_name` varchar(256) DEFAULT NULL,
  `fst_name_on_pos` varchar(50) DEFAULT NULL COMMENT 'Nama Item yang akan di gunakan di System POS',
  `fst_vendor_item_name` varchar(256) DEFAULT NULL,
  `fin_item_maingroup_id` int(11) DEFAULT NULL,
  `fin_item_group_id` int(11) DEFAULT NULL,
  `fin_item_subgroup_id` int(11) DEFAULT NULL,
  `fin_item_type_id` enum('1','2','3','4','5') DEFAULT '4' COMMENT '1=Raw Material, 2=Semi Finished Material, 3=Supporting Material, 4=Ready Product, 5=Logistic',
  `fin_standard_vendor_id` int(11) DEFAULT NULL COMMENT 'Dari Master Relation where fst_relation_type contains 2',
  `fin_optional_vendor_id` int(11) DEFAULT NULL COMMENT 'Dari Master Relation where fst_relation_type contains 2',
  `fbl_is_batch_number` tinyint(1) DEFAULT 0,
  `fbl_is_serial_number` tinyint(1) DEFAULT 0,
  `fdc_scale_for_bom` decimal(12,2) DEFAULT 1.00,
  `fst_storage_rack_info` varchar(256) DEFAULT NULL,
  `fst_memo` text DEFAULT NULL,
  `fst_max_item_discount` varchar(256) DEFAULT NULL,
  `fdc_min_basic_unit_avg_cost` decimal(12,2) DEFAULT 0.00 COMMENT 'Opsional, jika di isi maka bisa dihasilkan Alert report barang-barang yang perhitungan harga rata2 dibawah Minimal',
  `fdc_max_basic_unit_avg_cost` decimal(12,2) DEFAULT 0.00 COMMENT 'Opsional, jika di isi maka bisa dihasilkan Alert report barang-barang yang perhitungan harga rata2 diatas Maximal',
  `fst_sni_no` varchar(50) DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_item_id`),
  UNIQUE KEY `idx_itemcode` (`fst_item_code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `msitems` */

insert  into `msitems`(`fin_item_id`,`fst_item_code`,`fst_item_name`,`fst_name_on_pos`,`fst_vendor_item_name`,`fin_item_maingroup_id`,`fin_item_group_id`,`fin_item_subgroup_id`,`fin_item_type_id`,`fin_standard_vendor_id`,`fin_optional_vendor_id`,`fbl_is_batch_number`,`fbl_is_serial_number`,`fdc_scale_for_bom`,`fst_storage_rack_info`,`fst_memo`,`fst_max_item_discount`,`fdc_min_basic_unit_avg_cost`,`fdc_max_basic_unit_avg_cost`,`fst_sni_no`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'AB1230','Testing','','Test Vendor',1,2,3,'4',NULL,NULL,1,1,1.00,'',NULL,'2.5',10000.00,12000.00,'','A',0,'2019-06-10 14:04:16',1,'2019-07-10 19:28:38'),(2,'AB2250','Silver Queen',NULL,'Choco',2,1,2,'4',1,1,2,2,2.00,NULL,'Pre Order','5',50.00,200.00,NULL,'A',0,'2019-06-11 16:16:10',NULL,NULL),(3,'PR001','Promo Item',NULL,'Promo Item',1,1,2,'4',1,1,1,1,1.00,NULL,NULL,'5',1.00,1.00,NULL,'A',1,'2019-06-25 22:47:56',NULL,NULL),(4,'LARX-MX12','Black Florest','BF01A','Choco',3,3,4,'1',NULL,NULL,1,1,1.00,'1','Test','10',1.00,100000.00,'1','A',4,'2019-07-16 13:30:15',4,'2019-07-16 15:00:59');

/*Table structure for table `msitemspecialpricinggroupdetails` */

DROP TABLE IF EXISTS `msitemspecialpricinggroupdetails`;

CREATE TABLE `msitemspecialpricinggroupdetails` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_item_id` int(11) NOT NULL,
  `fst_unit` varchar(100) NOT NULL,
  `fin_cust_pricing_group_id` int(11) NOT NULL,
  `fdc_selling_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`,`fin_item_id`,`fst_unit`,`fin_cust_pricing_group_id`,`fdc_selling_price`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `msitemspecialpricinggroupdetails` */

insert  into `msitemspecialpricinggroupdetails`(`fin_rec_id`,`fin_item_id`,`fst_unit`,`fin_cust_pricing_group_id`,`fdc_selling_price`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (4,1,'pack',1,11000.00,'A',1,'2019-07-10 19:28:39',NULL,NULL),(5,4,'KG',3,100000.00,'A',4,'2019-07-16 15:01:00',NULL,NULL);

/*Table structure for table `msitemunitdetails` */

DROP TABLE IF EXISTS `msitemunitdetails`;

CREATE TABLE `msitemunitdetails` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_item_id` int(11) NOT NULL,
  `fst_unit` varchar(100) NOT NULL,
  `fbl_is_basic_unit` tinyint(1) NOT NULL DEFAULT 0,
  `fdc_conv_to_basic_unit` decimal(12,2) NOT NULL DEFAULT 1.00,
  `fbl_is_selling` tinyint(1) DEFAULT 0,
  `fbl_is_buying` tinyint(1) NOT NULL DEFAULT 0,
  `fbl_is_retail` tinyint(1) DEFAULT 0 COMMENT 'Unit di gunakan di POS, Cuma boleh 1',
  `fbl_is_production_output` tinyint(1) NOT NULL DEFAULT 0,
  `fdc_price_list` decimal(12,2) NOT NULL DEFAULT 0.00,
  `fdc_het` decimal(12,2) DEFAULT 0.00 COMMENT '*Harga Eceran Terendah',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `msitemunitdetails` */

insert  into `msitemunitdetails`(`fin_rec_id`,`fin_item_id`,`fst_unit`,`fbl_is_basic_unit`,`fdc_conv_to_basic_unit`,`fbl_is_selling`,`fbl_is_buying`,`fbl_is_retail`,`fbl_is_production_output`,`fdc_price_list`,`fdc_het`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,2,'PACK',1,1.00,1,1,0,0,7000.00,0.00,'A',1,'2019-06-24 13:56:05',NULL,NULL),(2,2,'BOX',0,40.00,1,0,0,0,280000.00,0.00,'A',1,'2019-06-24 13:56:48',NULL,NULL),(9,1,'PACK',1,1.00,1,0,0,0,10000.00,0.00,'A',1,'2019-07-10 19:28:38',NULL,NULL),(10,1,'BOX',0,20.00,1,0,0,0,200000.00,0.00,'A',1,'2019-07-10 19:28:39',NULL,NULL),(16,4,'KG',1,10.00,1,1,0,0,100000.00,100000.00,'A',4,'2019-07-16 15:00:59',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `msmembergroups` */

insert  into `msmembergroups`(`fin_member_group_id`,`fst_member_group_name`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'SILVER','A',1,'2019-07-10 17:35:42',NULL,NULL),(2,'GOLD','A',4,'2019-07-11 15:04:28',4,'2019-07-11 15:10:39'),(3,'PLATINUM','A',4,'2019-07-11 15:07:59',4,'2019-07-11 15:10:24'),(4,'DIAMOND','A',4,'2019-07-11 16:23:51',NULL,NULL),(5,'PEARL','A',4,'2019-07-12 18:01:20',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `msmemberships` */

insert  into `msmemberships`(`fin_rec_id`,`fst_member_no`,`fin_relation_id`,`fin_member_group_id`,`fst_name_on_card`,`fdt_expiry_date`,`fdc_member_discount_percent`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'12345abcd',1,1,'Testing','2019-05-13',2.00,'A',4,'2019-05-10 12:31:43',4,'2019-05-10 15:05:58'),(2,'123456abcds',15,2,'Naver1','2019-05-17',2.50,'A',4,'2019-05-10 13:32:30',4,'2019-05-21 16:52:02'),(3,'007Bond',16,3,'James Bonding','2019-05-15',5.00,'A',4,'2019-05-10 15:10:53',4,'2019-05-10 15:14:25'),(4,'008Bind',13,2,'Bindeng Banget','2019-05-17',2.00,'A',4,'2019-05-13 11:04:13',4,'2019-05-13 11:06:22'),(5,'ABCD23',16,1,'Tester','2019-05-28',1.50,'A',4,'2019-05-21 09:02:55',4,'2019-07-11 16:43:39'),(6,'E5430a',24,2,'Sri Wahyuni','2019-05-28',2.00,'A',4,'2019-05-21 09:43:16',4,'2019-07-11 16:22:25'),(7,'KomaxG470',16,3,'Bindeng','2019-05-27',5.00,'A',4,'2019-05-21 14:11:56',4,'2019-07-11 16:47:38'),(8,'V1.0.0',9,3,'Testing','2020-05-24',5.00,'A',4,'2019-05-21 15:58:44',4,'2019-07-11 15:32:09'),(9,'LAX-MRX302',16,3,'Cihuy Uhuy','2019-07-31',5.00,'A',4,'2019-07-11 16:45:45',4,'2019-07-11 18:21:02'),(10,'AIISC-19/DM',16,4,'Sarasvati','2019-07-24',2.00,'A',4,'2019-07-12 17:37:43',4,'2019-07-12 17:38:44');

/*Table structure for table `mspromo` */

DROP TABLE IF EXISTS `mspromo`;

CREATE TABLE `mspromo` (
  `fin_promo_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_list_branch_id` varchar(100) DEFAULT NULL COMMENT 'Multiselect branch yang ikut serta promo ini',
  `fst_promo_type` enum('POS','OFFICE','ALL') DEFAULT NULL,
  `fst_promo_name` varchar(100) DEFAULT NULL,
  `fdt_start` date DEFAULT NULL,
  `fdt_end` date DEFAULT NULL,
  `fbl_disc_per_item` tinyint(1) DEFAULT 0,
  `fin_promo_item_id` int(10) DEFAULT NULL,
  `fdb_promo_qty` double(12,2) DEFAULT NULL COMMENT 'bila ini di isi fdc_min_total_purchase harus 0',
  `fst_promo_unit` varchar(100) DEFAULT NULL COMMENT 'bila ini di isi fdc_min_total_purchase harus 0',
  `fdc_cashback` decimal(12,2) DEFAULT 0.00,
  `fst_other_prize` varchar(100) DEFAULT NULL,
  `fdc_other_prize_in_value` decimal(12,2) DEFAULT 0.00,
  `fbl_promo_gabungan` tinyint(1) DEFAULT NULL,
  `fbl_qty_gabungan` tinyint(1) DEFAULT NULL,
  `fdb_qty_gabungan` double(12,2) unsigned zerofill DEFAULT NULL,
  `fst_unit_gabungan` varchar(100) DEFAULT NULL,
  `fdc_min_total_purchase` decimal(12,2) DEFAULT 0.00 COMMENT 'bila ini di isi fin_promo_qty harus 0',
  `fbl_is_multiples_prize` tinyint(1) DEFAULT 0 COMMENT 'Untuk qty gabungan bila field ini true maka hadiah berlaku untuk kelipatan',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_promo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `mspromo` */

insert  into `mspromo`(`fin_promo_id`,`fst_list_branch_id`,`fst_promo_type`,`fst_promo_name`,`fdt_start`,`fdt_end`,`fbl_disc_per_item`,`fin_promo_item_id`,`fdb_promo_qty`,`fst_promo_unit`,`fdc_cashback`,`fst_other_prize`,`fdc_other_prize_in_value`,`fbl_promo_gabungan`,`fbl_qty_gabungan`,`fdb_qty_gabungan`,`fst_unit_gabungan`,`fdc_min_total_purchase`,`fbl_is_multiples_prize`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,NULL,'OFFICE','Testing Promo','2019-06-01','2019-07-31',0,3,3.12,'pack',0.00,'',0.00,1,0,000000001.00,'box',0.00,0,'A',1,'2019-06-25 22:39:16',1,'2019-07-10 19:20:18'),(2,NULL,'OFFICE','Testing Devi','2019-06-01','2019-07-31',0,3,3.12,'PACK',50000.00,'Payung Cantik',100000.00,1,0,000000040.00,'PACK',0.00,0,'A',1,'0000-00-00 00:00:00',NULL,NULL),(3,NULL,'OFFICE','PROMO JULI 2019','2019-07-01','2019-07-31',0,2,5.00,'PACK',0.00,'',0.00,NULL,0,000000100.00,'PACK',0.00,0,'A',1,'2019-07-10 19:32:10',NULL,NULL),(5,NULL,'OFFICE','ProMis','2019-07-15','2019-07-17',0,4,5.00,'KG',100000.00,'Test',100.00,NULL,0,NULL,'KG',100.00,0,'A',4,'2019-07-16 17:42:01',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

/*Data for the table `mspromoitems` */

insert  into `mspromoitems`(`fin_rec_id`,`fin_promo_id`,`fst_item_type`,`fin_item_id`,`fdb_qty`,`fst_unit`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (13,1,'ITEM',2,10.00,'pack','A',1,'2019-07-10 19:20:18',NULL,NULL),(14,1,'ITEM',1,10.00,'pack','A',1,'2019-07-10 19:20:18',NULL,NULL),(15,1,'ITEM',1,10.00,'PACK','A',1,'2019-07-10 19:20:18',NULL,NULL),(16,1,'SUB GROUP',3,120.45,'PACK','A',1,'2019-07-10 19:20:18',NULL,NULL),(17,3,'SUB GROUP',2,10.00,'PACK','A',1,'2019-07-10 19:32:10',NULL,NULL),(18,3,'ITEM',1,10.00,'PACK','A',1,'2019-07-10 19:32:10',NULL,NULL),(19,2,'ITEM',1,10.00,'PACK','A',1,'2019-07-11 12:36:58',NULL,NULL),(20,2,'ITEM',2,20.00,'PACK','A',1,'2019-07-11 12:37:23',NULL,NULL),(21,5,'ITEM',4,100.00,'KG','A',4,'2019-07-16 17:42:01',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

/*Data for the table `mspromoitemscustomer` */

insert  into `mspromoitemscustomer`(`fin_id`,`fin_promo_id`,`fst_participant_type`,`fin_customer_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (11,1,'RELATION',2,'A',1,'2019-07-10 19:20:19',NULL,NULL),(12,1,'MEMBER GROUP',1,'A',1,'2019-07-10 19:20:19',NULL,NULL),(13,1,'RELATION GROUP',1,'A',1,'2019-07-10 19:20:19',NULL,NULL),(14,3,'RELATION',13,NULL,1,'2019-07-10 19:32:10',NULL,NULL),(15,3,'RELATION',16,NULL,1,'2019-07-10 19:32:10',NULL,NULL),(16,3,'RELATION GROUP',1,NULL,1,'2019-07-10 19:32:10',NULL,NULL),(17,5,'RELATION',13,NULL,4,'2019-07-16 17:42:01',NULL,NULL),(18,5,'MEMBER GROUP',3,NULL,4,'2019-07-16 17:42:02',NULL,NULL),(19,5,'RELATION GROUP',6,NULL,4,'2019-07-16 17:42:02',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `msrelationgroups` */

insert  into `msrelationgroups`(`fin_relation_group_id`,`fst_relation_group_name`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'Customer','A',1,'2019-05-02 17:22:40',1,'2019-05-03 15:15:53'),(2,'Supplier/Vendor1','A',1,'2019-05-02 17:45:28',4,'2019-05-10 09:52:33'),(3,'Ekspedisi1','A',1,'2019-05-02 17:45:40',4,'2019-05-10 10:25:30'),(4,'Total1','A',4,'2019-05-03 09:36:27',4,'2019-05-03 09:45:34'),(5,'Dropshipper','A',4,'2019-05-21 16:22:01',NULL,NULL),(6,'HOKBEN','A',4,'2019-07-15 09:04:40',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

/*Data for the table `msrelations` */

insert  into `msrelations`(`fin_relation_id`,`fin_branch_id`,`fin_relation_group_id`,`fst_relation_type`,`fin_parent_id`,`fst_business_type`,`fin_sales_area_id`,`fin_sales_id`,`fst_relation_name`,`fst_gender`,`fdt_birth_date`,`fst_birth_place`,`fst_address`,`fst_phone`,`fst_fax`,`fst_postal_code`,`fin_country_id`,`fst_area_code`,`fin_cust_pricing_group_id`,`fdc_credit_limit`,`fst_nik`,`fst_npwp`,`fst_relation_notes`,`fin_warehouse_id`,`fin_terms_payment`,`fin_top_komisi`,`fin_top_plus_komisi`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,1,2,'2',0,'P',1,11,'Testing','M','1981-06-13','Jakarta','Jakarta','0812 8888 8888','0812 8888 8888','12340',1,'31.75.01.1003',1,30000000.00,'360311234500004','1234567890','Test Notes',3,12,10,8,'A',4,'2019-05-08 12:24:49',4,'2019-07-11 11:27:22'),(13,1,2,'1,2',20,'P',1,11,'Mocca','M','1981-06-13','Jakarta','Tangerang','0819 9999 000','0819 9999 000','15560',1,'31.72.02.1002',4,100000000.00,'','123456789015','Uji Coba Ke-4',3,20,12,8,'A',4,'2019-05-09 09:31:18',4,'2019-07-15 15:55:29'),(16,1,3,'2',NULL,'C',2,11,'Minions','','2019-07-11','','Jakarta','0818 8888 0909','0818 8888 090','12430',1,'31.75.01.1002',3,4000000000.00,'360311128800006','12345678901413','Test Notes\r\nCoba Test Lagi\r\n',3,14,12,8,'A',4,'2019-05-10 09:15:52',4,'2019-07-11 13:36:42'),(19,1,3,'2,3',NULL,'C',3,11,'Mocca','','2019-07-11','','Tangerang','0817 8888 990','0817 8888 990','15540',1,'31.75.02.1004',5,2500000000.00,'','123456789015','Coba Test Lagi\r\n',1,10,8,6,'A',4,'2019-05-14 12:22:19',4,'2019-07-11 13:40:31'),(20,1,1,'1,2',28,'P',2,16,'Ummay','F','1980-07-10','Jakarta','Kebayoran Baru','0817 0089 922','0817 0089 922','12340',1,'31.72.01.1001',2,5000000000.00,'','123456789014ac','Test Notes\r\nTest Notes\r\nTest Notes\r\nTest Notes\r\n',3,12,10,8,'A',4,'2019-05-15 13:25:35',4,'2019-07-15 15:42:50'),(21,1,1,'1,3',26,'P',1,11,'Mocca','F','1980-07-17','Jakarta','Tangerang','0818 8888 123','0818 8888 123','15540',1,'36.03.12.2001',3,75000000.00,'','123456789013156','Test Notes\r\nTest Notes\r\nUji Coba Ke-4\r\n',3,12,10,8,'A',4,'2019-05-15 14:35:48',4,'2019-07-15 16:48:45'),(23,1,1,'1,2,3',13,'C',1,11,'Coba Lagi2','','1970-01-01','','Bogor','','','12430',1,'13.05.05.2002',1,750000000.00,'','1234567890654','Coba Test Lagi\r\n',3,14,12,8,'A',4,'2019-05-15 14:51:16',4,'2019-07-15 15:44:11'),(25,1,2,'2,3',NULL,'C',2,11,'Mocca1','','1970-01-01','','Tangerang','0813 1212 0098','0813 1212 0098','15540',1,'31.72.02.1001',0,300000000.00,'','1234567890180','Coba Test Lagi\r\nTest Notes\r\nTest Lagi\r\n',3,12,10,8,'A',4,'2019-05-21 09:08:26',4,'2019-07-11 13:17:24'),(26,1,1,'1',26,'P',1,11,'Nano Nani','F','1980-07-17','Jakarta','DEPOK','0818 8888 1010','0818 8888 1010','14450',1,'31.74.07.1001',2,20000000.00,'360311234567890','12345678901001','Test Notes\r\nCoba Test Lagi\r\n',3,12,10,8,'A',4,'2019-06-10 13:52:05',4,'2019-07-15 15:40:53'),(28,1,1,'1',26,'C',1,11,'Minion','','1970-01-01','','Jakarta','0818 8888 0909','0818 8888 0909','12430',1,'31.74.07.1001',1,600000000.00,'','123456789013','Uji Coba Ke-4\r\n',3,12,10,8,'A',4,'2019-07-11 13:11:07',4,'2019-07-15 15:39:26');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `mssalesarea` */

insert  into `mssalesarea`(`fin_sales_area_id`,`fst_name`,`fin_sales_regional_id`,`fin_sales_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'JAKARTA SELATAN',4,15,'A',0,'2019-07-06 17:49:17',4,'2019-07-16 16:33:38'),(2,'BOGOR',1,14,'A',4,'2019-07-08 18:44:05',4,'2019-07-11 18:28:10'),(3,'SEMARANG',2,16,'A',4,'2019-07-08 18:58:48',4,'2019-07-11 18:28:25'),(4,'SURABAYA',3,13,'A',4,'2019-07-16 16:35:26',4,'2019-07-16 16:36:20');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `msshippingaddress` */

insert  into `msshippingaddress`(`fin_shipping_address_id`,`fst_name`,`fin_relation_id`,`fst_area_code`,`fst_shipping_address`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'Home',20,NULL,'Perum Puri Permata Blok B1 No 178\r\nCipondoh Makmur, Cipondoh\r\nTangerang','A',1,'2019-07-20 13:26:14',NULL,NULL),(2,'Office',20,NULL,'Apartemen Robinson, Jl. Jemb. Dua Raya No.2, RT.1/RW.4, Pejagalan, \r\nKec. Penjaringan, Kota Jkt Utara, \r\nDaerah Khusus Ibukota Jakarta \r\n14450',NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `mssubgroupitems` */

DROP TABLE IF EXISTS `mssubgroupitems`;

CREATE TABLE `mssubgroupitems` (
  `fin_item_subgroup_id` int(10) NOT NULL AUTO_INCREMENT,
  `fst_item_subgroup_name` varchar(100) NOT NULL,
  `fin_item_group_id` int(10) NOT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_item_subgroup_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `mssubgroupitems` */

insert  into `mssubgroupitems`(`fin_item_subgroup_id`,`fst_item_subgroup_name`,`fin_item_group_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'PENCIL',2,'A',0,'0000-00-00 00:00:00',1,'2019-05-13 14:20:11'),(2,'CRAYON',1,'A',0,'0000-00-00 00:00:00',NULL,NULL),(3,'FANCY',2,'A',0,'0000-00-00 00:00:00',NULL,NULL),(4,'PROMO SBY',3,'A',1,'2019-05-09 09:54:47',1,'2019-05-13 12:34:57'),(5,'PROMO JKT',3,'A',1,'2019-05-09 09:55:04',1,'2019-05-13 14:19:52'),(6,'PROMO INDONESIA MERDEKA',3,'A',4,'2019-07-16 18:02:57',4,'2019-07-16 18:04:20');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `msunits` */

insert  into `msunits`(`fin_rec_id`,`fst_unit`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'KG','A',0,'0000-00-00 00:00:00',1,'2019-05-08 20:36:21'),(2,'PCS','A',0,'0000-00-00 00:00:00',1,'2019-05-08 20:37:44'),(3,'SET','A',1,'2019-05-08 20:37:34',NULL,NULL),(4,'PACK','A',1,'2019-07-08 18:20:44',NULL,NULL),(5,'BOX','A',1,'2019-07-08 18:20:55',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `msverification` */

insert  into `msverification`(`fin_rec_id`,`fst_controller`,`fst_verification_type`,`fin_department_id`,`fin_user_group_id`,`fin_seqno`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,'SO','CreditLimit',2,3,0,'A',1,'2019-07-07 06:33:07',NULL,NULL),(2,'SO','QtyOutStock',2,3,0,'A',1,'2019-07-11 19:47:03',NULL,NULL);

/*Table structure for table `mswarehouse` */

DROP TABLE IF EXISTS `mswarehouse`;

CREATE TABLE `mswarehouse` (
  `fin_warehouse_id` int(5) NOT NULL AUTO_INCREMENT,
  `fin_branch_id` int(5) DEFAULT NULL,
  `fst_warehouse_name` varchar(100) DEFAULT NULL,
  `fbl_is_external` tinyint(1) DEFAULT NULL COMMENT 'Apakah Gudang External? Gudang External adalah gudang titipan customer, tidak masuk sebagai aset perusahaan',
  `fbl_is_main` tinyint(1) DEFAULT NULL COMMENT 'Gudang Utama (gudang default)',
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_warehouse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `mswarehouse` */

insert  into `mswarehouse`(`fin_warehouse_id`,`fin_branch_id`,`fst_warehouse_name`,`fbl_is_external`,`fbl_is_main`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,1,'Test',1,1,'A',4,'2019-06-10 13:43:49',NULL,NULL),(3,1,'Chocohouse',0,1,'A',4,'2019-06-11 17:07:51',NULL,NULL),(4,3,'Vanila Skies',1,NULL,'A',4,'2019-07-16 15:29:22',4,'2019-07-16 15:30:18');

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
  `fin_item_id` int(11) DEFAULT NULL,
  `fst_notes` text DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_preorder_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `preorder` */

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `preorderbranchdetails` */

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
  `fdb_qty_in` double(12,2) DEFAULT NULL,
  `fdb_qty_out` double(12,2) DEFAULT NULL,
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
  `fin_branch_id` int(11) DEFAULT NULL,
  `fst_salesorder_no` varchar(20) DEFAULT NULL COMMENT 'FORMAT: XXXYYMM/99999, XXX=Prefix Transaksi (taruh di _Config), YY=TAHUN, MM=BULAN, 99999=Urutan Nomor transaksi (bisa per-tahun, bisa per-bulan, tergantung di_config)',
  `fdt_salesorder_date` date DEFAULT NULL,
  `fin_relation_id` int(11) DEFAULT NULL COMMENT 'hanya bisa pilih RelationType = Customer"',
  `fin_terms_payment` int(5) DEFAULT NULL COMMENT 'term pembayaran by default dari data relation',
  `fin_warehouse_id` int(11) DEFAULT NULL,
  `fin_sales_id` int(11) DEFAULT NULL COMMENT 'Ambil dari master user, dengan kode departement sesuai _Config ("SLS"), cukup salah satu dari 3 field ini yg harus diisi, sales itu level line worker, sales superviser itu Supervisor, sales manager itu middle management',
  `fst_curr_code` varchar(10) DEFAULT NULL,
  `fdc_exchange_rate_idr` decimal(12,2) DEFAULT NULL,
  `fin_shipping_address_id` int(11) DEFAULT NULL,
  `fst_memo` text DEFAULT NULL,
  `fbl_is_hold` tinyint(1) DEFAULT 0 COMMENT 'Sales Order di hold sementara (tidak bisa di proses lebih lanjut)',
  `fin_unhold_id` int(11) DEFAULT NULL COMMENT 'User yang melakukan unhold',
  `fdt_unhold_datetime` datetime DEFAULT NULL,
  `fbl_is_vat_include` bit(1) DEFAULT b'1' COMMENT 'Apakah harga sudah termasuk pajak, jika iya, maka PPN di hitung dari DPP (karna subtotal sudah trmsk PPn)',
  `fdc_dpp_amount` decimal(12,2) DEFAULT NULL COMMENT 'Dasar Pengenaan Pajak',
  `fdc_vat_percent` decimal(5,2) DEFAULT NULL,
  `fdc_vat_amount` decimal(5,2) DEFAULT NULL,
  `fdc_disc_percent` decimal(5,2) DEFAULT NULL,
  `fdc_disc_amount` decimal(5,2) DEFAULT NULL,
  `fdc_downpayment` decimal(12,2) DEFAULT 0.00,
  `fdc_downpayment_paid` decimal(12,2) DEFAULT 0.00,
  `fbl_is_closed` tinyint(1) DEFAULT NULL,
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_salesorder_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

/*Data for the table `trsalesorder` */

insert  into `trsalesorder`(`fin_salesorder_id`,`fin_branch_id`,`fst_salesorder_no`,`fdt_salesorder_date`,`fin_relation_id`,`fin_terms_payment`,`fin_warehouse_id`,`fin_sales_id`,`fst_curr_code`,`fdc_exchange_rate_idr`,`fin_shipping_address_id`,`fst_memo`,`fbl_is_hold`,`fin_unhold_id`,`fdt_unhold_datetime`,`fbl_is_vat_include`,`fdc_dpp_amount`,`fdc_vat_percent`,`fdc_vat_amount`,`fdc_disc_percent`,`fdc_disc_amount`,`fdc_downpayment`,`fdc_downpayment_paid`,`fbl_is_closed`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (18,NULL,'SLS1905/00001','2019-05-31',9,NULL,3,11,NULL,NULL,NULL,'delivery',NULL,1,'2019-07-11 17:05:44','',NULL,2.00,0.00,NULL,NULL,NULL,NULL,0,'A',4,'2019-05-31 12:21:55',4,'2019-07-11 17:10:34'),(19,NULL,'SLS1905/00002','2019-05-31',1,NULL,1,11,NULL,NULL,NULL,'delivery',1,NULL,NULL,'',NULL,2.00,194.00,NULL,NULL,NULL,NULL,0,'A',4,'2019-05-31 12:22:23',4,'2019-07-11 17:16:57'),(20,NULL,'SLS1905/00003','2019-05-31',16,NULL,3,13,NULL,NULL,NULL,'Pre Order',NULL,NULL,NULL,'',NULL,2.50,907.65,NULL,NULL,NULL,NULL,0,'A',NULL,NULL,4,'2019-07-11 17:12:50'),(21,NULL,'SLS1905/00004','2019-05-31',13,NULL,3,11,NULL,NULL,NULL,'Tambahan Testing Ulang',NULL,NULL,NULL,'',NULL,2.00,453.00,NULL,NULL,NULL,NULL,0,'A',4,'2019-05-31 12:23:42',4,'2019-07-11 18:01:38'),(22,NULL,'SLS1905/00007','2019-05-31',24,NULL,0,9,NULL,NULL,NULL,'delivery aaa',1,NULL,NULL,'\0',NULL,0.00,20.00,2.00,0.00,NULL,NULL,0,'A',4,'2019-05-31 12:49:32',NULL,NULL),(23,NULL,'SLS1905/00008','2019-05-31',2,NULL,0,9,NULL,NULL,NULL,'delivery',NULL,NULL,NULL,'',NULL,2.00,0.00,0.00,300.00,NULL,NULL,0,'A',4,'2019-05-31 12:55:14',4,'2019-05-31 13:47:48'),(24,NULL,'SO1906/00001','2019-06-30',15,NULL,0,5,NULL,NULL,NULL,'delivery abc',0,NULL,NULL,'',NULL,2.00,0.00,0.00,20.00,NULL,NULL,0,'A',4,'2019-06-30 13:40:50',NULL,NULL),(30,NULL,'SO1907/00001','2019-07-15',20,12,3,16,'IDR',1.00,NULL,'',0,NULL,NULL,'\0',6800.00,10.00,680.00,NULL,0.00,480.00,0.00,0,'S',12,'2019-07-15 15:24:10',NULL,NULL);

/*Table structure for table `trsalesorderdetails` */

DROP TABLE IF EXISTS `trsalesorderdetails`;

CREATE TABLE `trsalesorderdetails` (
  `fin_rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `fin_salesorder_id` int(11) DEFAULT NULL COMMENT 'ref: > trsalesorder.fin_salesorder_id',
  `fin_item_id` int(11) DEFAULT NULL COMMENT 'ref: > msitems.ItemId',
  `fst_custom_item_name` varchar(100) DEFAULT NULL,
  `fst_unit` varchar(100) DEFAULT NULL,
  `fdb_qty` double(12,2) DEFAULT NULL,
  `fdb_qty_out` double(12,2) DEFAULT 0.00 COMMENT 'Jumlah Qty yang sudah dibuat surat jalannya',
  `fdc_price` decimal(12,2) DEFAULT NULL,
  `fst_disc_item` varchar(100) DEFAULT NULL COMMENT 'Discount Item bertingkat berupa string, misal 10+5+2',
  `fdc_disc_amount` decimal(12,2) DEFAULT NULL,
  `fbl_is_promo_disc` tinyint(1) DEFAULT 0 COMMENT 'Bila transaksi mendapat promo disc per item di isi true',
  `fst_memo_item` text DEFAULT NULL,
  `fin_promo_id` int(11) DEFAULT NULL COMMENT 'Bila terisi merupakan item promo',
  `fst_active` enum('A','S','D') DEFAULT NULL,
  `fin_insert_id` int(11) DEFAULT NULL,
  `fdt_insert_datetime` datetime DEFAULT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `trsalesorderdetails` */

insert  into `trsalesorderdetails`(`fin_rec_id`,`fin_salesorder_id`,`fin_item_id`,`fst_custom_item_name`,`fst_unit`,`fdb_qty`,`fdb_qty_out`,`fdc_price`,`fst_disc_item`,`fdc_disc_amount`,`fbl_is_promo_disc`,`fst_memo_item`,`fin_promo_id`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (1,18,1,NULL,NULL,2.00,NULL,200.00,'',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(14,30,2,'Silver Queen','PACK',20.00,NULL,140.00,'0',0.00,0,'',0,'A',12,'2019-07-15 15:24:10',NULL,NULL),(15,30,1,'Testing','PACK',20.00,NULL,200.00,'0',0.00,0,'',0,'A',12,'2019-07-15 15:24:11',NULL,NULL),(16,30,2,'Silver Queen','PACK',5.00,NULL,1.00,'100',5.00,0,'',3,'A',12,'2019-07-15 15:24:11',NULL,NULL);

/*Table structure for table `trsuratjalan` */

DROP TABLE IF EXISTS `trsuratjalan`;

CREATE TABLE `trsuratjalan` (
  `fin_sj_id` int(11) NOT NULL AUTO_INCREMENT,
  `fst_sj_no` varchar(20) DEFAULT NULL,
  `fdt_sj_date` datetime DEFAULT NULL,
  `fin_salesorder_id` int(11) DEFAULT NULL,
  `fin_warehouse_id` int(11) DEFAULT NULL,
  `fst_no_polisi` varchar(10) DEFAULT NULL,
  `fin_driver_id` int(10) DEFAULT NULL COMMENT 'Ambil dari master user departement driver',
  `fin_shipping_address_id` int(11) DEFAULT NULL,
  `fst_del_add` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `fst_no_reff` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `fst_sj_memo` text DEFAULT NULL,
  `fin_print_no` int(4) DEFAULT NULL,
  `fst_sj_time` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
  `fbl_hold` bit(1) DEFAULT NULL,
  `fin_unhold_id` int(11) DEFAULT NULL,
  `fdt_sj_return` datetime DEFAULT NULL,
  `fst_sj_return_resi_no` varchar(20) DEFAULT NULL,
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

/*Table structure for table `trsuratjalandetails` */

DROP TABLE IF EXISTS `trsuratjalandetails`;

CREATE TABLE `trsuratjalandetails` (
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

/*Data for the table `trsuratjalandetails` */

/*Table structure for table `trverification` */

DROP TABLE IF EXISTS `trverification`;

CREATE TABLE `trverification` (
  `fin_rec_id` bigint(20) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `trverification` */

insert  into `trverification`(`fin_rec_id`,`fst_controller`,`fin_transaction_id`,`fin_seqno`,`fst_message`,`fin_department_id`,`fin_user_group_id`,`fst_verification_status`,`fst_notes`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (3,'SO',30,0,'Item for Sales Order SO1907/00001 Out of stock',2,3,'VF','3','D',12,'2019-07-15 15:24:11',NULL,NULL),(4,'SO',30,0,'Sales Order SO1907/00001 Customer credit limit is reached',2,3,'VF','3','D',12,'2019-07-15 15:24:11',NULL,NULL);

/*Table structure for table `trvoucher` */

DROP TABLE IF EXISTS `trvoucher`;

CREATE TABLE `trvoucher` (
  `fin_rec_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fst_transaction_type` enum('SALESORDER') DEFAULT NULL,
  `fin_transaction_id` int(11) DEFAULT NULL,
  `fin_promo_id` int(11) DEFAULT NULL,
  `fin_branch_id` int(11) DEFAULT NULL COMMENT 'bila kosong, maka voucher bisa digunakan oleh setiap branch',
  `fin_relation_id` int(11) DEFAULT NULL COMMENT 'bila kosong, maka voucher bisa digunakan oleh siapa saja',
  `fst_voucher_code` varchar(100) DEFAULT NULL COMMENT 'optional, voucher digunakan dengan memasukan kode voucher',
  `fdc_disc_percent` decimal(12,2) DEFAULT NULL COMMENT 'Voucher berbentuk disc',
  `fdc_value` decimal(12,2) DEFAULT NULL COMMENT 'voucher berbentuk potongan harga',
  `fbl_is_used` tinyint(1) DEFAULT 0 COMMENT 'flag menunjukan apakah voucher ini telah di gunakan',
  `fdt_used_datetime` datetime DEFAULT NULL,
  `fin_used_transaction_id` int(11) DEFAULT NULL COMMENT 'id transaction yang memakian voucher ini',
  `fst_memo` text DEFAULT NULL,
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`fin_rec_id`),
  KEY `fin_rec_id` (`fin_rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `trvoucher` */

insert  into `trvoucher`(`fin_rec_id`,`fst_transaction_type`,`fin_transaction_id`,`fin_promo_id`,`fin_branch_id`,`fin_relation_id`,`fst_voucher_code`,`fdc_disc_percent`,`fdc_value`,`fbl_is_used`,`fdt_used_datetime`,`fin_used_transaction_id`,`fst_memo`,`fst_active`,`fin_insert_id`,`fdt_insert_datetime`,`fin_update_id`,`fdt_update_datetime`) values (2,'SALESORDER',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'A',12,'2019-07-19 15:21:57',NULL,NULL),(3,'SALESORDER',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'A',12,'2019-07-19 17:17:05',NULL,NULL);

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

insert  into `users`(`fin_user_id`,`fst_username`,`fst_password`,`fst_fullname`,`fst_gender`,`fdt_birthdate`,`fst_birthplace`,`fst_address`,`fst_phone`,`fst_email`,`fin_branch_id`,`fin_department_id`,`fin_group_id`,`fbl_admin`,`fst_active`,`fdt_insert_datetime`,`fin_insert_id`,`fdt_update_datetime`,`fin_update_id`) values (4,'enny06','c50e5b88116a073a72aea201b96bfe8e','Enny Nuraini','F','1979-10-06','Jakarta','Tangerang','08128042742','enny06@yahoo.com',1,0,2,1,'A','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0),(5,'udin123','3af4c9341e31bce1f4262a326285170d','Udin Sedunia','F','1980-06-12','Makasar','Depok','087772721096','udin123@yahoo.com',1,3,1,1,'A','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0),(9,'dimpi80','4aba2f8cbc594d39020a0187f1331670','Dimas Widiastuti','F','1980-09-18','Depok','Depok','081380804521','dimpi80@yahoo.com',1,3,1,0,'A','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0),(11,'anne80','4a094e453e6ee6a8253def63db4d1509','Annie Emma Limahelu','F','1970-01-01','Jakarta','Jatiasih, Bekasi','0813 4562 9825','anne80@yahoo.com',1,1,NULL,1,'A','2019-05-21 10:23:26',4,'2019-05-21 10:25:17',4),(12,'devibong@yahoo.com','06a6077b0cfcb0f4890fb5f2543c43be','Devi Bastian','M','1978-08-26','Pematang Siantar',NULL,NULL,'devibong@yahoo.com',1,0,NULL,0,'A','0000-00-00 00:00:00',0,'0000-00-00 00:00:00',0),(13,'sales1','06a6077b0cfcb0f4890fb5f2543c43be','Sales No 1 National','M','1989-07-17','Jakarta',NULL,NULL,NULL,1,2,1,0,'A','2019-07-06 17:37:42',0,'0000-00-00 00:00:00',0),(14,'sales2','06a6077b0cfcb0f4890fb5f2543c43be','Sales No 2 Regional','M','1989-07-17','Jakarta',NULL,NULL,NULL,1,2,1,0,'A','2019-07-06 17:39:31',0,'0000-00-00 00:00:00',0),(15,'sales3','06a6077b0cfcb0f4890fb5f2543c43be','Sales No 3 Area','M','1989-07-17','Jakarta',NULL,NULL,NULL,1,2,1,0,'A','2019-07-06 17:39:31',0,'0000-00-00 00:00:00',0),(16,'sales4','06a6077b0cfcb0f4890fb5f2543c43be','Sales No 4 biasa','M','1989-07-17','Jakarta',NULL,NULL,NULL,1,2,1,0,'A','2019-07-06 17:39:31',0,'0000-00-00 00:00:00',0);

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