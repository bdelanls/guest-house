-- MySQL Script generated by MySQL Workbench
-- Sun Oct  3 15:18:41 2021
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema guest_house
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema guest_house
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `guest_house` DEFAULT CHARACTER SET utf8 ;
USE `guest_house` ;

-- -----------------------------------------------------
-- Table `guest_house`.`guestroom`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `guest_house`.`guestroom` ;

CREATE TABLE IF NOT EXISTS `guest_house`.`guestroom` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(255) NOT NULL,
  `max_persons` SMALLINT NOT NULL,
  `num_bed` SMALLINT NOT NULL DEFAULT 1,
  `add_bed` SMALLINT NOT NULL,
  `wifi` TINYINT(1) NOT NULL DEFAULT 0,
  `tv` TINYINT(1) NOT NULL DEFAULT 0,
  `clim` TINYINT(1) NOT NULL DEFAULT 0,
  `area` SMALLINT NOT NULL,
  `price` FLOAT NOT NULL,
  `promotion` SMALLINT UNSIGNED NULL,
  `disabled` TINYINT(1) NOT NULL DEFAULT 0,
  `pets` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `guest_house`.`role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `guest_house`.`role` ;

CREATE TABLE IF NOT EXISTS `guest_house`.`role` (
  `id` SMALLINT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'customer');


-- -----------------------------------------------------
-- Table `guest_house`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `guest_house`.`user` ;

CREATE TABLE IF NOT EXISTS `guest_house`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(45) NOT NULL,
  `lastname` VARCHAR(45) NOT NULL,
  `login` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(25) NOT NULL,
  `role_id` SMALLINT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_user_role1`
    FOREIGN KEY (`role_id`)
    REFERENCES `guest_house`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_user_role1_idx` ON `guest_house`.`user` (`role_id` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `guest_house`.`restoration`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `guest_house`.`restoration` ;

CREATE TABLE IF NOT EXISTS `guest_house`.`restoration` (
  `id` SMALLINT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `guest_house`.`booking`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `guest_house`.`booking` ;

CREATE TABLE IF NOT EXISTS `guest_house`.`booking` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `guestroom_id` INT NOT NULL,
  `arrival` DATE NOT NULL,
  `departure` DATE NOT NULL,
  `num_of_persons` TINYINT UNSIGNED NOT NULL,
  `taxi` TINYINT(1) NOT NULL DEFAULT 0,
  `restoration_id` SMALLINT NOT NULL,
  PRIMARY KEY (`id`, `user_id`, `guestroom_id`),
  CONSTRAINT `fk_user_has_guestroom_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `guest_house`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_guestroom_guestroom1`
    FOREIGN KEY (`guestroom_id`)
    REFERENCES `guest_house`.`guestroom` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_booking_restoration1`
    FOREIGN KEY (`restoration_id`)
    REFERENCES `guest_house`.`restoration` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_user_has_guestroom_guestroom1_idx` ON `guest_house`.`booking` (`guestroom_id` ASC) VISIBLE;

CREATE INDEX `fk_user_has_guestroom_user_idx` ON `guest_house`.`booking` (`user_id` ASC) VISIBLE;

CREATE INDEX `fk_booking_restoration1_idx` ON `guest_house`.`booking` (`restoration_id` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `guest_house`.`opinion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `guest_house`.`opinion` ;

CREATE TABLE IF NOT EXISTS `guest_house`.`opinion` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `comment` LONGTEXT NOT NULL,
  `note` TINYINT NOT NULL,
  `valid` TINYINT(1) NOT NULL DEFAULT 0,
  `user_id` INT(11) NOT NULL,
  `guestroom_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `user_id`, `guestroom_id`),
  CONSTRAINT `fk_opinion_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `guest_house`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_opinion_guestroom1`
    FOREIGN KEY (`guestroom_id`)
    REFERENCES `guest_house`.`guestroom` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_opinion_user1_idx` ON `guest_house`.`opinion` (`user_id` ASC) VISIBLE;

CREATE INDEX `fk_opinion_guestroom1_idx` ON `guest_house`.`opinion` (`guestroom_id` ASC) VISIBLE;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
