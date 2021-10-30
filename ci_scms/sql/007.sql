/*
SQLyog Community v12.09 (64 bit)
MySQL - 5.6.17 : Database - v
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `avxg_users` */

DROP TABLE IF EXISTS `avxg_users`;

CREATE TABLE `avxg_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL,
  `password` text,
  `email` text NOT NULL,
  `last_login` text,
  `ip` text,
  `session_id` text,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `ci_sessions` */

DROP TABLE IF EXISTS `ci_sessions`;

CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `content_gallery` */

DROP TABLE IF EXISTS `content_gallery`;

CREATE TABLE `content_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `link` text,
  `description` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `content_multi_gallery` */

DROP TABLE IF EXISTS `content_multi_gallery`;

CREATE TABLE `content_multi_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `link` text NOT NULL,
  `description` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `content_text` */

DROP TABLE IF EXISTS `content_text`;

CREATE TABLE `content_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text,
  `text_noformat` text,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `content_text` */

/*Table structure for table `gallery_data` */

DROP TABLE IF EXISTS `gallery_data`;

CREATE TABLE `gallery_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(11) NOT NULL,
  `name` text,
  `link` text NOT NULL,
  `description` text,
  `img` text NOT NULL,
  `thumb` text NOT NULL,
  `orig_img` text,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `gallery_data` */

/*Table structure for table `menu` */

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `link` text NOT NULL,
  `order` int(11) NOT NULL,
  `double` tinyint(1) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `menu` */

/*Table structure for table `multi_gallery_data` */

DROP TABLE IF EXISTS `multi_gallery_data`;

CREATE TABLE `multi_gallery_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folderid` int(11) NOT NULL,
  `name` text,
  `link` text NOT NULL,
  `description` text,
  `thumb` text,
  `mid` text,
  `big` text,
  `orig` text,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `multi_gallery_data` */


/*Table structure for table `multi_gallery_folders` */

DROP TABLE IF EXISTS `multi_gallery_folders`;

CREATE TABLE `multi_gallery_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `galleryid` int(11) NOT NULL,
  `name` text,
  `link` text NOT NULL,
  `description` text,
  `thumb` text NOT NULL,
  `order` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `multi_gallery_folders` */

/*Table structure for table `page_contents` */

DROP TABLE IF EXISTS `page_contents`;

CREATE TABLE `page_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pageid` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `content_type` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `page_contents` */

/*Table structure for table `session_links` */

DROP TABLE IF EXISTS `session_links`;

CREATE TABLE `session_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` text NOT NULL,
  `cookie_session_id` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

/*Data for the table `session_links` */

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `value` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `settings` */

insert  into `settings`(`id`,`name`,`value`) values (1,'reg_enabled','false');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
