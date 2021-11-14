-- Adminer 4.7.8 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `caleg`;
CREATE TABLE `caleg` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `idprodi` int(11) NOT NULL,
  `idfoto` char(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idprodi` (`idprodi`),
  CONSTRAINT `caleg_ibfk_2` FOREIGN KEY (`idprodi`) REFERENCES `prodi` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `capres`;
CREATE TABLE `capres` (
  `id` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `visi` text NOT NULL,
  `misi` text NOT NULL,
  `idfoto` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE `mahasiswa` (
  `nim` char(8) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `idprodi` int(11) NOT NULL,
  `angkatan` int(11) NOT NULL,
  `sso` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`nim`),
  UNIQUE KEY `sso` (`sso`),
  KEY `idprodi` (`idprodi`),
  CONSTRAINT `mahasiswa_ibfk_2` FOREIGN KEY (`idprodi`) REFERENCES `prodi` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `pemilih`;
CREATE TABLE `pemilih` (
  `token` char(32) NOT NULL,
  `secret` char(32) NOT NULL,
  `idprodi` int(11) NOT NULL,
  `idcapres` int(11) NOT NULL,
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
  `nama` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `sesi`;
CREATE TABLE `sesi` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `waktu_buka` bigint(20) NOT NULL,
  `waktu_tutup` bigint(20) NOT NULL,
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


DROP VIEW IF EXISTS `v_kuotaprodi`;
CREATE TABLE `v_kuotaprodi` (`id` int(11), `nama` varchar(50), `kuota` decimal(22,0));


DROP TABLE IF EXISTS `v_kuotaprodi`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_kuotaprodi` AS select `prodi`.`id` AS `id`,`prodi`.`nama` AS `nama`,sum(case when `mahasiswa`.`nim` is null then 0 else 1 end) AS `kuota` from (`prodi` left join `mahasiswa` on(`mahasiswa`.`idprodi` = `prodi`.`id`)) group by `prodi`.`id`,`prodi`.`nama`;

-- 2021-11-14 15:36:53
