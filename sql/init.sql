-- MySQL dump 10.13  Distrib 8.4.11, for Linux (x86_64)
--
-- Host: localhost    Database: pawsmatch
-- ------------------------------------------------------
-- Server version	8.4.11

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `adopciones`
--

DROP TABLE IF EXISTS `adopciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `adopciones` (
  `id_adopcion` int NOT NULL AUTO_INCREMENT,
  `id_solicitud` int NOT NULL,
  `fecha_adopcion` date NOT NULL,
  `firma_aceptada` tinyint(1) DEFAULT '0',
  `ip_firma` varchar(45) DEFAULT NULL,
  `estado` enum('ACTIVA','FINALIZADA','DEVUELTA') DEFAULT 'ACTIVA',
  `observaciones` text,
  PRIMARY KEY (`id_adopcion`),
  KEY `fk_adopcion_solicitud` (`id_solicitud`),
  CONSTRAINT `fk_adopcion_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes` (`id_solicitud`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adopciones`
--

LOCK TABLES `adopciones` WRITE;
/*!40000 ALTER TABLE `adopciones` DISABLE KEYS */;
INSERT INTO `adopciones` VALUES (2,3,'2026-08-18',0,NULL,'ACTIVA',NULL),(4,2,'2026-08-18',0,NULL,'ACTIVA',NULL),(5,10,'2026-08-18',0,NULL,'ACTIVA',NULL),(6,11,'2026-03-15',1,NULL,'ACTIVA',NULL),(7,12,'2026-05-20',1,NULL,'ACTIVA',NULL),(8,13,'2026-07-10',1,NULL,'ACTIVA',NULL);
/*!40000 ALTER TABLE `adopciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adoptantes`
--

DROP TABLE IF EXISTS `adoptantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `adoptantes` (
  `id_adoptante` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `tipo_vivienda` enum('CASA','APARTAMENTO','OTRO') DEFAULT NULL,
  `tiene_patio` tinyint(1) DEFAULT '0',
  `tiene_otros_animales` tinyint(1) DEFAULT '0',
  `tiene_ninos` tinyint(1) DEFAULT '0',
  `experiencia_mascotas` enum('NINGUNA','BASICA','INTERMEDIA','ALTA') DEFAULT NULL,
  `tiempo_disponible` enum('BAJO','MEDIO','ALTO') DEFAULT NULL,
  `preferencia_especie` varchar(50) DEFAULT NULL,
  `preferencia_tamano` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_adoptante`),
  KEY `fk_adoptante_usuario` (`id_usuario`),
  CONSTRAINT `fk_adoptante_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adoptantes`
--

LOCK TABLES `adoptantes` WRITE;
/*!40000 ALTER TABLE `adoptantes` DISABLE KEYS */;
INSERT INTO `adoptantes` VALUES (1,1,'CASA',1,0,0,'BASICA','MEDIO','Perro','PEQUENO'),(2,2,'APARTAMENTO',0,1,0,NULL,'ALTO','Perro','MEDIANO'),(6,11,'CASA',1,1,1,'ALTA','ALTO','Gato',NULL),(14,20,'APARTAMENTO',0,1,1,'BASICA','MEDIO','Gato','PEQUENO'),(16,22,'CASA',1,0,0,'ALTA','ALTO','Perro','GRANDE'),(17,23,NULL,0,0,0,NULL,NULL,NULL,NULL),(18,24,'APARTAMENTO',0,0,0,'BASICA','MEDIO','Gato','PEQUENO'),(19,25,'CASA',1,0,0,'INTERMEDIA','ALTO','Perro','GRANDE'),(20,26,'APARTAMENTO',0,1,0,'BASICA','MEDIO','Gato','PEQUENO'),(21,27,'CASA',1,1,1,'ALTA','ALTO',NULL,'MEDIANO'),(22,28,'APARTAMENTO',0,0,0,'NINGUNA','BAJO','Gato','PEQUENO'),(23,29,'CASA',1,0,1,'BASICA','MEDIO','Perro','MEDIANO'),(24,30,'OTRO',0,1,0,'INTERMEDIA','ALTO',NULL,NULL);
/*!40000 ALTER TABLE `adoptantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacora`
--

DROP TABLE IF EXISTS `bitacora`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitacora` (
  `id_bitacora` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `accion` varchar(150) NOT NULL,
  `descripcion` text,
  `ip` varchar(45) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_bitacora`),
  KEY `fk_bitacora_usuario` (`id_usuario`),
  CONSTRAINT `fk_bitacora_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora`
--

LOCK TABLES `bitacora` WRITE;
/*!40000 ALTER TABLE `bitacora` DISABLE KEYS */;
INSERT INTO `bitacora` VALUES (1,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 01:13:04'),(5,5,'Aprobó solicitud de adopción','Solicitud #1 — Max','172.21.0.1','2026-08-18 01:13:33'),(7,5,'Registró mascota','Firulais','172.21.0.1','2026-08-18 01:15:22'),(8,5,'Cambió visibilidad de mascota','Firulais -> INACTIVO','172.21.0.1','2026-08-18 01:15:23'),(9,5,'Eliminó mascota','Firulais','172.21.0.1','2026-08-18 01:15:23'),(10,7,'Creó usuario','Prueba Admin (ADOPTANTE)','172.21.0.1','2026-08-18 01:15:33'),(12,7,'Creó usuario','Prueba Admin (ADOPTANTE)','172.21.0.1','2026-08-18 01:15:53'),(13,7,'Cambió estado de usuario','Prueba -> INACTIVO','172.21.0.1','2026-08-18 01:15:53'),(14,11,'Registro de usuario','Jenn Abarca Duran se registró como adoptante.','172.21.0.1','2026-08-18 01:22:53'),(15,11,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 01:23:28'),(16,11,'Solicitó adopción','Mascota #9','172.21.0.1','2026-08-18 01:25:09'),(17,11,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 01:26:03'),(18,11,'Solicitó adopción','Mascota #14','172.21.0.1','2026-08-18 01:27:11'),(19,11,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 01:28:08'),(20,5,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 01:31:44'),(21,5,'Aprobó solicitud de adopción','Solicitud #3 — Bruno','172.21.0.1','2026-08-18 01:32:13'),(22,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 01:33:22'),(23,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 01:52:24'),(24,5,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 01:52:24'),(28,5,'Aprobó solicitud de adopción','Solicitud #4 — Max','172.21.0.1','2026-08-18 01:52:35'),(29,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 01:57:20'),(32,5,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 02:01:36'),(36,5,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 02:06:26'),(37,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 02:06:27'),(38,11,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 02:13:24'),(46,1,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 02:36:50'),(47,1,'Solicitó recuperación de contraseña','','172.21.0.1','2026-08-18 02:37:56'),(48,1,'Restableció su contraseña','','172.21.0.1','2026-08-18 02:38:26'),(53,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 02:56:56'),(54,7,'Cambió estado de refugio','Huellitas Felices -> APROBADO','172.21.0.1','2026-08-18 02:56:56'),(59,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 03:03:57'),(60,7,'Actualizó estado de denuncia','Denuncia #1 -> EN_REVISION','172.21.0.1','2026-08-18 03:03:57'),(61,7,'Actualizó estado de denuncia','Denuncia #1 -> RESUELTO','172.21.0.1','2026-08-18 03:03:57'),(66,5,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 03:10:07'),(67,5,'Envió un mensaje','Solicitud #6','172.21.0.1','2026-08-18 03:10:07'),(68,6,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 03:10:28'),(69,20,'Registro de usuario','Maria Quesada Fonseca se registró como adoptante.','172.21.0.1','2026-08-18 03:20:11'),(70,20,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 03:21:13'),(71,20,'Solicitó adopción','Mascota #13','172.21.0.1','2026-08-18 03:22:33'),(72,20,'Envió un mensaje','Solicitud #7','172.21.0.1','2026-08-18 03:23:18'),(74,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 03:32:01'),(75,5,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 03:41:24'),(76,5,'Envió un mensaje','Solicitud #7','172.21.0.1','2026-08-18 03:42:52'),(77,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 16:40:14'),(78,5,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 16:42:26'),(79,6,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 16:43:13'),(80,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 16:44:47'),(81,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 17:24:40'),(82,5,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 17:25:43'),(83,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 17:34:04'),(84,11,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 17:35:16'),(85,5,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 17:57:01'),(86,6,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 17:58:19'),(87,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 17:59:27'),(88,11,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 18:29:30'),(89,5,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 18:51:41'),(90,11,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 19:47:29'),(91,5,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 19:51:55'),(92,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 19:56:16'),(93,11,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 20:02:30'),(94,11,'Envió un mensaje','Solicitud #2','172.21.0.1','2026-08-18 20:03:25'),(95,22,'Registro de usuario','Jorge Castro se registró como adoptante.','172.21.0.1','2026-08-18 22:18:46'),(96,22,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 22:18:59'),(97,22,'Solicitó adopción','Mascota #8','172.21.0.1','2026-08-18 22:19:57'),(98,11,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 22:41:47'),(99,23,'Registro de usuario','Eduardo Solis Rojas se registró como adoptante.','172.21.0.1','2026-08-18 22:43:04'),(100,23,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 22:43:17'),(101,24,'Registro de usuario','Mariam Abarca se registró como adoptante.','172.21.0.1','2026-08-18 22:59:44'),(102,24,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 22:59:54'),(103,24,'Solicitó adopción','Mascota #21','172.21.0.1','2026-08-18 23:00:37'),(104,24,'Envió un mensaje','Solicitud #9','172.21.0.1','2026-08-18 23:01:11'),(105,6,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 23:01:49'),(106,6,'Envió un mensaje','Solicitud #2','172.21.0.1','2026-08-18 23:02:26'),(107,6,'Aprobó solicitud de adopción','Solicitud #2 — Luna','172.21.0.1','2026-08-18 23:02:47'),(108,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 23:03:24'),(109,24,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 23:06:32'),(110,24,'Solicitó adopción','Mascota #13','172.21.0.1','2026-08-18 23:06:42'),(111,5,'Inicio de sesión','REFUGIO','172.21.0.1','2026-08-18 23:07:59'),(112,5,'Aprobó solicitud de adopción','Solicitud #10 — Nala','172.21.0.1','2026-08-18 23:08:16'),(113,11,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 23:10:19'),(114,11,'Creó una denuncia','Mascota #19 — BIENESTAR','172.21.0.1','2026-08-18 23:12:56'),(115,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 23:13:35'),(116,7,'Actualizó estado de denuncia','Denuncia #2 -> EN_REVISION','172.21.0.1','2026-08-18 23:13:49'),(117,11,'Inicio de sesión','ADOPTANTE','172.21.0.1','2026-08-18 23:28:25'),(118,7,'Inicio de sesión','ADMIN_GENERAL','172.21.0.1','2026-08-18 23:32:29');
/*!40000 ALTER TABLE `bitacora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentos`
--

DROP TABLE IF EXISTS `documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documentos` (
  `id_documento` int NOT NULL AUTO_INCREMENT,
  `id_solicitud` int NOT NULL,
  `tipo_documento` enum('CEDULA','COMPROBANTE_DOMICILIO','OTRO') NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta_archivo` varchar(500) NOT NULL,
  `fecha_subida` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('PENDIENTE','VALIDADO','RECHAZADO') DEFAULT 'PENDIENTE',
  PRIMARY KEY (`id_documento`),
  KEY `fk_documento_solicitud` (`id_solicitud`),
  CONSTRAINT `fk_documento_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes` (`id_solicitud`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentos`
--

LOCK TABLES `documentos` WRITE;
/*!40000 ALTER TABLE `documentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entrevistas`
--

DROP TABLE IF EXISTS `entrevistas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entrevistas` (
  `id_entrevista` int NOT NULL AUTO_INCREMENT,
  `id_solicitud` int NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `modalidad` enum('PRESENCIAL','VIRTUAL') DEFAULT 'PRESENCIAL',
  `estado` enum('PROGRAMADA','REALIZADA','CANCELADA') DEFAULT 'PROGRAMADA',
  `observaciones` text,
  PRIMARY KEY (`id_entrevista`),
  KEY `fk_entrevista_solicitud` (`id_solicitud`),
  CONSTRAINT `fk_entrevista_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes` (`id_solicitud`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entrevistas`
--

LOCK TABLES `entrevistas` WRITE;
/*!40000 ALTER TABLE `entrevistas` DISABLE KEYS */;
/*!40000 ALTER TABLE `entrevistas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favoritos` (
  `id_favorito` int NOT NULL AUTO_INCREMENT,
  `id_adoptante` int NOT NULL,
  `id_mascota` int NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_favorito`),
  UNIQUE KEY `uq_favorito` (`id_adoptante`,`id_mascota`),
  KEY `fk_favorito_mascota` (`id_mascota`),
  CONSTRAINT `fk_favorito_adoptante` FOREIGN KEY (`id_adoptante`) REFERENCES `adoptantes` (`id_adoptante`),
  CONSTRAINT `fk_favorito_mascota` FOREIGN KEY (`id_mascota`) REFERENCES `mascotas` (`id_mascota`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favoritos`
--

LOCK TABLES `favoritos` WRITE;
/*!40000 ALTER TABLE `favoritos` DISABLE KEYS */;
INSERT INTO `favoritos` VALUES (1,6,9,'2026-08-18 01:25:14'),(4,17,21,'2026-08-18 22:44:40'),(5,17,17,'2026-08-18 22:46:50'),(6,18,21,'2026-08-18 23:00:34');
/*!40000 ALTER TABLE `favoritos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mascotas`
--

DROP TABLE IF EXISTS `mascotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mascotas` (
  `id_mascota` int NOT NULL AUTO_INCREMENT,
  `id_refugio` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `especie` varchar(50) NOT NULL,
  `raza` varchar(100) DEFAULT NULL,
  `edad` int DEFAULT NULL,
  `sexo` enum('MACHO','HEMBRA') DEFAULT NULL,
  `tamano` enum('PEQUENO','MEDIANO','GRANDE') DEFAULT NULL,
  `descripcion` text,
  `historia` text,
  `estado_salud` text,
  `vacunado` tinyint(1) DEFAULT '0',
  `esterilizado` tinyint(1) DEFAULT '0',
  `compatible_ninos` tinyint(1) DEFAULT '0',
  `compatible_animales` tinyint(1) DEFAULT '0',
  `nivel_energia` enum('BAJO','MEDIO','ALTO') DEFAULT NULL,
  `estado` enum('DISPONIBLE','EN_PROCESO','ADOPTADO','INACTIVO') DEFAULT 'DISPONIBLE',
  `foto` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mascota`),
  KEY `fk_mascota_refugio` (`id_refugio`),
  CONSTRAINT `fk_mascota_refugio` FOREIGN KEY (`id_refugio`) REFERENCES `refugios` (`id_refugio`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mascotas`
--

LOCK TABLES `mascotas` WRITE;
/*!40000 ALTER TABLE `mascotas` DISABLE KEYS */;
INSERT INTO `mascotas` VALUES (8,3,'Max','Perro','Labrador Retriever',2,'MACHO','GRANDE','Amigable, juguetón y muy cariñoso. Ideal para familias con niños.',NULL,NULL,1,0,1,1,'ALTO','DISPONIBLE','https://images.unsplash.com/photo-1552053831-71594a27632d?auto=format&fit=crop&w=500&q=80','2026-08-18 00:46:44'),(9,4,'Luna','Gato','Siamés',0,'HEMBRA','PEQUENO','Tímida al principio pero muy cariñosa. Esterilizada y vacunada.',NULL,NULL,1,1,1,0,'MEDIO','ADOPTADO','https://images.unsplash.com/photo-1574158622682-e40e69881006?auto=format&fit=crop&w=500&q=80','2026-08-18 00:46:44'),(10,3,'Rocky','Perro','Mestizo',1,'MACHO','MEDIANO','Energético y leal. Vacunado y desparasitado al día.',NULL,NULL,1,0,1,1,'ALTO','DISPONIBLE','https://images.unsplash.com/photo-1537151625747-768eb6cf92b2?auto=format&fit=crop&w=500&q=80','2026-08-18 00:46:44'),(11,4,'Mía','Gato','Persa',3,'HEMBRA','MEDIANO','Tranquila y elegante. Perfecta para hogares sin otros animales.',NULL,NULL,1,1,1,0,'BAJO','DISPONIBLE','https://images.unsplash.com/photo-1574144611937-0df059b5ef3e?auto=format&fit=crop&w=500&q=80','2026-08-18 00:46:44'),(12,3,'Toby','Perro','Beagle',3,'MACHO','MEDIANO','Curioso y muy sociable. Se lleva bien con otros perros.',NULL,NULL,1,1,1,1,'MEDIO','ADOPTADO','https://images.unsplash.com/photo-1587300003388-59208cc962cb?auto=format&fit=crop&w=500&q=80','2026-08-18 00:46:44'),(13,3,'Nala','Gato','Mestizo',0,'HEMBRA','PEQUENO','Juguetona y activa. Le encanta explorar y trepar.',NULL,NULL,1,0,1,1,'ALTO','ADOPTADO','https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&w=500&q=80','2026-08-18 00:46:44'),(14,3,'Bruno','Perro','Pastor Alemán',5,'MACHO','GRANDE','Inteligente y protector. Necesita espacio y ejercicio diario.',NULL,NULL,1,0,0,0,'ALTO','ADOPTADO','https://images.unsplash.com/photo-1583511655826-05700d52f4d9?auto=format&fit=crop&w=500&q=80','2026-08-18 00:46:44'),(17,3,'Dante','Perro','Mestizo',0,'MACHO','PEQUENO','Cachorro juguetón y muy cariñoso, le encanta explorar todo lo que lo rodea. Se lleva bien con otros perros y con niños.',NULL,NULL,1,0,1,1,'ALTO','DISPONIBLE','https://images.unsplash.com/photo-1560807707-8cc77767d783?auto=format&fit=crop&w=500&q=80','2026-08-18 04:25:49'),(18,4,'Marcelino','Perro','Mestizo',4,'MACHO','MEDIANO','Perro tranquilo y leal, ideal para una familia que busque un compañero fiel. Prefiere ser el único animal en casa.',NULL,NULL,1,1,1,0,'MEDIO','ADOPTADO','https://images.unsplash.com/photo-1615394992869-e76cb89217ea?auto=format&fit=crop&w=500&q=80','2026-08-18 04:25:49'),(19,3,'Roberto','Perro','Mestizo',0,'MACHO','PEQUENO','Cachorro curioso y lleno de energía, todavía está aprendiendo pero es muy sociable con personas y otras mascotas.',NULL,NULL,1,0,1,1,'ALTO','DISPONIBLE','https://images.unsplash.com/photo-1594653283108-953a4f93400e?auto=format&fit=crop&w=500&q=80','2026-08-18 04:25:49'),(20,4,'Iris','Gato','Mestiza',0,'HEMBRA','PEQUENO','Gatita pequeña y muy juguetona, le fascina trepar y perseguir cualquier cosa que se mueva.',NULL,NULL,1,0,1,1,'ALTO','DISPONIBLE','https://images.unsplash.com/photo-1637424864367-7ab8752c19c6?auto=format&fit=crop&w=500&q=80','2026-08-18 04:25:49'),(21,3,'Debora','Gato','Mestiza',5,'HEMBRA','MEDIANO','Gata adulta tranquila y cariñosa, disfruta de siestas largas y espacios silenciosos. Prefiere ser la única mascota.',NULL,NULL,1,1,0,1,'BAJO','ADOPTADO','https://images.unsplash.com/photo-1495360010541-f48722b34f7d?auto=format&fit=crop&w=500&q=80','2026-08-18 04:25:49'),(22,4,'Laila','Gato','Mestiza',3,'HEMBRA','PEQUENO','Gata independiente pero cariñosa a su manera, se adapta bien a hogares con niños.',NULL,NULL,1,1,1,0,'MEDIO','DISPONIBLE','https://images.unsplash.com/photo-1607237226678-81617187cf4c?auto=format&fit=crop&w=500&q=80','2026-08-18 04:25:49');
/*!40000 ALTER TABLE `mascotas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensajes`
--

DROP TABLE IF EXISTS `mensajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensajes` (
  `id_mensaje` int NOT NULL AUTO_INCREMENT,
  `id_remitente` int NOT NULL,
  `id_destinatario` int NOT NULL,
  `id_solicitud` int DEFAULT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `leido` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_mensaje`),
  KEY `fk_mensaje_remitente` (`id_remitente`),
  KEY `fk_mensaje_destinatario` (`id_destinatario`),
  KEY `fk_mensaje_solicitud` (`id_solicitud`),
  CONSTRAINT `fk_mensaje_destinatario` FOREIGN KEY (`id_destinatario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_mensaje_remitente` FOREIGN KEY (`id_remitente`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_mensaje_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes` (`id_solicitud`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensajes`
--

LOCK TABLES `mensajes` WRITE;
/*!40000 ALTER TABLE `mensajes` DISABLE KEYS */;
INSERT INTO `mensajes` VALUES (3,20,5,7,'Hola, me pueden enviar más información sobre ella estoy interesada, gracias','2026-08-18 03:23:18',1),(4,5,20,7,'Claro Nala es super cariñosa, y muy tranquila. Dime que mas quisieras saber sobre ella','2026-08-18 03:42:52',0),(5,11,6,2,'hola, Luna aun esta disponible?','2026-08-18 20:03:25',1),(6,24,5,9,'Hola esa gatita aun está disponible me interesa mas info.','2026-08-18 23:01:11',0),(7,6,11,2,'sí claro, dime si quieres más información aun sobre ella','2026-08-18 23:02:26',0);
/*!40000 ALTER TABLE `mensajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificaciones` (
  `id_notificacion` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `mensaje` text NOT NULL,
  `tipo` enum('SOLICITUD','SEGUIMIENTO','MENSAJE','SISTEMA') DEFAULT 'SISTEMA',
  `leida` tinyint(1) DEFAULT '0',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notificacion`),
  KEY `fk_notificacion_usuario` (`id_usuario`),
  CONSTRAINT `fk_notificacion_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones`
--

LOCK TABLES `notificaciones` WRITE;
/*!40000 ALTER TABLE `notificaciones` DISABLE KEYS */;
INSERT INTO `notificaciones` VALUES (4,7,'Nuevo adoptante registrado','Jenn Abarca Duran (jennesy@hotmail.com) se registró como adoptante.','SISTEMA',0,'2026-08-18 01:22:53'),(5,6,'Nueva solicitud de adopción','Jenn Abarca Duran solicitó adoptar a Luna.','SOLICITUD',0,'2026-08-18 01:25:09'),(6,5,'Nueva solicitud de adopción','Jenn Abarca Duran solicitó adoptar a Bruno.','SOLICITUD',0,'2026-08-18 01:27:11'),(7,11,'¡Tu solicitud fue aprobada!','Tu solicitud para adoptar a Bruno fue aprobada. ¡Felicidades!','SOLICITUD',0,'2026-08-18 01:32:13'),(11,7,'Nuevo adoptante registrado','Test3 Views (test.views@example.com) se registró como adoptante.','SISTEMA',0,'2026-08-18 01:59:26'),(12,7,'Nuevo adoptante registrado','Final Check (final.check@example.com) se registró como adoptante.','SISTEMA',0,'2026-08-18 02:06:25'),(13,6,'Nueva solicitud de adopción','Final Check solicitó adoptar a Luna.','SOLICITUD',0,'2026-08-18 02:06:26'),(14,7,'Nuevo adoptante registrado','Recu Test (recu.test@example.com) se registró como adoptante.','SISTEMA',1,'2026-08-18 02:30:59'),(15,7,'Nuevo adoptante registrado','Match Test (match.test@example.com) se registró como adoptante.','SISTEMA',0,'2026-08-18 02:47:37'),(16,7,'Nuevo refugio pendiente de aprobación','Huellitas Felices (huellitas@example.com) se registró y espera revisión.','SISTEMA',0,'2026-08-18 02:56:32'),(18,7,'Nuevo adoptante registrado','Denuncia Test (denuncia.test@example.com) se registró como adoptante.','SISTEMA',0,'2026-08-18 03:03:56'),(19,7,'Nueva denuncia recibida','Denuncia Test reportó un caso de tipo BIENESTAR.','SISTEMA',0,'2026-08-18 03:03:56'),(24,7,'Nuevo adoptante registrado','Maria Quesada Fonseca (mariquesada1@gmail.com) se registró como adoptante.','SISTEMA',0,'2026-08-18 03:20:11'),(25,5,'Nueva solicitud de adopción','Maria Quesada Fonseca solicitó adoptar a Nala.','SOLICITUD',0,'2026-08-18 03:22:33'),(26,5,'Nuevo mensaje','Maria Quesada Fonseca te escribió sobre Nala.','MENSAJE',0,'2026-08-18 03:23:18'),(27,7,'Nuevo adoptante registrado','Toast Test (toast.test@example.com) se registró como adoptante.','SISTEMA',0,'2026-08-18 03:30:58'),(28,20,'Nuevo mensaje','Refugio San Roque te escribió sobre Nala.','MENSAJE',0,'2026-08-18 03:42:52'),(29,6,'Nuevo mensaje','Jenn Abarca Duran te escribió sobre Luna.','MENSAJE',0,'2026-08-18 20:03:25'),(30,7,'Nuevo adoptante registrado','Jorge Castro (castrojorge1@gmail.com) se registró como adoptante.','SISTEMA',1,'2026-08-18 22:18:46'),(31,5,'Nueva solicitud de adopción','Jorge Castro solicitó adoptar a Max.','SOLICITUD',0,'2026-08-18 22:19:57'),(32,7,'Nuevo adoptante registrado','Eduardo Solis Rojas (eduardorojas@gmail.com) se registró como adoptante.','SISTEMA',1,'2026-08-18 22:43:04'),(33,7,'Nuevo adoptante registrado','Mariam Abarca (mariamdu@gmail.com) se registró como adoptante.','SISTEMA',1,'2026-08-18 22:59:44'),(34,5,'Nueva solicitud de adopción','Mariam Abarca solicitó adoptar a Debora.','SOLICITUD',0,'2026-08-18 23:00:37'),(35,5,'Nuevo mensaje','Mariam Abarca te escribió sobre Debora.','MENSAJE',0,'2026-08-18 23:01:11'),(36,11,'Nuevo mensaje','Patitas Felices te escribió sobre Luna.','MENSAJE',0,'2026-08-18 23:02:26'),(37,11,'¡Tu solicitud fue aprobada!','Tu solicitud para adoptar a Luna fue aprobada. ¡Felicidades!','SOLICITUD',0,'2026-08-18 23:02:47'),(38,5,'Nueva solicitud de adopción','Mariam Abarca solicitó adoptar a Nala.','SOLICITUD',0,'2026-08-18 23:06:42'),(39,24,'¡Tu solicitud fue aprobada!','Tu solicitud para adoptar a Nala fue aprobada. ¡Felicidades!','SOLICITUD',0,'2026-08-18 23:08:16'),(40,7,'Nueva denuncia recibida','Jenn Abarca Duran reportó un caso de tipo BIENESTAR.','SISTEMA',0,'2026-08-18 23:12:56');
/*!40000 ALTER TABLE `notificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recuperaciones_password`
--

DROP TABLE IF EXISTS `recuperaciones_password`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recuperaciones_password` (
  `id_recuperacion` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_expiracion` datetime NOT NULL,
  `usado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_recuperacion`),
  KEY `fk_recuperacion_usuario` (`id_usuario`),
  CONSTRAINT `fk_recuperacion_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recuperaciones_password`
--

LOCK TABLES `recuperaciones_password` WRITE;
/*!40000 ALTER TABLE `recuperaciones_password` DISABLE KEYS */;
INSERT INTO `recuperaciones_password` VALUES (3,1,'89fca8c4d7d7338d09247698c6a9a96b47725c15bfcb6c3a425eb4e4fa54cfcb','2026-08-18 02:37:56','2026-08-18 03:37:56',1);
/*!40000 ALTER TABLE `recuperaciones_password` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refugios`
--

DROP TABLE IF EXISTS `refugios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refugios` (
  `id_refugio` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `nombre_refugio` varchar(150) NOT NULL,
  `cedula_juridica` varchar(30) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `descripcion` text,
  `estado` enum('PENDIENTE','APROBADO','RECHAZADO') DEFAULT 'PENDIENTE',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_refugio`),
  KEY `fk_refugio_usuario` (`id_usuario`),
  CONSTRAINT `fk_refugio_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refugios`
--

LOCK TABLES `refugios` WRITE;
/*!40000 ALTER TABLE `refugios` DISABLE KEYS */;
INSERT INTO `refugios` VALUES (3,5,'Refugio San Roque','3-101-111111','8888-1001','Heredia, Costa Rica','Refugio dedicado al rescate y adopción responsable de perros y gatos.','APROBADO','2026-08-18 00:46:44'),(4,6,'Patitas Felices','3-101-222222','8888-1002','San José, Costa Rica','Organización sin fines de lucro enfocada en el bienestar animal.','APROBADO','2026-08-18 00:46:44'),(6,31,'Huellitas del Valle',NULL,'8877-1122','Cartago, Costa Rica','Refugio dedicado al rescate de perros y gatos en el valle central.','APROBADO','2026-08-18 23:37:14'),(7,32,'Adopta CR',NULL,'8877-3344','Alajuela, Costa Rica','Organización nueva enfocada en adopciones responsables.','PENDIENTE','2026-08-18 23:37:14');
/*!40000 ALTER TABLE `refugios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reportes`
--

DROP TABLE IF EXISTS `reportes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reportes` (
  `id_reporte` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_mascota` int DEFAULT NULL,
  `tipo` enum('MALTRATO','BIENESTAR','OTRO') NOT NULL,
  `descripcion` text NOT NULL,
  `estado` enum('PENDIENTE','EN_REVISION','RESUELTO') DEFAULT 'PENDIENTE',
  `fecha_reporte` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_reporte`),
  KEY `fk_reporte_usuario` (`id_usuario`),
  KEY `fk_reporte_mascota` (`id_mascota`),
  CONSTRAINT `fk_reporte_mascota` FOREIGN KEY (`id_mascota`) REFERENCES `mascotas` (`id_mascota`),
  CONSTRAINT `fk_reporte_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reportes`
--

LOCK TABLES `reportes` WRITE;
/*!40000 ALTER TABLE `reportes` DISABLE KEYS */;
INSERT INTO `reportes` VALUES (2,11,19,'BIENESTAR','buenas dicen que este perrito es de una raza muy brava. Deberían tener cuidado en el refugio por los otros perros y animales que tengan','EN_REVISION','2026-08-18 23:12:56'),(3,11,8,'BIENESTAR','En las fotos se ve con el pelaje algo descuidado, sería bueno confirmar que está recibiendo los cuidados adecuados.','PENDIENTE','2026-08-18 23:37:14'),(4,20,10,'OTRO','La ficha no indicaba la fecha exacta de vacunación, ya se aclaró directamente con el refugio.','RESUELTO','2026-08-08 23:37:14'),(5,22,17,'MALTRATO','Un vecino comentó que el perro pasa mucho tiempo solo sin agua. Podrían verificar con el refugio.','EN_REVISION','2026-08-15 23:37:14'),(6,24,20,'BIENESTAR','Quisiera confirmar si la gata ya fue esterilizada antes de continuar con el proceso de adopción.','PENDIENTE','2026-08-18 23:37:14');
/*!40000 ALTER TABLE `reportes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id_rol`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'ADMIN_GENERAL','Administrador general de PawsMatch'),(2,'REFUGIO','Usuario encargado de administrar un refugio'),(3,'ADOPTANTE','Usuario que busca adoptar una mascota');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seguimientos`
--

DROP TABLE IF EXISTS `seguimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seguimientos` (
  `id_seguimiento` int NOT NULL AUTO_INCREMENT,
  `id_adopcion` int NOT NULL,
  `fecha_programada` date NOT NULL,
  `fecha_realizada` datetime DEFAULT NULL,
  `tipo` enum('SEMANA','MES','TRES_MESES','OTRO') NOT NULL,
  `estado` enum('PENDIENTE','COMPLETADO','VENCIDO') DEFAULT 'PENDIENTE',
  `estado_salud` varchar(150) DEFAULT NULL,
  `adaptacion` text,
  `observaciones` text,
  PRIMARY KEY (`id_seguimiento`),
  KEY `fk_seguimiento_adopcion` (`id_adopcion`),
  CONSTRAINT `fk_seguimiento_adopcion` FOREIGN KEY (`id_adopcion`) REFERENCES `adopciones` (`id_adopcion`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seguimientos`
--

LOCK TABLES `seguimientos` WRITE;
/*!40000 ALTER TABLE `seguimientos` DISABLE KEYS */;
INSERT INTO `seguimientos` VALUES (4,2,'2026-08-25',NULL,'SEMANA','PENDIENTE',NULL,NULL,NULL),(5,2,'2026-09-17',NULL,'MES','PENDIENTE',NULL,NULL,NULL),(6,2,'2026-11-16',NULL,'TRES_MESES','PENDIENTE',NULL,NULL,NULL),(10,4,'2026-08-25',NULL,'SEMANA','PENDIENTE',NULL,NULL,NULL),(11,4,'2026-09-17',NULL,'MES','PENDIENTE',NULL,NULL,NULL),(12,4,'2026-11-16',NULL,'TRES_MESES','PENDIENTE',NULL,NULL,NULL),(13,5,'2026-08-25',NULL,'SEMANA','PENDIENTE',NULL,NULL,NULL),(14,5,'2026-09-17',NULL,'MES','PENDIENTE',NULL,NULL,NULL),(15,5,'2026-11-16',NULL,'TRES_MESES','PENDIENTE',NULL,NULL,NULL),(16,6,'2026-03-22','2026-03-22 15:00:00','SEMANA','COMPLETADO','Excelente','Se adaptó muy bien desde el primer día.',NULL),(17,6,'2026-04-14','2026-04-15 11:00:00','MES','COMPLETADO','Excelente','Ya tiene su rutina y se lleva bien con la familia.',NULL),(18,6,'2026-06-13','2026-06-14 16:00:00','TRES_MESES','COMPLETADO','Excelente','Completamente integrado al hogar.',NULL),(19,7,'2026-05-27','2026-05-27 14:00:00','SEMANA','COMPLETADO','Bueno','Un poco tímido al inicio, ya está más confiado.',NULL),(20,7,'2026-06-19','2026-06-20 10:00:00','MES','COMPLETADO','Excelente','Se adaptó completamente a la familia.',NULL),(21,7,'2026-08-18',NULL,'TRES_MESES','PENDIENTE',NULL,NULL,NULL),(22,8,'2026-07-17','2026-07-17 12:00:00','SEMANA','COMPLETADO','Excelente','Muy tranquila, se acomodó rápido a la casa.',NULL),(23,8,'2026-08-09','2026-08-09 13:00:00','MES','COMPLETADO','Excelente','Ya se lleva bien con el resto de la familia.',NULL),(24,8,'2026-10-08',NULL,'TRES_MESES','PENDIENTE',NULL,NULL,NULL);
/*!40000 ALTER TABLE `seguimientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitudes`
--

DROP TABLE IF EXISTS `solicitudes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes` (
  `id_solicitud` int NOT NULL AUTO_INCREMENT,
  `id_adoptante` int NOT NULL,
  `id_mascota` int NOT NULL,
  `fecha_solicitud` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('PENDIENTE','EN_REVISION','APROBADA','RECHAZADA','CANCELADA') DEFAULT 'PENDIENTE',
  `motivo` text,
  `observaciones_refugio` text,
  `fecha_respuesta` datetime DEFAULT NULL,
  PRIMARY KEY (`id_solicitud`),
  KEY `fk_solicitud_adoptante` (`id_adoptante`),
  KEY `fk_solicitud_mascota` (`id_mascota`),
  CONSTRAINT `fk_solicitud_adoptante` FOREIGN KEY (`id_adoptante`) REFERENCES `adoptantes` (`id_adoptante`),
  CONSTRAINT `fk_solicitud_mascota` FOREIGN KEY (`id_mascota`) REFERENCES `mascotas` (`id_mascota`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitudes`
--

LOCK TABLES `solicitudes` WRITE;
/*!40000 ALTER TABLE `solicitudes` DISABLE KEYS */;
INSERT INTO `solicitudes` VALUES (2,6,9,'2026-08-18 01:25:09','APROBADA','',NULL,'2026-08-18 23:02:47'),(3,6,14,'2026-08-18 01:27:11','APROBADA','',NULL,'2026-08-18 01:32:13'),(7,14,13,'2026-08-18 03:22:33','RECHAZADA','','La mascota ya fue adoptada por otra persona.','2026-08-18 23:08:16'),(8,16,8,'2026-08-18 22:19:57','PENDIENTE','',NULL,NULL),(9,18,21,'2026-08-18 23:00:37','PENDIENTE','',NULL,NULL),(10,18,13,'2026-08-18 23:06:42','APROBADA','',NULL,'2026-08-18 23:08:16'),(11,19,12,'2026-03-05 10:00:00','APROBADA','Busco un compañero activo para hacer ejercicio.',NULL,'2026-03-10 09:00:00'),(12,20,18,'2026-05-10 10:00:00','APROBADA','Tengo experiencia con perros medianos y espacio en casa.',NULL,'2026-05-15 09:00:00'),(13,16,21,'2026-06-30 10:00:00','APROBADA','Siempre he querido una gata tranquila para compañía.',NULL,'2026-07-05 09:00:00'),(14,21,8,'2026-08-18 23:38:07','PENDIENTE','Me gustaría darle un hogar activo, tengo patio grande.',NULL,NULL),(15,22,20,'2026-08-18 23:38:07','PENDIENTE','Busco una gata tranquila para compañía en el apartamento.',NULL,NULL),(16,23,19,'2026-08-18 23:38:07','EN_REVISION','Tengo experiencia con perros medianos y ya coordiné una visita.',NULL,NULL),(17,24,22,'2026-08-14 23:38:07','RECHAZADA','Quisiera adoptarla para mi apartamento.','Otra familia con más experiencia con gatos fue seleccionada primero.','2026-08-17 23:38:07');
/*!40000 ALTER TABLE `solicitudes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `id_rol` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo` (`correo`),
  KEY `fk_usuario_rol` (`id_rol`),
  CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,3,'Julieth','Duran Solis','julieth@gmail.com','$2y$10$212MuQgXbdDwGyyiTf4NReHYl3gLiISt5OnlEcf3l2TI5LqZ..b62','88201506','ACTIVO','2026-08-17 21:25:04'),(2,3,'Manuel','Gómez Fallas','manug@gmail.com','$2y$10$g7kNmwc.Q.PQ7VWIyFrw8.rlFKUp9ivostJjnKTndLiTjeJgZa6Y6','85149021','ACTIVO','2026-08-17 22:43:05'),(5,2,'Refugio','San Roque','contacto@sanroque.org','$2y$10$P3BzY8nCNKrFrVbDPEYkJ.spZNcd/Vac7RTkHCGBIOCq5qvfdKh3u','8888-1001','ACTIVO','2026-08-18 00:46:44'),(6,2,'Patitas','Felices','info@patitasfelices.org','$2y$10$P3BzY8nCNKrFrVbDPEYkJ.spZNcd/Vac7RTkHCGBIOCq5qvfdKh3u','8888-1002','ACTIVO','2026-08-18 00:46:44'),(7,1,'Administrador','General','admin@pawsmatch.com','$2y$10$P3BzY8nCNKrFrVbDPEYkJ.spZNcd/Vac7RTkHCGBIOCq5qvfdKh3u','8888-0000','ACTIVO','2026-08-18 01:06:57'),(11,3,'Jenn','Abarca Duran','jennesy@hotmail.com','$2y$10$J2fPGaCnq9CfjkPouTiYR.tNqIHDXwIEjCKF4XkWZ3HNeavC.LXMa','88452126','ACTIVO','2026-08-18 01:22:53'),(20,3,'Maria','Quesada Fonseca','mariquesada1@gmail.com','$2y$10$weYMdWMQwMEkaxIVCn6Z0ew6fZr0JU0iAQxwxgr0yHKsGeAOlXtx2','85423365','ACTIVO','2026-08-18 03:20:11'),(22,3,'Jorge','Castro','castrojorge1@gmail.com','$2y$10$hQT20WAT5eDubpG2FmF21.qA/zexWGUHqAFh2UfSQNRJWoy2MwqPq','85263545','ACTIVO','2026-08-18 22:18:46'),(23,3,'Eduardo','Solis Rojas','eduardorojas@gmail.com','$2y$10$H7JBbo7zzy8uBXSOg9jpJOZ5T6PF61yHgTK97S.qpgk5PfeOaJJ2u','87456633','ACTIVO','2026-08-18 22:43:04'),(24,3,'Mariam','Abarca','mariamdu@gmail.com','$2y$10$gAMMmFgv3z5t/Ytjl3gr.e6TbPMfQPY4ksbrorrAGG.X86GBrhJZi','82541212','ACTIVO','2026-08-18 22:59:44'),(25,3,'Andrés','Vargas Solano','andres.vargas@gmail.com','$2y$10$X.b8M3EgFwepPVJrHInhZOFd3A7JRgtkq30iauvShastNSZkINVOy','8811-2233','ACTIVO','2026-08-18 23:36:47'),(26,3,'Sofía','Herrera Mata','sofia.herrera@gmail.com','$2y$10$X.b8M3EgFwepPVJrHInhZOFd3A7JRgtkq30iauvShastNSZkINVOy','8822-3344','ACTIVO','2026-08-18 23:36:47'),(27,3,'Diego','Chacón Ureña','diego.chacon@gmail.com','$2y$10$X.b8M3EgFwepPVJrHInhZOFd3A7JRgtkq30iauvShastNSZkINVOy','8833-4455','ACTIVO','2026-08-18 23:36:47'),(28,3,'Valentina','Rojas Castillo','valentina.rojas@gmail.com','$2y$10$X.b8M3EgFwepPVJrHInhZOFd3A7JRgtkq30iauvShastNSZkINVOy','8844-5566','ACTIVO','2026-08-18 23:36:47'),(29,3,'Kevin','Salas Brenes','kevin.salas@gmail.com','$2y$10$X.b8M3EgFwepPVJrHInhZOFd3A7JRgtkq30iauvShastNSZkINVOy','8855-6677','ACTIVO','2026-08-18 23:36:47'),(30,3,'Camila','Alvarado Pérez','camila.alvarado@gmail.com','$2y$10$X.b8M3EgFwepPVJrHInhZOFd3A7JRgtkq30iauvShastNSZkINVOy','8866-7788','ACTIVO','2026-08-18 23:36:47'),(31,2,'Huellitas','del Valle','huellitasdelvalle@gmail.com','$2y$10$X.b8M3EgFwepPVJrHInhZOFd3A7JRgtkq30iauvShastNSZkINVOy','8877-1122','ACTIVO','2026-08-18 23:36:47'),(32,2,'Adopta','CR','contacto@adoptacr.org','$2y$10$X.b8M3EgFwepPVJrHInhZOFd3A7JRgtkq30iauvShastNSZkINVOy','8877-3344','ACTIVO','2026-08-18 23:36:47');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'pawsmatch'
--

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-18 23:38:42
