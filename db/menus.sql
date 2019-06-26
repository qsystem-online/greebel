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
  `fst_link` text,
  `fin_parent_id` int(11) NOT NULL,
  `fbl_active` tinyint(1) NOT NULL,
  UNIQUE KEY `fin_id` (`fin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

/*Data for the table `menus` */

insert  into `menus`(`fin_id`,`fin_order`,`fst_order`,`fst_menu_name`,`fst_caption`,`fst_icon`,`fst_type`,`fst_link`,`fin_parent_id`,`fbl_active`) values (1,1,'10','dashboard','Dashboard','<i class=\"fa fa-dashboard\"></i>','TREEVIEW','welcome/advanced_element',0,1),(2,2,'20','master','Master Data','','HEADER',NULL,0,1),(23,0,'20.10','master_accounting','Master Accounting','','TREEVIEW',NULL,0,1),(24,0,'20.10.10','gl_account','GL Account','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','gl/GLAccounts',23,1),(25,0,'20.20','master_operasional','Master Operasional','','TREEVIEW',NULL,0,1),(26,0,'20.20.10','master_item','Barang Dagangan','','TREEVIEW',NULL,25,1),(27,0,'20.20.10.10','item_group','Items Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/msgroupitems\r\n',26,1),(28,0,'20.20.10.20','item_main_group','Main Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/msmaingroupitems',26,1),(29,0,'20.20.10.30','item_sub_group','Sub Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/mssubgroupitems',26,1),(30,0,'20.20.10.40','items','Items','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/msitems',26,1),(31,0,'20.20.20','master_currency','Kurs / Mata Uang','','TREEVIEW','master/currency',25,1),(32,0,'20.20.30','relation','Relations','','TREEVIEW',NULL,25,1),(33,0,'20.20.30.10','relation_group','Groups','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/msrelationgroups',32,1),(34,0,'20.20.30.20','relation_customer_vendor','Customer / Vendor','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/msrelations',32,1),(35,0,'20.20.30.30','membership','Membership','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/msmemberships',32,1),(36,0,'20.20.40','warehouse','Gudang','','TREEVIEW','master/mswarehouse',25,1),(37,0,'20.20.50','pricing','Master Prices','','TREEVIEW',NULL,25,1),(38,0,'20.20.50.10','pricing_group','Pricing Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/mscustpricinggroups',37,1),(39,0,'20.20.50.20','prices','Prices','<i class=\"fa fa-circle-o\"></i>','TREEVIEW',NULL,37,1),(40,0,'20.20.50.30','discount','Discount','<i class=\"fa fa-circle-o\"></i>','TREEVIEW',NULL,37,1),(41,0,'20.30','master_system','Master System','','TREEVIEW',NULL,0,1),(42,0,'20.20.60','promo','Master Promo','','TREEVIEW',NULL,25,1),(43,0,'20.30.10','branch','Cabang','','TREEVIEW',NULL,41,1),(44,0,'20.30.20','department','Departemen','','TREEVIEW',NULL,41,1),(45,0,'20.30.30','level','Jabatan','','TREEVIEW',NULL,41,1),(46,0,'20.30.40','user','user','','TREEVIEW',NULL,41,1),(47,0,'30','transaction','Transaksi','','HEADER',NULL,0,1),(48,0,'30.10','purchase','Pembelian','','TREEVIEW',NULL,0,1),(49,0,'30.20','sales','Penjualan','','TREEVIEW',NULL,0,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
