<?php
if (!defined('SYSTEM_ROOT')) { die('FUCK!'); } 
global $m;
/*很多用die，因为没必要再判断别的模式*/
if (isset($_REQUEST['search'])) {/*搜索模式，有bdname或eou*/
	loadhead();
	?>
		<div class="panel panel-primary" style="margin:5% 15% 5% 15%;">
			<div class="panel-heading">
				<h3 class="panel-title">搜索结果</h3>
			</div>   
		<div style="margin:0% 5% 5% 5%;"><br/>
	<?php
		if(!empty($_REQUEST['bdname'])){/*用百度ID*/
			$bdname = addslashes($_REQUEST['bdname']);
			$result = $m->once_fetch_array("SELECT `usname`,`stname` FROM `".DB_NAME."`.`".DB_PREFIX."allinfo` WHERE `bdname`='{$bdname}'");;
			if(!empty($result)) {
				$stname=$result['stname'];
				$usname=$result['usname'];
				$sturl=$m->once_fetch_array("SELECT `sturl` FROM `".DB_NAME."`.`".DB_PREFIX."fyy_massistant_url` WHERE `stname`='{$stname}'");
				$sturl=$sturl['sturl'];
				echo '在 <a href="'.$sturl.'">'.$stname.'</a> 站点找到名为 '.$usname.' 的用户<br/>';
			}
			else { echo '未找到符合条件的用户！<br/>'; }
		}
		elseif(!empty($_REQUEST['eou'])){
			$eou = addslashes($_REQUEST['eou']);
			$result = $m->once_fetch_array("SELECT `stname` FROM `".DB_NAME."`.`".DB_PREFIX."allinfo` WHERE `usname`='{$eou}'");
			if(!empty($result)){/*用云签用户名*/
				$stname=$result['stname'];
				$sturl=$m->once_fetch_array("SELECT `sturl` FROM `".DB_NAME."`.`".DB_PREFIX."fyy_massistant_url` WHERE `stname`='{$stname}'");
				$sturl=$sturl['sturl'];
				echo '在 <a href="'.$sturl.'">'.$stname.'</a> 站点找到名为 '.$eou.' 的用户<br/>';
			}
			else{
				$result = $m->once_fetch_array("SELECT `stname` FROM `".DB_NAME."`.`".DB_PREFIX."allinfo` WHERE `email`='{$eou}'");
				if(!empty($result)){/*用邮箱*/
					$stname=$result['stname'];
					$sturl=$m->once_fetch_array("SELECT `sturl` FROM `".DB_NAME."`.`".DB_PREFIX."fyy_massistant_url` WHERE `stname`='{$stname}'");
					$sturl=$sturl['sturl'];
					echo '在 <a href="'.$sturl.'">'.$stname.'</a> 站点找到邮箱为 '.$eou.' 的用户<br/>';
				}
				else { echo '未找到符合条件的用户！<br/>'; }
			}
		}
		$durl = option::xget('fyy_massistant','durl');
		if(!empty($durl)) { echo '<br/><a href="'."{$durl}".'" class="btn btn-info btn-sm">回到导航页</a>'; }
		die;
	?>
		</div><p>　　插件作者：<a href="http://fyy.l19l.com/">FYY</a> // 程序作者：<a href="http://zhizhe8.net">无名智者</a> & <a href="http://www.longtings.com/">mokeyjay</a></p>
	<?php
	die;
}

$key = option::xget('fyy_massistant','key');
/*key不对直接滚蛋*/
if (!isset($_REQUEST['key']) || $_REQUEST['key'] != $key) { die('fuckyou'); }
/*普通功能*/
if (isset($_REQUEST['bind']) && !empty($_REQUEST['bdname'])){/*绑定模式：不介绍，有stname(站点名，必须有)、usname(云签用户名)、bduss、bdname、email*/
	$bduss = addslashes($_REQUEST['bduss']);
	$bdname = addslashes($_REQUEST['bdname']);
	$find = $m->once_fetch_array("SELECT * FROM `".DB_NAME."`.`".DB_PREFIX."allinfo` WHERE `bdname` = '{$bdname}'");
	if(empty($find)) {
		echo 'ok2';
		$stname = addslashes($_REQUEST['stname']);
		$usname = addslashes($_REQUEST['usname']);
		$email = addslashes($_REQUEST['email']);
		$m->query("INSERT INTO `".DB_NAME."`.`".DB_PREFIX."allinfo` (`stname`,`usname`,`email`,`bduss`,`bdname`) VALUES ('{$stname}','{$usname}','{$email}','{$bduss}','{$bdname}')");
		die;
	}	
	else { die('found'); }
}
elseif (isset($_REQUEST['unbind'])) {/*解绑模式：单个删除，有bduss、usname*/
	echo 'ok3';
	$bduss = addslashes($_REQUEST['bduss']);
	$usname = addslashes($_REQUEST['usname']);
	$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX."allinfo` WHERE `bduss` = '{$bduss}' AND `usname` = '{$usname}'");
	die;
}
elseif (isset($_REQUEST['delus'])) {/*删除模式：从删除用户，有stname、usname*/
	echo 'ok1';
	$stname = addslashes($_REQUEST['stname']);
	$usname = addslashes($_REQUEST['usname']);
	$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX."allinfo` WHERE `stname` = '{$stname}' AND `usname` = '{$usname}'");
	die;
}
elseif (isset($_REQUEST['shangchuan'])) {/*上传确定，有stname*/
	$stname = addslashes($_REQUEST['stname']);
	$sturl = addslashes($_REQUEST['sturl']);
	$result1 = $m->once_fetch_array("SELECT * FROM `".DB_PREFIX."allinfo` WHERE `stname`='{$stname}'");
	$result2 = $m->once_fetch_array("SELECT * FROM `".DB_PREFIX."fyy_massistant_url` WHERE `sturl`='{$sturl}'");
	if(empty($result1) && empty($result2)) { 
		echo 'can';
		$m->query("INSERT INTO `".DB_NAME."`.`".DB_PREFIX."fyy_massistant_url` (`stname`,`sturl`) VALUES ('{$stname}','{$sturl}')");
		die;
	}
	else { die('nocan'); }
}