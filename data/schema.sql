CREATE TABLE `references` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '' COMMENT 'i.e. foo2018',
  `title` varchar(250) DEFAULT '',
  `authors` varchar(250) DEFAULT NULL,
  `source` varchar(250) DEFAULT NULL,
  `license` varchar(50) DEFAULT NULL COMMENT 'SPDX license identifier',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;