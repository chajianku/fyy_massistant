<?php
if (!defined('SYSTEM_ROOT')) { die('Insufficient Permissions'); } 
$s = option::pget('fyy_massistant');
	if(empty($s['key']) && !isset($_GET['begin'])){
		?>
			<div class="jumbotron">
				<h1>Hello, master!</h1>
				<h2>你的云签助手（总站）已经安装完毕</h2>
				<br/>
				<p>由于本插件较为复杂，需要您先阅读一下<a href="http://www.stus8.com/forum.php?mod=viewthread&tid=6531" target="_blank">注意事项</a>（第二页）</p>
				<p>仔细的阅读之后，相信您对这款插件…</p>
				<p>还是不怎么了解</p>
				<br/>
				<p>还请您在使用中慢慢探索……</p>
				<p><a class="btn btn-primary btn-lg" href="index.php?mod=admin:setplug&plug=fyy_massistant&begin" role="button">开始设置</a></p>
			</div>
		<?php
		die;
	}
?>

<ul class="nav nav-tabs" role="tablist">
	<li<?php if(isset($_REQUEST['plug']) && $_REQUEST['plug']=="fyy_massistant" && $_REQUEST['p']==''){echo ' class="active"';}?>><a href="index.php?mod=admin:setplug&plug=fyy_massistant">设置</a></li>
	<li<?php if(isset($_REQUEST['p']) && $_REQUEST['p']=='2'){echo ' class="active"';}?>><a href="index.php?mod=admin:setplug&plug=fyy_massistant&p=2">黑名单</a></li>
	<li<?php if(isset($_REQUEST['p']) && $_REQUEST['p']=='3'){echo ' class="active"';}?>><a href="index.php?mod=admin:setplug&plug=fyy_massistant&p=3">删除用户</a></li>
</ul><br/>

<?php
if(isset($_REQUEST['plug']) && $_REQUEST['plug']=="fyy_massistant" && $_REQUEST['p']==''){
	if (isset($_GET['ok'])) { echo '<div class="alert alert-success">设置保存成功</div>'; }
		elseif (isset($_GET['begin'])) { echo '</br><div class="alert alert-warning"><h4>提示：</h4>使用本插件必须把设置填写完整、无误</br><strong>key：</strong>您不同站点间联系的“密码”，所有站点填写的key一定要相同<strong><br/>导航页地址：</strong>这个是用户挑选站点的地址，比如<a href="http://www.tbsign.cn/" target="_blank">http://www.tbsign.cn/</a>这就是一个导航页。没有可留空</div><div class="alert alert-info">您的分站开启密码为：assistant，请记住它，在您安装云签助手（分站）时能用到它</div>'; }
?>
	<h3>总站设置中心</h3>
	注意：管理员要删除某绑定，必须使用总站管理中心的删除用户功能！<br/>
	　<font color="red">不允许从数据库或其他插件删除绑定！</font></br>
	<form action="setting.php?mod=plugin:fyy_massistant" method="post">
		</br>基础设置
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:30%"></th>
					<th style="width:70%"></th>
				</tr>
			</thead>
			<tbody>
			<!--
				<tr>
					<td>总开关</td>
					<td><input type="checkbox" name="on" <?php if($s['on'] == 1) echo 'checked="checked"'; ?> value='1'><?php if($s['on'] == 1) echo ' 总开关目前已开启'; else echo ' <font color="red"><span class="glyphicon glyphicon-warning-sign"></span> <b>警告：</b></font><b>本插件目前处于停用状态，如果某一分站为开启，将导致无法该站绑定</b>';?></td>
				</tr>
			-->
				<tr>
					<td>Key<br/>支持任意长度的数字和字母</td>
					<td><input type="text" value="<?php echo $s['key'] ?>" name="key" class="form-control" required></td>
				</tr>
				<tr>
					<td>导航页地址<br/></td>
					<td><input type="url" value="<?php echo $s['durl'] ?>" name="durl" class="form-control" /></td>
				</tr>
			</tbody>
		</table>
		</br><button type="submit" class="btn btn-success">保存设定</button>
	</form>
<?php
}
elseif(isset($_REQUEST['p']) && $_REQUEST['p']=='2') {
	global $m;
	/*自己玩SQL注入我可管不着。。*/
	if (isset($_GET['addblack'])) {
		$bdname = $_REQUEST['bdname'];
		$stname=$m->once_fetch_array("SELECT `stname` FROM `".DB_NAME."`.`".DB_PREFIX."allinfo` WHERE `bdname`='{$bdname}'");
		$stname=$stname['stname'];
		if(!empty($stname)){/*添加前先删除*/
			$key=option::xget('fyy_massistant','key');
			$sturl=$m->once_fetch_array("SELECT `sturl` FROM `".DB_NAME."`.`".DB_PREFIX."fyy_massistant_url` WHERE `stname`='{$stname}'");
			$sturl=$sturl['sturl'];
			$post = new wcurl("{$sturl}?pub_plugin=fyy_assistant&del&key={$key}&bdname={$bdname}");
			$a = $post->exec();
		}
		$m->query("INSERT INTO `".DB_NAME."`.`".DB_PREFIX."allinfo` (`stname`,`usname`,`bduss`,`bdname`) VALUES ('黑名单用户','','','{$bdname}')");
	}
	elseif (isset($_GET['delblack'])) {
		$bdname = $_REQUEST['bdname'];
		$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX."allinfo` WHERE `bdname` = '{$bdname}'");
	}
