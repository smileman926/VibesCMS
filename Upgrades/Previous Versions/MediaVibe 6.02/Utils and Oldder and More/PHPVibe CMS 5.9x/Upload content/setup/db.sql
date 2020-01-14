CREATE TABLE IF NOT EXISTS `#dbprefix#activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `object` varchar(200) DEFAULT NULL,
  `extra` mediumtext DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#ads` (
  `ad_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ad_spot` varchar(64) NOT NULL DEFAULT '',
  `ad_type` varchar(64) NOT NULL DEFAULT '0',
  `ad_content` longtext DEFAULT NULL,
  `ad_title` varchar(64) DEFAULT NULL,
  `ad_pos` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`ad_id`),
  KEY `ad_type_idx` (`ad_type`),
  KEY `ad_spot_idx` (`ad_spot`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#channels` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `child_of` int(11) DEFAULT NULL,
  `picture` varchar(150) DEFAULT NULL,
  `cat_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  `cat_desc` varchar(500) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  `type` int(255) NOT NULL DEFAULT '1',
  `sub` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#conversation` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_one` int(11) DEFAULT NULL,
  `user_two` int(11) DEFAULT NULL,
  `started` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `closedby` int(11) DEFAULT NULL,
  PRIMARY KEY (`c_id`),
  KEY `user_one` (`user_one`),
  KEY `user_two` (`user_two`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#con_msgs` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `reply` text COLLATE utf8_general_mysql500_ci,
  `by_user` int(11) DEFAULT NULL,
  `at_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `conv` int(11) DEFAULT NULL,
  `read_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`msg_id`),
  KEY `by_user` (`by_user`),
  KEY `conv` (`conv`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=0 ;
CREATE TABLE IF NOT EXISTS `#dbprefix#crons` (
  `cron_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cron_type` varchar(500) DEFAULT NULL,
  `cron_name` varchar(64) NOT NULL DEFAULT '',
  `cron_period` mediumint(9) NOT NULL DEFAULT '86400',
  `cron_pages` int(11) NOT NULL DEFAULT '5',
  `cron_lastrun` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cron_value` longtext DEFAULT NULL,
  PRIMARY KEY (`cron_id`),
  KEY `cron_type_idx` (`cron_type`(333))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#em_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` varchar(64) DEFAULT NULL,
  `created` varchar(50) DEFAULT NULL,
  `sender_id` varchar(128) DEFAULT NULL,
  `comment_text` text,
  `reply` int(11) NOT NULL DEFAULT '0',
  `rating_cache` int(11) NOT NULL DEFAULT '0',
  `access_key` varchar(100) DEFAULT NULL,
  `visible` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#em_likes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` int(10) unsigned DEFAULT NULL,
  `sender_ip` bigint(20) DEFAULT NULL,
  `vote` enum('1','-1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `comment_id` (`comment_id`),
  KEY `sender_ip` (`sender_ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#hearts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `vid` varchar(200) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  `type` varchar(200) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_uni` (`uid`,`vid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#homepage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ord` int(11) DEFAULT NULL,
  `title` longtext DEFAULT NULL,
  `type` varchar(200) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  `ident` text CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  `querystring` varchar(200) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `mtype` int(11) NOT NULL DEFAULT '1',
  `car` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media` int(11) NOT NULL DEFAULT '1',
  `token` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `pub` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `date` text COLLATE utf8_swedish_ci DEFAULT NULL,
  `featured` int(11) DEFAULT NULL,
  `private` int(11) NOT NULL DEFAULT '0',
  `source` longtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `title` varchar(300) COLLATE utf8_swedish_ci DEFAULT NULL,
  `thumb` longtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `tags` varchar(500) COLLATE utf8_swedish_ci DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `liked` int(11) DEFAULT NULL,
  `disliked` int(11) DEFAULT NULL,
  `nsfw` int(11) DEFAULT NULL,
  `privacy` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `iTitleSearch` (`title`),
  KEY `iviews_idx` (`views`),
  KEY `idates_idx` (`date`(50)),
  KEY `ipub_idx` (`pub`),
  FULLTEXT KEY `iSearchText` (`title`,`description`,`tags`),
  FULLTEXT KEY `iSearchTitleText` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#jads` (
  `jad_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `jad_type` varchar(64) NOT NULL DEFAULT '0',
  `jad_box` varchar(64) NOT NULL DEFAULT '0',
  `jad_start` varchar(64) NOT NULL DEFAULT '0',
  `jad_end` varchar(64) NOT NULL DEFAULT '0',
  `jad_body` longtext DEFAULT NULL,
  `jad_title` varchar(64) DEFAULT NULL,
  `jad_pos` varchar(64) DEFAULT NULL,
  `jad_extra` text DEFAULT NULL,
  PRIMARY KEY (`jad_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#langs` (
  `lang_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term` longtext DEFAULT NULL,
  PRIMARY KEY (`lang_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#languages` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lang_name` varchar(204) NOT NULL DEFAULT '',
  `lang_code` varchar(64) NOT NULL DEFAULT '',
  `lang_terms` longtext DEFAULT NULL,
  PRIMARY KEY (`term_id`),
  UNIQUE KEY `lang_code` (`lang_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `vid` varchar(200) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  `type` varchar(200) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_uni` (`uid`,`vid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#noty` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned DEFAULT NULL,
  `note` mediumtext DEFAULT NULL,
  `read` int(10) unsigned DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext DEFAULT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`),
  UNIQUE KEY `option_name_uni` (`option_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#pages` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `menu` int(11) NOT NULL DEFAULT '0',
  `m_order` int(11) NOT NULL DEFAULT '1',
  `date` text COLLATE utf8_swedish_ci DEFAULT NULL,
  `title` varchar(300) COLLATE utf8_swedish_ci DEFAULT NULL,
  `pic` longtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `content` longtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `tags` varchar(500) COLLATE utf8_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#playlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ptype` int(11) NOT NULL DEFAULT '1',
  `owner` int(11) DEFAULT NULL,
  `picture` varchar(150) DEFAULT NULL,
  `title` varchar(150) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  `description` varchar(500) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  `views` mediumint(9) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#playlist_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playlist` int(11) DEFAULT NULL,
  `video_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `playlist_idx` (`playlist`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#postcats` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `picture` varchar(150) DEFAULT NULL,
  `cat_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  `cat_desc` varchar(500) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#posts` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `ch` int(11) NOT NULL DEFAULT '1',
  `date` text COLLATE utf8_swedish_ci DEFAULT NULL,
  `title` varchar(300) COLLATE utf8_swedish_ci DEFAULT NULL,
  `pic` longtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `content` longtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `tags` varchar(500) COLLATE utf8_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#reports` (
  `r_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `vid` varchar(200) CHARACTER SET utf8 COLLATE utf8_swedish_ci DEFAULT NULL,
  `reason` longtext CHARACTER SET utf8 COLLATE utf8_swedish_ci,
  `motive` longtext DEFAULT NULL,
  PRIMARY KEY (`r_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#tags` (
  `tagid` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) COLLATE utf8_swedish_ci DEFAULT NULL,
  `tcount` int(11) DEFAULT NULL,
  PRIMARY KEY (`tagid`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#users` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `pass` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `password` mediumtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `lastlogin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `group_id` varchar(255) COLLATE utf8_swedish_ci NOT NULL DEFAULT '4',
  `avatar` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `cover` mediumtext COLLATE utf8_swedish_ci,
  `date_registered` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `gid` mediumtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `fid` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `oauth_token` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `local` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `bio` longtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `views` mediumint(9) NOT NULL DEFAULT '0',
  `fblink` text COLLATE utf8_swedish_ci DEFAULT NULL,
  `twlink` text COLLATE utf8_swedish_ci DEFAULT NULL,
  `glink` text COLLATE utf8_swedish_ci DEFAULT NULL,
  `iglink` text COLLATE utf8_swedish_ci DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `lastNoty` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#users_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `fid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid_idx` (`uid`),
  KEY `fid_idx` (`fid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#users_groups` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  `default_value` tinyint(1) DEFAULT NULL,
  `access_level` bigint(32) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media` int(11) NOT NULL DEFAULT '1',
  `token` varchar(255) COLLATE utf8_swedish_ci DEFAULT NULL,
  `pub` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `date` text COLLATE utf8_swedish_ci DEFAULT NULL,
  `featured` int(11) DEFAULT NULL,
  `private` int(11) NOT NULL DEFAULT '0',
  `source` longtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `tmp_source` mediumtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `title` varchar(300) COLLATE utf8_swedish_ci DEFAULT NULL,
  `thumb` longtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `duration` int(10) DEFAULT NULL,
  `description` longtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `tags` varchar(500) COLLATE utf8_swedish_ci DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `liked` int(11) DEFAULT NULL,
  `disliked` int(11) DEFAULT NULL,
  `nsfw` int(11) DEFAULT NULL,
  `embed` longtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `remote` longtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `srt` mediumtext COLLATE utf8_swedish_ci DEFAULT NULL,
  `privacy` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `TitleSearch` (`title`),
  KEY `views_idx` (`views`),
  KEY `dates_idx` (`date`(50)),
  KEY `pub_idx` (`pub`),
  KEY `source_idx` (`source`(300)),
  KEY `tmp_source_idx` (`tmp_source`(300)),
  FULLTEXT KEY `SearchText` (`title`,`description`,`tags`),
  FULLTEXT KEY `SearchTitleText` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `#dbprefix#videos_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `name` varchar(500) DEFAULT NULL,
  `path` mediumtext CHARACTER SET utf8 COLLATE utf8_swedish_ci,
  `ext` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;