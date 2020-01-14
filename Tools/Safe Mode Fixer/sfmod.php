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
ALTER TABLE `#dbprefix#playlists` CHANGE `views` `views` mediumint(9) DEFAULT '0';
";
$dos = explode(";",$qs);
if($dos) {
echo "<ol>";	
foreach ($dos as $do) {
if(not_empty($do)) {
$qt = str_replace("#dbprefix#",DB_PREFIX, trim($do));
$db->query($qt);
echo PHP_EOL."<li>Querie executed for safe mode compatibility fix: ".PHP_EOL." <code><pre>";
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