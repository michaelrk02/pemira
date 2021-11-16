-- Adminer 4.7.8 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `caleg`;
CREATE TABLE `caleg` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `idprodi` int(11) DEFAULT NULL,
  `idfoto` char(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idprodi` (`idprodi`),
  CONSTRAINT `caleg_ibfk_2` FOREIGN KEY (`idprodi`) REFERENCES `prodi` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `capres`;
CREATE TABLE `capres` (
  `id` int(11) NOT NULL,
  `nama` varchar(200) DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `idfoto` char(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE `mahasiswa` (
  `nim` char(8) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `idprodi` int(11) DEFAULT NULL,
  `angkatan` int(11) DEFAULT NULL,
  `sso` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`nim`),
  UNIQUE KEY `sso` (`sso`),
  KEY `idprodi` (`idprodi`),
  CONSTRAINT `mahasiswa_ibfk_2` FOREIGN KEY (`idprodi`) REFERENCES `prodi` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `pemilih`;
CREATE TABLE `pemilih` (
  `token` char(32) NOT NULL,
  `secret` char(32) DEFAULT NULL,
  `idprodi` int(11) DEFAULT NULL,
  `idcapres` int(11) DEFAULT NULL,
  `idcaleg` int(11) DEFAULT NULL,
  PRIMARY KEY (`token`),
  KEY `idcapres` (`idcapres`),
  KEY `idprodi` (`idprodi`),
  KEY `idcaleg` (`idcaleg`),
  CONSTRAINT `pemilih_ibfk_4` FOREIGN KEY (`idcapres`) REFERENCES `capres` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pemilih_ibfk_5` FOREIGN KEY (`idprodi`) REFERENCES `prodi` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pemilih_ibfk_6` FOREIGN KEY (`idcaleg`) REFERENCES `caleg` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `prodi`;
CREATE TABLE `prodi` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `sesi`;
CREATE TABLE `sesi` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `waktu_buka` bigint(20) DEFAULT NULL,
  `waktu_tutup` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `sesi_prodi`;
CREATE TABLE `sesi_prodi` (
  `idprodi` int(11) NOT NULL,
  `idsesi` int(11) NOT NULL,
  PRIMARY KEY (`idprodi`,`idsesi`),
  KEY `idsesi` (`idsesi`),
  CONSTRAINT `sesi_prodi_ibfk_3` FOREIGN KEY (`idprodi`) REFERENCES `prodi` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `sesi_prodi_ibfk_4` FOREIGN KEY (`idsesi`) REFERENCES `sesi` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP VIEW IF EXISTS `v_caleg_pemilih`;
CREATE TABLE `v_caleg_pemilih` (`id` int(11), `nama` varchar(100), `prodi_id` int(11), `prodi_nama` varchar(50), `jumlah` decimal(23,0));


DROP VIEW IF EXISTS `v_capres_pemilih`;
CREATE TABLE `v_capres_pemilih` (`id` int(11), `nama` varchar(200), `idfoto` char(255), `jumlah` decimal(23,0));


DROP VIEW IF EXISTS `v_mahasiswa_full`;
CREATE TABLE `v_mahasiswa_full` (`nim` char(8), `nama` varchar(100), `prodi` varchar(50), `angkatan` int(11), `sso` varchar(100));


DROP VIEW IF EXISTS `v_prodi_canvote`;
CREATE TABLE `v_prodi_canvote` (`id` int(11), `nama` varchar(50), `canvote` int(1));


DROP VIEW IF EXISTS `v_prodi_kuota`;
CREATE TABLE `v_prodi_kuota` (`id` int(11), `nama` varchar(50), `jumlah` decimal(23,0));


DROP VIEW IF EXISTS `v_prodi_listsesi`;
CREATE TABLE `v_prodi_listsesi` (`id` int(11), `nama` varchar(50), `sesi_id` int(11), `sesi_nama` varchar(100), `sesi_waktu_buka` bigint(20), `sesi_waktu_tutup` bigint(20));


DROP VIEW IF EXISTS `v_prodi_pemilih`;
CREATE TABLE `v_prodi_pemilih` (`id` int(11), `nama` varchar(50), `jumlah` decimal(23,0));


DROP VIEW IF EXISTS `v_prodi_statistik`;
CREATE TABLE `v_prodi_statistik` (`id` int(11), `nama` varchar(50), `pemilih` decimal(23,0), `useraktif` decimal(23,0), `kuota` decimal(23,0));


DROP VIEW IF EXISTS `v_prodi_useraktif`;
CREATE TABLE `v_prodi_useraktif` (`id` int(11), `nama` varchar(50), `jumlah` decimal(23,0));


DROP VIEW IF EXISTS `v_sesi_listprodi`;
CREATE TABLE `v_sesi_listprodi` (`id` int(11), `nama` varchar(100), `waktu_buka` bigint(20), `waktu_tutup` bigint(20), `prodi_id` int(11), `prodi_nama` varchar(50));


DROP TABLE IF EXISTS `v_caleg_pemilih`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_caleg_pemilih` AS select `caleg`.`id` AS `id`,`caleg`.`nama` AS `nama`,`prodi`.`id` AS `prodi_id`,`prodi`.`nama` AS `prodi_nama`,sum(`pemilih`.`token` is not null) AS `jumlah` from ((`caleg` left join `pemilih` on(`pemilih`.`idcaleg` = `caleg`.`id`)) left join `prodi` on(`prodi`.`id` = `caleg`.`idprodi`)) group by `caleg`.`id`,`caleg`.`nama`,`prodi`.`id`,`prodi`.`nama`;

DROP TABLE IF EXISTS `v_capres_pemilih`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_capres_pemilih` AS select `capres`.`id` AS `id`,`capres`.`nama` AS `nama`,`capres`.`idfoto` AS `idfoto`,sum(`pemilih`.`token` is not null) AS `jumlah` from (`capres` left join `pemilih` on(`pemilih`.`idcapres` = `capres`.`id`)) group by `capres`.`id`,`capres`.`nama`;

DROP TABLE IF EXISTS `v_mahasiswa_full`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_mahasiswa_full` AS select `mahasiswa`.`nim` AS `nim`,`mahasiswa`.`nama` AS `nama`,`prodi`.`nama` AS `prodi`,`mahasiswa`.`angkatan` AS `angkatan`,`mahasiswa`.`sso` AS `sso` from (`mahasiswa` join `prodi` on(`prodi`.`id` = `mahasiswa`.`idprodi`));

DROP TABLE IF EXISTS `v_prodi_canvote`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_prodi_canvote` AS select `prodi`.`id` AS `id`,`prodi`.`nama` AS `nama`,sum(`sesi`.`waktu_buka` <= unix_timestamp(current_timestamp()) and unix_timestamp(current_timestamp()) < `sesi`.`waktu_tutup`) > 0 AS `canvote` from ((`prodi` left join `sesi_prodi` on(`sesi_prodi`.`idprodi` = `prodi`.`id`)) left join `sesi` on(`sesi`.`id` = `sesi_prodi`.`idsesi`)) group by `prodi`.`id`,`prodi`.`nama`;

DROP TABLE IF EXISTS `v_prodi_kuota`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_prodi_kuota` AS select `prodi`.`id` AS `id`,`prodi`.`nama` AS `nama`,sum(`mahasiswa`.`nim` is not null) AS `jumlah` from (`prodi` left join `mahasiswa` on(`mahasiswa`.`idprodi` = `prodi`.`id`)) group by `prodi`.`id`,`prodi`.`nama`;

DROP TABLE IF EXISTS `v_prodi_listsesi`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_prodi_listsesi` AS select `prodi`.`id` AS `id`,`prodi`.`nama` AS `nama`,`sesi`.`id` AS `sesi_id`,`sesi`.`nama` AS `sesi_nama`,`sesi`.`waktu_buka` AS `sesi_waktu_buka`,`sesi`.`waktu_tutup` AS `sesi_waktu_tutup` from ((`prodi` join `sesi_prodi` on(`sesi_prodi`.`idprodi` = `prodi`.`id`)) join `sesi` on(`sesi`.`id` = `sesi_prodi`.`idsesi`));

DROP TABLE IF EXISTS `v_prodi_pemilih`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_prodi_pemilih` AS select `prodi`.`id` AS `id`,`prodi`.`nama` AS `nama`,sum(`pemilih`.`token` is not null) AS `jumlah` from (`prodi` left join `pemilih` on(`pemilih`.`idprodi` = `prodi`.`id`)) group by `prodi`.`id`,`prodi`.`nama`;

DROP TABLE IF EXISTS `v_prodi_statistik`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_prodi_statistik` AS select `prodi`.`id` AS `id`,`prodi`.`nama` AS `nama`,ifnull(`v_prodi_pemilih`.`jumlah`,0) AS `pemilih`,ifnull(`v_prodi_useraktif`.`jumlah`,0) AS `useraktif`,ifnull(`v_prodi_kuota`.`jumlah`,0) AS `kuota` from (((`prodi` left join `v_prodi_pemilih` on(`v_prodi_pemilih`.`id` = `prodi`.`id`)) left join `v_prodi_useraktif` on(`v_prodi_useraktif`.`id` = `prodi`.`id`)) left join `v_prodi_kuota` on(`v_prodi_kuota`.`id` = `prodi`.`id`));

DROP TABLE IF EXISTS `v_prodi_useraktif`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_prodi_useraktif` AS select `prodi`.`id` AS `id`,`prodi`.`nama` AS `nama`,sum(`mahasiswa`.`sso` is not null) AS `jumlah` from (`prodi` left join `mahasiswa` on(`mahasiswa`.`idprodi` = `prodi`.`id`)) group by `prodi`.`id`,`prodi`.`nama`;

DROP TABLE IF EXISTS `v_sesi_listprodi`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_sesi_listprodi` AS select `sesi`.`id` AS `id`,`sesi`.`nama` AS `nama`,`sesi`.`waktu_buka` AS `waktu_buka`,`sesi`.`waktu_tutup` AS `waktu_tutup`,`prodi`.`id` AS `prodi_id`,`prodi`.`nama` AS `prodi_nama` from ((`sesi` join `sesi_prodi` on(`sesi_prodi`.`idsesi` = `sesi`.`id`)) join `prodi` on(`prodi`.`id` = `sesi_prodi`.`idprodi`));

-- 2021-11-16 15:08:19
