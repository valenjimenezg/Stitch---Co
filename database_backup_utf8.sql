-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: stitch
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('stitch-co-cache-5c785c036466adea360111aa28563bfd556b5fba','i:1;',1776268783),('stitch-co-cache-5c785c036466adea360111aa28563bfd556b5fba:timer','i:1776268783;',1776268783),('stitch-co-cache-config_tasa_bcv_manual','d:478.58;',2091624618),('stitch-co-cache-config_usar_tasa_manual','b:1;',2091624618);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Lanas','2026-04-12 05:27:27','2026-04-13 00:58:09'),(2,'Telas','2026-04-12 05:27:27','2026-04-13 00:58:09'),(3,'Kits','2026-04-12 05:27:27','2026-04-13 08:28:16'),(4,'Mercer├¡a y Botones','2026-04-12 05:27:27','2026-04-13 08:37:45'),(5,'Tejidos','2026-04-13 08:48:41','2026-04-13 08:48:41'),(6,'Costura','2026-04-13 08:48:41','2026-04-13 08:48:41'),(7,'Manualidades','2026-04-13 08:48:41','2026-04-13 08:48:41'),(8,'Botones','2026-04-13 09:04:59','2026-04-13 09:04:59'),(9,'Accesorios','2026-04-13 09:04:59','2026-04-13 09:04:59');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentarios_producto`
--

DROP TABLE IF EXISTS `comentarios_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comentarios_producto` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `producto_id` bigint(20) unsigned NOT NULL,
  `titulo` varchar(120) DEFAULT NULL,
  `calificacion` tinyint(4) NOT NULL COMMENT 'Rango de 1 a 5',
  `comentario` text DEFAULT NULL,
  `aprobado` tinyint(1) NOT NULL DEFAULT 0,
  `verified_purchase` tinyint(1) NOT NULL DEFAULT 0,
  `respuesta_admin` text DEFAULT NULL,
  `respondido_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comentarios_producto_user_id_foreign` (`user_id`),
  KEY `comentarios_producto_producto_id_foreign` (`producto_id`),
  CONSTRAINT `comentarios_producto_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comentarios_producto_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentarios_producto`
--

