/*
SQLyog Community v11.51 (32 bit)
MySQL - 5.5.25 : Database - andrej
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `users` */

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `login` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `reg_date` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `photo_user` varchar(50) DEFAULT NULL,
  `adm` varchar(5) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`last_name`,`login`,`password`,`mail`,`reg_date`,`country`,`city`,`photo_user`,`adm`) values (85,'oleg','kolmyk','luger','9d3dc7094d3dcb31ffe2960ad891dd04','test@test','Mon, 20 Apr 2015 22:23:18 +0300','ukr','khr','upload/1.jpg','1');
insert  into `users`(`id`,`name`,`last_name`,`login`,`password`,`mail`,`reg_date`,`country`,`city`,`photo_user`,`adm`) values (86,'vasa','vaskin123','vasa','9d3dc7094d3dcb31ffe2960ad891dd04','test10@test10','Mon, 20 Apr 2015 22:27:25 +0300','ukr','khr','upload/2.jpg','');
insert  into `users`(`id`,`name`,`last_name`,`login`,`password`,`mail`,`reg_date`,`country`,`city`,`photo_user`,`adm`) values (87,'peta','petrov','peta','9d3dc7094d3dcb31ffe2960ad891dd04','test3@test3','Wed, 22 Apr 2015 23:08:05 +0300','ukr','khr','upload/3.jpg',NULL);
insert  into `users`(`id`,`name`,`last_name`,`login`,`password`,`mail`,`reg_date`,`country`,`city`,`photo_user`,`adm`) values (88,'test','test','test','9d3dc7094d3dcb31ffe2960ad891dd04','test3@test3','Thu, 23 Apr 2015 11:00:56 +0300','test','test','upload/4.jpg',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
