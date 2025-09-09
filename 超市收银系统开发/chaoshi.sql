# Host: localhost  (Version: 5.7.26)
# Date: 2025-06-16 10:16:57
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "categories"
#

CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "categories"
#

INSERT INTO `categories` VALUES (1,'膨化零食','2025-06-15 13:43:46',NULL),(2,'生鲜水果','2025-06-15 13:43:46',NULL),(3,'生活用具','2025-06-15 13:43:46',NULL),(4,'学习用具','2025-06-15 13:43:46',NULL);

#
# Structure for table "members"
#

CREATE TABLE `members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `real_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sex` tinyint(4) DEFAULT '1',
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `points` int(11) DEFAULT '0',
  `openid` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "members"
#

INSERT INTO `members` VALUES (1,'浮生若梦','张三','13800138000',1,'images/profile1.jpg',100,'openid12345','2025-06-15 13:43:46',NULL),(2,'梦想家','李四','13900139000',2,'images/profile2.jpg',200,'openid12346','2025-06-15 13:43:46',NULL),(3,'探索者','王五','13700137000',1,'images/profile3.jpg',150,'openid12347','2025-06-15 13:43:46',NULL);

#
# Structure for table "addresses"
#

CREATE TABLE `addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(10) unsigned NOT NULL,
  `recipient_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "addresses"
#

INSERT INTO `addresses` VALUES (1,1,'张三','重庆市渝中区','某某街道123号','400000','13800138000','2025-06-15 13:43:46',NULL),(2,2,'李四','北京市海淀区','某某小区456号','100000','13900139000','2025-06-15 13:43:46',NULL),(3,3,'王五','上海市浦东新区','某某大厦789号','200000','13700137000','2025-06-15 13:43:46',NULL);

#
# Structure for table "news"
#

CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "news"
#

INSERT INTO `news` VALUES (1,'你好','水电费水电费','2025-06-16 07:25:59'),(2,'啥地方的师傅师傅','水电费水电费','2025-06-16 07:57:51'),(3,'气温','气温','2025-06-16 10:00:15');

#
# Structure for table "orders"
#

CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` tinyint(4) DEFAULT '1',
  `payment_method` tinyint(4) DEFAULT '1',
  `member_id` int(10) unsigned NOT NULL,
  `recipient_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "orders"
#

INSERT INTO `orders` VALUES (1,'ORD202406150001',123.00,1,1,1,'张三','重庆市渝中区','某某街道123号','400000','13800138000','2025-06-15 13:43:46',NULL),(2,'ORD202406150002',234.50,1,1,2,'李四','北京市海淀区','某某小区456号','100000','13900139000','2025-06-15 13:43:46',NULL),(3,'ORD202406150003',99.99,1,1,3,'王五','上海市浦东新区','某某大厦789号','200000','13700137000','2025-06-15 13:43:46',NULL);

#
# Structure for table "products"
#

CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `picture_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `tag` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "products"
#

INSERT INTO `products` VALUES (1,'小王子薯片','办公室休闲零食网红膨化食品',12.00,'/2023/Images/1.jpeg',1,'学生最爱','2025-06-15 13:43:46','2025-06-16 10:14:07'),(2,'新鲜苹果','新鲜富士山红苹果',9.00,'/2023/Images/1.jpeg',2,'健康食品','2025-06-15 13:43:46','2025-06-16 10:14:04'),(3,'吸水拖把','高效清洁拖把',25.00,'/2023/Images/1.jpeg',3,'生活必备','2025-06-15 13:43:46','2025-06-16 10:14:02'),(4,'铅笔套装','学生用铅笔套装',15.00,'/2023/Images/1.jpeg',4,'学习用品','2025-06-15 13:43:46','2025-06-16 10:14:00');

#
# Structure for table "order_products"
#

CREATE TABLE `order_products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_products_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "order_products"
#

INSERT INTO `order_products` VALUES (1,1,1,2,12.00,'2025-06-15 13:43:46',NULL),(2,1,2,1,9.00,'2025-06-15 13:43:46',NULL),(3,2,3,1,25.00,'2025-06-15 13:43:46',NULL),(4,2,4,2,15.00,'2025-06-15 13:43:46',NULL),(5,3,1,1,12.00,'2025-06-15 13:43:46',NULL),(6,3,3,1,25.00,'2025-06-15 13:43:46',NULL);

#
# Structure for table "users"
#

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "users"
#

INSERT INTO `users` VALUES (1,'赵云','7878@qq.com','$2y$10$H7DoGItIebWk4xpFzzZOoeBFDm4HD6D4OCMhiE6Ofyt9mcLCReol2','user','2025-06-15 15:40:38',NULL),(2,'关羽','123456@qq.com','$2y$10$/SLp1R7MlYxf3Nl47.W6Ee.OZkL2mE2DIvohpOk0KEUMOE9IKXBDe','user','2025-06-15 16:04:42',NULL),(3,'郑博文','zbw123@qq.com','$2y$10$3pQcYuZv6ZGbEk0WAPqgxuvr21uPlMXDYE.u8YR45NcfyXG7RDAFu','admin','2025-06-15 16:05:47',NULL),(4,'zsbc','zsbc@163.com','$2y$10$iq57PzGSdlw.rKSko/r3P.s/jhontaVUb2RlCvkx7yiKC0GQzRDqG','user','2025-06-15 19:08:58',NULL),(6,'气温','qwe@qq.com','$2y$10$b/tA7La9W.xOPtFN599IUOXiOWKrGk4tX67ORA4E03bgrm4ZpOMem','user','2025-06-15 19:57:30',NULL),(7,'zhao','zhao@123.com','$2y$10$.CfB1d8fkah9OXtD8r8JDeKh80z0qGjYRdjNLYV46tXIU/2AJWOiW','user','2025-06-15 20:00:35',NULL);

#
# Structure for table "messages"
#

CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "messages"
#

INSERT INTO `messages` VALUES (1,4,'你好，我想要十个苹果！','2025-06-15 19:46:05',1),(2,6,'qweqe','2025-06-15 19:57:59',2),(3,4,'你好','2025-06-15 22:58:03',1),(4,4,'我想要臭豆腐','2025-06-16 07:25:22',1),(5,4,'斯蒂芬森','2025-06-16 07:56:58',1);
