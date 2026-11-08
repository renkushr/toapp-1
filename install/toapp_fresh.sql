/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 5.7.40 : Database - admin_toapp2
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `admin_notifications` */

DROP TABLE IF EXISTS `admin_notifications`;

CREATE TABLE `admin_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `click_url` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=353 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `admin_notifications` */


/*Table structure for table `admin_password_resets` */

DROP TABLE IF EXISTS `admin_password_resets`;

CREATE TABLE `admin_password_resets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `admin_password_resets` */

/*Table structure for table `admins` */

DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`,`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `admins` */

insert  into `admins`(`id`,`name`,`email`,`username`,`email_verified_at`,`image`,`password`,`remember_token`,`license`,`status`,`created_at`,`updated_at`) values 
(1,'Super Admin','admin@example.com','admin',NULL,NULL,'$2y$10$s0MjyjVKvuS9gY2ThSeJ9.543RSM.9JxzFja4xKGYeNDBb4K0sU3i',NULL,NULL,1,NULL,NULL);

/*Table structure for table `cron_job_logs` */

DROP TABLE IF EXISTS `cron_job_logs`;

CREATE TABLE `cron_job_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cron_job_id` int(10) unsigned NOT NULL DEFAULT '0',
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `duration` int(11) NOT NULL DEFAULT '0',
  `error` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cron_job_logs` */


/*Table structure for table `cron_jobs` */

DROP TABLE IF EXISTS `cron_jobs`;

CREATE TABLE `cron_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` text COLLATE utf8mb4_unicode_ci,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cron_schedule_id` int(11) NOT NULL DEFAULT '0',
  `next_run` datetime DEFAULT NULL,
  `last_run` datetime DEFAULT NULL,
  `is_running` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cron_jobs` */

insert  into `cron_jobs`(`id`,`name`,`alias`,`action`,`url`,`cron_schedule_id`,`next_run`,`last_run`,`is_running`,`is_default`,`created_at`,`updated_at`) values 
(1,'Get Interest','get_interest','[\"App\\\\Http\\\\Controllers\\\\CronController\", \"getInterest\"]',NULL,1,'2024-09-21 09:29:28','2024-09-20 09:29:28',1,1,NULL,'2024-09-20 16:29:28');

/*Table structure for table `cron_schedules` */

DROP TABLE IF EXISTS `cron_schedules`;

CREATE TABLE `cron_schedules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interval` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cron_schedules` */

insert  into `cron_schedules`(`id`,`name`,`interval`,`status`,`created_at`,`updated_at`) values 
(1,'Daily',86400,1,NULL,NULL);

/*Table structure for table `deposits` */

DROP TABLE IF EXISTS `deposits`;

CREATE TABLE `deposits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `method_code` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `method_currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `detail` text COLLATE utf8mb4_unicode_ci,
  `btc_amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btc_wallet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `try` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>success, 2=>pending, 3=>cancel',
  `from_api` tinyint(1) NOT NULL DEFAULT '0',
  `admin_feedback` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `success_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `failed_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_cron` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `deposits` */


/*Table structure for table `device_tokens` */

DROP TABLE IF EXISTS `device_tokens`;

CREATE TABLE `device_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_app` tinyint(1) NOT NULL DEFAULT '0',
  `token` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `device_tokens` */

/*Table structure for table `extensions` */

DROP TABLE IF EXISTS `extensions`;

