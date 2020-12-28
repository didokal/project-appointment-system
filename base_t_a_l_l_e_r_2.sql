-- MySQL dump 10.13  Distrib 8.0.20, for Win64 (x86_64)
--
-- Host: localhost    Database: base_t_a_l_l_e_r_2
-- ------------------------------------------------------
-- Server version	8.0.20

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `idCategory` int NOT NULL AUTO_INCREMENT,
  `nameCat` varchar(30) NOT NULL,
  PRIMARY KEY (`idCategory`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (48,'Diagnostico'),(49,'Mantenimiento'),(50,'Reparaciones'),(56,'asd');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `citas`
--

DROP TABLE IF EXISTS `citas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `citas` (
  `idCita` int NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` time(6) NOT NULL,
  `hora_fin` time(6) NOT NULL,
  `nombreEmpleado` varchar(40) NOT NULL,
  `nombreServicio` varchar(40) NOT NULL,
  `idCliente` int NOT NULL,
  PRIMARY KEY (`idCita`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `citas`
--

LOCK TABLES `citas` WRITE;
/*!40000 ALTER TABLE `citas` DISABLE KEYS */;
INSERT INTO `citas` VALUES (66,'2020-01-15','09:00:00.000000','10:15:00.000000','Dian','Diagnostico de ECU',54),(67,'2020-01-15','11:15:00.000000','12:30:00.000000','Dian','Diagnostico de ECU',54),(68,'2020-03-10','08:00:00.000000','08:45:00.000000','ivan','Cambio de aceite',55),(69,'2020-06-24','08:15:00.000000','09:30:00.000000','Dian','Diagnostico de ECU',56),(71,'2020-06-18','10:00:00.000000','10:30:00.000000','ivan','Cambio de filto de aceite',58),(72,'2020-06-17','08:15:00.000000','09:30:00.000000','Dian','Diagnostico de ECU',59),(73,'2020-06-24','11:15:00.000000','12:00:00.000000','ivan','Cambio de aceite',60),(75,'2020-06-24','12:00:00.000000','13:15:00.000000','ivan','Diagnostico de ECU',62),(76,'2020-06-24','13:45:00.000000','15:00:00.000000','ivan','Diagnostico de ECU',63),(77,'2020-06-24','16:45:00.000000','18:00:00.000000','ivan','Diagnostico de ECU',54),(78,'2020-06-24','08:15:00.000000','08:45:00.000000','pechio','Cambio de filto de aceite',54),(79,'2020-06-24','11:45:00.000000','12:15:00.000000','pechio','Cambio de filto de aceite',54),(80,'2020-06-24','08:30:00.000000','09:45:00.000000','Велизар','Diagnostico de ECU',54),(81,'2020-06-24','11:00:00.000000','12:15:00.000000','asd2','Diagnostico de ECU',54),(82,'2020-06-24','09:30:00.000000','10:45:00.000000','asd2','Diagnostico de ECU',54),(83,'2020-06-24','08:00:00.000000','08:30:00.000000','asd2','Cambio de filto de aceite',64),(84,'2020-06-24','11:00:00.000000','12:15:00.000000','Dian','Diagnostico de ECU',65),(85,'2020-06-24','16:15:00.000000','17:30:00.000000','Dian','Diagnostico de ECU',65),(86,'2020-06-24','14:30:00.000000','15:45:00.000000','Dian','Diagnostico de ECU',65),(87,'2020-06-24','13:00:00.000000','14:15:00.000000','Dian','Diagnostico de ECU',65),(88,'2020-06-24','10:00:00.000000','10:30:00.000000','Dian','Cambio de filto de aceite',54),(90,'2020-06-24','11:00:00.000000','12:15:00.000000','Велизар','Diagnostico de ECU',54),(91,'2020-06-24','13:00:00.000000','14:15:00.000000','Велизар','Diagnostico de ECU',54),(92,'2020-06-24','17:00:00.000000','18:15:00.000000','Велизар','Diagnostico de ECU',66),(93,'2020-09-28','08:00:00.000000','09:15:00.000000','Dian','Diagnostico de ECU',54),(94,'2020-06-30','08:00:00.000000','09:15:00.000000','Dian','Diagnostico de ECU',67),(101,'2020-09-29','09:00:00.000000','09:45:00.000000','ivan','Cambio de aceite',64),(102,'2020-09-30','08:00:00.000000','09:15:00.000000','ivan','Diagnostico de ECU',64),(105,'2020-09-30','08:00:00.000000','09:15:00.000000','Dian','Diagnostico de ECU',54),(106,'2020-10-09','08:30:00.000000','09:45:00.000000','Dian','Diagnostico de ECU',79),(107,'2020-10-13','09:15:00.000000','10:30:00.000000','Dian','Diagnostico de ECU',80),(108,'2020-10-20','08:15:00.000000','09:30:00.000000','Dian','Diagnostico de ECU',81),(109,'2020-10-16','08:45:00.000000','10:00:00.000000','Dian','Diagnostico de ECU',82),(110,'2020-10-15','08:00:00.000000','08:45:00.000000','Dian','Cambio de aceite',83),(111,'2020-10-16','10:15:00.000000','11:30:00.000000','Dian','Diagnostico de ECU',84),(112,'2020-10-17','09:00:00.000000','09:45:00.000000','Dian','Cambio de aceite',85),(113,'2020-10-14','08:15:00.000000','09:30:00.000000','Dian','Diagnostico de ECU',86),(114,'2020-10-14','10:15:00.000000','11:30:00.000000','Dian','Diagnostico de ECU',87);
/*!40000 ALTER TABLE `citas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `idCliente` int NOT NULL AUTO_INCREMENT,
  `nombreCliente` varchar(40) NOT NULL,
  `telefonoCliente` bigint NOT NULL,
  `correoCliente` varchar(40) NOT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idCliente`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (54,'cliente',3,'cliente@a.a','cliente'),(55,'cliente',612,'cliente2@a.a','cliente2'),(59,'pechooo',1222333111,'pechooo@a.a',NULL),(63,'nose',1112223311,'nose@a.a',NULL),(64,'cliente',634294193,'cliente@a.a',NULL),(65,'dian',12311111,'asd@a.aaaa',NULL),(66,'dido',2,'',NULL),(67,'асд',897232433,'0@a.a',NULL),(68,'асд',1,'didokal@gmail.com','асд'),(74,'asdasd',1231232,'asdasd@a.aaaaaa',NULL),(75,'wwwwwwwww',123123123,'',NULL),(76,'weqwewww',233332323,'didokal@gmail.c222om',NULL),(77,'сссссссс',23123123,'',NULL),(79,'Dian',123,'didokal@gmail.com',NULL),(80,'NDian',1234,'didohkal@gmail.com',NULL),(81,'асд',333,'асд@асд',NULL),(82,'Dian',666333,'didjhokal@gmail.com',NULL),(83,'Gdhshsjsh',526161617,'vshshhs@hshs.hshs',NULL),(84,'Hehdhd',727262,'heheje@jdj.hdjd',NULL),(85,'Didokabzamalov',34634000,'didokabzamalo@abv.bg',NULL),(86,'DianIvanov',349999,'didokahfjfjl@gmail.com',NULL),(87,'Sonya',345555,'vdhdh@a.a',NULL);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleados` (
  `idEmpleado` int NOT NULL AUTO_INCREMENT,
  `nombreEmpleado` varchar(40) NOT NULL,
  `telefonoEmpleado` char(15) NOT NULL,
  `emailEmpleado` varchar(30) NOT NULL,
  `contrasena` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idEmpleado`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleados`
--

LOCK TABLES `empleados` WRITE;
/*!40000 ALTER TABLE `empleados` DISABLE KEYS */;
INSERT INTO `empleados` VALUES (30,'Dian','1','dian@a.a','Dian'),(31,'ivan','2','ivan@a.a','ivan'),(32,'pechio','0123','pecho@ab.co','pechio'),(33,'Велизар','0876363610','velizar@a.a','velizar');
/*!40000 ALTER TABLE `empleados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hojas_de_trabajo`
--

DROP TABLE IF EXISTS `hojas_de_trabajo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hojas_de_trabajo` (
  `idHojaTrabajo` int NOT NULL AUTO_INCREMENT,
  `fechaEntrada` date NOT NULL,
  `fechaPrevistaEntrega` date NOT NULL,
  `nombres` varchar(60) NOT NULL,
  `cif_Nif` varchar(15) NOT NULL,
  `empresa` varchar(50) NOT NULL,
  `telefono` int NOT NULL,
  `movil` int NOT NULL,
  `direccion` varchar(40) NOT NULL,
  `cp` int NOT NULL,
  `poblacion` varchar(10) NOT NULL,
  `marcaModelo` varchar(30) NOT NULL,
  `matricula` varchar(20) NOT NULL,
  `km` float NOT NULL,
  `vin` varchar(17) NOT NULL,
  `combustible` varchar(5) NOT NULL,
  `trabajosRealizar` varchar(100) NOT NULL,
  `notaTaller` varchar(100) NOT NULL,
  `totalSinIva` float NOT NULL,
  `iva` float NOT NULL,
  `totalFactura` float NOT NULL,
  `idCita` int NOT NULL,
  PRIMARY KEY (`idHojaTrabajo`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hojas_de_trabajo`
--

LOCK TABLES `hojas_de_trabajo` WRITE;
/*!40000 ALTER TABLE `hojas_de_trabajo` DISABLE KEYS */;
INSERT INTO `hojas_de_trabajo` VALUES (10,'0000-00-00','0000-00-00','диан','54645645664','',0,8945646,'',4000,'пловдив','BMW X6','3091fbp',1231,'zzzz213123222','3/4','Cambio de aceite<br>Cambio de filtros (aceite, aire, combustible, habitaculo)<br>','',20,4,24,68),(11,'2020-06-07','0000-00-00','асдд','','',0,0,'',0,'','','112',0,'','','s<br>2<br>3<br>4<br>5<br>','',20,0,20,66),(15,'0000-00-00','0000-00-00','','','',0,0,'',0,'','','',0,'zzzz213123222','','cambio pastillas<br>cambio aceite<br>nada<br>','',25,0,25,69),(28,'0000-00-00','0000-00-00','','','',0,0,'',0,'','','',15.001,'','','','hola',75.3,15.06,90.36,72),(30,'0000-00-00','0000-00-00','','','',0,0,'',0,'','','3090aaa',0,'','','','',0,0,0,67),(31,'0000-00-00','0000-00-00','','','',0,0,'',0,'','','',1234,'','','','',198,39.6,237.6,74),(32,'2020-06-17','2020-06-26','','','',0,0,'',0,'','','',2,'VVV111','','','',11411,2282.2,13693.2,73),(33,'2020-06-16','2020-06-23','','','',0,0,'',0,'','','',1,'','','','',0,0,0,75),(34,'2020-09-28','2020-09-30','','','',0,0,'',0,'','','',0,'zzzz213123222','','','',60.6,0,60.6,100),(35,'0000-00-00','0000-00-00','','','',0,0,'',0,'','','',0,'','','','',32.5,6.5,39,92);
/*!40000 ALTER TABLE `hojas_de_trabajo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hojas_de_trabajo_servicio_producto`
--

DROP TABLE IF EXISTS `hojas_de_trabajo_servicio_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hojas_de_trabajo_servicio_producto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_hoja_trabajo` int NOT NULL,
  `titulo` varchar(500) NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unidad` float NOT NULL,
  `cantidad_total` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1364 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hojas_de_trabajo_servicio_producto`
--

LOCK TABLES `hojas_de_trabajo_servicio_producto` WRITE;
/*!40000 ALTER TABLE `hojas_de_trabajo_servicio_producto` DISABLE KEYS */;
INSERT INTO `hojas_de_trabajo_servicio_producto` VALUES (262,70,'asd',2,4,5),(1276,72,'uno',1,10,10),(1309,74,'da',1,10,10),(1310,74,'da74',2,20,40),(1311,74,'tres74',74,2,148),(1317,73,'uno73',1,10,10),(1318,73,'dos73',2,200,400),(1319,73,'tres73',3,3000,9000),(1320,73,'cuatro73',5,400,2000),(1321,73,'cinco73',1,1,1),(1346,100,'www',15,4,60.6),(1351,66,'uno',2,4,5),(1352,66,'doss',2,4,5),(1353,66,'tressss',2,4,5),(1354,66,'a',2,4,5),(1355,69,'cambio pastillas',2,4,9),(1356,69,'cambio aceite',2,4,8),(1357,69,'nada',2,4,8),(1360,68,'Cambio de aceite',2,5,10),(1361,68,'Cambio de filtros (aceite, aire, combustible, habitaculo)',2,5,10),(1362,92,'asd',1,12.5,12.5),(1363,92,'asd2',2,10,20);
/*!40000 ALTER TABLE `hojas_de_trabajo_servicio_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horario_semanal`
--

DROP TABLE IF EXISTS `horario_semanal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `horario_semanal` (
  `idHorarioSem` int NOT NULL AUTO_INCREMENT,
  `horaStart` time(6) NOT NULL,
  `horaFin` time(6) NOT NULL,
  `diaSemana` varchar(15) NOT NULL,
  `idEmple` int NOT NULL,
  PRIMARY KEY (`idHorarioSem`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horario_semanal`
--

LOCK TABLES `horario_semanal` WRITE;
/*!40000 ALTER TABLE `horario_semanal` DISABLE KEYS */;
INSERT INTO `horario_semanal` VALUES (22,'08:00:00.000000','22:00:00.000000','Понеделник',30),(23,'08:00:00.000000','22:00:00.000000','Вторник',30),(24,'08:00:00.000000','22:00:00.000000','Сряда',30),(25,'08:00:00.000000','22:00:00.000000','Четвъртък',30),(26,'08:00:00.000000','22:00:00.000000','Петък',30),(27,'08:00:00.000000','22:00:00.000000','Събота',30),(28,'08:00:00.000000','22:00:00.000000','Неделя',30),(29,'08:00:00.000000','22:00:00.000000','Понеделник',31),(30,'08:00:00.000000','22:00:00.000000','Вторник',31),(31,'08:00:00.000000','22:00:00.000000','Сряда',31),(32,'08:00:00.000000','22:00:00.000000','Четвъртък',31),(33,'08:00:00.000000','22:00:00.000000','Петък',31),(34,'08:00:00.000000','22:00:00.000000','Събота',31),(35,'08:00:00.000000','22:00:00.000000','Неделя',31),(36,'08:00:00.000000','22:00:00.000000','Понеделник',32),(37,'08:00:00.000000','22:00:00.000000','Вторник',32),(38,'08:00:00.000000','22:00:00.000000','Сряда',32),(39,'08:00:00.000000','22:00:00.000000','Четвъртък',32),(40,'08:00:00.000000','22:00:00.000000','Петък',32),(41,'08:00:00.000000','22:00:00.000000','Събота',32),(42,'08:00:00.000000','22:00:00.000000','Неделя',32),(43,'08:00:00.000000','22:00:00.000000','Понеделник',33),(44,'08:00:00.000000','22:00:00.000000','Вторник',33),(45,'08:00:00.000000','22:00:00.000000','Сряда',33),(46,'08:00:00.000000','22:00:00.000000','Четвъртък',33),(47,'08:00:00.000000','22:00:00.000000','Петък',33),(48,'08:00:00.000000','22:00:00.000000','Събота',33),(49,'08:00:00.000000','22:00:00.000000','Неделя',33),(50,'08:00:00.000000','22:00:00.000000','Понеделник',34),(51,'08:00:00.000000','22:00:00.000000','Вторник',34),(52,'08:00:00.000000','22:00:00.000000','Сряда',34),(53,'08:00:00.000000','22:00:00.000000','Четвъртък',34),(54,'08:00:00.000000','22:00:00.000000','Петък',34),(55,'08:00:00.000000','22:00:00.000000','Събота',34),(56,'08:00:00.000000','22:00:00.000000','Неделя',34),(106,'08:00:00.000000','22:00:00.000000','Понеделник',37),(107,'08:00:00.000000','22:00:00.000000','Вторник',37),(108,'08:00:00.000000','22:00:00.000000','Сряда',37),(109,'08:00:00.000000','22:00:00.000000','Четвъртък',37),(110,'08:00:00.000000','22:00:00.000000','Петък',37),(111,'08:00:00.000000','22:00:00.000000','Събота',37),(112,'08:00:00.000000','08:00:00.000000','Неделя',37),(113,'08:00:00.000000','22:00:00.000000','Понеделник',39),(114,'08:00:00.000000','22:00:00.000000','Вторник',39),(115,'08:00:00.000000','22:00:00.000000','Сряда',39),(116,'08:00:00.000000','22:00:00.000000','Четвъртък',39),(117,'08:00:00.000000','22:00:00.000000','Петък',39),(118,'08:00:00.000000','22:00:00.000000','Събота',39),(119,'08:00:00.000000','22:00:00.000000','Неделя',39),(120,'08:00:00.000000','22:00:00.000000','Понеделник',42),(121,'08:00:00.000000','22:00:00.000000','Вторник',42),(122,'08:00:00.000000','22:00:00.000000','Сряда',42),(123,'08:00:00.000000','22:00:00.000000','Четвъртък',42),(124,'08:00:00.000000','22:00:00.000000','Петък',42),(125,'08:00:00.000000','22:00:00.000000','Събота',42),(126,'08:00:00.000000','08:00:00.000000','Неделя',42),(127,'08:00:00.000000','22:00:00.000000','Понеделник',49),(128,'08:00:00.000000','22:00:00.000000','Вторник',49),(129,'08:00:00.000000','22:00:00.000000','Сряда',49),(130,'08:00:00.000000','22:00:00.000000','Четвъртък',49),(131,'08:00:00.000000','22:00:00.000000','Петък',49),(132,'08:00:00.000000','15:00:00.000000','Събота',49),(133,'08:00:00.000000','08:00:00.000000','Неделя',49);
/*!40000 ALTER TABLE `horario_semanal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicios`
--

DROP TABLE IF EXISTS `servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicios` (
  `idServicio` int NOT NULL AUTO_INCREMENT,
  `nombreServ` varchar(40) NOT NULL,
  `duracionServ` int NOT NULL,
  `idCategoria` int NOT NULL,
  PRIMARY KEY (`idServicio`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicios`
--

LOCK TABLES `servicios` WRITE;
/*!40000 ALTER TABLE `servicios` DISABLE KEYS */;
INSERT INTO `servicios` VALUES (27,'Diagnostico de ECU',60,48),(28,'Cambio de aceite',30,49),(29,'Cambio de filto de aceite',15,49),(32,'nose',152,56),(37,'nose2',30,48),(38,'asd2',2,48);
/*!40000 ALTER TABLE `servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicios_empleados`
--

DROP TABLE IF EXISTS `servicios_empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicios_empleados` (
  `idServEmple` int NOT NULL AUTO_INCREMENT,
  `precio` int NOT NULL,
  `idEmpleado` int NOT NULL,
  `nombreServ` varchar(40) NOT NULL,
  PRIMARY KEY (`idServEmple`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicios_empleados`
--

LOCK TABLES `servicios_empleados` WRITE;
/*!40000 ALTER TABLE `servicios_empleados` DISABLE KEYS */;
INSERT INTO `servicios_empleados` VALUES (15,10,30,'Diagnostico de ECU'),(16,0,30,'Cambio de aceite'),(17,0,30,'Cambio de filto de aceite'),(20,0,31,'Diagnostico de ECU'),(21,0,31,'Cambio de aceite'),(22,0,31,'Cambio de filto de aceite'),(23,0,31,'Cambio de filtro de habitaculo'),(24,0,31,'Reparacion de turbo'),(25,0,30,'nose'),(26,0,32,'Cambio de filto de aceite'),(27,0,33,'Diagnostico de ECU'),(28,0,33,'Cambio de aceite'),(35,0,34,'Diagnostico de ECU'),(36,0,34,'Cambio de aceite'),(37,0,34,'Cambio de filto de aceite'),(38,0,34,'nose'),(39,0,36,'Diagnostico de ECU'),(40,0,36,'Cambio de aceite'),(41,0,36,'Cambio de filto de aceite'),(42,0,36,'nose'),(43,0,37,'Diagnostico de ECU'),(44,0,37,'Cambio de aceite'),(45,0,37,'Cambio de filto de aceite'),(46,0,37,'nose'),(48,0,40,'Diagnostico de ECU'),(49,0,40,'Cambio de aceite'),(50,0,40,'Cambio de filto de aceite'),(51,0,40,'nose'),(52,0,42,'Diagnostico de ECU'),(53,0,42,'Cambio de aceite'),(54,0,42,'nose'),(55,0,49,'Diagnostico de ECU'),(56,0,49,'Cambio de aceite'),(57,0,49,'Cambio de filto de aceite'),(58,0,49,'nose');
/*!40000 ALTER TABLE `servicios_empleados` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-12-28 19:06:45
