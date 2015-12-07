<?php
/*
Plugin Name: 云签助手（总站）
Version: 1.3
Plugin URL: http://www.stus8.com/forum.php?mod=viewthread&tid=6531
Description: 多站点的最佳管理工具
Author: FYY
Author Email:fyy@l19l.com
Author URL: http://fyy.l19l.com
For: V3.8+
*/
if (!defined('SYSTEM_ROOT')) { die('FUCK!'); } 

function fyy_massistant_setting() {
	?>
	<li <?php if($_GET['plug'] == 'fyy_massistant') { echo 'class="active"'; } ?> ><a href="index.php?mod=admin:setplug&plug=fyy_massistant"><span class="glyphicon glyphicon-bookmark"></span> 总站管理中心</a></li>
	<?php
}


/*navi_3左侧第二栏; navi_8顶部*/
addAction('admin_plugins','fyy_massistant');/*总站管理中心（插件管理）*/
addAction('navi_3','fyy_massistant_setting');/*总站管理中心*/
addAction('navi_8','fyy_massistant_setting');/*总站管理中心*/