CREATE TABLE `extensions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `act` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `script` text COLLATE utf8mb4_unicode_ci,
  `shortcode` text COLLATE utf8mb4_unicode_ci COMMENT 'object',
  `support` text COLLATE utf8mb4_unicode_ci COMMENT 'help section',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=>enable, 2=>disable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `extensions` */

insert  into `extensions`(`id`,`act`,`name`,`description`,`image`,`script`,`shortcode`,`support`,`status`,`created_at`,`updated_at`) values 
(1,'tawk-chat','Tawk.to','Key location is shown bellow','tawky_big.png','<script>\r\n                        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();\r\n                        (function(){\r\n                        var s1=document.createElement(\"script\"),s0=document.getElementsByTagName(\"script\")[0];\r\n                        s1.async=true;\r\n                        s1.src=\"https://embed.tawk.to/{{app_key}}\";\r\n                        s1.charset=\"UTF-8\";\r\n                        s1.setAttribute(\"crossorigin\",\"*\");\r\n                        s0.parentNode.insertBefore(s1,s0);\r\n                        })();\r\n                    </script>','{\"app_key\":{\"title\":\"App Key\",\"value\":\"3dcedc6b2e54977e0e1dbc872703057e0e44f4d1\"}}','twak.png',1,'2019-10-19 06:16:05','2024-08-10 13:23:21'),
(2,'google-recaptcha2','Google Recaptcha 2','Key location is shown bellow','recaptcha3.png','\n<script src=\"https://www.google.com/recaptcha/api.js\"></script>\n<div class=\"g-recaptcha\" data-sitekey=\"{{site_key}}\" data-callback=\"verifyCaptcha\"></div>\n<div id=\"g-recaptcha-error\"></div>','{\"site_key\":{\"title\":\"Site Key\",\"value\":\"----------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"value\":\"----------------\"}}','recaptcha.png',0,'2019-10-19 06:16:05','2024-06-10 12:04:36'),
(3,'custom-captcha','Custom Captcha','Just put any random string','customcaptcha.png',NULL,'{\"random_key\":{\"title\":\"Random String\",\"value\":\"SecureString\"}}','na',0,'2019-10-19 06:16:05','2022-11-20 11:41:23'),
(4,'google-analytics','Google Analytics','Key location is shown bellow','google_analytics.png','<script async src=\"https://www.googletagmanager.com/gtag/js?id={{measurement_id}}\"></script>\n                <script>\n                  window.dataLayer = window.dataLayer || [];\n                  function gtag(){dataLayer.push(arguments);}\n                  gtag(\"js\", new Date());\n                \n                  gtag(\"config\", \"{{measurement_id}}\");\n                </script>','{\"measurement_id\":{\"title\":\"Measurement ID\",\"value\":\"------\"}}','ganalytics.png',0,NULL,'2021-05-04 17:19:12');

/*Table structure for table `forms` */

DROP TABLE IF EXISTS `forms`;

CREATE TABLE `forms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `act` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_data` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `forms` */

insert  into `forms`(`id`,`act`,`form_data`,`created_at`,`updated_at`) values 
(7,'kyc','{\"full_name\":{\"name\":\"Full Name\",\"label\":\"full_name\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"nid_number\":{\"name\":\"NID Number\",\"label\":\"nid_number\",\"is_required\":\"required\",\"extensions\":null,\"options\":[],\"type\":\"text\"},\"gender\":{\"name\":\"Gender\",\"label\":\"gender\",\"is_required\":\"required\",\"extensions\":null,\"options\":[\"Male\",\"Female\",\"Others\"],\"type\":\"select\"},\"you_hobby\":{\"name\":\"You Hobby\",\"label\":\"you_hobby\",\"is_required\":\"required\",\"extensions\":null,\"options\":[\"Programming\",\"Gardening\",\"Traveling\",\"Others\"],\"type\":\"checkbox\"},\"nid_photo\":{\"name\":\"NID Photo\",\"label\":\"nid_photo\",\"is_required\":\"required\",\"extensions\":\"jpg,png\",\"options\":[],\"type\":\"file\"}}','2022-03-17 09:56:14','2022-10-13 13:13:55'),
(20,'withdraw_method','[]','2024-08-15 17:15:41','2024-08-15 17:15:41'),
(21,'manual_deposit','[]','2024-08-15 17:16:42','2024-08-15 17:16:42'),
(22,'withdraw_method','[]','2024-09-14 16:32:53','2024-09-14 16:32:53');

/*Table structure for table `frontends` */

DROP TABLE IF EXISTS `frontends`;

CREATE TABLE `frontends` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `data_keys` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_values` longtext COLLATE utf8mb4_unicode_ci,
  `seo_content` longtext COLLATE utf8mb4_unicode_ci,
  `tempname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `frontends` */

insert  into `frontends`(`id`,`data_keys`,`data_values`,`seo_content`,`tempname`,`slug`,`created_at`,`updated_at`) values 
(1,'seo.data','{\"seo_image\":\"1\",\"keywords\":[\"admin\",\"blog\",\"aaaa\",\"ddd\",\"aaa\",\"hyip\",\"hyiplab\",\"litehyip\",\"investment\",\"profit\",\"income\",\"script\",\"phpscript\"],\"description\":\"To-App\",\"social_title\":\"To-App\",\"social_description\":\"To-App\",\"image\":\"672b60de31b6b1730896094.png\"}',NULL,'basic','','2020-07-05 06:42:52','2024-11-06 19:28:14'),
(24,'about.content','{\"has_image\":\"1\",\"heading\":\"About LiteHyip\",\"subheading\":\"Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sunt debitis ipsam molestias! Magni at molestiae a, eveniet voluptas et doloribus expedita officia, quo, ducimus rem unde sequi beatae vitae quis eveniet voluptas.\",\"about_image\":\"635a2d306e8e41666854192.png\"}',NULL,'basic',NULL,'2020-10-28 07:51:20','2022-10-27 11:33:12'),
(27,'contact_us.content','{\"heading\":\"Get in Touch\",\"subheading\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt voluptates rerum corporis molestias dolores.\",\"email_address\":\"5555f\",\"contact_details\":\"5555h\",\"contact_number\":\"5555a\",\"latitude\":\"5555h\",\"longitude\":\"5555s\",\"website_footer\":\"Website Footer\"}',NULL,'basic',NULL,'2020-10-28 07:59:19','2022-10-27 11:55:51'),
(28,'counter.content','{\"heading\":\"Latest News\",\"sub_heading\":\"Register New Account\"}',NULL,'basic',NULL,'2020-10-28 08:04:02','2020-10-28 08:04:02'),
(31,'social_icon.element','{\"title\":\"https:\\/\\/www.youtube.com\",\"social_icon\":\"<i class=\\\"fab fa-facebook-f\\\"><\\/i>\",\"url\":\"https:\\/\\/www.facebook.com\\/\"}',NULL,'basic',NULL,'2020-11-12 11:07:30','2022-10-27 13:10:45'),
(33,'feature.content','{\"heading\":\"Why You Trust Our Service\",\"subheading\":\"We will utilize your money providing a source of high income while minimizing any possibility of risk in a very secure way.\"}',NULL,'basic',NULL,'2021-01-04 06:40:54','2022-10-27 13:14:25'),
(34,'feature.element','{\"title\":\"Expert Management\",\"description\":\"Replacing a maintains the amount of lines. When replacing a selection. help agencies to define their new business objectives and then create.\\r\\nIt is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.\",\"feature_icon\":\"<i class=\\\"las la-user-graduate\\\"><\\/i>\"}',NULL,'basic',NULL,'2021-01-04 06:41:02','2022-11-06 19:59:10'),
(35,'service.element','{\"trx_type\":\"withdraw\",\"service_icon\":\"<i class=\\\"las la-highlighter\\\"><\\/i>\",\"title\":\"asdfasdf\",\"description\":\"asdfasdfasdfasdf\"}',NULL,'basic',NULL,'2021-03-06 08:12:10','2021-03-06 08:12:10'),
(36,'service.content','{\"trx_type\":\"deposit\",\"heading\":\"asdf fffff\",\"subheading\":\"555\"}',NULL,'basic',NULL,'2021-03-06 08:27:34','2022-03-30 15:07:06'),
(39,'banner.content','{\"has_image\":\"1\",\"heading\":\"Invest for Future in Stable Platform and Make Fast Money\",\"subheading\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias quibusdam eveniet similique magni accusantium soluta totam incidunt quam quis architecto amet.\",\"image\":\"635a30a7d70821666855079.jpg\"}',NULL,'basic',NULL,'2021-05-02 13:09:30','2022-11-06 18:46:35'),
(41,'cookie.data','{\"short_desc\":\"We may use cookies or any other tracking technologies when you visit our website, including any other media form, mobile website, or mobile application related or connected to help customize the Site and improve your experience.\",\"description\":\"<div class=\\\"mb-5\\\" style=\\\"margin-bottom: 3rem !important;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight: 600; line-height: 1.3; font-size: 24px; \\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right: 0px; margin-left: 0px; font-size: 18px !important;\\\">We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom: 3rem !important;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight: 600; line-height: 1.3; font-size: 24px;\\\">How do we protect your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right: 0px; margin-left: 0px; font-size: 18px !important;\\\">All provided delicate\\/credit data is sent through Stripe.<br>After an exchange, your private data (credit cards, social security numbers, financials, and so on) won\'t be put away on our workers.<\\/p><\\/div><div class=\\\"mb-5\\\" margin-bottom:=\\\"\\\" 3rem=\\\"\\\" !important;\\\"=\\\"\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight: 600; line-height: 1.3; font-size: 24px;\\\">Do we disclose any information to outside parties?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right: 0px; margin-left: 0px; font-size: 18px !important;\\\">We don\'t sell, exchange, or in any case move to outside gatherings by and by recognizable data. This does exclude confided in outsiders who help us in working our site, leading our business, or adjusting you, since those gatherings consent to keep this data private. We may likewise deliver your data when we accept discharge is suitable to follow the law, implement our site strategies, or ensure our own or others\' rights, property, or wellbeing.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom: 3rem !important;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight: 600; line-height: 1.3; font-size: 24px;\\\">Children\'s Online Privacy Protection Act Compliance<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right: 0px; margin-left: 0px; font-size: 18px !important;\\\">We are consistent with the prerequisites of COPPA (Children\'s Online Privacy Protection Act), we don\'t gather any data from anybody under 13 years old. Our site, items, and administrations are completely coordinated to individuals who are in any event 13 years of age or more established.<\\/p><\\/div><div class=\\\"mb-5\\\" margin-bottom:=\\\"\\\" 3rem=\\\"\\\" !important;\\\"=\\\"\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight: 600; line-height: 1.3; font-size: 24px; \\\">Changes to our Privacy Policy<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right: 0px; margin-left: 0px; font-size: 18px !important;\\\">If we decide to change our privacy policy, we will post those changes on this page.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom: 3rem !important;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight: 600; line-height: 1.3; font-size: 24px; \\\">How long we retain your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right: 0px; margin-left: 0px; font-size: 18px !important;\\\">At the point when you register for our site, we cycle and keep your information we have about you however long you don\'t erase the record or withdraw yourself (subject to laws and guidelines).<\\/p><\\/div><div class=\\\"mb-5\\\" margin-bottom:=\\\"\\\" 3rem=\\\"\\\" !important;\\\"=\\\"\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight: 600; line-height: 1.3; font-size: 24px;\\\">What we don\\u2019t do with your data<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right: 0px; margin-left: 0px; font-size: 18px !important;\\\">We don\'t and will never share, unveil, sell, or in any case give your information to different organizations for the promoting of their items or administrations.<\\/p><\\/div>\",\"status\":1}',NULL,'basic',NULL,'2020-07-05 06:42:52','2022-11-02 22:07:47'),
(42,'policy_pages.element','{\"title\":\"Privacy Policy\",\"details\":\"<div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">How do we protect your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">All provided delicate\\/credit data is sent through Stripe.<br \\/>After an exchange, your private data (credit cards, social security numbers, financials, and so on) won\'t be put away on our workers.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">Do we disclose any information to outside parties?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We don\'t sell, exchange, or in any case move to outside gatherings by and by recognizable data. This does exclude confided in outsiders who help us in working our site, leading our business, or adjusting you, since those gatherings consent to keep this data private. We may likewise deliver your data when we accept discharge is suitable to follow the law, implement our site strategies, or ensure our own or others\' rights, property, or wellbeing.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">Children\'s Online Privacy Protection Act Compliance<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We are consistent with the prerequisites of COPPA (Children\'s Online Privacy Protection Act), we don\'t gather any data from anybody under 13 years old. Our site, items, and administrations are completely coordinated to individuals who are in any event 13 years of age or more established.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">Changes to our Privacy Policy<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">If we decide to change our privacy policy, we will post those changes on this page.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">How long we retain your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">At the point when you register for our site, we cycle and keep your information we have about you however long you don\'t erase the record or withdraw yourself (subject to laws and guidelines).<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">What we don\\u2019t do with your data<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We don\'t and will never share, unveil, sell, or in any case give your information to different organizations for the promoting of their items or administrations.<\\/p><\\/div>\"}',NULL,'basic','privacy-policy','2021-06-09 15:50:42','2022-10-30 14:55:43'),
(43,'policy_pages.element','{\"title\":\"Terms of Service\",\"details\":\"<div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We claim all authority to dismiss, end, or handicap any help with or without cause per administrator discretion. This is a Complete independent facilitating, on the off chance that you misuse our ticket or Livechat or emotionally supportive network by submitting solicitations or protests we will impair your record. The solitary time you should reach us about the seaward facilitating is if there is an issue with the worker. We have not many substance limitations and everything is as per laws and guidelines. Try not to join on the off chance that you intend to do anything contrary to the guidelines, we do check these things and we will know, don\'t burn through our own and your time by joining on the off chance that you figure you will have the option to sneak by us and break the terms.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><ul class=\\\"font-18\\\" style=\\\"padding-left:15px;list-style-type:disc;font-size:18px;\\\"><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">Configuration requests - If you have a fully managed dedicated server with us then we offer custom PHP\\/MySQL configurations, firewalls for dedicated IPs, DNS, and httpd configurations.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">Software requests - Cpanel Extension Installation will be granted as long as it does not interfere with the security, stability, and performance of other users on the server.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">Emergency Support - We do not provide emergency support \\/ Phone Support \\/ LiveChat Support. Support may take some hours sometimes.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">Webmaster help - We do not offer any support for webmaster related issues and difficulty including coding, &amp; installs, Error solving. if there is an issue where a library or configuration of the server then we can help you if it\'s possible from our end.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">Backups - We keep backups but we are not responsible for data loss, you are fully responsible for all backups.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">We Don\'t support any child porn or such material.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">No spam-related sites or material, such as email lists, mass mail programs, and scripts, etc.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">No harassing material that may cause people to retaliate against you.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">No phishing pages.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">You may not run any exploitation script from the server. reason can be terminated immediately.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">If Anyone attempting to hack or exploit the server by using your script or hosting, we will terminate your account to keep safe other users.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">Malicious Botnets are strictly forbidden.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">Spam, mass mailing, or email marketing in any way are strictly forbidden here.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">Malicious hacking materials, trojans, viruses, &amp; malicious bots running or for download are forbidden.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">Resource and cronjob abuse is forbidden and will result in suspension or termination.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">Php\\/CGI proxies are strictly forbidden.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">CGI-IRC is strictly forbidden.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">No fake or disposal mailers, mass mailing, mail bombers, SMS bombers, etc.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">NO CREDIT OR REFUND will be granted for interruptions of service, due to User Agreement violations.<\\/li><\\/ul><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">Terms &amp; Conditions for Users<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">Before getting to this site, you are consenting to be limited by these site Terms and Conditions of Use, every single appropriate law, and guidelines, and concur that you are answerable for consistency with any material neighborhood laws. If you disagree with any of these terms, you are restricted from utilizing or getting to this site.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">Support<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">Whenever you have downloaded our item, you may get in touch with us for help through email and we will give a valiant effort to determine your issue. We will attempt to answer using the Email for more modest bug fixes, after which we will refresh the center bundle. Content help is offered to confirmed clients by Tickets as it were. Backing demands made by email and Livechat.<\\/p><p class=\\\"my-3 font-18 font-weight-bold\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">On the off chance that your help requires extra adjustment of the System, at that point, you have two alternatives:<\\/p><ul class=\\\"font-18\\\" style=\\\"padding-left:15px;list-style-type:disc;font-size:18px;\\\"><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">Hang tight for additional update discharge.<\\/li><li style=\\\"margin-top:0px;margin-right:0px;margin-left:0px;\\\">Or on the other hand, enlist a specialist (We offer customization for extra charges).<\\/li><\\/ul><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">Ownership<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">You may not guarantee scholarly or selective possession of any of our items, altered or unmodified. All items are property, we created them. Our items are given \\\"with no guarantees\\\" without guarantee of any sort, either communicated or suggested. On no occasion will our juridical individual be subject to any harms including, however not restricted to, immediate, roundabout, extraordinary, accidental, or significant harms or different misfortunes emerging out of the utilization of or powerlessness to utilize our items.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">Warranty<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We don\'t offer any guarantee or assurance of these Services in any way. When our Services have been modified we can\'t ensure they will work with all outsider plugins, modules, or internet browsers. Program similarity ought to be tried against the show formats on the demo worker. If you don\'t mind guarantee that the programs you use will work with the component, as we can not ensure that our systems will work with all program mixes.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">Unauthorized\\/Illegal Usage<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">You may not utilize our things for any illicit or unapproved reason or may you, in the utilization of the stage, disregard any laws in your locale (counting yet not restricted to copyright laws) just as the laws of your nation and International law. Specifically, it is disallowed to utilize the things on our foundation for pages that advance: brutality, illegal intimidation, hard sexual entertainment, bigotry, obscenity content or warez programming joins.<br \\/><br \\/>You can\'t imitate, copy, duplicate, sell, exchange or adventure any of our segment, utilization of the offered on our things, or admittance to the administration without the express composed consent by us or item proprietor.<br \\/><br \\/>Our Members are liable for all substance posted on the discussion and demo and movement that happens under your record.<br \\/><br \\/>We hold the chance of hindering your participation account quickly if we will think about a particularly not allowed conduct.<br \\/><br \\/>If you make a record on our site, you are liable for keeping up the security of your record, and you are completely answerable for all exercises that happen under the record and some other activities taken regarding the record. You should quickly inform us, of any unapproved employments of your record or some other penetrates of security.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">Fiverr, Seoclerks Sellers Or Affiliates<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We do NOT ensure full SEO campaign conveyance within 24 hours. We make no assurance for conveyance time by any means. We give our best assessment to orders during the putting in of requests, anyway, these are gauges. We won\'t be considered liable for loss of assets, negative surveys or you being prohibited for late conveyance. If you are selling on a site that requires time touchy outcomes, utilize Our SEO Services at your own risk.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">Payment\\/Refund Policy<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">No refund or cash back will be made. After a deposit has been finished, it is extremely unlikely to invert it. You should utilize your equilibrium on requests our administrations, Hosting, SEO campaign. You concur that once you complete a deposit, you won\'t document a debate or a chargeback against us in any way, shape, or form.<br \\/><br \\/>If you document a debate or chargeback against us after a deposit, we claim all authority to end every single future request, prohibit you from our site. False action, for example, utilizing unapproved or taken charge cards will prompt the end of your record. There are no special cases.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;\\\">Free Balance \\/ Coupon Policy<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We offer numerous approaches to get FREE Balance, Coupons and Deposit offers yet we generally reserve the privilege to audit it and deduct it from your record offset with any explanation we may it is a sort of misuse. If we choose to deduct a few or all of free Balance from your record balance, and your record balance becomes negative, at that point the record will naturally be suspended. If your record is suspended because of a negative Balance you can request to make a custom payment to settle your equilibrium to actuate your record.<\\/p><\\/div>\"}',NULL,'basic','terms-of-service','2021-06-09 15:51:18','2022-10-30 14:59:59'),
(44,'maintenance.data','{\"description\":\"<div class=\\\"mb-5\\\" style=\\\"margin-bottom: 3rem !important;\\\"><h3 class=\\\"mb-3\\\" style=\\\"text-align: center; font-weight: 600; line-height: 1.3; font-size: 24px;\\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"text-align: center; margin-right: 0px; margin-left: 0px; font-size: 18px !important;\\\">We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div>\",\"image\":\"6665baa7691ec1717942951.png\"}',NULL,'basic',NULL,'2020-07-05 06:42:52','2024-06-09 15:22:31'),
(45,'about.element','{\"title\":\"Secure Investments\",\"description\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, minus maiores.\",\"icon\":\"<i class=\\\"fas fa-shield-alt\\\"><\\/i>\"}',NULL,'basic',NULL,'2022-10-27 11:33:48','2022-10-27 11:33:48'),
(46,'about.element','{\"title\":\"Fast Online Transfer\",\"description\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, minus maiores.\",\"icon\":\"<i class=\\\"fas fa-tachometer-alt\\\"><\\/i>\"}',NULL,'basic',NULL,'2022-10-27 11:34:16','2022-10-27 11:34:16'),
(47,'about.element','{\"title\":\"Profit Guaranteed\",\"description\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, minus maiores.\",\"icon\":\"<i class=\\\"fas fa-award\\\"><\\/i>\"}',NULL,'basic',NULL,'2022-10-27 11:34:39','2022-10-27 11:34:39'),
(48,'contact_us.element','{\"address_type\":\"Office Address\",\"address\":\"690 E 14th St, New York, NY 10009, USA\",\"icon\":\"<i class=\\\"fas fa-map-marker-alt\\\"><\\/i>\"}',NULL,'basic','','2022-10-27 11:59:17','2024-06-10 11:41:47'),
(49,'contact_us.element','{\"address_type\":\"Phone Number\",\"address\":\"+12345678900\",\"icon\":\"<i class=\\\"fas fa-mobile-alt\\\"><\\/i>\"}',NULL,'basic',NULL,'2022-10-27 12:00:33','2022-11-02 19:16:28'),
(50,'contact_us.element','{\"address_type\":\"Email Address\",\"address\":\"support@example.com\",\"icon\":\"<i class=\\\"fas fa-envelope-open\\\"><\\/i>\"}',NULL,'basic',NULL,'2022-10-27 12:01:05','2022-11-02 19:16:07'),
(51,'faq.content','{\"heading\":\"Frequently Asked Questions\",\"subheading\":\"We will utilize your money providing a source of high income while minimizing any possibility of risk in a very secure way.\"}',NULL,'basic',NULL,'2022-10-27 12:59:00','2022-10-27 12:59:00'),
(52,'faq.element','{\"question\":\"Why you will use is LiteHyip?\",\"answer\":\"Main point of the platform is\\u00a0Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque porttitor in nisi non efficitur. Curabitur porta, arcu malesuada efficitur ultricies, lectus risus imperdiet orci, ut varius ante enim non risus. Duis volutpat orci eget vulputate sollicitudin. Vestibulum commodo est ut velit porttitor pretium. Integer aliquet arcu mauris, vel cursus elit pulvinar a. Duis ante dui, aliquam dapibus lobortis sed, dignissim nec justo. Morbi mattis quam at enim rhoncus, sit amet feugiat urna eleifend. Donec molestie iaculis velit, et sodales sem dignissim in. Mauris ac dignissim diam. In fringilla quis justo id lacinia. Nam ante sapien, porttitor sed varius eu, egestas sed justo. Aenean placerat velit eget arcu elementum placerat. Nam sollicitudin volutpat diam ac lacinia. Suspendisse nec augue et ante rutrum porttitor\"}',NULL,'basic',NULL,'2022-10-27 12:59:20','2022-11-03 14:40:10'),
(53,'faq.element','{\"question\":\"Nesciunt eius similique tenetur corporis fuga.\",\"answer\":\"Main point of the platform is\\u00a0Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque porttitor in nisi non efficitur. Curabitur porta, arcu malesuada efficitur ultricies, lectus risus imperdiet orci, ut varius ante enim non risus. Duis volutpat orci eget vulputate sollicitudin. Vestibulum commodo est ut velit porttitor pretium. Integer aliquet arcu mauris, vel cursus elit pulvinar a. Duis ante dui, aliquam dapibus lobortis sed, dignissim nec justo. Morbi mattis quam at enim rhoncus, sit amet feugiat urna eleifend. Donec molestie iaculis velit, et sodales sem dignissim in. Mauris ac dignissim diam. In fringilla quis justo id lacinia. Nam ante sapien, porttitor sed varius eu, egestas sed justo. Aenean placerat velit eget arcu elementum placerat. Nam sollicitudin volutpat diam ac lacinia. Suspendisse nec augue et ante rutrum porttitor.\"}',NULL,'basic',NULL,'2022-10-27 13:03:17','2022-11-03 14:40:47'),
(54,'faq.element','{\"question\":\"How does the platform enrich my company?\",\"answer\":\"Main point of the platform is\\u00a0Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque porttitor in nisi non efficitur. Curabitur porta, arcu malesuada efficitur ultricies, lectus risus imperdiet orci, ut varius ante enim non risus. Duis volutpat orci eget vulputate sollicitudin. Vestibulum commodo est ut velit porttitor pretium. Integer aliquet arcu mauris, vel cursus elit pulvinar a. Duis ante dui, aliquam dapibus lobortis sed, dignissim nec justo. Morbi mattis quam at enim rhoncus, sit amet feugiat urna eleifend. Donec molestie iaculis velit, et sodales sem dignissim in. Mauris ac dignissim diam. In fringilla quis justo id lacinia. Nam ante sapien, porttitor sed varius eu, egestas sed justo. Aenean placerat velit eget arcu elementum placerat. Nam sollicitudin volutpat diam ac lacinia. Suspendisse nec augue et ante rutrum porttitor.\"}',NULL,'basic',NULL,'2022-10-27 13:04:01','2022-11-03 14:41:35'),
(55,'faq.element','{\"question\":\"Lorem ipsum dolor sit amet, consectetur adipisicing elit.\",\"answer\":\"Main point of the platform is\\u00a0Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque porttitor in nisi non efficitur. Curabitur porta, arcu malesuada efficitur ultricies, lectus risus imperdiet orci, ut varius ante enim non risus. Duis volutpat orci eget vulputate sollicitudin. Vestibulum commodo est ut velit porttitor pretium. Integer aliquet arcu mauris, vel cursus elit pulvinar a. Duis ante dui, aliquam dapibus lobortis sed, dignissim nec justo. Morbi mattis quam at enim rhoncus, sit amet feugiat urna eleifend. Donec molestie iaculis velit, et sodales sem dignissim in. Mauris ac dignissim diam. In fringilla quis justo id lacinia. Nam ante sapien, porttitor sed varius eu, egestas sed justo. Aenean placerat velit eget arcu elementum placerat. Nam sollicitudin volutpat diam ac lacinia. Suspendisse nec augue et ante rutrum porttitor.\"}',NULL,'basic',NULL,'2022-10-27 13:06:50','2022-11-03 14:42:20'),
(56,'faq.element','{\"question\":\"Eligendi in enim quisquam dolor voluptates good.\",\"answer\":\"Main point of the platform is\\u00a0Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque porttitor in nisi non efficitur. Curabitur porta, arcu malesuada efficitur ultricies, lectus risus imperdiet orci, ut varius ante enim non risus. Duis volutpat orci eget vulputate sollicitudin. Vestibulum commodo est ut velit porttitor pretium. Integer aliquet arcu mauris, vel cursus elit pulvinar a. Duis ante dui, aliquam dapibus lobortis sed, dignissim nec justo. Morbi mattis quam at enim rhoncus, sit amet feugiat urna eleifend. Donec molestie iaculis velit, et sodales sem dignissim in. Mauris ac dignissim diam. In fringilla quis justo id lacinia. Nam ante sapien, porttitor sed varius eu, egestas sed justo. Aenean placerat velit eget arcu elementum placerat. Nam sollicitudin volutpat diam ac lacinia. Suspendisse nec augue et ante rutrum porttitor.\"}',NULL,'basic',NULL,'2022-10-27 13:08:38','2022-11-03 14:43:03'),
(57,'social_icon.element','{\"title\":\"Youtube\",\"social_icon\":\"<i class=\\\"fab fa-youtube\\\"><\\/i>\",\"url\":\"https:\\/\\/www.youtube.com\"}',NULL,'basic',NULL,'2022-10-27 13:11:36','2022-10-27 13:11:36'),
(58,'social_icon.element','{\"title\":\"Instagram\",\"social_icon\":\"<i class=\\\"lab la-instagram\\\"><\\/i>\",\"url\":\"https:\\/\\/www.instagram.com\"}',NULL,'basic',NULL,'2022-10-27 13:12:06','2022-10-27 13:12:06'),
(59,'social_icon.element','{\"title\":\"Twitter\",\"social_icon\":\"<i class=\\\"fab fa-twitter\\\"><\\/i>\",\"url\":\"https:\\/\\/www.twitter.com\"}',NULL,'basic',NULL,'2022-10-27 13:12:28','2022-10-27 13:12:28'),
(60,'social_icon.element','{\"title\":\"Linkedin\",\"social_icon\":\"<i class=\\\"fab fa-linkedin\\\"><\\/i>\",\"url\":\"https:\\/\\/www.linkedin.com\"}',NULL,'basic',NULL,'2022-10-27 13:13:03','2022-10-27 13:13:03'),
(61,'feature.element','{\"title\":\"Secure Investment\",\"description\":\"It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here.\",\"feature_icon\":\"<i class=\\\"fas fa-shield-alt\\\"><\\/i>\"}',NULL,'basic',NULL,'2022-10-27 13:16:02','2022-11-06 20:00:42'),
(62,'feature.element','{\"title\":\"Verified Security\",\"description\":\"Replacing a maintains the amount of lines. When replacing a selection. help agencies to define their new business objectives and then create.\",\"feature_icon\":\"<i class=\\\"fas fa-anchor\\\"><\\/i>\"}',NULL,'basic',NULL,'2022-10-27 13:16:38','2022-11-06 20:01:21'),
(65,'how_to_work.content','{\"heading\":\"It\'s Easy to Join and Make Money\",\"subheading\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed nec cursus ex, a tincidunt arcu. Curabitur viverra orci at velit scelerisque, sed tincidunt ipsum bibendum.\"}',NULL,'basic',NULL,'2022-10-27 13:19:13','2022-11-01 20:04:05'),
(66,'how_to_work.element','{\"title\":\"Create an Account\",\"description\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, minus maiores.\"}',NULL,'basic',NULL,'2022-10-27 13:19:28','2022-10-30 11:38:56'),
(67,'how_to_work.element','{\"title\":\"Choose Lottery\",\"description\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, minus maiores.\"}',NULL,'basic',NULL,'2022-10-27 13:20:26','2022-10-30 11:39:02'),
(68,'how_to_work.element','{\"title\":\"Pick Lottery\",\"description\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, minus maiores.\"}',NULL,'basic',NULL,'2022-10-27 13:20:36','2022-10-30 11:39:07'),
(69,'how_to_work.element','{\"title\":\"Win Lottery\",\"description\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, minus maiores.\"}',NULL,'basic',NULL,'2022-10-27 13:20:44','2022-10-30 11:39:11'),
(70,'latest_trx.content','{\"heading\":\"Latest Deposit And Withdraw\"}',NULL,'basic',NULL,'2022-10-27 13:26:35','2022-10-27 13:26:35'),
(71,'overview.element','{\"title\":\"Total Members\",\"text\":\"860000\",\"icon\":\"<i class=\\\"fas fa-users\\\"><\\/i>\"}',NULL,'basic',NULL,'2022-10-27 13:28:30','2022-11-16 23:26:39'),
(72,'overview.element','{\"title\":\"Average Investment\",\"text\":\"13.3M\",\"icon\":\"<i class=\\\"las la-coins\\\"><\\/i>\"}',NULL,'basic',NULL,'2022-10-27 13:29:18','2022-11-06 19:48:04'),
(73,'overview.element','{\"title\":\"Total Visitors\",\"text\":\"100000\",\"icon\":\"<i class=\\\"far fa-eye\\\"><\\/i>\"}',NULL,'basic',NULL,'2022-10-27 13:29:45','2022-10-27 13:30:22'),
(74,'package.content','{\"heading\":\"Latest Investment Plans\"}',NULL,'basic',NULL,'2022-10-27 13:31:50','2022-11-16 23:28:18'),
(75,'payment.content','{\"heading\":\"Payment We Accept\",\"subheading\":\"Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse voluptatum eaque earum quos quia? Id aspernatur ratione, voluptas nulla rerum laudantium neque ipsam eaque\"}',NULL,'basic',NULL,'2022-10-27 13:32:07','2022-10-27 13:32:07'),
(76,'payment.element','{\"gateway_name\":\"PayPal\",\"title\":\"Investors\",\"amount\":\"24K\"}',NULL,'basic',NULL,'2022-10-27 13:32:26','2022-10-30 14:32:28'),
(77,'payment.element','{\"gateway_name\":\"Perfect Money\",\"title\":\"Investors\",\"amount\":\"56K\"}',NULL,'basic',NULL,'2022-10-27 13:32:57','2022-10-30 14:32:41'),
(78,'payment.element','{\"gateway_name\":\"Stripe\",\"title\":\"Investors\",\"amount\":\"22K\"}',NULL,'basic',NULL,'2022-10-27 13:33:14','2022-10-30 14:32:54'),
(79,'payment.element','{\"gateway_name\":\"Skrill\",\"title\":\"Investors\",\"amount\":\"44K\"}',NULL,'basic',NULL,'2022-10-27 13:33:23','2022-10-30 14:33:09'),
(80,'testimonial.content','{\"heading\":\"What User Say About Us\",\"subheading\":\"Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nisi suscipit, sunt obcaecati porro consequuntur quo deleniti voluptatum at qui eum quibusdam sapiente laborum\"}',NULL,'basic',NULL,'2022-10-27 13:42:12','2022-10-30 13:58:25'),
(81,'testimonial.element','{\"has_image\":\"1\",\"name\":\"Ramimatullah\",\"number_of_star\":\"5\",\"comment\":\"Lorem ipsum dolor sit amet, consectetur adicing elit. Eius aut odit non. Sunt, laborum Nemo erunt sit libero eius corporis voluptates sapie smoss.\",\"designation\":\"LiteHyip User\",\"image\":\"6375d0dcebcd51668665564.png\"}',NULL,'basic',NULL,'2022-10-27 13:43:22','2022-11-17 15:12:44'),
(82,'testimonial.element','{\"has_image\":\"1\",\"name\":\"David Oxy\",\"number_of_star\":\"5\",\"comment\":\"Lorem ipsum dolor sit amet, consectetur adicing elit. Eius aut odit non. Sunt, laborum Nemo erunt sit libero eius corporis voluptates sapie smoss.\",\"designation\":\"Software Develeper\",\"image\":\"6375d0e56cc611668665573.png\"}',NULL,'basic',NULL,'2022-10-27 13:44:14','2022-11-17 15:12:53'),
(83,'testimonial.element','{\"has_image\":\"1\",\"name\":\"Jenifer Roksana\",\"number_of_star\":\"4\",\"comment\":\"Lorem ipsum dolor sit amet, consectetur adicing elit. Eius aut odit non. Sunt, laborum Nemo erunt sit libero eius corporis voluptates sapie smoss.\",\"designation\":\"Senior Dr.\",\"image\":\"6375d0ea7b94d1668665578.png\"}',NULL,'basic',NULL,'2022-10-27 13:44:54','2022-11-17 15:12:58'),
(84,'testimonial.element','{\"has_image\":\"1\",\"name\":\"Nixer Angel\",\"number_of_star\":\"5\",\"comment\":\"Lorem ipsum dolor sit amet, consectetur adicing elit. Eius aut odit non. Sunt, laborum Nemo erunt sit libero eius corporis voluptates sapie smoss.\",\"designation\":\"Film Star\",\"image\":\"6375d0ef9a2991668665583.png\"}',NULL,'basic',NULL,'2022-10-27 13:45:45','2022-11-17 15:13:03'),
(85,'top_investor.element','{\"has_image\":\"1\",\"name\":\"Admi Corp\",\"country\":\"UK\",\"date\":\"2022-11-01\",\"image\":\"635e45944236a1667122580.jpeg\"}',NULL,'basic',NULL,'2022-10-27 13:47:30','2022-10-30 14:06:20'),
(86,'top_investor.content','{\"heading\":\"Top Investors\"}',NULL,'basic',NULL,'2022-10-27 13:47:40','2022-10-27 13:47:40'),
(87,'top_investor.element','{\"has_image\":\"1\",\"name\":\"Spider man\",\"country\":\"USA\",\"date\":\"2022-02-28\",\"image\":\"635e45c06f8131667122624.png\"}',NULL,'basic',NULL,'2022-10-27 13:47:59','2022-10-30 14:07:04'),
(88,'top_investor.element','{\"has_image\":\"1\",\"name\":\"Mask\",\"country\":\"USA\",\"date\":\"2022-10-12\",\"image\":\"635e45a5ee2921667122597.jpeg\"}',NULL,'basic',NULL,'2022-10-27 13:48:45','2022-10-30 14:06:37'),
(89,'top_investor.element','{\"has_image\":\"1\",\"name\":\"Nisat\",\"country\":\"Bangladesh\",\"date\":\"2022-04-22\",\"image\":\"635e45ae137bf1667122606.jpeg\"}',NULL,'basic',NULL,'2022-10-27 13:49:24','2022-10-30 14:06:46'),
(90,'payment.element','{\"gateway_name\":\"Paytm\",\"title\":\"Investors\",\"amount\":\"30K\"}',NULL,'basic',NULL,'2022-10-30 14:33:49','2022-10-30 14:33:49'),
(91,'payment.element','{\"gateway_name\":\"Paystack\",\"title\":\"Investors\",\"amount\":\"44K\"}',NULL,'basic',NULL,'2022-10-30 14:34:57','2022-10-30 14:34:57'),
(92,'breadcrumb.content','{\"has_image\":\"1\",\"image\":\"635e4fd8a83bb1667125208.jpg\"}',NULL,'basic',NULL,'2022-10-30 14:50:08','2022-10-30 14:50:08'),
(93,'faq.element','{\"question\":\"How can I get referral bonus by LiteHyip\",\"answer\":\"Main point of the platform is\\u00a0Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque porttitor in nisi non efficitur. Curabitur porta, arcu malesuada efficitur ultricies, lectus risus imperdiet orci, ut varius ante enim non risus. Duis volutpat orci eget vulputate sollicitudin. Vestibulum commodo est ut velit porttitor pretium. Integer aliquet arcu mauris, vel cursus elit pulvinar a. Duis ante dui, aliquam dapibus lobortis sed, dignissim nec justo. Morbi mattis quam at enim rhoncus, sit amet feugiat urna eleifend. Donec molestie iaculis velit, et sodales sem dignissim in. Mauris ac dignissim diam. In fringilla quis justo id lacinia. Nam ante sapien, porttitor sed varius eu, egestas sed justo. Aenean placerat velit eget arcu elementum placerat. Nam sollicitudin volutpat diam ac lacinia. Suspendisse nec augue et ante rutrum porttitor.\"}',NULL,'basic',NULL,'2022-11-01 19:53:39','2022-11-03 14:43:32'),
(94,'how_it_work.content','{\"heading\":\"It\'s Easy to Join and Make Money\",\"subheading\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam consequatur ipsam ab aperiam facilis ad deserunt debitis ullam. Labore dolore odio magnam corporis in iure.\"}',NULL,'basic',NULL,'2022-11-06 19:43:01','2022-11-06 19:43:01'),
(95,'how_it_work.element','{\"title\":\"Register and Login\",\"description\":\"Creating an account is the first step. then you need to log in\"}',NULL,'basic',NULL,'2022-11-06 19:44:54','2022-11-06 19:52:11'),
(96,'how_it_work.element','{\"title\":\"Add Fund\",\"description\":\"Next, pick a payment method and add funds to your account\"}',NULL,'basic',NULL,'2022-11-06 19:50:20','2022-11-06 19:50:20'),
(97,'how_it_work.element','{\"title\":\"Select A Service\",\"description\":\"Select the services you want and get ready to receive more publicity\"}',NULL,'basic',NULL,'2022-11-06 19:50:36','2022-11-06 19:50:36'),
(98,'how_it_work.element','{\"title\":\"Enjoy Super Results\",\"description\":\"You can enjoy incredible results when your order is complete\"}',NULL,'basic',NULL,'2022-11-06 19:50:52','2022-11-06 19:50:52'),
(99,'feature.element','{\"title\":\"Instant Withdrawal\",\"description\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam consequatur ipsam ab aperiam facilis ad deserunt debitis ullam. Labore dolore odio magnam corporis in iure.\",\"feature_icon\":\"<i class=\\\"fas fa-hand-holding-usd\\\"><\\/i>\"}',NULL,'basic',NULL,'2022-11-06 19:55:17','2022-11-06 20:02:01'),
(100,'feature.element','{\"title\":\"Quick Deposit\",\"description\":\"It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.\",\"feature_icon\":\"<i class=\\\"las la-file-invoice-dollar\\\"><\\/i>\"}',NULL,'basic',NULL,'2022-11-06 19:55:52','2022-11-06 20:02:15'),
(101,'feature.element','{\"title\":\"Registered Company\",\"description\":\"Replacing a maintains the amount of lines. When replacing a selection. help agencies to define their new business objectives and then create.\",\"feature_icon\":\"<i class=\\\"las la-registered\\\"><\\/i>\"}',NULL,'basic',NULL,'2022-11-06 19:55:53','2022-11-06 20:02:50'),
(102,'kyc_info.content','{\"verification_instruction\":\"Lorem ipsum, dolor sit amet consectetur adipisicing elit. Hic officia quodnatus, non dicta perspiciatis, quae repellendus ea illum aut debitis sint amet? Ratione  voluptates beatae numquam.\",\"pending_instruction\":\"Lorem ipsum, dolor sit amet consectetur adipisicing elit. Hic officia quodnatus, non dicta perspiciatis, quae repellendus ea illum aut debitis sint amet? Ratione  voluptates beatae numquam.\"}',NULL,'basic',NULL,'2022-11-06 23:28:31','2022-11-16 23:42:39'),
(103,'kyc.content','{\"required\":\"Complete KYC to unlock the full potential of our platform! KYC helps us verify your identity and keep things secure. It is quick and easy just follow the on-screen instructions. Get started with KYC verification now!\",\"pending\":\"Your KYC verification is being reviewed. We might need some additional information. You will get an email update soon. In the meantime, explore our platform with limited features.\",\"reject\":\"We regret to inform you that the Know Your Customer (KYC) information provided has been reviewed and unfortunately, it has not met our verification standards.\"}',NULL,'basic','','2024-05-18 12:06:56','2024-05-18 12:06:56'),
(104,'policy_pages.element','{\"title\":\"content\",\"details\":\"<div style=\\\"background-size:initial;padding:10px 12px 10px 15px;color:rgb(51,51,51);font-family:\'Helvetica Neue\', Arial, \'Liberation Sans\', FreeSans, sans-serif;font-size:14px;\\\"><h1 style=\\\"font-size:28px;font-family:inherit;font-weight:700;line-height:35px;color:rgb(51,51,51);padding:0px;letter-spacing:-1px;\\\"><\\/h1><p style=\\\"margin-right:0px;margin-bottom:20px;margin-left:0px;color:rgb(51,51,51);font-size:16px;letter-spacing:normal;\\\">As conversations went on, it became clear that finding the significant capital to meet GuideOne\\u2019s needs would be difficult. Like many Midwest mutuals, GuideOne experienced a drop in surplus in the early 2020s driven by rising loss costs and an increase in claims severity.<\\/p><p style=\\\"margin-right:0px;margin-bottom:20px;margin-left:0px;color:rgb(51,51,51);font-size:16px;letter-spacing:normal;\\\"><\\/p><div class=\\\"article-inline-sidebar article-inline-sidebar-left\\\" style=\\\"background:rgb(247,245,242);margin-bottom:16px;padding:16px;width:281px;float:left;margin-left:-28px;margin-right:15px;font-size:16px;font-weight:400;letter-spacing:normal;\\\"><em style=\\\"margin-top:0px;\\\"><span style=\\\"font-weight:700;\\\">This story is an excerpt of member content posted to Insurance Journal\\u2019s sister publication, Carrier Management.<\\/span><\\/em>\\u00a0<em style=\\\"margin-bottom:0px;\\\"><span style=\\\"font-weight:700;\\\">To read the rest of GuideOne\\u2019s story,\\u00a0<a href=\\\"https:\\/\\/www.carriermanagement.com\\/features\\/2024\\/08\\/21\\/265585.htm\\\" style=\\\"background-color:transparent;color:rgb(175,35,28);text-decoration-line:underline;\\\">click here<\\/a>.<\\/span><\\/em><\\/div><p style=\\\"margin-right:0px;margin-bottom:20px;margin-left:0px;color:rgb(51,51,51);font-size:16px;letter-spacing:normal;\\\"><\\/p><p style=\\\"margin-right:0px;margin-bottom:20px;margin-left:0px;color:rgb(51,51,51);font-size:16px;letter-spacing:normal;\\\">By 2022, the company\\u2019s options were either taking on additional debt or securing more reinsurance, said Ken Cadematori, CEO of GuideOne.<\\/p><p style=\\\"margin-right:0px;margin-bottom:20px;margin-left:0px;color:rgb(51,51,51);font-size:16px;letter-spacing:normal;\\\">That is until a GuideOne board member brought up a new idea. Bain Capital Insurance was closing in on an inaugural insurance fund of more than $1 billion. Chuck Chamness, a senior advisor for Bain\\u2019s insurance portfolio, was a former president of the National Association of Mutual Insurance Companies (NAMIC) who understood the struggles that companies like GuideOne go through to secure funding.<\\/p><p style=\\\"margin-right:0px;margin-bottom:20px;margin-left:0px;color:rgb(51,51,51);font-size:16px;letter-spacing:normal;\\\">Why not see if Bain would be interested in investing some of its insurance capital into this 78-year-old Des Moines-based mutual?<\\/p><p style=\\\"margin-right:0px;margin-bottom:20px;margin-left:0px;color:rgb(51,51,51);font-size:16px;letter-spacing:normal;\\\">Initial conversations between Bain and GuideOne left the mutual company\\u2019s leaders intrigued but also wary of what the undertaking would mean for their policyholders, according to Cadematori.<\\/p><div class=\\\"bzn bzn-intext-2\\\" style=\\\"margin:20px 0px 20px -15px;font-size:16px;font-weight:400;letter-spacing:normal;\\\"><div style=\\\"text-align:center;\\\"><img src=\\\"https:\\/\\/i.imgur.com\\/FMa8duN.jpeg\\\" width=\\\"300\\\" alt=\\\"FMa8duN.jpeg\\\" \\/><br \\/><\\/div><ins style=\\\"text-decoration-line:none;\\\"><div><img src=\\\"https:\\/\\/ra.wellsmedia.com\\/www\\/delivery\\/lg.php?bannerid=15969&campaignid=8935&zoneid=162&loc=https%3A%2F%2Fwww.insurancejournal.com%2Fnews%2Fnational%2F2024%2F09%2F24%2F794103.htm&referer=https%3A%2F%2Fwww.insurancejournal.com%2Fnews%2F&cb=32c4c703a1\\\" width=\\\"0\\\" height=\\\"0\\\" alt=\\\"\\\" style=\\\"border:0px;width:0px;height:0px;\\\" \\/><\\/div><\\/ins><\\/div><p style=\\\"margin-right:0px;margin-bottom:20px;margin-left:0px;color:rgb(51,51,51);font-size:16px;letter-spacing:normal;\\\">\\u201cIt was probably bigger than we were anticipating and very much a transformational change that we didn\\u2019t think we necessarily needed to undergo at that point in time,\\u201d said Cadematori \\u201cBut it did get us thinking.\\u201d<\\/p><p style=\\\"margin-right:0px;margin-bottom:20px;margin-left:0px;color:rgb(51,51,51);font-size:16px;letter-spacing:normal;\\\">As GuideOne leaders pondered their next steps, time was running short for the company to secure funding.<\\/p><\\/div>\"}',NULL,'basic','content','2024-09-24 17:49:52','2024-09-27 18:04:30');

/*Table structure for table `gateway_currencies` */

DROP TABLE IF EXISTS `gateway_currencies`;

CREATE TABLE `gateway_currencies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symbol` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method_code` int(11) DEFAULT NULL,
  `gateway_alias` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `max_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `gateway_parameter` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `gateway_currencies` */

insert  into `gateway_currencies`(`id`,`name`,`currency`,`symbol`,`method_code`,`gateway_alias`,`min_amount`,`max_amount`,`percent_charge`,`fixed_charge`,`rate`,`gateway_parameter`,`created_at`,`updated_at`) values 
(1,'test','1','',1000,'test',10.00000000,1000.00000000,0.00,0.00000000,1.00000000,NULL,'2024-08-15 17:16:42','2024-08-15 17:16:42'),
(3,'USD','USD','$',101,'Paypal',0.10000000,1000.00000000,0.00,0.00000000,1.00000000,'{\"paypal_email\":\"paypal@example.com\"}','2024-09-17 11:44:42','2024-09-17 11:44:42');

/*Table structure for table `gateways` */

DROP TABLE IF EXISTS `gateways`;

CREATE TABLE `gateways` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` int(10) unsigned NOT NULL DEFAULT '0',
  `code` int(11) DEFAULT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NULL',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=>enable, 2=>disable',
  `gateway_parameters` text COLLATE utf8mb4_unicode_ci,
  `supported_currencies` text COLLATE utf8mb4_unicode_ci,
  `crypto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: fiat currency, 1: crypto currency',
  `extra` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `gateways` */

insert  into `gateways`(`id`,`form_id`,`code`,`name`,`alias`,`image`,`status`,`gateway_parameters`,`supported_currencies`,`crypto`,`extra`,`description`,`created_at`,`updated_at`) values 
(1,0,101,'Paypal','Paypal','664dfd7220ea11716387186.png',1,'{\"paypal_email\":{\"title\":\"PayPal Email\",\"global\":true,\"value\":\"paypal@example.com\"}}','{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}',0,NULL,NULL,'2019-09-14 20:14:22','2024-09-17 11:44:42'),
(2,0,102,'Perfect Money','PerfectMoney','664dfdbde08241716387261.png',1,'{\"passphrase\":{\"title\":\"ALTERNATE PASSPHRASE\",\"global\":true,\"value\":\"hR26aw02Q1eEeUPSIfuwNypXX\"},\"wallet_id\":{\"title\":\"PM Wallet\",\"global\":false,\"value\":\"\"}}','{\"USD\":\"$\",\"EUR\":\"\\u20ac\"}',0,NULL,NULL,'2019-09-14 20:14:22','2021-05-21 08:35:33'),
(3,0,103,'Stripe Hosted','Stripe','664dfe040cd961716387332.png',1,'{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"CHANGE_ME_STRIPE_SECRET_KEY\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"CHANGE_ME_STRIPE_PUBLIC_KEY\"}}','{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}',0,NULL,NULL,'2019-09-14 20:14:22','2021-05-21 07:48:36'),
(4,0,104,'Skrill','Skrill','664dfde4de9ce1716387300.png',1,'{\"pay_to_email\":{\"title\":\"Skrill Email\",\"global\":true,\"value\":\"merchant@skrill.com\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"---\"}}','{\"AED\":\"AED\",\"AUD\":\"AUD\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"HRK\":\"HRK\",\"HUF\":\"HUF\",\"ILS\":\"ILS\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JOD\":\"JOD\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"KWD\":\"KWD\",\"MAD\":\"MAD\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"OMR\":\"OMR\",\"PLN\":\"PLN\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"SAR\":\"SAR\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TND\":\"TND\",\"TRY\":\"TRY\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\",\"COP\":\"COP\"}',0,NULL,NULL,'2019-09-14 20:14:22','2021-05-21 08:30:16'),
(5,0,105,'PayTM','Paytm','664dfdab9a3541716387243.png',1,'{\"MID\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"DIY12386817555501617\"},\"merchant_key\":{\"title\":\"Merchant Key\",\"global\":true,\"value\":\"bKMfNxPPf_QdZppa\"},\"WEBSITE\":{\"title\":\"Paytm Website\",\"global\":true,\"value\":\"DIYtestingweb\"},\"INDUSTRY_TYPE_ID\":{\"title\":\"Industry Type\",\"global\":true,\"value\":\"Retail\"},\"CHANNEL_ID\":{\"title\":\"CHANNEL ID\",\"global\":true,\"value\":\"WEB\"},\"transaction_url\":{\"title\":\"Transaction URL\",\"global\":true,\"value\":\"https:\\/\\/pguat.paytm.com\\/oltp-web\\/processTransaction\"},\"transaction_status_url\":{\"title\":\"Transaction STATUS URL\",\"global\":true,\"value\":\"https:\\/\\/pguat.paytm.com\\/paytmchecksum\\/paytmCallback.jsp\"}}','{\"AUD\":\"AUD\",\"ARS\":\"ARS\",\"BDT\":\"BDT\",\"BRL\":\"BRL\",\"BGN\":\"BGN\",\"CAD\":\"CAD\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"HRK\":\"HRK\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EGP\":\"EGP\",\"EUR\":\"EUR\",\"GEL\":\"GEL\",\"GHS\":\"GHS\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"KES\":\"KES\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"MAD\":\"MAD\",\"NPR\":\"NPR\",\"NZD\":\"NZD\",\"NGN\":\"NGN\",\"NOK\":\"NOK\",\"PKR\":\"PKR\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"ZAR\":\"ZAR\",\"KRW\":\"KRW\",\"LKR\":\"LKR\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"TRY\":\"TRY\",\"UGX\":\"UGX\",\"UAH\":\"UAH\",\"AED\":\"AED\",\"GBP\":\"GBP\",\"USD\":\"USD\",\"VND\":\"VND\",\"XOF\":\"XOF\"}',0,NULL,NULL,'2019-09-14 20:14:22','2021-05-21 10:00:44'),
(6,0,106,'Payeer','Payeer','664dfd5e82cc51716387166.png',1,'{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"866989763\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"7575\"}}','{\"USD\":\"USD\",\"EUR\":\"EUR\",\"RUB\":\"RUB\"}',0,'{\"status\":{\"title\": \"Status URL\",\"value\":\"ipn.Payeer\"}}',NULL,'2019-09-14 20:14:22','2022-08-28 17:11:14'),
(7,0,107,'PayStack','Paystack','664dfd95568601716387221.png',1,'{\"public_key\":{\"title\":\"Public key\",\"global\":true,\"value\":\"CHANGE_ME_STRIPE_PUBLIC_KEY\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"CHANGE_ME_STRIPE_SECRET_KEY\"}}','{\"USD\":\"USD\",\"NGN\":\"NGN\"}',0,'{\"callback\":{\"title\": \"Callback URL\",\"value\":\"ipn.Paystack\"},\"webhook\":{\"title\": \"Webhook URL\",\"value\":\"ipn.Paystack\"}}\r\n',NULL,'2019-09-14 20:14:22','2021-05-21 08:49:51'),
(9,0,109,'Flutterwave','Flutterwave','664dfcc5090a81716387013.png',1,'{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"----------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------------\"},\"encryption_key\":{\"title\":\"Encryption Key\",\"global\":true,\"value\":\"------------------\"}}','{\"BIF\":\"BIF\",\"CAD\":\"CAD\",\"CDF\":\"CDF\",\"CVE\":\"CVE\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"GHS\":\"GHS\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"KES\":\"KES\",\"LRD\":\"LRD\",\"MWK\":\"MWK\",\"MZN\":\"MZN\",\"NGN\":\"NGN\",\"RWF\":\"RWF\",\"SLL\":\"SLL\",\"STD\":\"STD\",\"TZS\":\"TZS\",\"UGX\":\"UGX\",\"USD\":\"USD\",\"XAF\":\"XAF\",\"XOF\":\"XOF\",\"ZMK\":\"ZMK\",\"ZMW\":\"ZMW\",\"ZWD\":\"ZWD\"}',0,NULL,NULL,'2019-09-14 20:14:22','2021-06-05 18:37:45'),
(10,0,110,'RazorPay','Razorpay','664dfdd5dc6711716387285.png',1,'{\"key_id\":{\"title\":\"Key Id\",\"global\":true,\"value\":\"rzp_test_kiOtejPbRZU90E\"},\"key_secret\":{\"title\":\"Key Secret \",\"global\":true,\"value\":\"osRDebzEqbsE1kbyQJ4y0re7\"}}','{\"INR\":\"INR\"}',0,NULL,NULL,'2019-09-14 20:14:22','2021-05-21 09:51:32'),
(11,0,111,'Stripe Storefront','StripeJs','664dfe135db781716387347.png',1,'{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"CHANGE_ME_STRIPE_SECRET_KEY\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"CHANGE_ME_STRIPE_PUBLIC_KEY\"}}','{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}',0,NULL,NULL,'2019-09-14 20:14:22','2021-05-21 07:53:10'),
(12,0,112,'Instamojo','Instamojo','664dfcdd1400b1716387037.png',1,'{\"api_key\":{\"title\":\"API KEY\",\"global\":true,\"value\":\"test_2241633c3bc44a3de84a3b33969\"},\"auth_token\":{\"title\":\"Auth Token\",\"global\":true,\"value\":\"test_279f083f7bebefd35217feef22d\"},\"salt\":{\"title\":\"Salt\",\"global\":true,\"value\":\"19d38908eeff4f58b2ddda2c6d86ca25\"}}','{\"INR\":\"INR\"}',0,NULL,NULL,'2019-09-14 20:14:22','2021-05-21 09:56:20'),
(13,0,501,'Blockchain','Blockchain','664dfbe83f9da1716386792.png',1,'{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"55529946-05ca-48ff-8710-f279d86b1cc5\"},\"xpub_code\":{\"title\":\"XPUB CODE\",\"global\":true,\"value\":\"xpub6CKQ3xxWyBoFAF83izZCSFUorptEU9AF8TezhtWeMU5oefjX3sFSBw62Lr9iHXPkXmDQJJiHZeTRtD9Vzt8grAYRhvbz4nEvBu3QKELVzFK\"}}','{\"BTC\":\"BTC\"}',1,NULL,NULL,'2019-09-14 20:14:22','2022-03-21 14:41:56'),
(15,0,503,'CoinPayments','Coinpayments','664dfc7d215d21716386941.png',1,'{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"---------------\"},\"private_key\":{\"title\":\"Private Key\",\"global\":true,\"value\":\"------------\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"93a1e014c4ad60a7980b4a7239673cb4\"}}','{\"BTC\":\"Bitcoin\",\"BTC.LN\":\"Bitcoin (Lightning Network)\",\"LTC\":\"Litecoin\",\"CPS\":\"CPS Coin\",\"VLX\":\"Velas\",\"APL\":\"Apollo\",\"AYA\":\"Aryacoin\",\"BAD\":\"Badcoin\",\"BCD\":\"Bitcoin Diamond\",\"BCH\":\"Bitcoin Cash\",\"BCN\":\"Bytecoin\",\"BEAM\":\"BEAM\",\"BITB\":\"Bean Cash\",\"BLK\":\"BlackCoin\",\"BSV\":\"Bitcoin SV\",\"BTAD\":\"Bitcoin Adult\",\"BTG\":\"Bitcoin Gold\",\"BTT\":\"BitTorrent\",\"CLOAK\":\"CloakCoin\",\"CLUB\":\"ClubCoin\",\"CRW\":\"Crown\",\"CRYP\":\"CrypticCoin\",\"CRYT\":\"CryTrExCoin\",\"CURE\":\"CureCoin\",\"DASH\":\"DASH\",\"DCR\":\"Decred\",\"DEV\":\"DeviantCoin\",\"DGB\":\"DigiByte\",\"DOGE\":\"Dogecoin\",\"EBST\":\"eBoost\",\"EOS\":\"EOS\",\"ETC\":\"Ether Classic\",\"ETH\":\"Ethereum\",\"ETN\":\"Electroneum\",\"EUNO\":\"EUNO\",\"EXP\":\"EXP\",\"Expanse\":\"Expanse\",\"FLASH\":\"FLASH\",\"GAME\":\"GameCredits\",\"GLC\":\"Goldcoin\",\"GRS\":\"Groestlcoin\",\"KMD\":\"Komodo\",\"LOKI\":\"LOKI\",\"LSK\":\"LSK\",\"MAID\":\"MaidSafeCoin\",\"MUE\":\"MonetaryUnit\",\"NAV\":\"NAV Coin\",\"NEO\":\"NEO\",\"NMC\":\"Namecoin\",\"NVST\":\"NVO Token\",\"NXT\":\"NXT\",\"OMNI\":\"OMNI\",\"PINK\":\"PinkCoin\",\"PIVX\":\"PIVX\",\"POT\":\"PotCoin\",\"PPC\":\"Peercoin\",\"PROC\":\"ProCurrency\",\"PURA\":\"PURA\",\"QTUM\":\"QTUM\",\"RES\":\"Resistance\",\"RVN\":\"Ravencoin\",\"RVR\":\"RevolutionVR\",\"SBD\":\"Steem Dollars\",\"SMART\":\"SmartCash\",\"SOXAX\":\"SOXAX\",\"STEEM\":\"STEEM\",\"STRAT\":\"STRAT\",\"SYS\":\"Syscoin\",\"TPAY\":\"TokenPay\",\"TRIGGERS\":\"Triggers\",\"TRX\":\" TRON\",\"UBQ\":\"Ubiq\",\"UNIT\":\"UniversalCurrency\",\"USDT\":\"Tether USD (Omni Layer)\",\"USDT.BEP20\":\"Tether USD (BSC Chain)\",\"USDT.ERC20\":\"Tether USD (ERC20)\",\"USDT.TRC20\":\"Tether USD (Tron/TRC20)\",\"VTC\":\"Vertcoin\",\"WAVES\":\"Waves\",\"XCP\":\"Counterparty\",\"XEM\":\"NEM\",\"XMR\":\"Monero\",\"XSN\":\"Stakenet\",\"XSR\":\"SucreCoin\",\"XVG\":\"VERGE\",\"XZC\":\"ZCoin\",\"ZEC\":\"ZCash\",\"ZEN\":\"Horizen\"}',1,NULL,NULL,'2019-09-14 20:14:22','2021-05-21 09:07:14'),
(16,0,504,'CoinPayments Fiat','CoinpaymentsFiat','664dfcb43e4821716386996.png',1,'{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"6515561\"}}','{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\"}',0,NULL,NULL,'2019-09-14 20:14:22','2021-05-21 09:07:44'),
(17,0,505,'Coingate','Coingate','664dfc56873fa1716386902.png',1,'{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"6354mwVCEw5kHzRJ6thbGo-N\"}}','{\"USD\":\"USD\",\"EUR\":\"EUR\"}',0,NULL,NULL,'2019-09-14 20:14:22','2022-03-30 16:24:57'),
(18,0,506,'Coinbase Commerce','CoinbaseCommerce','664dfc379fda91716386871.png',1,'{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"c47cd7df-d8e8-424b-a20a\"},\"secret\":{\"title\":\"Webhook Shared Secret\",\"global\":true,\"value\":\"55871878-2c32-4f64-ab66\"}}','{\"USD\":\"USD\",\"EUR\":\"EUR\",\"JPY\":\"JPY\",\"GBP\":\"GBP\",\"AUD\":\"AUD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CNY\":\"CNY\",\"SEK\":\"SEK\",\"NZD\":\"NZD\",\"MXN\":\"MXN\",\"SGD\":\"SGD\",\"HKD\":\"HKD\",\"NOK\":\"NOK\",\"KRW\":\"KRW\",\"TRY\":\"TRY\",\"RUB\":\"RUB\",\"INR\":\"INR\",\"BRL\":\"BRL\",\"ZAR\":\"ZAR\",\"AED\":\"AED\",\"AFN\":\"AFN\",\"ALL\":\"ALL\",\"AMD\":\"AMD\",\"ANG\":\"ANG\",\"AOA\":\"AOA\",\"ARS\":\"ARS\",\"AWG\":\"AWG\",\"AZN\":\"AZN\",\"BAM\":\"BAM\",\"BBD\":\"BBD\",\"BDT\":\"BDT\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"BIF\":\"BIF\",\"BMD\":\"BMD\",\"BND\":\"BND\",\"BOB\":\"BOB\",\"BSD\":\"BSD\",\"BTN\":\"BTN\",\"BWP\":\"BWP\",\"BYN\":\"BYN\",\"BZD\":\"BZD\",\"CDF\":\"CDF\",\"CLF\":\"CLF\",\"CLP\":\"CLP\",\"COP\":\"COP\",\"CRC\":\"CRC\",\"CUC\":\"CUC\",\"CUP\":\"CUP\",\"CVE\":\"CVE\",\"CZK\":\"CZK\",\"DJF\":\"DJF\",\"DKK\":\"DKK\",\"DOP\":\"DOP\",\"DZD\":\"DZD\",\"EGP\":\"EGP\",\"ERN\":\"ERN\",\"ETB\":\"ETB\",\"FJD\":\"FJD\",\"FKP\":\"FKP\",\"GEL\":\"GEL\",\"GGP\":\"GGP\",\"GHS\":\"GHS\",\"GIP\":\"GIP\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"GTQ\":\"GTQ\",\"GYD\":\"GYD\",\"HNL\":\"HNL\",\"HRK\":\"HRK\",\"HTG\":\"HTG\",\"HUF\":\"HUF\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"IMP\":\"IMP\",\"IQD\":\"IQD\",\"IRR\":\"IRR\",\"ISK\":\"ISK\",\"JEP\":\"JEP\",\"JMD\":\"JMD\",\"JOD\":\"JOD\",\"KES\":\"KES\",\"KGS\":\"KGS\",\"KHR\":\"KHR\",\"KMF\":\"KMF\",\"KPW\":\"KPW\",\"KWD\":\"KWD\",\"KYD\":\"KYD\",\"KZT\":\"KZT\",\"LAK\":\"LAK\",\"LBP\":\"LBP\",\"LKR\":\"LKR\",\"LRD\":\"LRD\",\"LSL\":\"LSL\",\"LYD\":\"LYD\",\"MAD\":\"MAD\",\"MDL\":\"MDL\",\"MGA\":\"MGA\",\"MKD\":\"MKD\",\"MMK\":\"MMK\",\"MNT\":\"MNT\",\"MOP\":\"MOP\",\"MRO\":\"MRO\",\"MUR\":\"MUR\",\"MVR\":\"MVR\",\"MWK\":\"MWK\",\"MYR\":\"MYR\",\"MZN\":\"MZN\",\"NAD\":\"NAD\",\"NGN\":\"NGN\",\"NIO\":\"NIO\",\"NPR\":\"NPR\",\"OMR\":\"OMR\",\"PAB\":\"PAB\",\"PEN\":\"PEN\",\"PGK\":\"PGK\",\"PHP\":\"PHP\",\"PKR\":\"PKR\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"RWF\":\"RWF\",\"SAR\":\"SAR\",\"SBD\":\"SBD\",\"SCR\":\"SCR\",\"SDG\":\"SDG\",\"SHP\":\"SHP\",\"SLL\":\"SLL\",\"SOS\":\"SOS\",\"SRD\":\"SRD\",\"SSP\":\"SSP\",\"STD\":\"STD\",\"SVC\":\"SVC\",\"SYP\":\"SYP\",\"SZL\":\"SZL\",\"THB\":\"THB\",\"TJS\":\"TJS\",\"TMT\":\"TMT\",\"TND\":\"TND\",\"TOP\":\"TOP\",\"TTD\":\"TTD\",\"TWD\":\"TWD\",\"TZS\":\"TZS\",\"UAH\":\"UAH\",\"UGX\":\"UGX\",\"UYU\":\"UYU\",\"UZS\":\"UZS\",\"VEF\":\"VEF\",\"VND\":\"VND\",\"VUV\":\"VUV\",\"WST\":\"WST\",\"XAF\":\"XAF\",\"XAG\":\"XAG\",\"XAU\":\"XAU\",\"XCD\":\"XCD\",\"XDR\":\"XDR\",\"XOF\":\"XOF\",\"XPD\":\"XPD\",\"XPF\":\"XPF\",\"XPT\":\"XPT\",\"YER\":\"YER\",\"ZMW\":\"ZMW\",\"ZWL\":\"ZWL\"}\r\n\r\n',0,'{\"endpoint\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.CoinbaseCommerce\"}}',NULL,'2019-09-14 20:14:22','2021-05-21 09:02:47'),
(24,0,113,'Paypal Express','PaypalSdk','664dfd81a377c1716387201.png',1,'{\"clientId\":{\"title\":\"Paypal Client ID\",\"global\":true,\"value\":\"Ae0-tixtSV7DvLwIh3Bmu7JvHrjh5EfGdXr_cEklKAVjjezRZ747BxKILiBdzlKKyp-W8W_T7CKH1Ken\"},\"clientSecret\":{\"title\":\"Client Secret\",\"global\":true,\"value\":\"EOhbvHZgFNO21soQJT1L9Q00M3rK6PIEsdiTgXRBt2gtGtxwRer5JvKnVUGNU5oE63fFnjnYY7hq3HBA\"}}','{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}',0,NULL,NULL,'2019-09-14 20:14:22','2021-05-21 06:01:08'),
(25,0,114,'Stripe Checkout','StripeV3','664dfe21196ce1716387361.png',1,'{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"CHANGE_ME_STRIPE_SECRET_KEY\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"CHANGE_ME_STRIPE_PUBLIC_KEY\"},\"end_point\":{\"title\":\"End Point Secret\",\"global\":true,\"value\":\"CHANGE_ME_STRIPE_WEBHOOK_SECRET\"}}','{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}',0,'{\"webhook\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.StripeV3\"}}',NULL,'2019-09-14 20:14:22','2021-05-21 07:58:38'),
(27,0,115,'Mollie','Mollie','664dfd0d48b421716387085.png',1,'{\"mollie_email\":{\"title\":\"Mollie Email \",\"global\":true,\"value\":\"mollie@example.com\"},\"api_key\":{\"title\":\"API KEY\",\"global\":true,\"value\":\"test_cucfwKTWfft9s337qsVfn5CC4vNkrn\"}}','{\"AED\":\"AED\",\"AUD\":\"AUD\",\"BGN\":\"BGN\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"HRK\":\"HRK\",\"HUF\":\"HUF\",\"ILS\":\"ILS\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\"}',0,NULL,NULL,'2019-09-14 20:14:22','2021-05-21 09:44:45'),
(30,0,116,'Cashmaal','Cashmaal','664dfbf8bff2c1716386808.png',1,'{\"web_id\":{\"title\":\"Web Id\",\"global\":true,\"value\":\"3748\"},\"ipn_key\":{\"title\":\"IPN Key\",\"global\":true,\"value\":\"546254628759524554647987\"}}','{\"PKR\":\"PKR\",\"USD\":\"USD\"}',0,'{\"webhook\":{\"title\": \"IPN URL\",\"value\":\"ipn.Cashmaal\"}}',NULL,NULL,'2021-06-22 15:05:04'),
(36,0,119,'Mercado Pago','MercadoPago','664dfcf15fb551716387057.png',1,'{\"access_token\":{\"title\":\"Access Token\",\"global\":true,\"value\":\"APP_USR-7924565816849832-082312-21941521997fab717db925cf1ea2c190-1071840315\"}}','{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}',0,NULL,NULL,NULL,'2022-09-14 14:41:14'),
(37,0,120,'Authorize.net','Authorize','664dfbac8036a1716386732.png',1,'{\"login_id\":{\"title\":\"Login ID\",\"global\":true,\"value\":\"59e4P9DBcZv\"},\"transaction_key\":{\"title\":\"Transaction Key\",\"global\":true,\"value\":\"47x47TJyLw2E7DbR\"}}','{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}',0,NULL,NULL,NULL,'2022-08-28 16:33:06'),
(46,0,121,'NMI','NMI','664dfd1dd6b5b1716387101.png',1,'{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"2F822Rw39fx762MaV7Yy86jXGTC7sCDy\"}}','{\"AED\":\"AED\",\"ARS\":\"ARS\",\"AUD\":\"AUD\",\"BOB\":\"BOB\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"RUB\":\"RUB\",\"SEC\":\"SEC\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TRY\":\"TRY\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\"}',0,NULL,NULL,NULL,'2022-08-28 17:32:31'),
(48,0,122,'Two Checkout','TwoCheckout','664dfe3a690001716387386.png',1,'{\"merchant_code\":{\"title\":\"Merchant Code\",\"global\":true,\"value\":\"253248016872\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"eQM)ID@&vG84u!O*g[p+\"}}','{\"AED\":\"AED\",\"AFN\":\"AFN\",\"ALL\":\"ALL\",\"ARS\":\"ARS\",\"AUD\":\"AUD\",\"AZN\":\"AZN\",\"BBD\":\"BBD\",\"BDT\":\"BDT\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"BMD\":\"BMD\",\"BND\":\"BND\",\"BOB\":\"BOB\",\"BRL\":\"BRL\",\"BSD\":\"BSD\",\"BWP\":\"BWP\",\"BYN\":\"BYN\",\"BZD\":\"BZD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"CRC\":\"CRC\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"DOP\":\"DOP\",\"DZD\":\"DZD\",\"EGP\":\"EGP\",\"EUR\":\"EUR\",\"FJD\":\"FJD\",\"GBP\":\"GBP\",\"GTQ\":\"GTQ\",\"HKD\":\"HKD\",\"HNL\":\"HNL\",\"HRK\":\"HRK\",\"HTG\":\"HTG\",\"HUF\":\"HUF\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"INR\":\"INR\",\"JMD\":\"JMD\",\"JOD\":\"JOD\",\"JPY\":\"JPY\",\"KES\":\"KES\",\"KRW\":\"KRW\",\"KWD\":\"KWD\",\"KZT\":\"KZT\",\"LAK\":\"LAK\",\"LBP\":\"LBP\",\"LRD\":\"LRD\",\"MAD\":\"MAD\",\"MDL\":\"MDL\",\"MMK\":\"MMK\",\"MOP\":\"MOP\",\"MRU\":\"MRU\",\"MUR\":\"MUR\",\"MVR\":\"MVR\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NAD\":\"NAD\",\"NGN\":\"NGN\",\"NIO\":\"NIO\",\"NOK\":\"NOK\",\"NPR\":\"NPR\",\"NZD\":\"NZD\",\"OMR\":\"OMR\",\"PAB\":\"PAB\",\"PEN\":\"PEN\",\"PGK\":\"PGK\",\"PHP\":\"PHP\",\"PKR\":\"PKR\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"RUB\":\"RUB\",\"SAR\":\"SAR\",\"SBD\":\"SBD\",\"SCR\":\"SCR\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"SVC\":\"SVC\",\"SYP\":\"SYP\",\"THB\":\"THB\",\"TND\":\"TND\",\"TOP\":\"TOP\",\"TRY\":\"TRY\",\"TTD\":\"TTD\",\"TWD\":\"TWD\",\"UAH\":\"UAH\",\"USD\":\"USD\",\"UYU\":\"UYU\",\"VEF\":\"VEF\",\"VND\":\"VND\",\"VUV\":\"VUV\",\"WST\":\"WST\",\"XCD\":\"XCD\",\"XOF\":\"XOF\",\"YER\":\"YER\",\"ZAR\":\"ZAR\"}',0,'{\"ipn\":{\"title\": \"Approve URL\",\"value\":\"ipn.TwoCheckout\"}}',NULL,NULL,'2022-08-31 13:21:15'),
(50,0,510,'Binance','Binance','664dfbc84638c1716386760.png',1,'{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"tsu3tjiq0oqfbtmlbevoeraxhfbp3brejnm9txhjxcp4to29ujvakvfl1ibsn3ja\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"jzngq4t04ltw8d4iqpi7admfl8tvnpehxnmi34id1zvfaenbwwvsvw7llw3zdko8\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"231129033\"}}','{\"BTC\":\"Bitcoin\",\"USD\":\"USD\",\"BNB\":\"BNB\"}',1,'{\"cron\":{\"title\": \"Cron Job URL\",\"value\":\"ipn.Binance\"}}',NULL,NULL,'2023-02-14 12:08:04'),
(51,0,124,'SslCommerz','SslCommerz','664dfdf36a9af1716387315.png',1,'{\"store_id\": {\"title\": \"Store ID\",\"global\": true,\"value\": \"---------\"},\"store_password\": {\"title\": \"Store Password\",\"global\": true,\"value\": \"----------\"}}','{\"BDT\":\"BDT\",\"USD\":\"USD\",\"EUR\":\"EUR\",\"SGD\":\"SGD\",\"INR\":\"INR\",\"MYR\":\"MYR\"}',0,NULL,NULL,NULL,'2023-05-06 14:43:01'),
(52,0,125,'Aamarpay','Aamarpay','664dfb74187251716386676.png',1,'{\"store_id\":{\"title\":\"Store ID\",\"global\":true,\"value\":\"---------\"},\"signature_key\":{\"title\":\"Signature Key\",\"global\":true,\"value\":\"----------\"}}','{\"BDT\":\"BDT\"}',0,NULL,NULL,NULL,'2024-06-10 10:37:23'),
(54,21,1000,'test','test','66bdd58ac35871723717002.jpg',1,'[]','[]',0,NULL,'<h3 style=\"margin-top: 5px; margin-bottom: 15px; font-weight: var(--fw-600); line-height: 1.5; font-size: 20px; position: relative; color: rgb(37, 37, 37); transition: 0.2s; font-family: Poppins, &quot;IBM Plex Sans Thai&quot;, sans-serif; background-color: rgb(246, 246, 246);\">Benefits of Term Life Insurance Contract</h3><ol style=\"padding-left: 18px; margin-bottom: 1rem; color: rgb(22, 36, 62); font-family: Poppins, &quot;IBM Plex Sans Thai&quot;, sans-serif; font-size: 15px; background-color: rgb(246, 246, 246);\"><li style=\"margin-top: 2px; margin-bottom: 2px;\">Death Benefit:<ul style=\"padding-top: 5px; padding-bottom: 5px;\"><li style=\"margin-top: 2px; margin-bottom: 2px; display: flex; gap: 8px;\">Lump Sum Payment: In the event of the insured\'s death during the policy term, the beneficiaries will receive a lump sum payment of [Coverage Amount]. This benefit provides financial support to cover living expenses, debts, education costs, and other financial needs.</li></ul></li><li style=\"margin-top: 2px; margin-bottom: 2px;\">Affordability:<ul style=\"padding-top: 5px; padding-bottom: 5px;\"><li style=\"margin-top: 2px; margin-bottom: 2px; display: flex; gap: 8px;\">Lower Premiums: Term life insurance generally offers lower premiums compared to permanent life insurance, making it an affordable option for individuals seeking significant coverage for a specific period.</li></ul></li><li style=\"margin-top: 2px; margin-bottom: 2px;\">Flexibility:<ul style=\"padding-top: 5px; padding-bottom: 5px;\"><li style=\"margin-top: 2px; margin-bottom: 2px; display: flex; gap: 8px;\">Term Options: Policyholders can choose the term length that best fits their needs, such as 10, 20, or 30 years, ensuring coverage during critical periods like raising children, paying off a mortgage, or during working years.</li><li style=\"margin-top: 2px; margin-bottom: 2px; display: flex; gap: 8px;\">Conversion Option: Many term life policies include a conversion option, allowing policyholders to convert their term policy to a permanent life insurance policy without undergoing additional medical exams, ensuring continued coverage.</li></ul></li><li style=\"margin-top: 2px; margin-bottom: 2px;\">Financial Security:<ul style=\"padding-top: 5px; padding-bottom: 5px;\"><li style=\"margin-top: 2px; margin-bottom: 2px; display: flex; gap: 8px;\">Income Replacement: Provides financial security to beneficiaries, ensuring they can maintain their standard of living and cover essential expenses in the absence of the primary earner.</li><li style=\"margin-top: 2px; margin-bottom: 2px; display: flex; gap: 8px;\">Debt Protection: Helps beneficiaries pay off outstanding debts such as mortgages, loans, and credit card balances, preventing financial burden.</li></ul></li><li style=\"margin-top: 2px; margin-bottom: 2px;\">Simplicity</li></ol>','2024-08-15 17:16:42','2024-08-15 17:16:42');

/*Table structure for table `general_settings` */

DROP TABLE IF EXISTS `general_settings`;

CREATE TABLE `general_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cur_text` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'currency text',
  `cur_sym` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'currency symbol',
  `email_from` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_from_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_template` text COLLATE utf8mb4_unicode_ci,
  `sms_template` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_template` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_color` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_color` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_config` text COLLATE utf8mb4_unicode_ci COMMENT 'email configuration',
  `sms_config` text COLLATE utf8mb4_unicode_ci,
  `firebase_config` text COLLATE utf8mb4_unicode_ci,
  `global_shortcodes` text COLLATE utf8mb4_unicode_ci,
  `multi_language` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Enable ⇾ 1, Disabled ⇾ 0',
  `kv` tinyint(1) NOT NULL DEFAULT '0',
  `ev` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'email verification, 0 - dont check, 1 - check',
  `en` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'email notification, 0 - dont send, 1 - send',
  `sv` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'mobile verication, 0 - dont check, 1 - check',
  `sn` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'sms notification, 0 - dont send, 1 - send',
  `pn` tinyint(1) NOT NULL DEFAULT '1',
  `force_ssl` tinyint(1) NOT NULL DEFAULT '0',
  `in_app_payment` tinyint(1) NOT NULL DEFAULT '1',
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT '0',
  `secure_password` tinyint(1) NOT NULL DEFAULT '0',
  `agree` tinyint(1) NOT NULL DEFAULT '0',
  `registration` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Off	, 1: On',
  `active_template` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `socialite_credentials` text COLLATE utf8mb4_unicode_ci,
  `system_customized` int(11) NOT NULL DEFAULT '0',
  `paginate_number` int(11) NOT NULL DEFAULT '0',
  `currency_format` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>Both\r\n2=>Text Only\r\n3=>Symbol Only',
  `deposit_commission` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `last_cron` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `available_version` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deposit_refcommission` tinyint(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `general_settings` */

insert  into `general_settings`(`id`,`site_name`,`cur_text`,`cur_sym`,`email_from`,`email_from_name`,`email_template`,`sms_template`,`sms_from`,`push_title`,`push_template`,`base_color`,`secondary_color`,`mail_config`,`sms_config`,`firebase_config`,`global_shortcodes`,`multi_language`,`kv`,`ev`,`en`,`sv`,`sn`,`pn`,`force_ssl`,`in_app_payment`,`maintenance_mode`,`secure_password`,`agree`,`registration`,`active_template`,`socialite_credentials`,`system_customized`,`paginate_number`,`currency_format`,`deposit_commission`,`last_cron`,`available_version`,`deposit_refcommission`,`created_at`,`updated_at`) values 
(1,'To-App','USD','$','no-reply@example.com','{{site_name}}','<html>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n<title>\n</title>\n<style type=\"text/css\">\n	.ReadMsgBody {\n		width: 100%;\n		background-color: #ffffff;\n	}\n	.ExternalClass {\n		width: 100%;\n		background-color: #ffffff;\n	}\n	.ExternalClass,\n	.ExternalClass p,\n	.ExternalClass span,\n	.ExternalClass font,\n	.ExternalClass td,\n	.ExternalClass div {\n		line-height: 100%;\n	}\n	html {\n		width: 100%;\n	}\n	body {\n		-webkit-text-size-adjust: none;\n		-ms-text-size-adjust: none;\n		margin: 0;\n		padding: 0;\n	}\n	table {\n		border-spacing: 0;\n		table-layout: fixed;\n		margin: 0 auto;\n		border-collapse: collapse;\n	}\n	table table table {\n		table-layout: auto;\n	}\n	.yshortcuts a {\n		border-bottom: none !important;\n	}\n	img:hover {\n		opacity: 0.9 !important;\n	}\n	a {\n		color: #0087ff;\n		text-decoration: none;\n	}\n	.textbutton a {\n		font-family: \"open sans\", arial, sans-serif !important;\n	}\n	.btn-link a {\n		color: #ffffff !important;\n	}\n	@media only screen and (max-width: 480px) {\n		body {\n			width: auto !important;\n		}\n		*[class=\"table-inner\"] {\n			width: 90% !important;\n			text-align: center !important;\n		}\n		*[class=\"table-full\"] {\n			width: 100% !important;\n			text-align: center !important;\n		} /* image */\n		img[class=\"img1\"] {\n			width: 100% !important;\n			height: auto !important;\n		}\n	}\n\n</style>\n<table bgcolor=\"#030442\" width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n	<tbody>\n		<tr>\n			<td height=\"50\">\n			</td>\n		</tr>\n		<tr>\n			<td align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\n				<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n					<tbody>\n						<tr>\n							<td align=\"center\" width=\"600\">\n								<table class=\"table-inner\" width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n									<tbody>\n										<tr>\n											<td bgcolor=\"#0087ff\" style=\"border-top-left-radius:6px; border-top-right-radius:6px;text-align:center;vertical-align:top;font-size:0;\" align=\"center\">\n												<table width=\"90%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n													<tbody>\n														<tr>\n															<td height=\"20\">\n															</td>\n														</tr>\n														<tr>\n															<td align=\"center\" style=\"font-family: Open sans, Arial, sans-serif; color:#FFFFFF; font-size:16px; font-weight: bold;\">\n															This is a System Generated Email</td>\n														</tr>\n														<tr>\n															<td height=\"20\">\n															</td>\n														</tr>\n													</tbody>\n												</table>\n											</td>\n										</tr>\n									</tbody>\n								</table>\n								<table class=\"table-inner\" width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n									<tbody>\n										<tr>\n											<td bgcolor=\"#FFFFFF\" align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\n												<table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n													<tbody>\n														<tr>\n															<td height=\"35\">\n															</td>\n														</tr>\n														<tr>\n															<td align=\"center\" style=\"vertical-align:top;font-size:0;\">\n																\n															</td>\n														</tr>\n														<tr>\n															<td height=\"40\"></td>\n														</tr>\n														<tr>\n															<td align=\"center\" style=\"font-family: Open Sans, Arial, sans-serif; font-size: 22px;color:#414a51;font-weight: bold;\">\n															Hello {{fullname}} ({{username}}) </td>\n														</tr>\n														<tr>\n															<td align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\n																<table width=\"40\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n																	<tbody>\n																		<tr>\n																			<td height=\"20\" style=\" border-bottom:3px solid #0087ff;\">\n																			</td>\n																		</tr>\n																	</tbody>\n																</table>\n															</td>\n														</tr>\n														<tr>\n															<td height=\"30\"></td>\n														</tr>\n														<tr>\n															<td align=\"left\" style=\"font-family: Open sans, Arial, sans-serif; color:#7f8c8d; font-size:16px; line-height: 28px;\">\n															{{message}}</td>\n														</tr>\n														<tr>\n															<td height=\"60\"></td>\n														</tr>\n													</tbody>\n												</table>\n											</td>\n										</tr>\n										<tr>\n											<td height=\"45\" align=\"center\" bgcolor=\"#f4f4f4\" style=\"border-bottom-left-radius:6px;border-bottom-right-radius:6px;\">\n												<table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n													<tbody>\n														<tr>\n															<td height=\"10\"></td>\n														</tr>\n														<tr>\n															<td class=\"preference-link\" align=\"center\" style=\"font-family: Open sans, Arial, sans-serif; color:#95a5a6; font-size:14px;\">\n																© 2024 <a href=\"#\"></a> &nbsp;. All Rights Reserved. </td>\n														</tr>\n														<tr>\n															<td height=\"10\"></td>\n														</tr>\n													</tbody>\n												</table>\n											</td>\n										</tr>\n									</tbody>\n								</table>\n							</td>\n						</tr>\n					</tbody>\n				</table>\n			</td>\n		</tr>\n		<tr>\n			<td height=\"60\"></td>\n		</tr>\n	</tbody>\n</table>\n</html>','hi {{fullname}} ({{username}}), {{message}}','ViserAdmin','{{site_name}}','{{site_name}}','ACE600','20204E','{\"name\":\"smtp\",\"host\":\"smtp.gmail.com\",\"port\":\"587\",\"enc\":\"tls\",\"username\":\"support@example.com\",\"password\":\"bjdaopwmiiansqfa\"}','{\"name\":\"nexmo\",\"clickatell\":{\"api_key\":\"----------------\"},\"infobip\":{\"username\":\"------------8888888\",\"password\":\"-----------------\"},\"message_bird\":{\"api_key\":\"-------------------\"},\"nexmo\":{\"api_key\":\"----------------------\",\"api_secret\":\"----------------------\"},\"sms_broadcast\":{\"username\":\"----------------------\",\"password\":\"-----------------------------\"},\"twilio\":{\"account_sid\":\"-----------------------\",\"auth_token\":\"---------------------------\",\"from\":\"----------------------\"},\"text_magic\":{\"username\":\"-----------------------\",\"apiv2_key\":\"-------------------------------\"},\"custom\":{\"method\":\"get\",\"url\":\"https:\\/\\/hostname\\/demo-api-v1\",\"headers\":{\"name\":[\"api_key\"],\"value\":[\"test_api 555\"]},\"body\":{\"name\":[\"from_number\"],\"value\":[\"5657545757\"]}}}',NULL,'{\n    \"site_name\":\"Name of your site\",\n    \"site_currency\":\"Currency of your site\",\n    \"currency_symbol\":\"Symbol of currency\"\n}',1,0,1,1,0,0,1,0,0,0,1,0,1,'basic','{\"google\":{\"client_id\":\"------------\",\"client_secret\":\"-------------\",\"status\":1},\"facebook\":{\"client_id\":\"------\",\"client_secret\":\"------\",\"status\":1},\"linkedin\":{\"client_id\":\"-----\",\"client_secret\":\"-----\",\"status\":1}}',0,20,1,1.00000000,'2024-09-20 09:29:28','3.0',1,NULL,'2024-11-03 15:52:00');

/*Table structure for table `investments` */

DROP TABLE IF EXISTS `investments`;

CREATE TABLE `investments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `trx` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `interest_type` tinyint(1) NOT NULL COMMENT ' 1=>PERCENT, 2=>FIXED',
  `interest_amount` decimal(28,8) NOT NULL,
  `total_return` int(11) NOT NULL,
  `total_paid` int(11) NOT NULL,
  `price_plan_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `next_return_date` datetime NOT NULL,
  `expire_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '2=>RUNNING, 1=>COMPLETED',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `investments` */

