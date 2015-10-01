/*
SQLyog Community v11.52 (32 bit)
MySQL - 5.1.40-community : Database - andrej
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`andrej` /*!40100 DEFAULT CHARACTER SET cp1251 */;

USE `andrej`;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `login` varchar(30) DEFAULT NULL,
  `password` varchar(30) DEFAULT NULL,
  `mail` varchar(30) DEFAULT NULL,
  `reg_date` varchar(30) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `photo_user` varchar(30) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=cp1251;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`last_name`,`login`,`password`,`mail`,`reg_date`,`country`,`city`,`photo_user`) values (1,'root','vasa','123456','123456','ssss','2015-09-02T01:30:20+04:00','ssss','ssss','images/user/bcdd187f7e166b3334'),(2,'root','vasa','123456','123456','ssss','2015-09-02T01:30:26+04:00','ssss','ssss','images/user/9bc2d4f4c6f5bc13fb');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
