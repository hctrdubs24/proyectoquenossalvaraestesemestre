

CREATE TABLE `ababardb`.`users` ( `id` INT(11) NULL AUTO_INCREMENT , `email` VARCHAR(200) NOT NULL , `password` VARCHAR(200) NOT NULL , `cargo` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
 CREATE TABLE `ababardb`.`roles` ( `id` INT NOT NULL AUTO_INCREMENT , `descripción` VARCHAR(200) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
 CREATE TABLE `ababardb`.`usuarios` ( `id` INT(11) NULL AUTO_INCREMENT , `email` VARCHAR(255) NOT NULL , `password` VARCHAR(255) NOT NULL , `rol_id` INT(11) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;