/*Table structure for table `languages` */

DROP TABLE IF EXISTS `languages`;

CREATE TABLE `languages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: not default language, 1: default language',
  `image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `languages` */

insert  into `languages`(`id`,`name`,`code`,`is_default`,`image`,`created_at`,`updated_at`) values 
(1,'English','en',1,'6665bd9f44f471717943711.png','2020-07-06 10:47:55','2024-06-09 15:35:11');

/*Table structure for table `notification_logs` */

DROP TABLE IF EXISTS `notification_logs`;

CREATE TABLE `notification_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sender` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_from` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_to` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `notification_type` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_read` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=395 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `notification_logs` */


/*Table structure for table `notification_templates` */

DROP TABLE IF EXISTS `notification_templates`;

CREATE TABLE `notification_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `act` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_body` text COLLATE utf8mb4_unicode_ci,
  `sms_body` text COLLATE utf8mb4_unicode_ci,
  `push_body` text COLLATE utf8mb4_unicode_ci,
  `shortcodes` text COLLATE utf8mb4_unicode_ci,
  `email_status` tinyint(1) NOT NULL DEFAULT '1',
  `email_sent_from_name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_sent_from_address` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_status` tinyint(1) NOT NULL DEFAULT '1',
  `sms_sent_from` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `notification_templates` */