?>
	<h4>黑名单用户：</h4>
	<?php
	$blacks = $m->query("SELECT `bdname` FROM `".DB_NAME."`.`".DB_PREFIX."allinfo` WHERE `stname` = '黑名单用户'");
	$allrow= $m->num_rows($blacks);
	$onrow = 0;
	while ( $onrow < $allrow ) {
		$bdname=$m->fetch_array($blacks);
		$bdname=$bdname['bdname'];
		$onrow ++;
		echo "{$bdname}".'　<a href="'.SYSTEM_URL.'index.php?mod=admin:setplug&plug=fyy_massistant&p=2&delblack&bdname='."{$bdname}".'" class="btn btn-default btn-xs">删除</a><br/>';
	}
?>
	<br/>
	<form name="form" method="post" action="<?php echo SYSTEM_URL; ?>index.php?mod=admin:setplug&plug=fyy_massistant&p=2&addblack&">
		<h4>添加黑名单</h4>
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:30%"></th>
					<th style="width:70%"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>百度ID<br/>如 戒要怎么戒</td>
					<td><input type="text" class="form-control" value='' name='bdname' id='bdname' placeholder="百度ID"/></td>
				</tr>
			</tbody>
		</table>
		<div class="btn-group" role="group" style="width:100%">
			<button type="submit" class="btn btn-success">加入黑名单</button>
		</div>
	</form>
<?php
}
elseif(isset($_REQUEST['p']) && $_REQUEST['p']=='3') {
	if (isset($_REQUEST['del1'])) {
		global $m;
		$key=option::xget('fyy_massistant','key');
		$bdname = $_REQUEST['bdname'];
		$stname=$m->once_fetch_array("SELECT `stname` FROM `".DB_NAME."`.`".DB_PREFIX."allinfo` WHERE `bdname`='{$bdname}'");
		$stname=$stname['stname'];
		if(!empty($stname)){/*如果在allinfo搜索到了对应用户*/
			$sturl=$m->once_fetch_array("SELECT `sturl` FROM `".DB_NAME."`.`".DB_PREFIX."fyy_massistant_url` WHERE `stname`='{$stname}'");
			$sturl=$sturl['sturl'];
			$post = new wcurl("{$sturl}?pub_plugin=fyy_assistant&del&key={$key}&bdname={$bdname}");
			$a = $post->exec();
			if($a == 'done1') {
				$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX."allinfo` WHERE `bdname` = '{$bdname}'");
				echo '<div class="alert alert-success">删除成功！</div>';
			}
			elseif($a == '4041') { echo '<div class="alert alert-danger">失败：无此用户！</div>'; }
			else { echo '<div class="alert alert-danger">失败：与该站点连接失败！</div>'; }
		}
		else { echo '<div class="alert alert-danger">失败：无此用户！</div>'; }
	}
	elseif(isset($_REQUEST['del2'])){
		global $m;
		$key=option::xget('fyy_massistant','key');
		$usname = $_REQUEST['usname'];
		$sturl = $_REQUEST['sturl'];
		$post = new wcurl("{$sturl}?pub_plugin=fyy_assistant&del&key={$key}&usname={$usname}");
		$a = $post->exec();
		if($a=='done2'){
			$stname=$m->once_fetch_array("SELECT `stname` FROM `".DB_NAME."`.`".DB_PREFIX."fyy_massistant_url` WHERE `sturl`='{$sturl}'");
			$stname=$stname['stname'];
			$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX."allinfo` WHERE `usname` = '{$usname}' AND `stname` = '{$stname}'");
			echo '<div class="alert alert-success">删除成功！</div>';
		}
		elseif($a == '4042') { echo '<div class="alert alert-danger">失败：无此用户！</div>'; }
		else { echo '<div class="alert alert-danger">失败：与该站点连接失败！</div>'; }
	}
	elseif(isset($_GET['del4'])){
		if(!empty($_REQUEST['sleep'])) { $sleep = $_REQUEST['sleep']; }
		else { $sleep=0.15; }
		global $m;
		$key=option::xget('fyy_massistant','key');
		$result = $m->query("SELECT `bduss`,`bdname` FROM `".DB_NAME."`.`".DB_PREFIX."allinfo`");
		$allrow = $result->num_rows;
		$die = 0;
		$onrow = 0;
		while($onrow < $allrow) {
			$thresult=$m->fetch_array($result);
			$bduss=$thresult['bduss'];
			$bdid=getBaiduId($bduss);
			sleep($sleep);
			if(empty($bdid)){
				$stname=$m->once_fetch_array("SELECT `stname` FROM `".DB_NAME."`.`".DB_PREFIX."allinfo` WHERE `bduss`='{$bduss}'");
				$stname=$stname['stname'];
				$sturl=$m->once_fetch_array("SELECT `sturl` FROM `".DB_NAME."`.`".DB_PREFIX."fyy_massistant_url` WHERE `stname`='{$stname}'");
				$sturl=$sturl['sturl'];
				$bdname=$thresult['bdname'];
				$post = new wcurl("{$sturl}?pub_plugin=fyy_assistant&del&key={$key}&bdname={$bdname}");
				$a = $post->exec();
				$die++;
				$m->query("DELETE FROM `".DB_NAME."`.`".DB_PREFIX."allinfo` WHERE `bduss` = '{$bduss}'");
				sleep($sleep);
			}
			$onrow++;
		}
		echo '</br><div class="alert alert-success">为您检测了'."{$allrow}条绑定信息，其中有{$die}条失效，已经删除</div>";
	}
