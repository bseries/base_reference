CREATE TABLE `references` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '' COMMENT 'i.e. foo2018',
  `title` varchar(250) DEFAULT '',
  `authors` varchar(250) DEFAULT NULL,
  `source` varchar(250) DEFAULT NULL,
  `license` varchar(50) DEFAULT NULL COMMENT 'SPDX license identifier',
  `changes` varchar(250) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `media` ADD `reference_id` INT(11)  UNSIGNED  NULL  DEFAULT NULL  AFTER `source`;