insert  into `notification_templates`(`id`,`act`,`name`,`subject`,`push_title`,`email_body`,`sms_body`,`push_body`,`shortcodes`,`email_status`,`email_sent_from_name`,`email_sent_from_address`,`sms_status`,`sms_sent_from`,`push_status`,`created_at`,`updated_at`) values 
(1,'BAL_ADD','Balance - Added','Your Account has been Credited','{{site_name}} - Balance Added','<div><div style=\"font-family: Montserrat, sans-serif;\">{{amount}} {{site_currency}} has been added to your account .</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><span style=\"color: rgb(33, 37, 41); font-family: Montserrat, sans-serif;\">Your Current Balance is :&nbsp;</span><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">{{post_balance}}&nbsp; {{site_currency}}&nbsp;</span></font><br></div><div><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></font></div><div>Admin note:&nbsp;<span style=\"color: rgb(33, 37, 41); font-size: 12px; font-weight: 600; white-space: nowrap; text-align: var(--bs-body-text-align);\">{{remark}}</span></div>','{{amount}} {{site_currency}} credited in your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin note is \"{{remark}}\"',NULL,'{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 19:00:00','2022-04-03 09:18:28'),
(2,'BAL_SUB','Balance - Subtracted','Your Account has been Debited','{{site_name}} - Balance Subtracted','<div style=\"font-family: Montserrat, sans-serif;\">{{amount}} {{site_currency}} has been subtracted from your account .</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><span style=\"color: rgb(33, 37, 41); font-family: Montserrat, sans-serif;\">Your Current Balance is :&nbsp;</span><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">{{post_balance}}&nbsp; {{site_currency}}</span></font><br><div><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></font></div><div>Admin Note: {{remark}}</div>','{{amount}} {{site_currency}} debited from your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin Note is {{remark}}',NULL,'{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 19:00:00','2022-04-03 09:24:11'),
(3,'DEPOSIT_COMPLETE','Deposit - Automated - Successful','Deposit Completed Successfully','{{site_name}} - Deposit successful','<div>Your deposit of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>has been completed Successfully.<span style=\"font-weight: bolder;\"><br></span></div><div><span style=\"font-weight: bolder;\"><br></span></div><div><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div><br></div><div>Amount : {{amount}} {{site_currency}}</div><div>Charge:&nbsp;<font color=\"#000000\">{{charge}} {{site_currency}}</font></div><div><br></div><div>Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div>Received : {{method_amount}} {{method_currency}}<br></div><div>Paid via :&nbsp; {{method_name}}</div><div><br></div><div>Transaction Number : {{trx}}</div><div><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div><font size=\"5\">Your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}} {{site_currency}}</span></font></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>','{{amount}} {{site_currency}} Deposit successfully by {{method_name}}',NULL,'{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 19:00:00','2022-04-03 09:25:43'),
(4,'DEPOSIT_APPROVE','Deposit - Manual - Approved','Your Deposit is Approved','{{site_name}} - Deposit Request Approved','<div style=\"font-family: Montserrat, sans-serif;\">Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>is Approved .<span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{site_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{site_currency}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Received : {{method_amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Paid via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\">Your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}} {{site_currency}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div>','Admin Approve Your {{amount}} {{site_currency}} payment request by {{method_name}} transaction : {{trx}}',NULL,'{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 19:00:00','2022-04-03 09:26:07'),
(5,'DEPOSIT_REJECT','Deposit - Manual - Rejected','Your Deposit Request is Rejected','{{site_name}} - Deposit Request Rejected','<div style=\"font-family: Montserrat, sans-serif;\">Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}} has been rejected</span>.<span style=\"font-weight: bolder;\"><br></span></div><div><br></div><div><br></div><div style=\"font-family: Montserrat, sans-serif;\">Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Received : {{method_amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Paid via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge: {{charge}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number was : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">if you have any queries, feel free to contact us.<br></div><br style=\"font-family: Montserrat, sans-serif;\"><div style=\"font-family: Montserrat, sans-serif;\"><br><br></div><span style=\"color: rgb(33, 37, 41); font-family: Montserrat, sans-serif;\">{{rejection_message}}</span><br>','Admin Rejected Your {{amount}} {{site_currency}} payment request by {{method_name}}\r\n\r\n{{rejection_message}}',NULL,'{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"rejection_message\":\"Rejection message by the admin\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 19:00:00','2022-04-05 10:45:27'),
(6,'DEPOSIT_REQUEST','Deposit - Manual - Requested','Deposit Request Submitted Successfully',NULL,'<div>Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>submitted successfully<span style=\"font-weight: bolder;\">&nbsp;.<br></span></div><div><span style=\"font-weight: bolder;\"><br></span></div><div><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div><br></div><div>Amount : {{amount}} {{site_currency}}</div><div>Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{site_currency}}</font></div><div><br></div><div>Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div>Payable : {{method_amount}} {{method_currency}}<br></div><div>Pay via :&nbsp; {{method_name}}</div><div><br></div><div>Transaction Number : {{trx}}</div><div><br></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>','{{amount}} {{site_currency}} Deposit requested by {{method_name}}. Charge: {{charge}} . Trx: {{trx}}',NULL,'{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 19:00:00','2022-04-03 09:29:19'),
(7,'PASS_RESET_CODE','Password - Reset - Code','Password Reset','{{site_name}} Password Reset Code','<div style=\"font-family: Montserrat, sans-serif;\">We have received a request to reset the password for your account on&nbsp;<span style=\"font-weight: bolder;\">{{time}} .<br></span></div><div style=\"font-family: Montserrat, sans-serif;\">Requested From IP:&nbsp;<span style=\"font-weight: bolder;\">{{ip}}</span>&nbsp;using&nbsp;<span style=\"font-weight: bolder;\">{{browser}}</span>&nbsp;on&nbsp;<span style=\"font-weight: bolder;\">{{operating_system}}&nbsp;</span>.</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><br style=\"font-family: Montserrat, sans-serif;\"><div style=\"font-family: Montserrat, sans-serif;\"><div>Your account recovery code is:&nbsp;&nbsp;&nbsp;<font size=\"6\"><span style=\"font-weight: bolder;\">{{code}}</span></font></div><div><br></div></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\" color=\"#CC0000\">If you do not wish to reset your password, please disregard this message.&nbsp;</font><br></div><div><font size=\"4\" color=\"#CC0000\"><br></font></div>','Your account recovery code is: {{code}}',NULL,'{\"code\":\"Verification code for password reset\",\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}',1,'no-reply@to-app.com','no-reply@to-app.com',0,NULL,0,'2021-11-03 19:00:00','2024-09-26 21:22:00'),
(8,'PASS_RESET_DONE','Password - Reset - Confirmation','You have reset your password',NULL,'<p style=\"text-align: center; font-family: Montserrat, sans-serif;\"><br></p><p style=\"text-align: center; font-family: Montserrat, sans-serif;\"><br></p><p style=\"font-family: Montserrat, sans-serif;\">You have successfully reset your password.</p><p style=\"font-family: Montserrat, sans-serif;\">You changed from&nbsp; IP:&nbsp;<span style=\"font-weight: bolder;\">{{ip}}</span>&nbsp;using&nbsp;<span style=\"font-weight: bolder;\">{{browser}}</span>&nbsp;on&nbsp;<span style=\"font-weight: bolder;\">{{operating_system}}&nbsp;</span>&nbsp;on&nbsp;<span style=\"font-weight: bolder;\">{</span><img style=\"color: var(--bs-card-color); background-color: var(--bs-card-bg); font-family: Poppins, sans-serif; font-size: 1rem; text-align: var(--bs-body-text-align);\"><span style=\"font-weight: bolder; font-size: 0.875rem; background-color: var(--bs-card-bg); text-align: var(--bs-body-text-align);\">{time}}</span></p><p style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></p><p style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><font color=\"#ff0000\">If you did not change that, please contact us as soon as possible.</font></span></p>','Your password has been changed successfully',NULL,'{\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 19:00:00','2024-09-26 21:34:50'),
(9,'ADMIN_SUPPORT_REPLY','Support - Reply','Reply Support Ticket','{{site_name}} - Support Ticket Replied','<div><p><span data-mce-style=\"font-size: 11pt;\" style=\"font-size: 11pt;\"><span style=\"font-weight: bolder;\">A member from our support team has replied to the following ticket:</span></span></p><p><span style=\"font-weight: bolder;\"><span data-mce-style=\"font-size: 11pt;\" style=\"font-size: 11pt;\"><span style=\"font-weight: bolder;\"><br></span></span></span></p><p><span style=\"font-weight: bolder;\">[Ticket#{{ticket_id}}] {{ticket_subject}}<br><br>Click here to reply:&nbsp; {{link}}</span></p><p>----------------------------------------------</p><p>Here is the reply :<br></p><p>{{reply}}<br></p></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>','Your Ticket#{{ticket_id}} :  {{ticket_subject}} has been replied.',NULL,'{\"ticket_id\":\"ID of the support ticket\",\"ticket_subject\":\"Subject  of the support ticket\",\"reply\":\"Reply made by the admin\",\"link\":\"URL to view the support ticket\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 19:00:00','2022-03-21 03:47:51'),
(10,'EVER_CODE','Verification - Email','Please verify your email address',NULL,'<div style=\"text-align: center;\"><img src=\"https://check-insure.com/img/logo.svg\" alt=\"\" align=\"none\"><br></div>Welcome to check-insure.com<div><br><div><div style=\"\"><div style=\"\"><font color=\"#212529\" face=\"Montserrat, sans-serif\">To complete your quotation, please verify your email address by adding this code to our verification page.</font></div><div style=\"\"><font face=\"Montserrat, sans-serif\"><br></font></div><div style=\"\"><br></div></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"color: rgb(33, 37, 41); background-color: var(--bs-card-bg); font-size: 1rem; text-align: var(--bs-body-text-align);\">Your verification code is:</span><font size=\"6\"><span style=\"font-weight: bolder;\">&nbsp;{{code}}</span></font></div></div></div>','---',NULL,'{\"code\":\"Email verification code\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 19:00:00','2024-09-26 21:25:42'),
(11,'SVER_CODE','Verification - SMS','Verify Your Mobile Number',NULL,'---','Your phone verification code is: {{code}}',NULL,'{\"code\":\"SMS Verification Code\"}',0,NULL,NULL,1,NULL,0,'2021-11-03 19:00:00','2022-03-21 02:24:37'),
(12,'WITHDRAW_APPROVE','Withdraw - Approved','Withdraw Request has been Processed and your money is sent','{{site_name}} - Withdrawal Request Approved','<div style=\"font-family: Montserrat, sans-serif;\">Your withdraw request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp; via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>has been Processed Successfully.<span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your withdraw:<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{site_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{site_currency}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">You will get: {{method_amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">-----</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\">Details of Processed Payment :</font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\"><span style=\"font-weight: bolder;\">{{admin_details}}</span></font></div>','Admin Approve Your {{amount}} {{site_currency}} withdraw request by {{method_name}}. Transaction {{trx}}',NULL,'{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"admin_details\":\"Details provided by the admin\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 19:00:00','2022-03-21 03:50:16'),
(13,'WITHDRAW_REJECT','Withdraw - Rejected','Withdraw Request has been Rejected and your money is refunded to your account','{{site_name}} - Withdrawal Request Rejected','<div style=\"font-family: Montserrat, sans-serif;\">Your withdraw request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp; via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>has been Rejected.<span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your withdraw:<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{site_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{site_currency}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">You should get: {{method_amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">----</div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"3\"><br></font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"3\">{{amount}} {{currency}} has been&nbsp;<span style=\"font-weight: bolder;\">refunded&nbsp;</span>to your account and your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}}</span><span style=\"font-weight: bolder;\">&nbsp;{{site_currency}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">-----</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\">Details of Rejection :</font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\"><span style=\"font-weight: bolder;\">{{admin_details}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br><br><br><br><br></div><div></div><div></div>','Admin Rejected Your {{amount}} {{site_currency}} withdraw request. Your Main Balance {{post_balance}}  {{method_name}} , Transaction {{trx}}',NULL,'{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this action\",\"admin_details\":\"Rejection message by the admin\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 19:00:00','2022-03-21 03:57:46'),
(14,'WITHDRAW_REQUEST','Withdraw - Requested','Withdraw Request Submitted Successfully','{{site_name}} - Requested for withdrawal','<div style=\"font-family: Montserrat, sans-serif;\">Your withdraw request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp; via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>has been submitted Successfully.<span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your withdraw:<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{site_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{site_currency}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">You will get: {{method_amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\">Your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}} {{site_currency}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br><br><br></div>','{{amount}} {{site_currency}} withdraw requested by {{method_name}}. You will get {{method_amount}} {{method_currency}} Trx: {{trx}}',NULL,'{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this transaction\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 19:00:00','2022-03-21 11:39:03'),
(15,'DEFAULT','Default Template','{{subject}}','{{subject}}','{{message}}','{{message}}','{{message}}','{\"subject\":\"Subject\",\"message\":\"Message\"}',1,NULL,NULL,1,NULL,1,'2019-09-14 20:14:22','2024-06-09 15:17:33'),
(16,'KYC_APPROVE','KYC Approved','KYC has been approved','{{site_name}} - KYC Approved',NULL,NULL,NULL,'[]',1,NULL,NULL,1,NULL,0,NULL,NULL),
(17,'KYC_REJECT','KYC Rejected','KYC has been rejected','{{site_name}} - KYC Rejected',NULL,NULL,NULL,'{\"reason\":\"Rejection Reason\"}',1,NULL,NULL,1,NULL,0,NULL,NULL),
(18,'LOGIN_CODE','Verification - OTP Login','Please verify Login Otp','Verify Login Otp','Welcome to check-insure.com<div><br><div><div style=\"\"><div style=\"\"><font color=\"#212529\" face=\"Montserrat, sans-serif\">To complete your quotation, please verify your email address by adding this code to our verification page.</font></div><div style=\"\"><font face=\"Montserrat, sans-serif\"><br></font></div><div style=\"\"><br></div></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"color: rgb(33, 37, 41); background-color: var(--bs-card-bg); font-size: 1rem; text-align: var(--bs-body-text-align);\">Your verification code is:</span><font size=\"6\"><span style=\"font-weight: bolder;\">&nbsp;{{code}}</span></font></div></div></div>','---',NULL,'{\"code\":\"Login Verification code\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 19:00:00','2024-08-06 18:02:34');

