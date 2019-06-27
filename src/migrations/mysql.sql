/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '账号',
  `email` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱',
  `real_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `avatar` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `address` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '地址',
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `last_time` int(11) NOT NULL DEFAULT '0' COMMENT '上一次登录时间',
  `last_ip` char(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '上一次登录的IP',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','admin@admin.com','管理员','','湖南省,岳阳市,岳阳县','$2y$13$rtwpPbd6MrKh0Xt86XxCweAcPOCsNdy8QgoQnZdXA5cn9YDmuOAvS','5vLaPpUS-I-XxJaoGP-GZDk474WdnaK3_1469073015','gKkLFMdB2pvIXOFNpF_Aeemvdf1j0YUM',0,1559903027,'127.0.0.1',1559903027,1559903027),(2,'guest','guest@admin.com','客户','','湖南省,岳阳市,岳阳县','$2y$13$L7hj01oTVUDal2EzyNUKdOTdqbEgnPdPi6qz34j8anSc4aibFpnmC','CgScbf1E96N3pqH01b0mVi_Z58j8QsRV_1501916190','tArp_Kv4z1JlzBUZYCL33N24AZL-_77p',0,1559903027,'127.0.0.1',1559903027,1559903027);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('admin','2',1559903028),('administrator','1',1559903028);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('admin',1,'管理员',NULL,NULL,1559903028,1559903028),('admin/admin/create',2,'管理员添加',NULL,NULL,1559903028,1559903028),('admin/admin/delete',2,'管理员删除',NULL,NULL,1559903028,1559903028),('admin/admin/index',2,'管理员显示',NULL,NULL,1559903028,1559903028),('admin/admin/update',2,'管理员修改',NULL,NULL,1559903028,1559903028),('admin/item/create',2,'权限添加',NULL,NULL,1559903028,1559903028),('admin/item/delete',2,'权限删除',NULL,NULL,1559903028,1559903028),('admin/item/index',2,'权限显示',NULL,NULL,1559903028,1559903028),('admin/item/update',2,'权限修改',NULL,NULL,1559903028,1559903028),('admin/menu/create',2,'菜单添加',NULL,NULL,1559903028,1559903028),('admin/menu/delete',2,'菜单删除',NULL,NULL,1559903028,1559903028),('admin/menu/index',2,'菜单显示',NULL,NULL,1559903028,1559903028),('admin/menu/update',2,'菜单修改',NULL,NULL,1559903028,1559903028),('admin/role/create',2,'角色添加',NULL,NULL,1559903028,1559903028),('admin/role/delete',2,'角色删除',NULL,NULL,1559903028,1559903028),('admin/role/index',2,'角色显示',NULL,NULL,1559903028,1559903028),('admin/role/update',2,'角色修改',NULL,NULL,1559903028,1559903028),('admin/rule/index',2,'规则显示',NULL,NULL,1559903028,1559903028),('adminAuth',3,'管理员权限',NULL,NULL,1559903028,1559903028),('administrator',1,'超级管理员',NULL,NULL,1559903028,1559903028),('menuAuth',3,'菜单权限',NULL,NULL,1559903028,1559903028);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` VALUES ('admin','admin/admin/create'),('adminAuth','admin/admin/create'),('adminAuth','admin/admin/delete'),('admin','admin/admin/index'),('adminAuth','admin/admin/index'),('admin','admin/admin/update'),('adminAuth','admin/admin/update'),('adminAuth','admin/item/create'),('adminAuth','admin/item/delete'),('adminAuth','admin/item/index'),('adminAuth','admin/item/update'),('admin','admin/menu/create'),('menuAuth','admin/menu/create'),('menuAuth','admin/menu/delete'),('admin','admin/menu/index'),('menuAuth','admin/menu/index'),('admin','admin/menu/update'),('menuAuth','admin/menu/update'),('adminAuth','admin/role/create'),('adminAuth','admin/role/delete'),('adminAuth','admin/role/index'),('adminAuth','admin/role/update'),('adminAuth','admin/rule/index'),('administrator','adminAuth'),('administrator','menuAuth');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_rule`
--

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `parent` int(11) NOT NULL DEFAULT '0' COMMENT '父类',
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '栏目',
  `route` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '地址',
  `icon` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'icon-desktop' COMMENT '图标',
  `sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `data` blob COMMENT '数据{Json}',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='菜单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,0,'权限管理','','icon-desktop',0,NULL,1,0,0),(2,1,'管理员列表','admin/admin/index','icon-desktop',0,NULL,1,0,0),(3,1,'创建管理员','admin/admin/create','icon-desktop',0,NULL,1,0,0),(4,1,'菜单列表','admin/menu/index','icon-desktop',0,NULL,1,0,0),(5,1,'创建菜单','admin/menu/create','icon-desktop',0,NULL,1,0,0),(6,1,'角色列表','admin/role/index','menu-icon fa fa-graduation-cap',0,NULL,1,0,0),(7,1,'创建角色','admin/role/create','icon-desktop',0,NULL,1,0,0),(8,1,'权限列表','admin/item/index','menu-icon fa fa-fire',0,NULL,1,0,0),(9,1,'创建权限','admin/item/create','icon-desktop',0,NULL,1,0,0),(10,1,'规则列表','admin/rule/index','menu-icon fa shield',0,NULL,1,0,0);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
