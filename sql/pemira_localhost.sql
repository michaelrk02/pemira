-- MariaDB dump 10.19  Distrib 10.9.4-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: pemira
-- ------------------------------------------------------
-- Server version	10.9.4-MariaDB

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
-- Table structure for table `caleg`
--

DROP TABLE IF EXISTS `caleg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caleg` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `idprodi` int(11) DEFAULT NULL,
  `idfoto` char(255) DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idprodi` (`idprodi`),
  CONSTRAINT `caleg_ibfk_2` FOREIGN KEY (`idprodi`) REFERENCES `prodi` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `capres`
--

DROP TABLE IF EXISTS `capres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `capres` (
  `id` int(11) NOT NULL,
  `nama` varchar(200) DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `idfoto` char(255) DEFAULT NULL,
  `metadata` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mahasiswa`
--

DROP TABLE IF EXISTS `mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mahasiswa` (
  `nim` char(32) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `idprodi` int(11) DEFAULT NULL,
  `angkatan` int(11) DEFAULT NULL,
  `sso` varchar(100) DEFAULT NULL,
  `kode_akses` text DEFAULT NULL,
  `kode_akses_expire` datetime DEFAULT NULL,
  `qr_session_id` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`nim`),
  UNIQUE KEY `sso` (`sso`),
  KEY `idprodi` (`idprodi`),
  CONSTRAINT `mahasiswa_ibfk_2` FOREIGN KEY (`idprodi`) REFERENCES `prodi` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `partai`
--

DROP TABLE IF EXISTS `partai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partai` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `idfoto` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pemilih`
--

DROP TABLE IF EXISTS `pemilih`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pemilih` (
  `token` char(32) NOT NULL,
  `secret` char(32) DEFAULT NULL,
  `signature` char(32) DEFAULT NULL,
  `idprodi` int(11) DEFAULT NULL,
  `idcapres` int(11) DEFAULT NULL,
  `idpartai` int(11) DEFAULT NULL,
  `idcaleg` int(11) DEFAULT NULL,
  PRIMARY KEY (`token`),
  KEY `idcapres` (`idcapres`),
  KEY `idprodi` (`idprodi`),
  KEY `idcaleg` (`idcaleg`),
  KEY `idpartai` (`idpartai`),
  CONSTRAINT `pemilih_ibfk_4` FOREIGN KEY (`idcapres`) REFERENCES `capres` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pemilih_ibfk_5` FOREIGN KEY (`idprodi`) REFERENCES `prodi` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pemilih_ibfk_6` FOREIGN KEY (`idcaleg`) REFERENCES `caleg` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pemilih_ibfk_7` FOREIGN KEY (`idpartai`) REFERENCES `partai` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prodi`
--

DROP TABLE IF EXISTS `prodi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prodi` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sesi`
--

DROP TABLE IF EXISTS `sesi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sesi` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `waktu_buka` bigint(20) DEFAULT NULL,
  `waktu_tutup` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sesi_prodi`
--

DROP TABLE IF EXISTS `sesi_prodi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sesi_prodi` (
  `idprodi` int(11) NOT NULL,
  `idsesi` int(11) NOT NULL,
  PRIMARY KEY (`idprodi`,`idsesi`),
  KEY `idsesi` (`idsesi`),
  CONSTRAINT `sesi_prodi_ibfk_5` FOREIGN KEY (`idprodi`) REFERENCES `prodi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sesi_prodi_ibfk_6` FOREIGN KEY (`idsesi`) REFERENCES `sesi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `v_caleg_pemilih`
--

DROP TABLE IF EXISTS `v_caleg_pemilih`;
/*!50001 DROP VIEW IF EXISTS `v_caleg_pemilih`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_caleg_pemilih` AS SELECT
 1 AS `id`,
  1 AS `nama`,
  1 AS `prodi_id`,
  1 AS `prodi_nama`,
  1 AS `jumlah` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_capres_pemilih`
--

DROP TABLE IF EXISTS `v_capres_pemilih`;
/*!50001 DROP VIEW IF EXISTS `v_capres_pemilih`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_capres_pemilih` AS SELECT
 1 AS `id`,
  1 AS `nama`,
  1 AS `idfoto`,
  1 AS `jumlah` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_mahasiswa_full`
--

DROP TABLE IF EXISTS `v_mahasiswa_full`;
/*!50001 DROP VIEW IF EXISTS `v_mahasiswa_full`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_mahasiswa_full` AS SELECT
 1 AS `nim`,
  1 AS `nama`,
  1 AS `prodi`,
  1 AS `angkatan`,
  1 AS `sso` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_partai_pemilih`
--

DROP TABLE IF EXISTS `v_partai_pemilih`;
/*!50001 DROP VIEW IF EXISTS `v_partai_pemilih`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_partai_pemilih` AS SELECT
 1 AS `id`,
  1 AS `nama`,
  1 AS `idfoto`,
  1 AS `jumlah` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_prodi_canvote`
--

DROP TABLE IF EXISTS `v_prodi_canvote`;
/*!50001 DROP VIEW IF EXISTS `v_prodi_canvote`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_prodi_canvote` AS SELECT
 1 AS `id`,
  1 AS `nama`,
  1 AS `canvote` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_prodi_kuota`
--

DROP TABLE IF EXISTS `v_prodi_kuota`;
/*!50001 DROP VIEW IF EXISTS `v_prodi_kuota`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_prodi_kuota` AS SELECT
 1 AS `id`,
  1 AS `nama`,
  1 AS `jumlah` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_prodi_listsesi`
--

DROP TABLE IF EXISTS `v_prodi_listsesi`;
/*!50001 DROP VIEW IF EXISTS `v_prodi_listsesi`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_prodi_listsesi` AS SELECT
 1 AS `id`,
  1 AS `nama`,
  1 AS `sesi_id`,
  1 AS `sesi_nama`,
  1 AS `sesi_waktu_buka`,
  1 AS `sesi_waktu_tutup` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_prodi_pemilih`
--

DROP TABLE IF EXISTS `v_prodi_pemilih`;
/*!50001 DROP VIEW IF EXISTS `v_prodi_pemilih`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_prodi_pemilih` AS SELECT
 1 AS `id`,
  1 AS `nama`,
  1 AS `jumlah` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_prodi_statistik`
--

DROP TABLE IF EXISTS `v_prodi_statistik`;
/*!50001 DROP VIEW IF EXISTS `v_prodi_statistik`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_prodi_statistik` AS SELECT
 1 AS `id`,
  1 AS `nama`,
  1 AS `pemilih`,
  1 AS `useraktif`,
  1 AS `kuota` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_prodi_useraktif`
--

DROP TABLE IF EXISTS `v_prodi_useraktif`;
/*!50001 DROP VIEW IF EXISTS `v_prodi_useraktif`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_prodi_useraktif` AS SELECT
 1 AS `id`,
  1 AS `nama`,
  1 AS `jumlah` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_sesi_listprodi`
--

DROP TABLE IF EXISTS `v_sesi_listprodi`;
/*!50001 DROP VIEW IF EXISTS `v_sesi_listprodi`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_sesi_listprodi` AS SELECT
 1 AS `id`,
  1 AS `nama`,
  1 AS `waktu_buka`,
  1 AS `waktu_tutup`,
  1 AS `prodi_id`,
  1 AS `prodi_nama` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_sesi_listprodi_inv`
--

DROP TABLE IF EXISTS `v_sesi_listprodi_inv`;
/*!50001 DROP VIEW IF EXISTS `v_sesi_listprodi_inv`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_sesi_listprodi_inv` AS SELECT
 1 AS `id`,
  1 AS `nama`,
  1 AS `waktu_buka`,
  1 AS `waktu_tutup`,
  1 AS `prodi_id`,
  1 AS `prodi_nama` */;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'pemira'
--

--
-- Final view structure for view `v_caleg_pemilih`
--

/*!50001 DROP VIEW IF EXISTS `v_caleg_pemilih`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_caleg_pemilih` AS select `caleg`.`id` AS `id`,`caleg`.`nama` AS `nama`,`prodi`.`id` AS `prodi_id`,`prodi`.`nama` AS `prodi_nama`,sum(`pemilih`.`token` is not null) AS `jumlah` from ((`caleg` left join `pemilih` on(`pemilih`.`idcaleg` = `caleg`.`id`)) left join `prodi` on(`prodi`.`id` = `caleg`.`idprodi`)) group by `caleg`.`id`,`caleg`.`nama`,`prodi`.`id`,`prodi`.`nama` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_capres_pemilih`
--

/*!50001 DROP VIEW IF EXISTS `v_capres_pemilih`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_capres_pemilih` AS select `capres`.`id` AS `id`,`capres`.`nama` AS `nama`,`capres`.`idfoto` AS `idfoto`,sum(`pemilih`.`token` is not null) AS `jumlah` from (`capres` left join `pemilih` on(`pemilih`.`idcapres` = `capres`.`id`)) group by `capres`.`id`,`capres`.`nama` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_mahasiswa_full`
--

/*!50001 DROP VIEW IF EXISTS `v_mahasiswa_full`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_mahasiswa_full` AS select `mahasiswa`.`nim` AS `nim`,`mahasiswa`.`nama` AS `nama`,`prodi`.`nama` AS `prodi`,`mahasiswa`.`angkatan` AS `angkatan`,`mahasiswa`.`sso` AS `sso` from (`mahasiswa` join `prodi` on(`prodi`.`id` = `mahasiswa`.`idprodi`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_partai_pemilih`
--

/*!50001 DROP VIEW IF EXISTS `v_partai_pemilih`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_partai_pemilih` AS select `partai`.`id` AS `id`,`partai`.`nama` AS `nama`,`partai`.`idfoto` AS `idfoto`,sum(`pemilih`.`token` is not null) AS `jumlah` from (`partai` left join `pemilih` on(`pemilih`.`idpartai` = `partai`.`id`)) group by `partai`.`id`,`partai`.`nama` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_prodi_canvote`
--

/*!50001 DROP VIEW IF EXISTS `v_prodi_canvote`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_prodi_canvote` AS select `prodi`.`id` AS `id`,`prodi`.`nama` AS `nama`,sum(`sesi`.`waktu_buka` <= unix_timestamp(current_timestamp()) and unix_timestamp(current_timestamp()) < `sesi`.`waktu_tutup`) > 0 AS `canvote` from ((`prodi` left join `sesi_prodi` on(`sesi_prodi`.`idprodi` = `prodi`.`id`)) left join `sesi` on(`sesi`.`id` = `sesi_prodi`.`idsesi`)) group by `prodi`.`id`,`prodi`.`nama` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_prodi_kuota`
--

/*!50001 DROP VIEW IF EXISTS `v_prodi_kuota`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_prodi_kuota` AS select `prodi`.`id` AS `id`,`prodi`.`nama` AS `nama`,sum(`mahasiswa`.`nim` is not null) AS `jumlah` from (`prodi` left join `mahasiswa` on(`mahasiswa`.`idprodi` = `prodi`.`id`)) group by `prodi`.`id`,`prodi`.`nama` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_prodi_listsesi`
--

/*!50001 DROP VIEW IF EXISTS `v_prodi_listsesi`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_prodi_listsesi` AS select `prodi`.`id` AS `id`,`prodi`.`nama` AS `nama`,`sesi`.`id` AS `sesi_id`,`sesi`.`nama` AS `sesi_nama`,`sesi`.`waktu_buka` AS `sesi_waktu_buka`,`sesi`.`waktu_tutup` AS `sesi_waktu_tutup` from ((`prodi` join `sesi_prodi` on(`sesi_prodi`.`idprodi` = `prodi`.`id`)) join `sesi` on(`sesi`.`id` = `sesi_prodi`.`idsesi`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_prodi_pemilih`
--

/*!50001 DROP VIEW IF EXISTS `v_prodi_pemilih`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_prodi_pemilih` AS select `prodi`.`id` AS `id`,`prodi`.`nama` AS `nama`,sum(`pemilih`.`token` is not null) AS `jumlah` from (`prodi` left join `pemilih` on(`pemilih`.`idprodi` = `prodi`.`id`)) group by `prodi`.`id`,`prodi`.`nama` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_prodi_statistik`
--

/*!50001 DROP VIEW IF EXISTS `v_prodi_statistik`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_prodi_statistik` AS select `prodi`.`id` AS `id`,`prodi`.`nama` AS `nama`,ifnull(`v_prodi_pemilih`.`jumlah`,0) AS `pemilih`,ifnull(`v_prodi_useraktif`.`jumlah`,0) AS `useraktif`,ifnull(`v_prodi_kuota`.`jumlah`,0) AS `kuota` from (((`prodi` left join `v_prodi_pemilih` on(`v_prodi_pemilih`.`id` = `prodi`.`id`)) left join `v_prodi_useraktif` on(`v_prodi_useraktif`.`id` = `prodi`.`id`)) left join `v_prodi_kuota` on(`v_prodi_kuota`.`id` = `prodi`.`id`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_prodi_useraktif`
--

/*!50001 DROP VIEW IF EXISTS `v_prodi_useraktif`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_prodi_useraktif` AS select `prodi`.`id` AS `id`,`prodi`.`nama` AS `nama`,sum(`mahasiswa`.`sso` is not null) AS `jumlah` from (`prodi` left join `mahasiswa` on(`mahasiswa`.`idprodi` = `prodi`.`id`)) group by `prodi`.`id`,`prodi`.`nama` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_sesi_listprodi`
--

/*!50001 DROP VIEW IF EXISTS `v_sesi_listprodi`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_sesi_listprodi` AS select `sesi`.`id` AS `id`,`sesi`.`nama` AS `nama`,`sesi`.`waktu_buka` AS `waktu_buka`,`sesi`.`waktu_tutup` AS `waktu_tutup`,`prodi`.`id` AS `prodi_id`,`prodi`.`nama` AS `prodi_nama` from (`sesi` join `prodi`) where exists(select 1 from `sesi_prodi` where `sesi_prodi`.`idsesi` = `sesi`.`id` and `sesi_prodi`.`idprodi` = `prodi`.`id` limit 1) order by `sesi`.`id`,`prodi`.`id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_sesi_listprodi_inv`
--

/*!50001 DROP VIEW IF EXISTS `v_sesi_listprodi_inv`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013  SQL SECURITY DEFINER */
/*!50001 VIEW `v_sesi_listprodi_inv` AS select `sesi`.`id` AS `id`,`sesi`.`nama` AS `nama`,`sesi`.`waktu_buka` AS `waktu_buka`,`sesi`.`waktu_tutup` AS `waktu_tutup`,`prodi`.`id` AS `prodi_id`,`prodi`.`nama` AS `prodi_nama` from (`sesi` join `prodi`) where !exists(select 1 from `sesi_prodi` where `sesi_prodi`.`idsesi` = `sesi`.`id` and `sesi_prodi`.`idprodi` = `prodi`.`id` limit 1) order by `sesi`.`id`,`prodi`.`id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-12-31 14:22:33
