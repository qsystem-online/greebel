/*
SQLyog Ultimate v10.42 
MySQL - 5.5.5-10.1.35-MariaDB : Database - db_greebel
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_greebel` /*!40100 DEFAULT CHARACTER SET latin1 */;

/*Table structure for table `glaccountgroups` */

DROP TABLE IF EXISTS `glaccountgroups`;

CREATE TABLE `glaccountgroups` (
  `GLAccountGroupId` int(10) NOT NULL AUTO_INCREMENT,
  `GLAccountMainGroupId` int(10) DEFAULT NULL,
  `GLAccountGroupName` varchar(100) DEFAULT NULL,
  `DefaultPost` enum('D','C') DEFAULT NULL,
  `Type` enum('BS','PL') DEFAULT NULL COMMENT 'Pilihan BS (BalanceSheet), PL (ProfitLoss Statement)',
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
  `GLAccountGroupId` int(10) NOT NULL,
  `GLAccountName` varchar(256) NOT NULL,
  `GLAccountLevel` enum('HD','DT') NOT NULL COMMENT 'Pilihan HD(Header). DT(Detail), DK(DetailKasBank)',
  `ParentGLAccountCode` varchar(100) DEFAULT NULL COMMENT 'Rekening Induk (hanya perlu diisi jika GLAccountLevel = Detail atau Detail Kasbank',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `mscountries` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `mscustpricinggroups` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msdistricts` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msitemdiscounts` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msitems` */

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
  `LastBuyingPrice` decimal(12,2) NOT NULL DEFAULT '0.00',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msmemberships` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msprovinces` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msrelationgroups` */

/*Table structure for table `msrelationprintoutnotes` */

DROP TABLE IF EXISTS `msrelationprintoutnotes`;

CREATE TABLE `msrelationprintoutnotes` (
  `RelationNoteId` int(5) NOT NULL AUTO_INCREMENT,
  `Notes` text,
  `PrintOut` varchar(100) DEFAULT NULL COMMENT 'SJ, FAKTUR, PO',
  `fst_active` enum('A','S','D') NOT NULL,
  `fin_insert_id` int(11) NOT NULL,
  `fdt_insert_datetime` datetime NOT NULL,
  `fin_update_id` int(11) DEFAULT NULL,
  `fdt_update_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`RelationNoteId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msrelationprintoutnotes` */

/*Table structure for table `msrelations` */

DROP TABLE IF EXISTS `msrelations`;

CREATE TABLE `msrelations` (
  `RelationId` int(5) NOT NULL AUTO_INCREMENT,
  `RelationGroupId` int(5) DEFAULT NULL,
  `RelationType` varchar(100) DEFAULT NULL COMMENT '1=Customer, 2=Supplier/Vendor, 3=Expedisi (boleh pilih lebih dari satu, simpan sebagai string dengan comma), Customer,Supplier/Vendor dan Expedisi define sebagai array di Constanta system supaya suatu saat bisa ditambah',
  `RelationName` varchar(256) DEFAULT NULL,
  `Address` text,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `msrelations` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `mssubdistricts` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