/*Table structure for table `package_buy_links` */

DROP TABLE IF EXISTS `package_buy_links`;

CREATE TABLE `package_buy_links` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `plan_id` int(10) DEFAULT NULL,
  `deposit_id` bigint(20) NOT NULL,
  `amount` decimal(28,2) DEFAULT NULL,
  `type` varchar(10) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1=paid,2=pending,3=failed',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;

/*Data for the table `package_buy_links` */

insert  into `package_buy_links`(`id`,`user_id`,`plan_id`,`deposit_id`,`amount`,`type`,`status`,`created_at`,`updated_at`) values 
(24,79,7,58,10.00,'month',2,'2024-09-20 17:26:48','2024-09-20 18:01:18'),
(25,79,10,0,1000.00,'year',0,'2024-09-20 17:27:39','2024-09-20 17:27:39'),
(26,79,10,0,100.00,'month',0,'2024-09-21 19:32:57','2024-09-21 19:32:57'),
(27,78,7,0,1000.00,'year',0,'2024-09-21 20:21:26','2024-09-21 20:21:26'),
(28,89,12,0,0.00,'month',0,'2024-09-27 22:47:51','2024-09-27 22:47:51');

/*Table structure for table `pages` */

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'template name',
  `secs` text COLLATE utf8mb4_unicode_ci,
  `seo_content` text COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pages` */

insert  into `pages`(`id`,`name`,`slug`,`tempname`,`secs`,`seo_content`,`is_default`,`created_at`,`updated_at`) values 
(1,'HOME','/','templates.basic.','[\"overview\",\"about\",\"package\",\"feature\",\"how_it_work\",\"latest_trx\",\"feature\",\"faq\",\"top_investor\",\"testimonial\",\"payment\"]',NULL,1,'2020-07-11 13:23:58','2022-11-07 17:35:34'),
(19,'Contact','contact','templates.basic.','',NULL,1,'2020-07-11 13:23:58','2022-11-07 17:35:34');

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */


/*Table structure for table `plans` */

DROP TABLE IF EXISTS `plans`;

CREATE TABLE `plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_amount` decimal(28,8) NOT NULL,
  `max_amount` decimal(28,8) NOT NULL,
  `total_return` int(11) NOT NULL COMMENT '''HOW MANY TIMES?''',
  `interest_type` tinyint(1) NOT NULL COMMENT '1⇒PERCENT, 2⇒FIXED',
  `interest` decimal(28,8) NOT NULL,
  `descript` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `monthprice` decimal(28,2) NOT NULL,
  `annualprice` decimal(28,2) NOT NULL,
  `monthprice1` decimal(28,2) NOT NULL,
  `monthprice2` decimal(28,2) NOT NULL,
  `monthprice3` decimal(28,2) NOT NULL,
  `agefrom` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ageto` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `health` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nicotin` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `sex` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1⇒ENABLE, 0⇒DISABLE',
  `category` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `term` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `monthprice6` decimal(28,2) NOT NULL,
  `monthprice7` decimal(28,2) NOT NULL,
  `monthprice8` decimal(28,2) NOT NULL,
  `annualprice1` decimal(28,2) NOT NULL,
  `annualprice2` decimal(28,2) NOT NULL,
  `annualprice3` decimal(28,2) NOT NULL,
  `annualprice4` decimal(28,2) NOT NULL,
  `annualprice5` decimal(28,2) NOT NULL,
  `annualprice6` decimal(28,2) NOT NULL,
  `annualprice7` decimal(28,2) NOT NULL,
  `annualprice8` decimal(28,2) NOT NULL,
  `monthprice4` decimal(28,2) NOT NULL,
  `monthprice5` decimal(28,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `plans` */

insert  into `plans`(`id`,`name`,`min_amount`,`max_amount`,`total_return`,`interest_type`,`interest`,`descript`,`monthprice`,`annualprice`,`monthprice1`,`monthprice2`,`monthprice3`,`agefrom`,`ageto`,`health`,`nicotin`,`sex`,`status`,`category`,`term`,`created_at`,`updated_at`,`monthprice6`,`monthprice7`,`monthprice8`,`annualprice1`,`annualprice2`,`annualprice3`,`annualprice4`,`annualprice5`,`annualprice6`,`annualprice7`,`annualprice8`,`monthprice4`,`monthprice5`) values 
(7,'Plan4',0.00000000,0.00000000,0,0,10.00000000,'insurance',10.00,1000.00,0.00,0.00,0.00,'19','100','1','0','1',1,'1','<span style=\"color: rgb(22, 36, 62); font-family: Poppins, &quot;IBM Plex Sans Thai&quot;, sans-serif; font-size: 15px; background-color: rgb(246, 246, 246);\"><b>Benefits of Term Life Insurance&nbsp;</b></span><div><span style=\"color: rgb(22, 36, 62); font-family: Poppins, &quot;IBM Plex Sans Thai&quot;, sans-serif; font-size: 15px; background-color: rgb(246, 246, 246);\"><br></span></div><div><span style=\"color: rgb(22, 36, 62); font-family: Poppins, &quot;IBM Plex Sans Thai&quot;, sans-serif; font-size: 15px; background-color: rgb(246, 246, 246);\">Contract Death Benefit: Lump Sum Payment: In the event of the insured\'s death during the policy term, the beneficiaries will receive a lump sum payment of [Coverage Amount]. This benefit provides financial support to cover living expenses, debts, education costs, and other financial needs. Affordability: Lower Premiums: Term life insurance generally offers lower premiums compared to permanent life insurance, making it an affordable option for individuals</span></div><div><span style=\"color: rgb(22, 36, 62); font-family: Poppins, &quot;IBM Plex Sans Thai&quot;, sans-serif; font-size: 15px; background-color: rgb(246, 246, 246);\"><br></span></div><div><span style=\"color: rgb(22, 36, 62); font-family: Poppins, &quot;IBM Plex Sans Thai&quot;, sans-serif; font-size: 15px; background-color: rgb(246, 246, 246);\">&nbsp;seeking significant coverage for a specific period. Flexibility:&nbsp;</span></div><div><span style=\"color: rgb(22, 36, 62); font-family: Poppins, &quot;IBM Plex Sans Thai&quot;, sans-serif; font-size: 15px; background-color: rgb(246, 246, 246);\"><br></span></div><div><span style=\"color: rgb(22, 36, 62); font-family: Poppins, &quot;IBM Plex Sans Thai&quot;, sans-serif; font-size: 15px; background-color: rgb(246, 246, 246);\"><b>Term Options:</b> Policyholders can choose the term length that best fits their needs, such as 10, 20, or 30 years, ensuring coverage during critical periods like raising children, paying off ','2024-08-15 15:36:26','2024-08-15 15:36:26',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),
(9,'test23',0.00000000,0.00000000,0,0,0.00000000,'insurance',100.00,1000.00,0.00,0.00,0.00,'15','188','1','0','2',1,'1','<b><font size=\"6\">test</font></b><div><b><font size=\"6\"><br></font></b></div><div><b><font size=\"1\">tesrrt</font></b></div>','2024-08-15 16:51:50','2024-08-15 16:51:50',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),
(10,'test88',0.00000000,0.00000000,0,0,0.00000000,'test88',100.00,1000.00,0.00,0.00,0.00,'10','100','1','1','1',1,'1','<font size=\"6\"><b>test</b></font><div><font size=\"6\"><b><br></b></font></div><div><font size=\"1\">test</font></div>','2024-08-15 16:56:39','2024-08-15 16:56:39',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),
(11,'Test insure',0.00000000,0.00000000,0,0,0.00000000,'test description',10.00,1000.00,0.00,0.00,0.00,'20','60','1','2','2',1,'2','test test test','2024-08-15 19:56:04','2024-08-15 19:56:04',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),
(12,'test iul',0.00000000,0.00000000,0,0,0.00000000,'test description',1000.00,10000.00,0.00,0.00,0.00,'20','50','1','1','2',1,'2','test tsttest','2024-08-15 19:57:20','2024-08-15 19:57:20',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),
(13,'Test insure 0000',0.00000000,0.00000000,0,0,0.00000000,'test description here 1234',99.00,9999.00,0.00,0.00,0.00,'20','50','2','2','2',1,'3','<b>Test term here</b><div><b><br></b></div><div><b>Bold Here</b></div><div><b><br></b></div><div><i>test Italic</i></div><div><i><br></i></div><div style=\"text-align: center;\"><i>Center test</i></div>','2024-08-15 23:26:47','2024-08-15 23:26:47',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),
(14,'Test insure 0000',0.00000000,0.00000000,0,0,0.00000000,'test description here 1234',99.00,9999.00,0.00,0.00,0.00,'20','50','1','1','1',1,'1','test 2','2024-08-21 16:36:14','2024-08-21 16:36:14',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),
(15,'Test insure 0000',0.00000000,0.00000000,0,0,0.00000000,'test description here 1234',99.00,9999.00,0.00,0.00,0.00,'20','50','1','1','1',1,'1','<br>','2024-09-14 20:51:09','2024-09-14 20:51:09',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,20.00,0.00,0.00),
(16,'Test insure 0000',0.00000000,0.00000000,0,0,0.00000000,'test description here 1234',99.00,9999.00,10.00,0.00,0.00,'20','50','1','1','1',1,'1','<br>','2024-09-14 20:51:36','2024-09-14 20:51:36',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00),
(18,'Test insure 0000',0.00000000,0.00000000,0,0,0.00000000,'test description here 1234',99.00,9999.00,0.00,0.00,0.00,'20','50','1','1','1',1,'1','<br>','2024-09-14 20:54:11','2024-09-14 20:54:11',0.00,0.00,0.00,100.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00);

/*Table structure for table `referrals` */

DROP TABLE IF EXISTS `referrals`;

CREATE TABLE `referrals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `commission_type` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `percent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `referrals` */

insert  into `referrals`(`id`,`commission_type`,`level`,`percent`,`status`,`created_at`,`updated_at`) values 
(5,'deposit_commission',1,10.00,1,'2024-09-20 16:07:24','2024-09-20 16:07:24'),
(6,'deposit_commission',2,5.00,1,'2024-09-20 16:07:24','2024-09-20 16:07:24'),
(7,'deposit_commission',3,4.00,1,'2024-09-20 16:07:24','2024-09-20 16:07:24'),
(8,'deposit_commission',4,4.00,1,'2024-09-20 16:07:24','2024-09-20 16:07:24'),
(9,'deposit_commission',5,4.00,1,'2024-09-20 16:07:24','2024-09-20 16:07:24'),
(10,'deposit_commission',6,4.00,1,'2024-09-20 16:07:24','2024-09-20 16:07:24'),
(11,'deposit_commission',7,4.00,1,'2024-09-20 16:07:24','2024-09-20 16:07:24'),
(12,'deposit_commission',8,4.00,1,'2024-09-20 16:07:24','2024-09-20 16:07:24');

/*Table structure for table `subscribers` */

DROP TABLE IF EXISTS `subscribers`;

CREATE TABLE `subscribers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `subscribers` */