?>
	<h3>删除用户</h3></br>
	1.<font color="red">严禁</font>使用非本页提供的方式删除用户</br>
	2.您可以先使用<a href="<?php echo SYSTEM_URL; ?>index.php?pub_plugin=fyy_assistant">忘记站点</a>功能来查找用户</br></br>
			
			<form name="form" method="post" action="<?php echo SYSTEM_URL; ?>index.php?mod=admin:setplug&plug=fyy_massistant&p=3&del1&" onsubmit="return confirm('此操作不可逆，您确定要执行吗？');">
				方式一：根据百度ID：删除对这个百度ID的绑定</br>
				<table class="table table-striped">
					<thead>
						<tr>
							<th style="width:30%"></th>
							<th style="width:70%"></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>百度ID<br/></td>
							<td><input type="text" class="form-control" name="bdname" id="bdname" required/></td>
						</tr>
					</tbody>
				</table>
				<button type="submit" class="btn btn-warning">删除</button>
			</form></br></br>

			<form name="form" method="post" action="<?php echo SYSTEM_URL; ?>index.php?mod=admin:setplug&plug=fyy_massistant&p=3&del2&" onsubmit="return confirm('此操作不可逆，您确定要执行吗？');">
				方式二：根据用户名和站点地址：删除这个用户的所有绑定</br>
				<table class="table table-striped">
					<thead>
						<tr>
							<th style="width:30%"></th>
							<th style="width:70%"></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>用户名<br/></td>
							<td><input type="text" class="form-control" name="usname" id="usname" required/></td>
						</tr>
						<tr>
							<td>站点地址<br/></td>
							<td><input type="text" class="form-control" name="sturl" id="sturl" required/></td>
						</tr>
					</tbody>
				</table>
				<button type="submit" class="btn btn-warning">删除</button>
			</form></br></br>

			方式三：前往 <a href="index.php?mod=admin:users">用户管理</a> 删除用户，将自动同步到总站</br></br></br>

			方式四：检查全部站点BDUSS，并删除失效的绑定</br>
			<li>该行为比较消耗资源，建议每周执行一次即可</li>
			<form action="index.php?mod=admin:setplug&plug=fyy_massistant&p=3&del4" method="post">
				<table class="table table-striped">
					<thead>
						<tr>
							<th style="width:30%"></th>
							<th style="width:70%"></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>循环间隔时间(s)<br/>根据空间好坏来设置</td>
							<td><input type="number" max=0.5 min=0.005 step=0.005 value=0.15 name="sleep" class="form-control" required/></td>
						</tr>
					</tbody>
				</table>
				<br/><button type="submit" class="btn btn-primary">检查并删除失效</button>
			</form>
<?php
}
?>