<?php
if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); }

function callback_init() {/*激活*/
	$s=array('key'=>'','durl'=>'');
	option::pset('fyy_massistant',$s);
	global $m;
	$m->query("CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".DB_PREFIX."allinfo` (
			`id`  int(30) NOT NULL AUTO_INCREMENT ,
			`stname` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`usname` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`email` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`bduss` TEXT NOT NULL ,
			`bdname` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			PRIMARY KEY (`id`)
			)
		ENGINE=MyISAM
		DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
		AUTO_INCREMENT=12
		CHECKSUM=0
		ROW_FORMAT=DYNAMIC
		DELAY_KEY_WRITE=0;");
	$m->query("INSERT INTO `".DB_NAME."`.`".DB_PREFIX."allinfo` (`stname`,`usname`,`email`,`bduss`,`bdname`) VALUES ('黑名单用户','示例用户','xxx@xxx.xxx','2VtSU0HUU51gzNUFBNXZKNpT2xjNlBVekVHc3Zv0MEVkb3dV2ZSUzNQMHkwUHBVQVyFBQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADJD01QQGT3RR3239NUQ','戒要怎么戒')");
	$m->query("CREATE TABLE IF NOT EXISTS `".DB_NAME."`.`".DB_PREFIX."fyy_massistant_url` (
			`id`  int(30) NOT NULL AUTO_INCREMENT ,
			`stname` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			`sturl` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
			PRIMARY KEY (`id`)
			)
		ENGINE=MyISAM
		DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
		AUTO_INCREMENT=12
		CHECKSUM=0
		ROW_FORMAT=DYNAMIC
		DELAY_KEY_WRITE=0;");
}

function callback_remove() {/*卸载*/
	option::pdel('fyy_massistant');
	global $m;
	$m->query("DROP TABLE IF EXISTS `".DB_NAME."`.`".DB_PREFIX."allinfo`");
	$m->query("DROP TABLE IF EXISTS `".DB_NAME."`.`".DB_PREFIX."fyy_massistant_url`");
}
?>