/*Table structure for table `support_attachments` */

DROP TABLE IF EXISTS `support_attachments`;

CREATE TABLE `support_attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `support_message_id` int(10) unsigned NOT NULL DEFAULT '0',
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `support_attachments` */


/*Table structure for table `support_messages` */

DROP TABLE IF EXISTS `support_messages`;

CREATE TABLE `support_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `support_ticket_id` int(10) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `message` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `support_messages` */


/*Table structure for table `support_tickets` */

DROP TABLE IF EXISTS `support_tickets`;

CREATE TABLE `support_tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ticket` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Open, 1: Answered, 2: Replied, 3: Closed',
  `priority` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Low, 2 = medium, 3 = heigh',
  `last_reply` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `support_tickets` */


/*Table structure for table `surveys` */

DROP TABLE IF EXISTS `surveys`;

CREATE TABLE `surveys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `health` varchar(40) DEFAULT NULL,
  `smoke` varchar(40) DEFAULT '0',
  `interest` varchar(40) DEFAULT NULL,
  `premium` varchar(40) DEFAULT NULL,
  `paylike` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4;

/*Data for the table `surveys` */

insert  into `surveys`(`id`,`user_id`,`health`,`smoke`,`interest`,`premium`,`paylike`,`created_at`,`updated_at`) values 
(37,77,'1',NULL,NULL,NULL,2,'2024-10-02 05:13:35','2024-10-02 05:13:35'),
(38,77,'2','1',NULL,NULL,2,'2024-10-02 05:14:07','2024-10-02 05:14:07');

/*Table structure for table `transactions` */

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(28,2) NOT NULL DEFAULT '0.00',
  `charge` decimal(28,2) NOT NULL DEFAULT '0.00',
  `post_balance` decimal(28,2) NOT NULL DEFAULT '0.00',
  `trx_type` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `transactions` */


/*Table structure for table `update_logs` */

DROP TABLE IF EXISTS `update_logs`;

CREATE TABLE `update_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `update_log` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `update_logs` */

