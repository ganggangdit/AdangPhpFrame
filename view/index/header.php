<?php
/*
 * @Created on 2014-7-28
 * @页面头部
 */
?>
<html>
	<head>
		<title><?php echo  $app_name ?></title>
		<meta name="Author" content="adang" />
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<meta name="Keywords" content="php,framwaork" />
		<link rel="stylesheet" type="text/css" href="http://<?php echo $BASE_DIR ?>/themes/default/css/global.css">
		<link rel="stylesheet" type="text/css" href="http://<?php echo $BASE_DIR ?>/themes/default/css/layout.css">
		<link rel="stylesheet" type="text/css" href="http://<?php echo $BASE_DIR ?>/themes/default/css/print.css">
		<script src="http://<?php echo $BASE_DIR ?>/themes/default/javascript/common.js"></script>
	</head>
	<body>
		<div id="content">
			<div id="header">
				<div id="top">
					<div id="top_left">
          						<!--&nbsp;<b> <?php echo isset($session)? $session['uname']:'' ?> 您好!&nbsp;现在是<?php echo date('Y:m:d H:i:s',time()) ?>&nbsp;[<a href="?controller=login.php&action=logout">退出</a>]  </b>-->
						<form action="login.php?action=login" method="post">
							&nbsp;
							      <input type="hidden" name="url" value="	<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>">
							用户名:<input class="inputheight" name="username" type="text" size="10">&nbsp;
							  密码:<input class="inputheight" name="password" type="password" size="10">
							<input type="submit" class="button"  name="loginsubmit" value="登录">&nbsp;
							<a href="?controller=register.php"><span style="color:red">免费注册</span></a>
						</form>
					</div>

					<div id="top_right">
						<form action="search" method="post">  <!--搜索表单-->
							<input type="radio" <?php  echo  $serType =="title" || $serType==''?"checked":'' ?> name="serType" value="title">标题
							<input type="radio" <?php  echo  $serType =="content" ?"checked":'' ?> name="serType" value="content">内容
							<input type="radio" <?php  echo  $serType =="keyword" ?"checked":'' ?> name="serType" value="keyword">关键字
							<input type="text"  name="search" size="15" value="<?php  echo isset($searchValue)?$searchValue:'' ?>" maxlength="255">
							<input type="submit"  class="button"  value="搜索">&nbsp;
						</form>
					</div>
				</div>

				<div id="logo">
					<a href="<?php $appPath ?>"><img border="0" alt="lampBrother_logo" src="<?php echo  $stylePath ?>/images/logo.gif"></a>
				</div>
				<div id="right_box">
					<div id="banner">
   						<a href=""><img width="500" height="70" alt="banner" src="<?php echo  $stylePath ?>/images/banner.gif" border=0></a>
					</div>

					<div id="tool">

						<ul>
							<li><a href="">用户定义</a></li>
							<li><a href="">用户定义</a></li>
							<li><a href="">用户定义</a></li>
							<li><a href="">用户定义</a></li>
						</ul>
					</div>
				</div>
				<div class="nav"> </div>
			</div>
			<div id="menu">
				<ul>
					<li><a href="?controller=column&pid=1">网站首页</a></li><li class="menudiv"> </li>
					<?php foreach($menu as $key => $value){  ?>
						<li><a href="controller=column&action=index&pid=<?php  echo  $value['colId']?>"><?php echo  $value['colTitle'] ?></a></li><li class="menudiv"> </li>
					<?php } ?>

					<li><a href="http://bbs.lampbrother.net" target="_blank">论坛交流</a></li>
				</ul>
			</div>
			<div class="nav"> </div>
			<div id="container">