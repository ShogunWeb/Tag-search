DROP TABLE IF EXISTS `#__tagsearch`;
 
CREATE TABLE `#__tagsearch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bemvindo` varchar(25) NOT NULL,
  `catid` int(11) NOT NULL DEFAULT '0',
  `params` TEXT NOT NULL DEFAULT '',
   PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
 
INSERT INTO `#__tagsearch` (`bemvindo`) VALUES
        ('Bonjour!'),
        ('Au revoir!');