/*Table structure for table `user_logins` */

DROP TABLE IF EXISTS `user_logins`;

CREATE TABLE `user_logins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_ip` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=312 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `user_logins` */


/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plan_id` int(11) NOT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dial_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ssn` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_by` int(10) unsigned NOT NULL DEFAULT '0',
  `balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci COMMENT 'contains full address',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: banned, 1: active',
  `kyc_data` text COLLATE utf8mb4_unicode_ci,
  `kyc_rejection_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: KYC Unverified, 2: KYC pending, 1: KYC verified',
  `ev` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: email unverified, 1: email verified',
  `sv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: mobile unverified, 1: mobile verified',
  `profile_complete` tinyint(1) NOT NULL DEFAULT '0',
  `ver_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'stores verification code',
  `ver_code_send_at` datetime DEFAULT NULL COMMENT 'verification send time',
  `ts` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: 2fa off, 1: 2fa on',
  `tv` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: 2fa unverified, 1: 2fa verified',
  `tsc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ban_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sex` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `height` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nicotin` int(10) NOT NULL DEFAULT '0',
  `health` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paylike` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `canwithdraw` decimal(28,2) NOT NULL,
  `waitwithdraw` decimal(28,2) NOT NULL,
  `refno` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

/*Table structure for table `verification_codes` */

DROP TABLE IF EXISTS `verification_codes`;

CREATE TABLE `verification_codes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `otp` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `expire_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4;

/*Data for the table `verification_codes` */


/*Table structure for table `visittors` */

DROP TABLE IF EXISTS `visittors`;

CREATE TABLE `visittors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ev` tinyint(1) NOT NULL COMMENT '	0: email unverified, 1: email verified',
  `ver_code` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'stores verification code',
  `ver_code_send_at` datetime NOT NULL COMMENT 'verification send time',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `visittors` */


/*Table structure for table `withdraw_methods` */

DROP TABLE IF EXISTS `withdraw_methods`;

CREATE TABLE `withdraw_methods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_limit` decimal(28,8) DEFAULT '0.00000000',
  `max_limit` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `fixed_charge` decimal(28,8) DEFAULT '0.00000000',
  `rate` decimal(28,8) DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) DEFAULT NULL,
  `currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `withdraw_methods` */

