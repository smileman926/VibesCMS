<?php  error_reporting(E_ALL); 
//Vital file include
require_once("load.php");
$db->query("ALTER TABLE  `".DB_PREFIX."users` ADD  `iglink` TEXT NOT NULL AFTER  `glink`");
echo "Users table updated...<br>";
 $db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media` int(11) NOT NULL DEFAULT '1',
  `token` varchar(255) COLLATE utf8_swedish_ci NOT NULL,
  `pub` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `date` text COLLATE utf8_swedish_ci NOT NULL,
  `featured` int(11) NOT NULL,
  `private` int(11) NOT NULL DEFAULT '0',
  `source` longtext COLLATE utf8_swedish_ci NOT NULL,
  `title` varchar(300) COLLATE utf8_swedish_ci NOT NULL,
  `thumb` longtext COLLATE utf8_swedish_ci NOT NULL,
  `description` longtext COLLATE utf8_swedish_ci NOT NULL,
  `tags` varchar(500) COLLATE utf8_swedish_ci NOT NULL,
  `category` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `liked` int(11) NOT NULL,
  `disliked` int(11) NOT NULL,
  `nsfw` int(11) NOT NULL,
  `privacy` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `iTitleSearch` (`title`),
  KEY `iviews_idx` (`views`),
  KEY `idates_idx` (`date`(50)),
  KEY `ipub_idx` (`pub`),
  FULLTEXT KEY `iSearchText` (`title`,`description`,`tags`),
  FULLTEXT KEY `iSearchTitleText` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=0");
echo "Images table created...<br>";

$db->query("INSERT INTO ".DB_PREFIX."images (id,media,token,pub,user_id,date,featured,private,source,title,thumb,description,tags,category,views,liked,disliked,nsfw,privacy)
  SELECT id,media,token,pub,user_id,date,featured,private,source,title,thumb,description,tags,category,views,liked,disliked,nsfw,privacy FROM ".DB_PREFIX."videos where media = 3"); 
$db->query("DELETE from ".DB_PREFIX."videos where media = 3");
echo "Images moved to new table...<br>";

$db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."conversation` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_one` int(11) NOT NULL,
  `user_two` int(11) NOT NULL,
  `started` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `closedby` int(11) DEFAULT NULL,
  PRIMARY KEY (`c_id`),
  KEY `user_one` (`user_one`),
  KEY `user_two` (`user_two`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=0");
echo "Conversations table created...<br>";
$db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."con_msgs` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `reply` text COLLATE utf8_general_mysql500_ci,
  `by_user` int(11) NOT NULL,
  `at_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `conv` int(11) NOT NULL,
  `read_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`msg_id`),
  KEY `by_user` (`by_user`),
  KEY `conv` (`conv`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=0");
echo "Messages table created...<br>";
$db->query("ALTER TABLE  `".DB_PREFIX."playlists` ADD  `ptype` INT NOT NULL DEFAULT  '1' AFTER  `id`");
echo "Playlist table updated...<br>";
$db->query("ALTER TABLE  `".DB_PREFIX."pages` ADD  `m_order` INT NOT NULL DEFAULT  '0' AFTER  `pid`");
echo "Pages table updated...<br>";
$db->query("ALTER TABLE  `".DB_PREFIX."homepage` CHANGE  `ident`  `ident` TEXT CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL");
echo "Homepage table updated...<br>";
$db->query("ALTER TABLE  `".DB_PREFIX."activity` CHANGE  `object`  `object` VARCHAR( 200 ) NOT NULL");
$db->query("ALTER TABLE  `".DB_PREFIX."con_msgs` CHANGE  `reply`  `reply` TEXT CHARACTER SET utf8 COLLATE utf8_swedish_ci NULL DEFAULT NULL");

echo "Activity table updated...<br>";
$db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."hearts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `vid` varchar(200) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `type` varchar(200) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_uni` (`uid`,`vid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0");
echo "Hearts table updated...<br>";
echo "<pre>";
$db->debug();
echo "</pre>";
?>