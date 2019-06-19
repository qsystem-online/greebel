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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

/*Data for the table `menus` */

insert  into `menus`(`fin_id`,`fin_order`,`fst_order`,`fst_menu_name`,`fst_caption`,`fst_icon`,`fst_type`,`fst_link`,`fin_parent_id`,`fbl_active`) values (1,1,'1','dashboard','Dashboard','<i class=\"fa fa-dashboard\"></i>','TREEVIEW','welcome/advanced_element',0,1),(2,2,'2','master','Master','','HEADER',NULL,0,1),(3,3,'2.3','relation','Relations','<i class=\"fa fa-circle-o\"></i>','TREEVIEW',NULL,0,1),(4,20,'2.3.2','customer_vendor','Customer / Vendor','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/msrelations',3,1),(5,10,'2.3.1','relation_group','Relation Groups','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','pr/msrelationgroups',3,1),(6,30,'2.3.3','membership','Membership','<i class=\"fa fa-files-o\"></i>','TREEVIEW','pr/msmemberships',3,1),(7,10,'2.5.1','pricing_group','Pricing Group','<i class=\"fa fa-edit\"></i>','TREEVIEW','pr/mscustpricinggroups',11,1),(8,4,'2.4','items','Items','<i class=\"fa fa-edit\"></i>','TREEVIEW',NULL,0,1),(9,10,'2.4.1','item_group','Items Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/msgroupitems',8,1),(10,50,'2.4.5','items','Items','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/msitems',8,1),(11,5,'2.5','prices','Prices','<i class=\"fa fa-edit\"></i>','TREEVIEW',NULL,0,1),(12,30,'2.4.3','item_main_group','Main Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/msmaingroupitems',8,1),(13,40,'2.4.4','item_sub_group','Sub Group','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/mssubgroupitems',8,1),(14,6,'2.6','branch','Branch','<i class=\"fa fa-circle-o\"></i>','TREEVIEW',NULL,0,1),(15,10,'2.6.1','branch','Branch','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/msbranches',14,1),(16,20,'2.6.2','warehouse','Warehouse','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/mswarehouse',14,1),(17,0,'5','accounting','Accounting','','HEADER',NULL,0,1),(18,0,'5.1','gl_account','GL Account','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','gl/GLAccounts',0,1),(19,0,'2.7','currency','Currency','<i class=\"fa fa-circle-o\"></i>','TREEVIEW',NULL,0,1),(20,0,'2.7.1','currency','Currency','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/currency',19,1),(21,0,'2.7.2','currency','Currency Exchange','<i class=\"fa fa-circle-o\"></i>','TREEVIEW',NULL,19,1),(22,0,'2.4.6','discount','Discount','<i class=\"fa fa-circle-o\"></i>','TREEVIEW','master/msitems/discount',8,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
