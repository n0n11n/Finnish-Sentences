


CREATE DATABASE IF NOT EXISTS `suomi1` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `suomi1`;

CREATE TABLE IF NOT EXISTS `tehtavat` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tyypi` int(11) NOT NULL,
  `valinnat` text NOT NULL,
  `kaanos` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `tehtavat` VALUES(1, 1, '[\r\n	[\"Sinä\", \"Sinua\", \"Sinun\"],\r\n	[\"olet\", \"on\", \"ovat\"],\r\n	[\"opiskelija\", \"opiskelijaa\", \"opiskelijat\"]\r\n]', 'You are a student.');
INSERT INTO `tehtavat` VALUES(2, 2, '[\r\n	[\"Hän\", \"Hänet\", \"Häntä\"],\r\n	[\"on\", \"olet\", \"ovat\"],\r\n	[\"pitkä\", \"pitkää\", \"pitkiä\"]\r\n]', 'He is tall.');
INSERT INTO `tehtavat` VALUES(3, 3, '[\r\n	[\"Annalla\", \"Annassa\", \"Annat\"],\r\n	[\"on\", \"olet\", \"ovat\"],\r\n	[\"omena\", \"omenassa\", \"omenaan\"]\r\n]', 'Anna has an apple.');
INSERT INTO `tehtavat` VALUES(4, 10, '[\r\n	[\"Ulkomailla\", \"Ulkomaissa\", \"Ulkomaihin\"],\r\n	[\"on\", \"olen\", \"ovat\"],\r\n	[\"suomalaisia\", \"suomalainen\", \"suomalaisen\"]\r\n]', 'There are Finns abroad.');

CREATE USER IF NOT EXISTS 'user'@'127.0.0.1' IDENTIFIED BY 'password';

GRANT USAGE ON *.* TO 'user'@'127.0.0.1';

GRANT SELECT ON `suomi1`.`tehtavat` TO 'user'@'127.0.0.1';