<?php error_reporting('E_ALL'); ?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<head>
<title>SQL SafeMode fixer</title>
<style type="text/css">
body {
	font-size:14px
	}
code {
	disply:block;margin:10px;
	}
pre {
white-space: pre-wrap;white-space: -moz-pre-wrap;white-space: -o-pre-wrap;word-wrap: break-word;
font-family: Courier, 'New Courier', monospace;font-size: 12px;border-radius: 5px;-moz-border-radius: 5px;
-webkit-border-radius: 5px;background-color: #eee;color: blue;padding:5px
    }
</style>
</head>
<body>
<?php require_once("load.php");
$qs = "
ALTER TABLE `#dbprefix#videos` CHANGE `liked` `liked` INT(11) DEFAULT '0';
ALTER TABLE `#dbprefix#videos` CHANGE `views` `views` INT(11) DEFAULT '0';
ALTER TABLE `#dbprefix#videos` CHANGE `disliked` `disliked` INT(11) DEFAULT '0';
ALTER TABLE `#dbprefix#videos` CHANGE `privacy` `privacy` INT(11) DEFAULT '0';
ALTER TABLE `#dbprefix#videos` CHANGE `duration` `duration` INT(11) DEFAULT '0';
ALTER TABLE `#dbprefix#videos` CHANGE `featured` `featured` INT(11) DEFAULT '0';
ALTER TABLE `#dbprefix#videos` CHANGE `nsfw` `nsfw` INT(11) DEFAULT '0';
ALTER TABLE `#dbprefix#images` CHANGE `liked` `liked` INT(11) DEFAULT '0';
ALTER TABLE `#dbprefix#images` CHANGE `views` `views` INT(11) DEFAULT '0';
ALTER TABLE `#dbprefix#images` CHANGE `disliked` `disliked` INT(11) DEFAULT '0';
ALTER TABLE `#dbprefix#images` CHANGE `privacy` `privacy` INT(11) DEFAULT '0';
ALTER TABLE `#dbprefix#images` CHANGE `featured` `featured` INT(11) DEFAULT '0';
ALTER TABLE `#dbprefix#images` CHANGE `nsfw` `nsfw` INT(11) DEFAULT '0';
ALTER TABLE `#dbprefix#images` ADD `ispremium` INT NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `#dbprefix#videos` ADD `ispremium` INT NOT NULL DEFAULT '0' AFTER `id`;
CREATE TABLE `#dbprefix#user_subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `payment_method` enum('paypal') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'paypal',
  `validity` int(5) NOT NULL COMMENT 'in month(s)',
  `valid_from` datetime NOT NULL,
  `valid_to` datetime NOT NULL,
  `item_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `txn_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_gross` float(10,2) NOT NULL,
  `currency_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `subscr_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payer_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_status` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `#dbprefix#users_groups` ADD `group_creative` TEXT NULL DEFAULT NULL AFTER `access_level`;
ALTER TABLE `#dbprefix#users_groups` CHANGE `admin` `ispremium` TINYINT(1) NULL DEFAULT NULL;
ALTER TABLE `#dbprefix#user_subscriptions` ADD PRIMARY KEY (`id`);
update vibe_users set avatar = replace(avatar,'uploads','storage/uploads');
update vibe_videos set thumb = replace(thumb,'uploads','storage/uploads');
update vibe_images set thumb = replace(thumb,'uploads','storage/uploads');
update vibe_channels set picture = replace(picture,'uploads','storage/uploads');
update vibe_playlists set picture = replace(picture,'uploads','storage/uploads');
";
$dos = explode(";",$qs);
if($dos) {
echo "<ol>";	
foreach ($dos as $do) {
if(not_empty($do)) {
$qt = str_replace("#dbprefix#",DB_PREFIX, trim($do));
$db->query($qt);
echo PHP_EOL."<li>Queries executed for database upgrade: ".PHP_EOL." <code><pre>";
echo $qt;
echo "</pre></code></li>".PHP_EOL;	
}
}
echo "</ol>";
} else {
echo "No queries?";	
}
?>
</body>
</html>