CREATE TABLE `partai` (
    `id` INT NOT NULL,
    `nama` VARCHAR(100) NOT NULL,
    `idfoto` CHAR(255) NOT NULL,

    PRIMARY KEY (`id`)
);

ALTER TABLE `pemilih`
ADD `idpartai` INT AFTER `idcapres`,
ADD FOREIGN KEY (`idpartai`) REFERENCES `partai` (`id`);

CREATE VIEW `v_partai_pemilih` AS
select
    `partai`.`id` AS `id`,
    `partai`.`nama` AS `nama`,
    `partai`.`idfoto` AS `idfoto`,
    sum(`pemilih`.`token` is not null) AS `jumlah`
from
    (`partai`
left join `pemilih` on
    (`pemilih`.`idpartai` = `partai`.`id`))
group by
    `partai`.`id`,
    `partai`.`nama`;