insert  into `withdraw_methods`(`id`,`form_id`,`name`,`image`,`min_limit`,`max_limit`,`fixed_charge`,`rate`,`percent_charge`,`currency`,`description`,`status`,`created_at`,`updated_at`) values 
(1,20,'bank','66e5585e85ce81726306398.png',10.00000000,100.00000000,0.00000000,1.00000000,0.00,'1','<h3 style=\"margin-top: 5px; margin-bottom: 15px; font-weight: var(--fw-600); line-height: 1.5; font-size: 20px; position: relative; color: rgb(37, 37, 37); transition: 0.2s; font-family: Poppins, &quot;IBM Plex Sans Thai&quot;, sans-serif; background-color: rgb(246, 246, 246);\"><br></h3>',1,'2024-08-15 17:15:41','2024-09-14 16:33:18'),
(2,22,'Paypal','66e5584503da41726306373.png',1.00000000,1000000.00000000,0.00000000,1.00000000,0.00,'$','<br>',1,'2024-09-14 16:32:53','2024-09-14 16:32:53');

/*Table structure for table `withdrawals` */

DROP TABLE IF EXISTS `withdrawals`;

CREATE TABLE `withdrawals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `method_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `after_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `withdraw_information` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>success, 2=>pending, 3=>cancel,  ',
  `admin_feedback` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `withdrawals` */


/*Table structure for table `years` */

DROP TABLE IF EXISTS `years`;

CREATE TABLE `years` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(250) NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4;

/*Data for the table `years` */

insert  into `years`(`id`,`year`,`created_at`) values 
(2,'1950','0000-00-00'),
(3,'1951','0000-00-00'),
(4,'1952','0000-00-00'),
(5,'1953','0000-00-00'),
(6,'1954','0000-00-00'),
(7,'1955','0000-00-00'),
(8,'1956','0000-00-00'),
(9,'1957','0000-00-00'),
(10,'1958','0000-00-00'),
(11,'1959','0000-00-00'),
(12,'1960','0000-00-00'),
(13,'1961','0000-00-00'),
(14,'1962','0000-00-00'),
(15,'1963','0000-00-00'),
(16,'1964','0000-00-00'),
(17,'1965','0000-00-00'),
(18,'1966','0000-00-00'),
(19,'1967','0000-00-00'),
(20,'1968','0000-00-00'),
(21,'1969','0000-00-00'),
(22,'1970','0000-00-00'),
(23,'1971','0000-00-00'),
(24,'1972','0000-00-00'),
(25,'1973','0000-00-00'),
(26,'1974','0000-00-00'),
(27,'1975','0000-00-00'),
(28,'1976','0000-00-00'),
(29,'1977','0000-00-00'),
(30,'1978','0000-00-00'),
(31,'1979','0000-00-00'),
(32,'1980','0000-00-00'),
(33,'1981','0000-00-00'),
(34,'1982','0000-00-00'),
(35,'1983','0000-00-00'),
(36,'1984','0000-00-00'),
(37,'1985','0000-00-00'),
(38,'1986','0000-00-00'),
(39,'1987','0000-00-00'),
(40,'1988','0000-00-00'),
(41,'1989','0000-00-00'),
(42,'1990','0000-00-00'),
(43,'1991','0000-00-00'),
(44,'1992','0000-00-00'),
(45,'1993','0000-00-00'),
(46,'1994','0000-00-00'),
(47,'1995','0000-00-00'),
(48,'1996','0000-00-00'),
(49,'1997','0000-00-00'),
(50,'1998','0000-00-00'),
(51,'1999','0000-00-00'),
(52,'2000','0000-00-00'),
(53,'2001','0000-00-00'),
(54,'2002','0000-00-00'),
(55,'2003','0000-00-00'),
(56,'2004','0000-00-00'),
(57,'2005','0000-00-00'),
(58,'2006','0000-00-00'),
(59,'2007','0000-00-00'),
(60,'2008','0000-00-00'),
(61,'2009','0000-00-00'),
(62,'2010','0000-00-00'),
(63,'2011','0000-00-00'),
(64,'2012','0000-00-00'),
(65,'2013','0000-00-00'),
(66,'2014','0000-00-00'),
(67,'2015','0000-00-00'),
(68,'2016','0000-00-00'),
(69,'2017','0000-00-00'),
(70,'2018','0000-00-00'),
(71,'2019','0000-00-00'),
(72,'2020','0000-00-00'),
(73,'2021','0000-00-00'),
(74,'2022','0000-00-00'),
(75,'2023','0000-00-00'),
(76,'2024','0000-00-00');

/* Procedure structure for procedure `sp_confirm_cod_import_list` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_confirm_cod_import_list` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `sp_confirm_cod_import_list`()
main_block: BEGIN
    DECLARE v_lock INT DEFAULT 0;
    DECLARE done INT DEFAULT 0;
    DECLARE v_id INT;
    DECLARE v_tracking VARCHAR(100);
    DECLARE v_cancel INT DEFAULT 0;

    DECLARE cur CURSOR FOR
        SELECT id, tracking
        FROM ordertracking
        WHERE `status` = 1;   -- pending

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        CLOSE cur;
        DO RELEASE_LOCK('sp_confirm_cod_import_list');
        RESIGNAL;
    END;

    -- 🔒 attempt lock
    SELECT GET_LOCK('sp_confirm_cod_import_list', 0) INTO v_lock;
    IF v_lock = 0 THEN
        LEAVE main_block;
    END IF;

    OPEN cur;

    read_loop: LOOP

        -- ❗ check cancel
        SELECT cancel INTO v_cancel
        FROM job_control
        WHERE job_key = 'cod_import';

        IF v_cancel = 1 THEN
            CLOSE cur;
            DO RELEASE_LOCK('sp_confirm_cod_import_list');
            LEAVE main_block;
        END IF;

        FETCH cur INTO v_id, v_tracking;
        IF done = 1 THEN
            LEAVE read_loop;
        END IF;

        -- mark processing
        UPDATE ordertracking
        SET status = 3, started_at = NOW()
        WHERE id = v_id;

        CALL sp_confirm_cod_import(v_tracking, v_id);

    END LOOP read_loop;

    CLOSE cur;
    DO RELEASE_LOCK('sp_confirm_cod_import_list');

END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_poststock_sales` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_poststock_sales` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `sp_poststock_sales`(IN in_order_id INT)
main_block: BEGIN

    DECLARE done INT DEFAULT 0;
    DECLARE var_oi_id INT;
    DECLARE var_order_id INT;
    DECLARE var_set_id INT;
    DECLARE var_product_id INT;
    DECLARE var_product_name VARCHAR(50);
    DECLARE var_code VARCHAR(50);
    DECLARE var_amount INT;
    DECLARE var_warehouse_id INT;
    DECLARE var_price DECIMAL(10,2);
    DECLARE var_cashon INT;
    DECLARE var_mem_id INT;
    DECLARE var_id INT DEFAULT 0;
    DECLARE temp_table VARCHAR(50);

    DECLARE cur CURSOR FOR
        SELECT oi_id, order_id, set_id, product_id, product_name, `code`, amount, warehouse_id
        FROM v_saleitems
        WHERE order_id = in_order_id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    -- กัน duplicate
    IF (SELECT COUNT(*) FROM sales WHERE order_id = in_order_id) > 0 THEN
        LEAVE main_block;
    END IF;

    SELECT cashon, mem_id
    INTO var_cashon, var_mem_id
    FROM v_orders
    WHERE id = in_order_id;

    -- หา invoice item
    SELECT IFNULL(MAX(oi.product_id),0) INTO var_id
    FROM v_orders_item oi
    LEFT JOIN categories c ON oi.cate_id=c.id
    WHERE oi.order_id = in_order_id
    AND c.deleted_at IS NULL
    AND c.invoice = 1;

    -- อัพเดทสถานะ order
    IF var_cashon = 0 THEN
        UPDATE orders SET `status` = 3 WHERE id = in_order_id;
        IF var_id > 0 THEN 
	    CALL sp_invoices_no(in_order_id,0); 
        END IF;
    ELSE
        UPDATE orders SET `status` = 2 WHERE id = in_order_id;
        IF var_mem_id IN (13990,21398) AND var_id > 0 THEN
            CALL sp_invoices_no(in_order_id,0);
        END IF;
    END IF;

    -- สร้าง sales record
    INSERT INTO sales(order_id) VALUES(in_order_id);
    SELECT LAST_INSERT_ID() INTO var_order_id;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO var_oi_id,var_order_id,var_set_id,var_product_id,var_product_name,var_code,var_amount,var_warehouse_id;

        IF done = 1 THEN
            LEAVE read_loop;
        END IF;

        CALL sp_stockremain_sales(var_warehouse_id, var_product_id, temp_table);
        CALL sp_stockremain_temp(var_order_id, temp_table);

        SET @sql = CONCAT(
            'INSERT INTO saleitems(oi_id,sale_id,product_id,lot_number,amount,price) ',
            'SELECT ',var_oi_id,',',var_order_id,',a.product_id,a.lot_number,SUM(a.amount),cost.cost_price_vat ',
            'FROM (SELECT product_id,lot_number,amount FROM `',temp_table,'` ORDER BY lot_number ASC LIMIT ',var_amount,') a ',
            'INNER JOIN tb_purchases_cost_lot cost ON cost.product_id=a.product_id AND cost.lot_number=a.lot_number ',
            'GROUP BY a.product_id,a.lot_number,cost.cost_price_vat'
        );

        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql = CONCAT('DROP TABLE `',temp_table,'`');
        PREPARE stmt2 FROM @sql;
        EXECUTE stmt2;
        DEALLOCATE PREPARE stmt2;

        ALTER TABLE saleitems AUTO_INCREMENT = 1;

    END LOOP read_loop;

    CLOSE cur;

    SELECT SUM(price*amount) INTO var_price FROM saleitems WHERE sale_id = var_order_id;
    UPDATE sales SET price = var_price, `status` = 1 WHERE id = var_order_id;

    CALL sp_stockcard;

END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;