LOCK TABLES `comentarios_producto` WRITE;
/*!40000 ALTER TABLE `comentarios_producto` DISABLE KEYS */;
INSERT INTO `comentarios_producto` VALUES (1,13,38,NULL,5,'Lo us├® para mi ├║ltimo proyecto y qued├│ espectacular.',1,1,NULL,NULL,'2026-03-14 10:36:53','2026-04-15 08:23:32'),(2,11,38,NULL,4,'Muy buen producto, aunque el precio podr├¡a ser un poco m├ís bajo.',1,1,NULL,NULL,'2026-02-05 23:11:18','2026-04-15 08:23:32'),(3,12,38,NULL,4,'El color es tal cual como se muestra en la foto. Me encant├│.',1,1,NULL,NULL,'2026-02-12 23:53:46','2026-04-15 08:23:32'),(4,9,38,NULL,4,'100% recomendado.',1,1,NULL,NULL,'2026-02-13 17:14:46','2026-04-15 08:23:32'),(5,7,39,NULL,5,'El color es tal cual como se muestra en la foto. Me encant├│.',1,1,NULL,NULL,'2026-01-20 11:15:31','2026-04-15 08:23:32'),(6,9,39,NULL,5,'Ideal para mis trabajos de mercer├¡a.',1,1,NULL,NULL,'2026-03-04 01:49:57','2026-04-15 08:23:32'),(7,12,39,NULL,4,'Lo us├® para mi ├║ltimo proyecto y qued├│ espectacular.',1,1,NULL,NULL,'2026-03-06 14:24:25','2026-04-15 08:23:32'),(8,10,40,NULL,4,'┬íExcelente calidad! Muy recomendado.',1,1,NULL,NULL,'2026-02-27 00:11:59','2026-04-15 08:23:32'),(9,13,40,NULL,5,'Lo us├® para mi ├║ltimo proyecto y qued├│ espectacular.',1,1,NULL,NULL,'2026-02-25 19:41:59','2026-04-15 08:23:32'),(10,6,40,NULL,5,'El color es tal cual como se muestra en la foto. Me encant├│.',1,1,NULL,NULL,'2026-03-07 14:38:53','2026-04-15 08:23:32'),(11,6,40,NULL,5,'Tienen mucha variedad. Todo excelente.',1,1,NULL,NULL,'2026-01-23 20:31:25','2026-04-15 08:23:32'),(12,10,40,NULL,4,'La textura es s├║per suave. Perfecto para lo que necesitaba.',1,1,NULL,NULL,'2026-02-17 14:36:50','2026-04-15 08:23:32'),(13,11,41,NULL,5,'Ideal para mis trabajos de mercer├¡a.',1,1,NULL,NULL,'2026-02-27 13:25:00','2026-04-15 08:23:32'),(14,10,41,NULL,4,'Me cost├│ conseguirlo pero vali├│ la pena.',1,1,NULL,NULL,'2026-03-13 08:48:42','2026-04-15 08:23:32'),(15,13,41,NULL,5,'Tienen mucha variedad. Todo excelente.',1,1,NULL,NULL,'2026-04-03 23:51:17','2026-04-15 08:23:32'),(16,10,41,NULL,5,'Lo us├® para mi ├║ltimo proyecto y qued├│ espectacular.',1,1,NULL,NULL,'2026-01-21 13:00:25','2026-04-15 08:23:32'),(17,6,41,NULL,4,'Me cost├│ conseguirlo pero vali├│ la pena.',1,1,NULL,NULL,'2026-01-30 14:45:25','2026-04-15 08:23:32'),(18,11,42,NULL,5,'┬íExcelente calidad! Muy recomendado.',1,1,NULL,NULL,'2026-01-18 18:05:08','2026-04-15 08:23:32'),(19,10,43,NULL,4,'100% recomendado.',1,1,NULL,NULL,'2026-03-27 04:28:17','2026-04-15 08:23:32'),(20,8,43,NULL,5,'┬íExcelente calidad! Muy recomendado.',1,1,NULL,NULL,'2026-04-07 14:10:48','2026-04-15 08:23:32'),(21,13,43,NULL,5,'100% recomendado.',1,1,NULL,NULL,'2026-02-24 10:01:32','2026-04-15 08:23:32'),(22,11,43,NULL,5,'Muy buen producto, aunque el precio podr├¡a ser un poco m├ís bajo.',1,1,NULL,NULL,'2026-03-14 13:10:46','2026-04-15 08:23:32'),(23,5,44,NULL,5,'┬íExcelente calidad! Muy recomendado.',1,1,NULL,NULL,'2026-02-08 02:57:08','2026-04-15 08:23:32'),(24,6,44,NULL,5,'Lleg├│ muy r├ípido y en perfectas condiciones. Volver├¡a a comprar.',1,1,NULL,NULL,'2026-04-08 17:46:47','2026-04-15 08:23:32'),(25,10,44,NULL,5,'Me cost├│ conseguirlo pero vali├│ la pena.',1,1,NULL,NULL,'2026-02-05 21:28:48','2026-04-15 08:23:32'),(26,5,44,NULL,4,'Lleg├│ muy r├ípido y en perfectas condiciones. Volver├¡a a comprar.',1,1,NULL,NULL,'2026-01-19 11:54:32','2026-04-15 08:23:32'),(27,7,45,NULL,5,'Muy buen producto, aunque el precio podr├¡a ser un poco m├ís bajo.',1,1,NULL,NULL,'2026-02-19 04:39:44','2026-04-15 08:23:32'),(28,4,45,NULL,5,'Lleg├│ muy r├ípido y en perfectas condiciones. Volver├¡a a comprar.',1,1,NULL,NULL,'2026-03-25 17:52:45','2026-04-15 08:23:32'),(29,12,45,NULL,5,'Muy buen producto, aunque el precio podr├¡a ser un poco m├ís bajo.',1,1,NULL,NULL,'2026-03-28 08:50:56','2026-04-15 08:23:32'),(30,12,46,NULL,4,'El color es tal cual como se muestra en la foto. Me encant├│.',1,1,NULL,NULL,'2026-02-09 07:58:52','2026-04-15 08:23:32'),(31,7,46,NULL,4,'Tienen mucha variedad. Todo excelente.',1,1,NULL,NULL,'2026-02-03 20:01:21','2026-04-15 08:23:32'),(32,11,47,NULL,4,'Tienen mucha variedad. Todo excelente.',1,1,NULL,NULL,'2026-02-06 06:20:18','2026-04-15 08:23:32'),(33,5,47,NULL,4,'Ideal para mis trabajos de mercer├¡a.',1,1,NULL,NULL,'2026-01-14 13:41:44','2026-04-15 08:23:32'),(34,12,47,NULL,5,'Ideal para mis trabajos de mercer├¡a.',1,1,NULL,NULL,'2026-02-11 02:30:42','2026-04-15 08:23:32'),(35,6,47,NULL,5,'La textura es s├║per suave. Perfecto para lo que necesitaba.',1,1,NULL,NULL,'2026-03-21 01:54:43','2026-04-15 08:23:32'),(36,6,47,NULL,4,'┬íExcelente calidad! Muy recomendado.',1,1,NULL,NULL,'2026-04-03 01:40:54','2026-04-15 08:23:32'),(37,7,48,NULL,5,'Ideal para mis trabajos de mercer├¡a.',1,1,NULL,NULL,'2026-02-20 02:48:05','2026-04-15 08:23:32'),(38,12,48,NULL,4,'El color es tal cual como se muestra en la foto. Me encant├│.',1,1,NULL,NULL,'2026-03-07 02:41:04','2026-04-15 08:23:32'),(39,11,48,NULL,4,'La textura es s├║per suave. Perfecto para lo que necesitaba.',1,1,NULL,NULL,'2026-01-26 05:40:13','2026-04-15 08:23:32'),(40,11,48,NULL,4,'Tienen mucha variedad. Todo excelente.',1,1,NULL,NULL,'2026-02-24 04:18:32','2026-04-15 08:23:32'),(41,4,49,NULL,4,'Lleg├│ muy r├ípido y en perfectas condiciones. Volver├¡a a comprar.',1,1,NULL,NULL,'2026-02-17 07:29:30','2026-04-15 08:23:32'),(42,8,49,NULL,4,'Muy buen producto, aunque el precio podr├¡a ser un poco m├ís bajo.',1,1,NULL,NULL,'2026-04-10 13:11:36','2026-04-15 08:23:32'),(43,12,49,NULL,4,'Tienen mucha variedad. Todo excelente.',1,1,NULL,NULL,'2026-04-02 16:10:36','2026-04-15 08:23:32'),(44,11,50,NULL,4,'El color es tal cual como se muestra en la foto. Me encant├│.',1,1,NULL,NULL,'2026-02-20 10:00:46','2026-04-15 08:23:32'),(45,11,50,NULL,4,'Tienen mucha variedad. Todo excelente.',1,1,NULL,NULL,'2026-03-03 23:56:51','2026-04-15 08:23:32'),(46,13,50,NULL,5,'El color es tal cual como se muestra en la foto. Me encant├│.',1,1,NULL,NULL,'2026-01-23 00:37:31','2026-04-15 08:23:32'),(47,8,51,NULL,4,'Lleg├│ muy r├ípido y en perfectas condiciones. Volver├¡a a comprar.',1,1,NULL,NULL,'2026-03-01 19:46:52','2026-04-15 08:23:32'),(48,10,51,NULL,4,'Me cost├│ conseguirlo pero vali├│ la pena.',1,1,NULL,NULL,'2026-03-02 17:30:14','2026-04-15 08:23:32'),(49,6,51,NULL,5,'Me cost├│ conseguirlo pero vali├│ la pena.',1,1,NULL,NULL,'2026-04-10 19:18:20','2026-04-15 08:23:32'),(50,8,52,NULL,5,'Lleg├│ muy r├ípido y en perfectas condiciones. Volver├¡a a comprar.',1,1,NULL,NULL,'2026-04-06 16:19:11','2026-04-15 08:23:32'),(51,8,52,NULL,4,'Lo us├® para mi ├║ltimo proyecto y qued├│ espectacular.',1,1,NULL,NULL,'2026-02-19 07:05:39','2026-04-15 08:23:32'),(52,12,52,NULL,4,'Lleg├│ muy r├ípido y en perfectas condiciones. Volver├¡a a comprar.',1,1,NULL,NULL,'2026-02-07 02:10:36','2026-04-15 08:23:32'),(53,4,53,NULL,4,'100% recomendado.',1,1,NULL,NULL,'2026-01-27 16:12:08','2026-04-15 08:23:32'),(54,13,54,NULL,5,'El color es tal cual como se muestra en la foto. Me encant├│.',1,1,NULL,NULL,'2026-04-14 03:28:29','2026-04-15 08:23:32'),(55,12,54,NULL,5,'Lo us├® para mi ├║ltimo proyecto y qued├│ espectacular.',1,1,NULL,NULL,'2026-01-23 12:11:49','2026-04-15 08:23:32'),(56,8,55,NULL,5,'La textura es s├║per suave. Perfecto para lo que necesitaba.',1,1,NULL,NULL,'2026-02-27 21:39:28','2026-04-15 08:23:32'),(57,11,55,NULL,5,'El color es tal cual como se muestra en la foto. Me encant├│.',1,1,NULL,NULL,'2026-03-22 07:57:56','2026-04-15 08:23:32'),(58,13,55,NULL,4,'Tienen mucha variedad. Todo excelente.',1,1,NULL,NULL,'2026-03-22 16:57:03','2026-04-15 08:23:32'),(59,13,55,NULL,4,'Ideal para mis trabajos de mercer├¡a.',1,1,NULL,NULL,'2026-01-15 02:04:31','2026-04-15 08:23:32'),(60,9,55,NULL,4,'Ideal para mis trabajos de mercer├¡a.',1,1,NULL,NULL,'2026-02-04 23:26:06','2026-04-15 08:23:32'),(61,13,56,NULL,4,'Tienen mucha variedad. Todo excelente.',1,1,NULL,NULL,'2026-02-02 00:51:31','2026-04-15 08:23:32'),(62,13,56,NULL,4,'Lo us├® para mi ├║ltimo proyecto y qued├│ espectacular.',1,1,NULL,NULL,'2026-04-13 23:51:06','2026-04-15 08:23:32'),(63,7,56,NULL,5,'Muy buen producto, aunque el precio podr├¡a ser un poco m├ís bajo.',1,1,NULL,NULL,'2026-03-31 20:15:57','2026-04-15 08:23:32'),(64,12,57,NULL,5,'Ideal para mis trabajos de mercer├¡a.',1,1,NULL,NULL,'2026-03-05 04:19:19','2026-04-15 08:23:32'),(65,6,57,NULL,4,'Tienen mucha variedad. Todo excelente.',1,1,NULL,NULL,'2026-01-31 02:58:50','2026-04-15 08:23:32'),(66,5,57,NULL,4,'Me cost├│ conseguirlo pero vali├│ la pena.',1,1,NULL,NULL,'2026-02-24 07:24:18','2026-04-15 08:23:32'),(67,6,58,NULL,4,'Me cost├│ conseguirlo pero vali├│ la pena.',1,1,NULL,NULL,'2026-01-16 21:03:31','2026-04-15 08:23:32'),(68,10,58,NULL,5,'La textura es s├║per suave. Perfecto para lo que necesitaba.',1,1,NULL,NULL,'2026-03-28 07:39:05','2026-04-15 08:23:32'),(69,8,58,NULL,4,'Lo us├® para mi ├║ltimo proyecto y qued├│ espectacular.',1,1,NULL,NULL,'2026-04-06 16:13:45','2026-04-15 08:23:32'),(70,9,58,NULL,4,'Muy buen producto, aunque el precio podr├¡a ser un poco m├ís bajo.',1,1,NULL,NULL,'2026-01-16 19:16:59','2026-04-15 08:23:32'),(71,7,58,NULL,4,'┬íExcelente calidad! Muy recomendado.',1,1,NULL,NULL,'2026-03-21 09:41:40','2026-04-15 08:23:32'),(72,10,59,NULL,5,'Lo us├® para mi ├║ltimo proyecto y qued├│ espectacular.',1,1,NULL,NULL,'2026-04-03 23:43:46','2026-04-15 08:23:32'),(73,11,59,NULL,4,'Lo us├® para mi ├║ltimo proyecto y qued├│ espectacular.',1,1,NULL,NULL,'2026-01-20 12:47:36','2026-04-15 08:23:32'),(74,4,59,NULL,4,'Tienen mucha variedad. Todo excelente.',1,1,NULL,NULL,'2026-01-15 12:32:17','2026-04-15 08:23:32'),(75,13,59,NULL,4,'Lo us├® para mi ├║ltimo proyecto y qued├│ espectacular.',1,1,NULL,NULL,'2026-01-17 04:25:01','2026-04-15 08:23:32'),(76,9,59,NULL,5,'Ideal para mis trabajos de mercer├¡a.',1,1,NULL,NULL,'2026-04-01 15:31:02','2026-04-15 08:23:32'),(77,5,60,NULL,5,'Muy buen producto, aunque el precio podr├¡a ser un poco m├ís bajo.',1,1,NULL,NULL,'2026-03-30 02:27:16','2026-04-15 08:23:32'),(78,8,60,NULL,5,'Lo us├® para mi ├║ltimo proyecto y qued├│ espectacular.',1,1,NULL,NULL,'2026-01-24 03:27:04','2026-04-15 08:23:32'),(79,5,60,NULL,4,'Ideal para mis trabajos de mercer├¡a.',1,1,NULL,NULL,'2026-03-02 18:41:58','2026-04-15 08:23:32'),(80,11,60,NULL,4,'100% recomendado.',1,1,NULL,NULL,'2026-04-10 18:03:55','2026-04-15 08:23:32'),(81,5,61,NULL,4,'100% recomendado.',1,1,NULL,NULL,'2026-02-02 16:01:01','2026-04-15 08:23:32'),(82,12,61,NULL,5,'Lo us├® para mi ├║ltimo proyecto y qued├│ espectacular.',1,1,NULL,NULL,'2026-04-12 10:56:13','2026-04-15 08:23:32'),(83,8,61,NULL,4,'Muy buen producto, aunque el precio podr├¡a ser un poco m├ís bajo.',1,1,NULL,NULL,'2026-01-31 05:43:25','2026-04-15 08:23:32'),(84,5,61,NULL,5,'Me cost├│ conseguirlo pero vali├│ la pena.',1,1,NULL,NULL,'2026-01-17 15:47:23','2026-04-15 08:23:32'),(85,9,62,NULL,4,'Me cost├│ conseguirlo pero vali├│ la pena.',1,1,NULL,NULL,'2026-03-23 14:32:42','2026-04-15 08:23:32'),(86,11,62,NULL,4,'Ideal para mis trabajos de mercer├¡a.',1,1,NULL,NULL,'2026-02-21 07:54:44','2026-04-15 08:23:32'),(87,6,62,NULL,5,'El color es tal cual como se muestra en la foto. Me encant├│.',1,1,NULL,NULL,'2026-03-09 21:08:52','2026-04-15 08:23:32'),(88,11,63,NULL,4,'Lleg├│ muy r├ípido y en perfectas condiciones. Volver├¡a a comprar.',1,1,NULL,NULL,'2026-02-24 07:00:04','2026-04-15 08:23:32'),(89,6,63,NULL,4,'Ideal para mis trabajos de mercer├¡a.',1,1,NULL,NULL,'2026-03-09 20:13:48','2026-04-15 08:23:32'),(90,9,64,NULL,5,'Ideal para mis trabajos de mercer├¡a.',1,1,NULL,NULL,'2026-03-15 09:47:02','2026-04-15 08:23:32'),(91,12,64,NULL,4,'Lo us├® para mi ├║ltimo proyecto y qued├│ espectacular.',1,1,NULL,NULL,'2026-03-23 07:54:25','2026-04-15 08:23:32'),(92,6,64,NULL,4,'┬íExcelente calidad! Muy recomendado.',1,1,NULL,NULL,'2026-02-25 12:39:58','2026-04-15 08:23:32'),(93,11,65,NULL,5,'Me cost├│ conseguirlo pero vali├│ la pena.',1,1,NULL,NULL,'2026-03-09 22:31:19','2026-04-15 08:23:32'),(94,2,58,NULL,4,'bien',1,1,NULL,NULL,'2026-04-14 10:45:39','2026-04-15 08:23:32'),(95,10,42,'Calidad-precio excelente',5,'No hab├¡a encontrado este producto a tan buen precio en ning├║n otro sitio. Adem├ís la calidad es superior a lo esperado.',1,1,NULL,NULL,'2026-04-15 08:23:32','2026-04-15 08:23:32'),(96,6,42,'Cumple expectativas',4,'Buena calidad en general. El producto lleg├│ en perfectas condiciones. Repetir├® la compra sin duda.',1,1,NULL,NULL,'2026-04-15 08:23:32','2026-04-15 08:23:32'),(97,7,53,'Calidad-precio excelente',5,'No hab├¡a encontrado este producto a tan buen precio en ning├║n otro sitio. Adem├ís la calidad es superior a lo esperado.',1,0,NULL,NULL,'2026-04-15 08:23:32','2026-04-15 08:23:32'),(98,4,53,'Calidad-precio excelente',5,'No hab├¡a encontrado este producto a tan buen precio en ning├║n otro sitio. Adem├ís la calidad es superior a lo esperado.',1,1,'┬íGracias por confiar en Stitch & Co! Es un placer saber que nuestros productos te hacen feliz. ­ƒÆ£','2026-03-27 08:23:32','2026-04-15 08:23:32','2026-04-15 08:23:32'),(99,5,65,'Buena compra',4,'Cumple perfectamente con lo descrito. Buen acabado y color exacto al de la foto. Lo recomiendo.',1,0,NULL,NULL,'2026-04-15 08:23:32','2026-04-15 08:23:32'),(100,7,65,'┬íLo amo!',5,'Desde que descubr├¡ Stitch & Co no compro en otro lugar. Siempre tienen lo que necesito y la calidad es inmejorable.',1,1,'┬íMuchas gracias por tu rese├▒a! Nos alegra saber que quedaste satisfecha. ┬íTe esperamos pronto! ­ƒºÁ','2026-02-22 08:23:32','2026-04-15 08:23:32','2026-04-15 08:23:32'),(101,8,65,'Material resistente',4,'Se nota que es un producto de calidad. Resistente y con un acabado profesional. Muy contenta.',1,1,NULL,NULL,'2026-04-15 08:23:32','2026-04-15 08:23:32'),(102,11,65,'Buen producto',4,'Buena relaci├│n calidad-precio. Lo recomiendo para cualquier tipo de proyecto de costura o manualidades.',1,1,NULL,NULL,'2026-04-15 08:23:32','2026-04-15 08:23:32');
/*!40000 ALTER TABLE `comentarios_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagen_variantes`
--

DROP TABLE IF EXISTS `imagen_variantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imagen_variantes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `detalle_producto_id` bigint(20) unsigned NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `imagen_variantes_detalle_producto_id_foreign` (`detalle_producto_id`),
  CONSTRAINT `imagen_variantes_detalle_producto_id_foreign` FOREIGN KEY (`detalle_producto_id`) REFERENCES `detalle_productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagen_variantes`
--

LOCK TABLES `imagen_variantes` WRITE;
/*!40000 ALTER TABLE `imagen_variantes` DISABLE KEYS */;
/*!40000 ALTER TABLE `imagen_variantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario_logs`
--

DROP TABLE IF EXISTS `inventario_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventario_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `variante_id` bigint(20) unsigned NOT NULL,
  `proveedor_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `cantidad_cambio` decimal(12,2) NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `orden_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventario_logs_variante_id_foreign` (`variante_id`),
  KEY `inventario_logs_proveedor_id_foreign` (`proveedor_id`),
  KEY `inventario_logs_user_id_foreign` (`user_id`),
  KEY `inventario_logs_orden_id_foreign` (`orden_id`),
  CONSTRAINT `inventario_logs_orden_id_foreign` FOREIGN KEY (`orden_id`) REFERENCES `ordenes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventario_logs_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventario_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventario_logs_variante_id_foreign` FOREIGN KEY (`variante_id`) REFERENCES `producto_variantes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario_logs`
--

LOCK TABLES `inventario_logs` WRITE;
/*!40000 ALTER TABLE `inventario_logs` DISABLE KEYS */;
INSERT INTO `inventario_logs` VALUES (1,56,NULL,NULL,-1.00,'Venta Web #3',3,'2026-04-14 02:20:16','2026-04-14 02:20:16'),(2,59,NULL,NULL,-27.00,'Venta Web #4',4,'2026-04-14 03:33:28','2026-04-14 03:33:28'),(3,59,NULL,NULL,27.00,'Devoluci├│n: Orden Cancelada por Admin',4,'2026-04-14 03:38:45','2026-04-14 03:38:45'),(4,56,NULL,NULL,1.00,'Devoluci├│n: Orden Cancelada por Admin',3,'2026-04-14 03:44:12','2026-04-14 03:44:12'),(5,59,NULL,NULL,27.00,'Devoluci├│n: Orden Cancelada por Admin',4,'2026-04-14 03:44:17','2026-04-14 03:44:17'),(6,56,NULL,NULL,1.00,'Devoluci├│n: Orden Cancelada por Admin',3,'2026-04-14 03:44:34','2026-04-14 03:44:34'),(7,59,NULL,NULL,27.00,'Devoluci├│n: Orden Cancelada por Admin',4,'2026-04-14 03:44:37','2026-04-14 03:44:37'),(9,70,NULL,NULL,-16.00,'Venta Web #6',6,'2026-04-14 08:30:55','2026-04-14 08:30:55'),(10,70,NULL,NULL,16.00,'Devoluci├│n: Orden Cancelada por Admin',6,'2026-04-14 10:54:52','2026-04-14 10:54:52'),(11,66,NULL,NULL,-1.00,'Venta Web #7',7,'2026-04-14 10:55:35','2026-04-14 10:55:35');
/*!40000 ALTER TABLE `inventario_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lotes_trabajos`
--

DROP TABLE IF EXISTS `lotes_trabajos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lotes_trabajos` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lotes_trabajos`
--

LOCK TABLES `lotes_trabajos` WRITE;
/*!40000 ALTER TABLE `lotes_trabajos` DISABLE KEYS */;
/*!40000 ALTER TABLE `lotes_trabajos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_02_25_173253_create_productos_table',1),(5,'2026_02_25_173254_create_detalle_productos_table',1),(6,'2026_02_25_173255_create_carritos_table',1),(7,'2026_02_25_173256_create_ventas_table',1),(8,'2026_02_25_173257_create_direcciones_table',1),(9,'2026_02_25_173258_create_detalle_carritos_table',1),(10,'2026_02_25_173259_create_detalle_ventas_table',1),(11,'2026_02_25_173260_create_lista_deseos_table',1),(12,'2026_03_13_000955_create_newsletter_subscribers_table',1),(13,'2026_03_15_203540_create_notificaciones_stock_table',1),(14,'2026_03_17_044045_create_imagen_variantes_table',1),(15,'2026_03_18_145954_update_ventas_table_for_guest_checkout',1),(16,'2026_03_18_191806_add_payment_details_to_ventas_table',1),(17,'2026_03_19_003152_modify_metodo_pago_enum_in_ventas_table',1),(18,'2026_03_19_220357_add_shipping_to_ventas_table',1),(19,'2026_03_19_223229_add_agencia_envio_to_ventas_table',1),(20,'2026_03_24_152301_create_invoices_table',1),(21,'2026_03_24_154500_alter_estado_column_in_ventas',1),(22,'2026_03_26_031601_add_precio_usd_to_detalle_productos_table',1),(23,'2026_03_26_053139_add_pago_movil_to_enum_ventas_table',1),(24,'2026_03_26_053721_add_debito_to_ventas_enum',1),(25,'2026_03_26_063235_create_movimiento_inventarios_table',1),(26,'2026_03_26_071649_add_shipping_fields_to_ventas_table',1),(27,'2026_03_26_150510_rename_invoices_to_facturas_table',1),(28,'2026_03_26_152051_rename_queue_tables_to_spanish',1),(29,'2026_03_26_180051_add_unidad_medida_to_detalle_productos_table',1),(30,'2026_03_28_001957_create_configuracions_table',1),(31,'2026_03_28_002009_add_tasa_bcv_aplicada_to_ventas_table',1),(32,'2026_03_28_060656_add_deleted_at_to_ventas_table',1),(33,'2026_03_29_195646_add_billing_and_delivery_columns_to_ventas_table',1),(34,'2026_04_07_132744_modify_users_table_add_documents',1),(35,'2026_04_07_141332_create_subscribers_table',1),(36,'2026_04_07_150327_create_categories_table',1),(37,'2026_04_07_150328_create_products_table',1),(38,'2026_04_07_150332_create_product_presentations_table',1),(39,'2026_04_07_151754_add_details_to_products_table',1),(40,'2026_04_08_024244_add_imagen_to_products_table',1),(41,'2026_04_09_012925_create_pagos_table',1),(42,'2026_04_09_012941_add_financial_breakdown_to_ventas_table',1),(43,'2026_04_09_055216_add_unit_conversion_to_detalle_productos_table',1),(44,'2026_04_09_182722_create_empaques_producto_table',1),(45,'2026_04_09_185431_add_empaque_id_to_cart_and_orders',1),(46,'2026_04_09_190859_add_alert_threshold_to_detalle_productos_table',1),(47,'2026_04_11_230815_erp_tables_refactor',1),(48,'2026_04_14_031133_add_instrucciones_uso_to_productos_table',2),(49,'2026_04_14_031738_create_comentarios_producto_table',2),(50,'2026_04_14_084927_add_galeria_to_productos_table',3),(51,'2026_04_14_224132_add_extra_fields_to_comentarios_producto_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificaciones_crm`
--

DROP TABLE IF EXISTS `notificaciones_crm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notificaciones_crm` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL DEFAULT 'newsletter',
  `variante_id` bigint(20) unsigned DEFAULT NULL,
  `procesado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notificaciones_crm_variante_id_foreign` (`variante_id`),
  CONSTRAINT `notificaciones_crm_variante_id_foreign` FOREIGN KEY (`variante_id`) REFERENCES `producto_variantes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones_crm`
--

LOCK TABLES `notificaciones_crm` WRITE;
/*!40000 ALTER TABLE `notificaciones_crm` DISABLE KEYS */;
INSERT INTO `notificaciones_crm` VALUES (1,'valengomezb@gmail.com','newsletter',NULL,0,'2026-04-12 05:40:55','2026-04-12 05:40:55'),(3,'cliente@stitchco.com.ve','stock_alert',71,1,'2026-04-14 07:34:24','2026-04-14 10:47:20'),(4,'valengomezb@gmail.com','stock_alert',71,1,'2026-04-14 07:50:02','2026-04-14 07:52:42');
/*!40000 ALTER TABLE `notificaciones_crm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orden_detalles`
--

DROP TABLE IF EXISTS `orden_detalles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orden_detalles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `orden_id` bigint(20) unsigned NOT NULL,
  `variante_id` bigint(20) unsigned DEFAULT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `unidad_medida_snapshot` varchar(255) NOT NULL DEFAULT 'Unidad',
  `factor_conversion_snapshot` decimal(10,4) NOT NULL DEFAULT 1.0000,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orden_detalles_orden_id_foreign` (`orden_id`),
  KEY `orden_detalles_variante_id_foreign` (`variante_id`),
  CONSTRAINT `orden_detalles_orden_id_foreign` FOREIGN KEY (`orden_id`) REFERENCES `ordenes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orden_detalles_variante_id_foreign` FOREIGN KEY (`variante_id`) REFERENCES `producto_variantes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orden_detalles`
--

LOCK TABLES `orden_detalles` WRITE;
/*!40000 ALTER TABLE `orden_detalles` DISABLE KEYS */;
INSERT INTO `orden_detalles` VALUES (2,3,56,1.00,11.42,'Unidad',1.0000,11.42,'2026-04-14 02:20:16','2026-04-14 02:20:16'),(3,4,59,27.00,12.32,'Unidad',1.0000,332.64,'2026-04-14 03:33:28','2026-04-14 03:33:28'),(5,6,70,16.00,11.62,'Unidad',1.0000,185.92,'2026-04-14 08:30:55','2026-04-14 08:30:55'),(6,7,66,1.00,9.53,'Unidad',1.0000,9.53,'2026-04-14 10:55:35','2026-04-14 10:55:35');
/*!40000 ALTER TABLE `orden_detalles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ordenes`
--

DROP TABLE IF EXISTS `ordenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ordenes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `estado` varchar(255) NOT NULL DEFAULT 'carrito',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `iva_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_abonado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tasa_bcv_aplicada` decimal(10,4) DEFAULT NULL,
  `direccion_envio` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`direccion_envio`)),
  `tipo_envio` varchar(255) DEFAULT NULL,
  `agencia_envio` varchar(255) DEFAULT NULL,
  `metodo_pago` varchar(255) DEFAULT NULL,
  `referencia_pago` varchar(255) DEFAULT NULL,
  `banco_pago` varchar(255) DEFAULT NULL,
  `telefono_pago` varchar(255) DEFAULT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ordenes_user_id_foreign` (`user_id`),
  CONSTRAINT `ordenes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordenes`
--

LOCK TABLES `ordenes` WRITE;
/*!40000 ALTER TABLE `ordenes` DISABLE KEYS */;
INSERT INTO `ordenes` VALUES (3,2,'cancelada',11.42,1.83,2.00,15.25,15.25,36.5000,'{\"calle\":\"Otro (Adyacencias), Avenida Juan Fern\\u00e1ndez de Le\\u00f3n \\/ Avenida Portugal, Colombia Norte\",\"ciudad\":\"Guanare\",\"estado\":\"Portuguesa\",\"zona\":\"Parroquia Guanare\",\"coordenadas\":{\"lat\":\"9.039585822147947\",\"lng\":\"-69.76381630064209\"},\"comprobante_url\":\"receipts\\/sbooS7v8wBugmQGgLXCZAmGoVU5EFs84Z1O0RcLI.png\"}','delivery','local','pago_movil','1234','0102 - Banco de Venezuela','04121234567','INV-20260413-00003','2026-04-14 02:42:21','2026-04-14 02:20:16','2026-04-14 03:44:40','2026-04-14 03:44:40'),(4,2,'cancelada',332.64,53.22,2.00,387.86,387.86,477.1500,'{\"calle\":\"Urb. El Nazareno, el estadio\",\"ciudad\":\"Guanare\",\"estado\":\"Portuguesa\",\"zona\":\"Parroquia Guanare\",\"coordenadas\":null,\"comprobante_url\":\"receipts\\/MYWqcmEt8g7tbXvQzDfN76wT6hOWTf5faRxbcpLx.jpg\"}','delivery','local','pago_movil','12345','0102 - Banco de Venezuela','04121234567','INV-20260413-00004',NULL,'2026-04-14 03:33:28','2026-04-14 03:44:40','2026-04-14 03:44:40'),(6,2,'cancelada',185.92,29.75,0.00,215.67,104.79,477.1500,'{\"calle\":\"Retiro en Tienda\",\"ciudad\":\"Guanare\",\"estado\":\"Portuguesa\",\"zona\":\"Retiro en Tienda\",\"coordenadas\":null,\"comprobante_url\":\"receipts\\/lhpTDJKf5HCFOYicA5cNqlUyotCRLgvakI8Zx09b.png\"}','retiro_tienda','local','pago_movil','1234','0177 - BANFANB','04121234567','INV-20260414-00006','2026-04-14 08:38:50','2026-04-14 08:30:55','2026-04-14 10:54:56','2026-04-14 10:54:56'),(7,2,'entregado',9.53,1.52,2.00,13.05,13.05,477.1500,'{\"calle\":\"Otro (Adyacencias), Avenida Juan Fern\\u00e1ndez de Le\\u00f3n \\/ Avenida Portugal, Colombia Norte\",\"ciudad\":\"Guanare\",\"estado\":\"Portuguesa\",\"zona\":\"Parroquia Guanare\",\"coordenadas\":{\"lat\":\"9.03917662521609\",\"lng\":\"-69.7642639192654\"},\"comprobante_url\":\"receipts\\/J8JbTl95jPJp7AT1x6n1g6NNS0IKJpUyzceciKGd.jpg\"}','delivery','local','pago_movil','1234','0174 - Banplus','04121234567','INV-20260414-00007','2026-04-15 17:12:25','2026-04-14 10:55:35','2026-04-15 17:12:25',NULL);
/*!40000 ALTER TABLE `ordenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto_variantes`
--

DROP TABLE IF EXISTS `producto_variantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producto_variantes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `producto_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `proveedor_id` bigint(20) unsigned DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `grosor` varchar(255) DEFAULT NULL,
  `marca` varchar(255) DEFAULT NULL,
  `unidad_medida` varchar(255) NOT NULL DEFAULT 'Unidad',
  `factor_conversion` decimal(10,4) NOT NULL DEFAULT 1.0000,
  `stock_base` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Solo usado si parent_id es nulo',
  `precio` decimal(10,2) DEFAULT NULL,
  `precio_usd` decimal(10,2) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `en_oferta` tinyint(1) NOT NULL DEFAULT 0,
  `descuento_porcentaje` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_variantes_producto_id_foreign` (`producto_id`),
  KEY `producto_variantes_parent_id_foreign` (`parent_id`),
  KEY `producto_variantes_proveedor_id_foreign` (`proveedor_id`),
  CONSTRAINT `producto_variantes_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `producto_variantes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `producto_variantes_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `producto_variantes_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto_variantes`
--

LOCK TABLES `producto_variantes` WRITE;
/*!40000 ALTER TABLE `producto_variantes` DISABLE KEYS */;
INSERT INTO `producto_variantes` VALUES (50,38,NULL,1,'Blanco',NULL,'Riley Blake','Metro',1.0000,54.00,9.49,NULL,'img/productos/1775513190_tela.jpg',0,16.00,'2026-04-13 08:53:13','2026-04-15 08:57:59',NULL),(51,39,NULL,1,'Negro',NULL,'Moda Fabrics','Metro',1.0000,34.00,12.41,NULL,'img/productos/1775513250_tela1.jpg',0,19.00,'2026-04-13 08:53:13','2026-04-15 08:57:59',NULL),(52,40,NULL,1,'Negro',NULL,'FreeSpirit','Metro',1.0000,60.00,3.16,NULL,'productos/1776136149_negrogim.webp',0,23.00,'2026-04-13 08:53:13','2026-04-15 08:57:59',NULL),(53,41,NULL,1,'Natural',NULL,'Robert Kaufman','Metro',1.0000,56.00,3.62,NULL,'img/productos/1775097935_lino.webp',1,18.00,'2026-04-13 08:53:13','2026-04-15 08:57:59',NULL),(54,42,NULL,1,'Blanco',NULL,'Loops & Threads','Unidad',1.0000,45.00,11.69,NULL,'img/productos/1775089039_hilo 100 algodon.png',0,17.00,'2026-04-13 08:53:13','2026-04-15 08:57:59',NULL),(55,39,NULL,1,'Rosa',NULL,'FreeSpirit','Metro',1.0000,17.00,2.67,NULL,'img/productos/1775514114_rosa.jpg',0,18.00,'2026-04-13 08:53:13','2026-04-15 08:57:59',NULL),(56,44,NULL,1,'Mostaza',NULL,'Loops & Threads','Unidad',1.0000,31.00,13.43,NULL,'img/productos/1775097676_mostaza.jpg',1,15.00,'2026-04-13 08:53:13','2026-04-15 08:23:32',NULL),(57,45,NULL,1,'Natural',NULL,'Lion Brand','Unidad',1.0000,50.00,12.37,NULL,'img/productos/1775089039_hilo 100 algodon.png',0,26.00,'2026-04-13 08:53:13','2026-04-15 08:57:59',NULL),(58,46,NULL,1,'├Ünico',NULL,'Tulip','Unidad',1.0000,41.00,13.67,NULL,'img/productos/1775514610_3.jpg',0,27.00,'2026-04-13 08:53:13','2026-04-15 08:23:32',NULL),(59,47,NULL,1,'├Ünico',NULL,'Susan Bates','Unidad',1.0000,81.00,14.00,NULL,'img/productos/1775514658_4.jpg',1,12.00,'2026-04-13 08:53:13','2026-04-15 08:23:32',NULL),(60,66,NULL,1,'Blanco',NULL,'ChiaoGoo','Ovillo',1.0000,31.00,3.02,NULL,'img/productos/1775514705_5.jpg',1,12.00,'2026-04-13 08:53:13','2026-04-15 08:57:59',NULL),(61,49,NULL,1,'├Ünico',NULL,'Susan Bates','Unidad',1.0000,17.00,12.35,NULL,'img/productos/1775512949_bambu.webp',0,11.00,'2026-04-13 08:53:13','2026-04-15 08:49:24',NULL),(62,50,NULL,1,'├Ünico',NULL,'Singer','Unidad',1.0000,39.00,12.70,NULL,'img/productos/1774760641_tijeras.jpg',0,25.00,'2026-04-13 08:53:13','2026-04-15 08:23:32',NULL),(63,51,NULL,1,'├Ünico',NULL,'Fiskars','Unidad',1.0000,38.00,6.27,NULL,'img/productos/1775094739_cremallera.jpg',0,23.00,'2026-04-13 08:53:13','2026-04-15 08:46:58',NULL),(64,52,NULL,1,'├Ünico',NULL,'Dritz','Unidad',1.0000,34.00,10.95,NULL,'img/productos/1775513107_b.jpg',0,15.00,'2026-04-13 08:53:13','2026-04-15 08:23:32',NULL),(65,53,NULL,1,'├Ünico',NULL,'Fiskars','Unidad',1.0000,52.00,11.75,NULL,'img/productos/1775094807_blanco.jpg',0,19.00,'2026-04-13 08:53:13','2026-04-15 08:23:32',NULL),(66,54,NULL,1,'├Ünico',NULL,'Dritz','Unidad',1.0000,32.00,11.76,NULL,'img/productos/1775513635_kit.jpg',1,19.00,'2026-04-13 08:53:13','2026-04-15 08:23:32',NULL),(67,55,NULL,1,'├Ünico',NULL,'ArtBin','Unidad',1.0000,19.00,13.29,NULL,'img/productos/1775514209_kit1.webp',0,27.00,'2026-04-13 08:53:13','2026-04-15 08:23:32',NULL),(68,56,NULL,1,'├Ünico',NULL,'Dritz','Unidad',1.0000,46.00,2.09,NULL,'img/productos/1775514969_kit4.jpg',1,13.00,'2026-04-13 08:53:13','2026-04-15 08:23:32',NULL),(69,57,NULL,1,'├Ünico',NULL,'Dritz','Unidad',1.0000,52.00,11.09,NULL,'img/productos/1775515021_ki5.jpg',0,15.00,'2026-04-13 08:53:13','2026-04-15 08:23:32',NULL),(70,58,NULL,1,'├Ünico',NULL,'La Mode','Unidad',1.0000,30.00,11.62,NULL,'productos/1776228476_nacar.webp',0,0.00,'2026-04-13 09:04:59','2026-04-15 18:35:34',NULL),(71,59,NULL,1,'Blanco',NULL,'Dritz','Unidad',1.0000,40.00,6.52,NULL,'img/productos/1775094807_blanco.jpg',1,11.00,'2026-04-13 09:04:59','2026-04-15 08:23:32',NULL),(72,39,NULL,1,'Azul',NULL,'FreeSpirit','Metro',1.0000,14.00,5.00,NULL,'productos/1776136186_denim.jpg',0,24.00,'2026-04-13 09:04:59','2026-04-15 08:57:59',NULL),(73,61,NULL,1,'├Ünico',NULL,NULL,'Unidad',1.0000,42.00,2.36,NULL,'img/productos/1775098026_denim.jpg',0,11.00,'2026-04-13 09:04:59','2026-04-14 07:08:22','2026-04-14 07:08:22'),(74,62,NULL,1,'Gris',NULL,'Clover','Unidad',1.0000,39.00,11.36,NULL,'img/productos/1774760641_tijeras.jpg',0,29.00,'2026-04-13 09:04:59','2026-04-15 08:48:35',NULL),(75,63,NULL,1,'Negro',NULL,'Dritz','Unidad',1.0000,24.00,11.69,NULL,'img/productos/1775094739_cremallera.jpg',0,18.00,'2026-04-13 09:04:59','2026-04-15 08:48:22',NULL),(76,64,NULL,1,'├Ünico',NULL,'Dritz','Unidad',1.0000,19.00,14.47,NULL,'img/productos/1775513635_kit.jpg',0,20.00,'2026-04-13 09:04:59','2026-04-15 08:23:32',NULL),(77,65,NULL,1,'├Ünico',NULL,'Fiskars','Unidad',1.0000,48.00,7.73,NULL,'img/productos/1775514209_kit1.webp',0,17.00,'2026-04-13 09:04:59','2026-04-15 08:23:32',NULL),(78,67,NULL,1,'Rosado',NULL,NULL,'Metro',1.0000,20.00,5.00,NULL,'productos/1776264427_kitty.jpg',0,0.00,'2026-04-15 18:47:07','2026-04-15 19:00:30','2026-04-15 19:00:30'),(79,67,78,1,'Rosado',NULL,NULL,'Metro',1.0000,0.00,5.00,NULL,'productos/1776264427_kitty.jpg',0,0.00,'2026-04-15 18:47:07','2026-04-15 18:52:13','2026-04-15 18:52:13'),(80,67,78,1,'Rosado',NULL,NULL,'Rollo',50.0000,0.00,5.00,NULL,'productos/1776264427_kitty.jpg',0,0.00,'2026-04-15 18:47:07','2026-04-15 18:52:13','2026-04-15 18:52:13'),(81,67,78,1,'Rosado',NULL,NULL,'Metro',1.0000,0.00,5.00,NULL,'productos/1776264427_kitty.jpg',0,0.00,'2026-04-15 18:52:13','2026-04-15 18:53:38','2026-04-15 18:53:38'),(82,67,78,1,'Rosado',NULL,NULL,'Rollo',20.0000,0.00,5.00,NULL,'productos/1776264427_kitty.jpg',0,0.00,'2026-04-15 18:52:13','2026-04-15 18:53:38','2026-04-15 18:53:38'),(83,67,78,1,'Rosado',NULL,NULL,'Metro',1.0000,0.00,5.00,NULL,'productos/1776264427_kitty.jpg',0,0.00,'2026-04-15 18:53:38','2026-04-15 18:54:22','2026-04-15 18:54:22'),(84,67,78,1,'Rosado',NULL,NULL,'Rollo',20.0000,0.00,5.00,NULL,'productos/1776264427_kitty.jpg',0,0.00,'2026-04-15 18:53:38','2026-04-15 18:54:22','2026-04-15 18:54:22'),(85,67,78,1,'Rosado',NULL,NULL,'Metro',1.0000,0.00,5.00,NULL,'productos/1776264427_kitty.jpg',0,0.00,'2026-04-15 18:54:22','2026-04-15 18:58:43','2026-04-15 18:58:43'),(86,67,78,1,'Rosado',NULL,NULL,'Rollo',20.0000,0.00,5.00,NULL,'productos/1776264427_kitty.jpg',0,0.00,'2026-04-15 18:54:22','2026-04-15 18:58:43','2026-04-15 18:58:43'),(87,67,78,1,'Rosado',NULL,NULL,'Metro',1.0000,0.00,5.00,NULL,'productos/1776264427_kitty.jpg',0,0.00,'2026-04-15 18:58:43','2026-04-15 19:00:30','2026-04-15 19:00:30'),(88,67,78,1,'Rosado',NULL,NULL,'Rollo',20.0000,0.00,5.00,NULL,'productos/1776264427_kitty.jpg',0,0.00,'2026-04-15 18:58:43','2026-04-15 19:00:30','2026-04-15 19:00:30'),(89,67,NULL,NULL,'rosa',NULL,NULL,'Unidad',1.0000,20.00,5.00,NULL,'productos/1776265298_kitty.jpg',0,0.00,'2026-04-15 19:01:38','2026-04-15 19:02:59',NULL),(90,67,89,NULL,NULL,NULL,NULL,'Metro',1.0000,0.00,5.00,NULL,'productos/1776265298_kitty.jpg',0,0.00,'2026-04-15 19:01:38','2026-04-15 19:02:59','2026-04-15 19:02:59'),(91,67,89,NULL,NULL,NULL,NULL,'Rollo',20.0000,0.00,5.00,NULL,'productos/1776265298_kitty.jpg',0,0.00,'2026-04-15 19:01:38','2026-04-15 19:02:59','2026-04-15 19:02:59'),(92,67,89,NULL,'rosa',NULL,NULL,'Metro',1.0000,0.00,5.00,NULL,'productos/1776265298_kitty.jpg',0,0.00,'2026-04-15 19:02:59','2026-04-15 19:02:59',NULL),(93,67,89,NULL,'rosa',NULL,NULL,'Rollo',20.0000,0.00,5.00,NULL,'productos/1776265298_kitty.jpg',0,0.00,'2026-04-15 19:02:59','2026-04-15 19:02:59',NULL);
/*!40000 ALTER TABLE `producto_variantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `instrucciones_uso` text DEFAULT NULL,
  `galeria` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`galeria`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `categoria_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (38,'Tela de Gabardina Blanca','Tela de gabardina blanca de excelente calidad, ideal para confecci├│n de uniformes, pantalones y chaquetas. Tejido firme con acabado diagonal caracter├¡stico. Resistente al uso continuo y f├ícil de planchar.','Instrucciones exclusivas de cuidado:\n\n1. Lavar a m├íquina en ciclo suave con agua fr├¡a (m├íx. 30┬░C).\n2. No usar lej├¡a ni productos agresivos.\n3. Planchar a temperatura media del rev├®s.\n4. Secar a la sombra para mantener el color vibrante.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',2),(39,'Tela de Cuero Sint├®tico','Cuero sint├®tico negro de alta calidad, perfecto para tapicer├¡a, bolsos, cinturones y proyectos de marroquiner├¡a. Textura suave con acabado liso profesional. Resistente al desgaste y f├ícil de limpiar.','Instrucciones exclusivas de cuidado:\n\n1. Lavar a m├íquina en ciclo suave con agua fr├¡a (m├íx. 30┬░C).\n2. No usar lej├¡a ni productos agresivos.\n3. Planchar a temperatura media del rev├®s.\n4. Secar a la sombra para mantener el color vibrante.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',2),(40,'Tela Denim Negro','Tela denim de algod├│n en color negro intenso. Peso medio ideal para jeans, chaquetas y faldas. Tejido resistente con ca├¡da natural. Prelavado para evitar encogimiento.','Instrucciones exclusivas de cuidado:\n\n1. Lavar a m├íquina en ciclo suave con agua fr├¡a (m├íx. 30┬░C).\n2. No usar lej├¡a ni productos agresivos.\n3. Planchar a temperatura media del rev├®s.\n4. Secar a la sombra para mantener el color vibrante.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',2),(41,'Tela de Lino Natural','Lino 100% natural en tono crudo. Fibra transpirable y fresca, perfecta para prendas de verano, cortinas y decoraci├│n del hogar. Textura org├ínica con ca├¡da elegante.','Instrucciones exclusivas de cuidado:\n\n1. Lavar a m├íquina en ciclo suave con agua fr├¡a (m├íx. 30┬░C).\n2. No usar lej├¡a ni productos agresivos.\n3. Planchar a temperatura media del rev├®s.\n4. Secar a la sombra para mantener el color vibrante.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',2),(42,'Hilo de Algod├│n Blanco','Cono de hilo 100% algod├│n blanco natural. Ideal para macram├®, tejido artesanal y proyectos decorativos. Fibra suave, resistente y libre de qu├¡micos. Grosor medio para m├║ltiples t├®cnicas.','Instrucciones exclusivas de cuidado:\n\n1. Lavar a m├íquina en ciclo suave con agua fr├¡a (m├íx. 30┬░C).\n2. No usar lej├¡a ni productos agresivos.\n3. Planchar a temperatura media del rev├®s.\n4. Secar a la sombra para mantener el color vibrante.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',1),(43,'Ovillo Rosa Premium - 22','Insumo de altisima calidad para tus proyectos de Lanas','Recomendaciones para su tejido:\n\n- Ideal para agujas de 4mm a 6mm.\n- Lavar a mano suavemente con jab├│n neutro, sin frotar bruscamente.\n- Secar en posici├│n horizontal (extendido sobre una toalla) para evitar que la prenda pierda su forma original.\n- No usar secadora autom├ítica bajo ning├║n motivo.',NULL,'2026-04-13 08:53:13','2026-04-14 12:12:03',1),(44,'Ovillo de Lana Mostaza','Ovillo de lana gruesa color mostaza, perfecto para tejer bufandas, gorros y mantas con agujas gruesas. Fibra c├ílida y esponjosa con excelente cobertura. Ideal para proyectos de invierno.','Recomendaciones para su tejido:\r\n\r\n- Ideal para agujas de 4mm a 6mm.\r\n- Lavar a mano suavemente con jab├│n neutro, sin frotar bruscamente.\r\n- Secar en posici├│n horizontal (extendido sobre una toalla) para evitar que la prenda pierda su forma original.\r\n- No usar secadora autom├ítica bajo ning├║n motivo.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',1),(45,'Hilo de Algod├│n Natural','Cono de hilo de algod├│n 100% natural, ideal para macram├®, crochet y tejido a mano. Fibra suave y ecol├│gica con buena torsi├│n. Vers├ítil para proyectos artesanales y decorativos.','Instrucciones exclusivas de cuidado:\n\n1. Lavar a m├íquina en ciclo suave con agua fr├¡a (m├íx. 30┬░C).\n2. No usar lej├¡a ni productos agresivos.\n3. Planchar a temperatura media del rev├®s.\n4. Secar a la sombra para mantener el color vibrante.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',1),(46,'Set de Agujas Intercambiables','Set profesional de agujas intercambiables con puntas de colores para f├ícil identificaci├│n de medidas. Incluye cables flexibles y accesorios. Estuche acolchado con organizador. Ideal para tejido circular y recto.','Modo de uso recomendado:\n\n- Manipular con cuidado durante su aplicaci├│n.\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',5),(47,'Regla Curva Francesa de Costura','Regla curva francesa (French Curve) de acr├¡lico transparente con medidas m├®tricas. Herramienta esencial para patronaje y dise├▒o de moda. Permite trazar curvas de escote, sisa y cadera con precisi├│n profesional.','Modo de uso recomendado:\n\n- Manipular con cuidado durante su aplicaci├│n.\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',5),(48,'Lana Chenille Gruesa Blanca','Pack de 2 ovillos de lana chenille extra gruesa color blanco crema. Textura ultra suave tipo terciopelo, ideal para mantas, cojines y amigurumis. R├ípida de tejer con agujas gruesas (10-15mm).','Modo de uso recomendado:\n\n- Manipular con cuidado durante su aplicaci├│n.\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',5),(49,'Set de Agujas de Crochet Intercambiables','Kit profesional de agujas de crochet con mangos de madera de bamb├║ y puntas de colores intercambiables. Incluye estuche organizador con cables, conectores y accesorios. Medidas variadas para todo tipo de proyecto.','Modo de uso recomendado:\r\n\r\n- Manipular con cuidado durante su aplicaci├│n.\r\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\r\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',5),(50,'Tijera de Costura Profesional','Tijera de modister├¡a profesional de acero inoxidable con tornillo dorado. Hojas afiladas de 8 pulgadas para cortes precisos en tela. Mango ergon├│mico cromado. Ideal para corte de patrones y confecci├│n.','Modo de uso recomendado:\n\n- Manipular con cuidado durante su aplicaci├│n.\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',6),(51,'Cierre Negro Separable','Cierre separable de nylon color negro, ideal para chaquetas, sudaderas y prendas de abrigo. Dientes de nylon resistentes con deslizador met├ílico de doble sentido. Disponible en largo est├índar.','Modo de uso recomendado:\r\n\r\n- Manipular con cuidado durante su aplicaci├│n.\r\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\r\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 08:53:13','2026-04-15 09:09:22',6),(52,'Botones Cl├ísicos N├ícar Blanco','Set de botones redondos de n├ícar natural blanco con 2 orificios. Acabado iridiscente artesanal con brillo sutil. Perfectos para camisas, blusas y prendas elegantes. Material resistente y duradero.','Modo de uso recomendado:\n\n- Manipular con cuidado durante su aplicaci├│n.\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',6),(53,'Botones Cl├ísicos N├ícar Blanco','Botones redondos de n├ícar genuino blanco con dos orificios. Brillo iridiscente natural que aporta elegancia a cualquier prenda. Ideales para camiser├¡a fina y alta costura.','Para una m├íxima durabilidad:\n\n- Coser firmemente utilizando hilo de poli├®ster resistente.\n- Evitar el contacto directo con planchas a altas temperaturas sobre la superficie del bot├│n.\n- Resiste perfectamente ciclos de lavado convencionales y limpieza en seco.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',6),(54,'Kit de Costura Completo Profesional','Estuche de costura profesional con agujas intercambiables de colores, cables flexibles y accesorios de tejido. Organizaci├│n perfecta para todas tus herramientas. Incluye estuche acolchado con Cierre.','Modo de uso recomendado:\n\n- Manipular con cuidado durante su aplicaci├│n.\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 08:53:13','2026-04-15 09:05:58',7),(55,'Kit de Costura Port├ítil con Hilos','Malet├¡n de costura port├ítil que incluye 36 carretes de hilo de colores surtidos, tijeras, agujas, alfileres, cinta m├®trica y accesorios esenciales. Todo lo que necesitas para reparaciones r├ípidas y proyectos creativos.','Recomendaciones para su tejido:\n\n- Ideal para agujas de 4mm a 6mm.\n- Lavar a mano suavemente con jab├│n neutro, sin frotar bruscamente.\n- Secar en posici├│n horizontal (extendido sobre una toalla) para evitar que la prenda pierda su forma original.\n- No usar secadora autom├ítica bajo ning├║n motivo.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',7),(56,'Alfileres con Cabeza de Vidrio','Caja de alfileres de acero inoxidable con cabeza esf├®rica de vidrio multicolor. Resistentes al calor de la plancha. F├íciles de ver y manipular gracias a sus cabezas de colores. Indispensables para costura y patronaje.','Modo de uso recomendado:\n\n- Manipular con cuidado durante su aplicaci├│n.\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',7),(57,'Kit de Bordado con Bastidor','Kit completo de bordado que incluye bastidor de bamb├║ con dise├▒o floral pre-impreso, 20 madejas de hilo moulin├® de colores, agujas de bordar y cortah├¡los. Ideal para principiantes y decoraci├│n artesanal.','Modo de uso recomendado:\n\n- Manipular con cuidado durante su aplicaci├│n.\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 08:53:13','2026-04-15 08:57:59',7),(58,'Botones N├ícar Blanco Premium','Botones de n├ícar blanco natural con acabado brillante iridiscente. Dos orificios para f├ícil costura. Material genuino de alta calidad para camisas, blusas y prendas de vestir elegantes.','Modo de uso recomendado:\r\n\r\n- Manipular con cuidado durante su aplicaci├│n.\r\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\r\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 09:04:59','2026-04-15 08:57:59',8),(59,'Botones N├ícar Blanco Cl├ísicos','Botones cl├ísicos de n├ícar natural en color blanco puro. Acabado liso con brillo perlado. Perfectos para prendas formales y casuales. Resistentes al lavado y planchado.','Para una m├íxima durabilidad:\r\n\r\n- Coser firmemente utilizando hilo de poli├®ster resistente.\r\n- Evitar el contacto directo con planchas a altas temperaturas sobre la superficie del bot├│n.\r\n- Resiste perfectamente ciclos de lavado convencionales y limpieza en seco.',NULL,'2026-04-13 09:04:59','2026-04-15 08:57:59',8),(60,'Tela de jean','Insumo premium de Telas','Modo de uso recomendado:\r\n\r\n- Manipular con cuidado durante su aplicaci├│n.\r\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\r\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 09:04:59','2026-04-15 07:52:09',8),(61,'Botones Vintage Decorativos','Insumo premium para Botones','Modo de uso recomendado:\n\n- Manipular con cuidado durante su aplicaci├│n.\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 09:04:59','2026-04-15 08:40:50',8),(62,'Tijera Profesional de Modister├¡a','Tijera profesional de modister├¡a de acero inoxidable con tornillo dorado decorativo. Hojas extra afiladas para cortes limpios en todo tipo de tela. Mango cromado ergon├│mico para uso prolongado sin fatiga.','Modo de uso recomendado:\r\n\r\n- Manipular con cuidado durante su aplicaci├│n.\r\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\r\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 09:04:59','2026-04-15 08:57:59',9),(63,'Cierre Negro Reforzado','Cierre separable reforzada de nylon negro con cursor met├ílico resistente. Ideal para chaquetas deportivas, bolsos y equipaje. Cierre suave y durable para uso intensivo.','Modo de uso recomendado:\r\n\r\n- Manipular con cuidado durante su aplicaci├│n.\r\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\r\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 09:04:59','2026-04-15 09:09:22',9),(64,'Kit de Agujas para M├íquina de Coser','Set completo de agujas universales para m├íquina de coser (HAX1) en 4 medidas: 65/9, 75/11, 90/14, 100/16 y 110/18. Incluye canillas met├ílicas. Compatible con la mayor├¡a de m├íquinas dom├®sticas Singer, Brother y Janome.','Recomendaciones para su tejido:\n\n- Ideal para agujas de 4mm a 6mm.\n- Lavar a mano suavemente con jab├│n neutro, sin frotar bruscamente.\n- Secar en posici├│n horizontal (extendido sobre una toalla) para evitar que la prenda pierda su forma original.\n- No usar secadora autom├ítica bajo ning├║n motivo.',NULL,'2026-04-13 09:04:59','2026-04-15 08:57:59',9),(65,'Kit de Costura Port├ítil Compacto','Malet├¡n organizador de costura con hilos multicolor, tijeras, agujas, cinta m├®trica y accesorios esenciales. Formato port├ítil ideal para viajes y reparaciones r├ípidas. Todo organizado en compartimentos pr├ícticos.','Modo de uso recomendado:\n\n- Manipular con cuidado durante su aplicaci├│n.\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-13 09:04:59','2026-04-15 08:57:59',9),(66,'Lana','Insumo de altisima calidad para tus proyectos de Tejidos','Modo de uso recomendado:\r\n\r\n- Manipular con cuidado durante su aplicaci├│n.\r\n- Mantener almacenado en un lugar fresco y seco, alejado de la humedad extrema o luz solar directa para evitar deterioro.\r\n- Limpiar con un pa├▒o seco si es necesario.',NULL,'2026-04-15 08:50:08','2026-04-15 08:50:08',5),(67,'Tela para sabanas','Para ni├▒as','lavar a mano',NULL,'2026-04-15 18:47:07','2026-04-15 18:47:07',2);
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proveedores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `tipo_documento` enum('V','E','J','G') NOT NULL DEFAULT 'J',
  `documento_identidad` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proveedores_documento_identidad_unique` (`documento_identidad`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
INSERT INTO `proveedores` VALUES (1,'Textiles Premium CA','J','123456789','0000-0000001','ventas@textilespremium.com','Zona Industrial, Galp├│n 4','2026-04-12 05:27:27','2026-04-14 03:04:08'),(2,'Mercer├¡a Internacional','J','987654321','0000-0000002','pedidos@merceriaint.com','Centro Comercial Los Telares','2026-04-12 05:27:27','2026-04-14 03:04:08'),(3,'Hilos del Norte','J','456789123','0000-0000003','logistica@hilosdelnorte.com','Avenida Principal del Norte, Edificio Sur','2026-04-12 05:27:27','2026-04-14 03:04:08');
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('4saDTl66HkS8efLwCWrFmQ7L779OpQmLWPa5k50a',14,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaFlWWnRsN1hQV0lHelRiSThEc1poNzBMUkRFaXNhVXpkZlhpQ3JTdSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTQ7fQ==',1776268314),('5bRXnqFwULu0BZa2PLEWtX876HzKZlOmTNJlZaPP',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVnEyODZya0tGaVV0NFlKUWJ2ZXpNa2h5NVBPakNnZzlxUWc4bUhMbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9jbGllbnRlcyI7czo1OiJyb3V0ZSI7czoxMzoiYWRtaW4uY2xpZW50cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1776268240),('9iPPocWwWgRZuA3LBV0xguPfgaalkX2br6OK4PiF',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUkNHS3A3WHNzRUhzOTZHRTNGQlhZZ1laWTRFUmNZV045Tm1XdWxLVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9hcGkvdmVudGFzLWNhdGVnb3JpYSI7czo1OiJyb3V0ZSI7czoyNjoiYWRtaW4uYXBpLnZlbnRhcy1jYXRlZ29yaWEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1776572660),('cV88AFTDx7bER8GYH6S96UfFyAeXISY4c4hYq6J3',NULL,'192.168.0.179','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQWJzcnVHTnJQY2U0TEtSOERQQlFFS3JDN2lWbmZBaGMxWTBNTERJUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTEwOiJodHRwOi8vMTkyLjE2OC4wLjE3Nzo4MDAwL2ZhY3R1cmEvNz9zaWduYXR1cmU9MjE3YWFiOThkMjM3OTI4Zjg5MTFmZDM0ZTdiZGQ1NWZjY2U0OTE2YzUyMDg3MjI2ODE1N2E2ODhjZGE5ZTdjYiI7czo1OiJyb3V0ZSI7czoxNDoiaW52b2ljZS5wdWJsaWMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1776259144),('E08NU0qysIqIR1je3GuV3YxT7IS0UhuzVhc7zoSM',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicVZ2QXM2OFI0aVpXWGNUb1c1dVNmMjZVbGwwUWZKQWcxc2ZObHBFSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9kdWN0by84OSI7czo1OiJyb3V0ZSI7czoxMzoicHJvZHVjdHMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1776572746),('rZEeCULnaCDunRXTZcVjEd5n5vW1ydIeENRCRngp',NULL,'192.168.0.179','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibUFJZ3ljelpOelZXajhTUkwxWEF4b0Z6Tm03NWN3Z0xGNDJRQk5uRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTEwOiJodHRwOi8vMTkyLjE2OC4wLjE3Nzo4MDAwL2ZhY3R1cmEvNz9zaWduYXR1cmU9MjE3YWFiOThkMjM3OTI4Zjg5MTFmZDM0ZTdiZGQ1NWZjY2U0OTE2YzUyMDg3MjI2ODE1N2E2ODhjZGE5ZTdjYiI7czo1OiJyb3V0ZSI7czoxNDoiaW52b2ljZS5wdWJsaWMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1776259143);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trabajos`
--

DROP TABLE IF EXISTS `trabajos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trabajos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trabajos`
--

LOCK TABLES `trabajos` WRITE;
/*!40000 ALTER TABLE `trabajos` DISABLE KEYS */;
/*!40000 ALTER TABLE `trabajos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trabajos_fallidos`
--

DROP TABLE IF EXISTS `trabajos_fallidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trabajos_fallidos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trabajos_fallidos`
--

LOCK TABLES `trabajos_fallidos` WRITE;
/*!40000 ALTER TABLE `trabajos_fallidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `trabajos_fallidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `rol` enum('cliente','admin') NOT NULL DEFAULT 'cliente',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `direcciones` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`direcciones`)),
  `lista_deseos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`lista_deseos`)),
  `tipo_documento` enum('V','E','J','G') DEFAULT NULL,
  `documento_identidad` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_documento_identidad_unique` (`documento_identidad`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','Stitch','admin@stitchco.com.ve',NULL,'$2y$12$PDqJiBzxc2YVMfGpTewOF.2qrfInhleqo3uzfCjvB1/B92mNX9rtG','+58 412 0000000','admin','muJzdfZHLTdDbxEYHLqDyDyemm8DqO9GIjJifDCi3hG5RnNa18PfdzCkYjxU','2026-04-12 05:27:27','2026-04-13 07:11:07',NULL,NULL,'V','11000000'),(2,'Mar├¡a','Gonz├ílez','cliente@stitchco.com.ve',NULL,'$2y$12$GrQZ9WrOSqIvOahJgq6jhOtDtVWCQDjTdWhYBkMQmFqQgPsiqcCBW','+58 414 1234567','cliente',NULL,'2026-04-12 05:27:27','2026-04-15 08:23:32',NULL,'[53]','V','22000000'),(3,'Valentina','G├│mez','valengomezb@gmail.com',NULL,'$2y$12$76qwiRx.KHuqtUQfiQ5n8.zhpmqgS9cYLbGYa2Ms3Hp7XoBCOpblS','04245659154','cliente',NULL,'2026-04-12 09:40:44','2026-04-15 08:23:32',NULL,NULL,'V','29632089'),(4,'Mateo','Puga','mateo.puga@example.net','2026-04-14 07:19:48','$2y$12$hS3CgCR2wMRvuLRpvAvTH.5oWgRj58qvcGP74y3Ek.yzelBTswCgy','+34 996 907572','cliente',NULL,'2026-04-14 07:19:48','2026-04-15 08:23:32',NULL,NULL,'V','32819225'),(5,'Isabela','Guevara','iguevara@example.com','2026-04-14 07:19:48','$2y$12$Rh1xDO5wETTbsKzaBwC7zusb8VvRfNj7OqEumFV2dUeaNLkM/5zUa','928 224588','cliente',NULL,'2026-04-14 07:19:48','2026-04-15 08:23:32',NULL,NULL,'V','83552040'),(6,'Aitana','Garay','aitana.garay@example.org','2026-04-14 07:19:48','$2y$12$5ukHlqJQQ83v7YvPwryJce/hUxsm4IK29TceSdo7Z4vgVQtnlQoWO','+34 988966511','cliente',NULL,'2026-04-14 07:19:48','2026-04-15 08:23:32',NULL,NULL,'V','39878503'),(7,'Aleix','Carbonell','aleix.carbonell@example.com','2026-04-14 07:19:49','$2y$12$d0tBfT1WTCy3nkHtWsw1n.VCChR9VMllAbbhMBGKidY8mHxX2AU/y','947 25 9525','cliente',NULL,'2026-04-14 07:19:49','2026-04-15 08:23:32',NULL,NULL,'V','90906312'),(8,'├ülvaro','Negr├│n','negron.alvaro@example.com','2026-04-14 07:19:49','$2y$12$u3vfTvqZCSL7wGu5RraQoexeBZTq3W87Urxbf8NMSwHrSEtKhQ49K','+34 969-375143','cliente',NULL,'2026-04-14 07:19:49','2026-04-15 08:23:32',NULL,NULL,'V','45494978'),(9,'Iker','Villanueva','villanueva.iker@example.com','2026-04-14 07:19:49','$2y$12$QYcopkKV2wFtKkNzQIb7DOpzaQ6sm4pChBTgBbc57U97Gph3FqkZC','+34 904 306400','cliente',NULL,'2026-04-14 07:19:49','2026-04-15 08:23:32',NULL,NULL,'V','79363948'),(10,'Francisco','Salvador','franciscojavier.salvador@example.org','2026-04-14 07:19:49','$2y$12$96/LgDNdxgL45PICEOSH0OOtuDXM4DXB0AoakJLmQ0qaeAxG/fGlS','924 586470','cliente',NULL,'2026-04-14 07:19:49','2026-04-15 08:23:32',NULL,NULL,'V','50234676'),(11,'C├®sar','Anaya','anaya.cesar@example.net','2026-04-14 07:19:49','$2y$12$fa3Aeua6i50QbzqWtpCYvukq60hb5YggxM5R9ruw/RkDTOshF3aVi','946-34-2550','cliente',NULL,'2026-04-14 07:19:49','2026-04-15 08:23:32',NULL,NULL,'V','34390810'),(12,'Rosa Mar├¡a','Pati├▒o','patino.rosamaria@example.net','2026-04-14 07:19:50','$2y$12$8ipDRvbuGxPWC4WtbDVEhuRbyYvdABEWr6oI4w0BXVGssC/JhnlKe','+34 986-062852','cliente',NULL,'2026-04-14 07:19:50','2026-04-15 08:23:32',NULL,NULL,'V','31310011'),(13,'Carmen','Cort├®s','ccortes@example.com','2026-04-14 07:19:50','$2y$12$Vt0QfU9rfGMeTBMtjz5JW.8nKRN7v04901216ONkKVVA78gc8.x0q','+34 917-74-8499','cliente',NULL,'2026-04-14 07:19:50','2026-04-15 08:23:32',NULL,NULL,'V','92456697'),(14,'Amarelis','Ibarra','amaybadug@gmail.com',NULL,'$2y$12$c89CSc02R3AztOMu./MsL.mqd789O7dNChaTJNzUkH0QsZuVifdFC','04125262452','cliente',NULL,'2026-04-15 19:14:58','2026-04-15 19:14:58',NULL,NULL,'V','15032367'),(15,'yo','nie','yomio@ya.com',NULL,'$2y$12$EAvvOaS5aOQ3LFmAj10cNuvv6gMJx1LvlUH1imC4fdK/lUNHsgaKW',NULL,'cliente',NULL,'2026-04-15 19:44:43','2026-04-15 19:50:20',NULL,'[70]','V','000001');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-19  0:27:59
