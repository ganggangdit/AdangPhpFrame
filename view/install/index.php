<html>
	<head>
		<title>LAMP_CMS内容管理系统</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link href="http://<?php echo $_SERVER['SERVER_NAME'].'/'.$app_name; ?>/themes/css/install.css" type="text/css" rel="stylesheet" /><!--引用的时候添加 http-->
	</head>

	<body>
		<?php
		if(isset($install_lock))
		{
			echo $install_lock;
			exit;
		}
		?>
        <div id="main-box">
			<div class="head-dark-box">
				<strong>LAMP_ 1.0.0内容管理系统安装向导</strong>
			</div>

		<!--配置主体-->
		<?php
		if(!isset($step) || isset($step) && $step == "1")  //欢迎安装
		{
		?>
			   <div class="body-box tip-msg">
				  欢迎您使用LAMP_CMS内容管理系统1.0beta，请认真阅读以下安装条款后进行安装
			   </div>
			  <form method="POST" action="http://<?php echo $_SERVER['SERVER_NAME'].'/'.$app_name; ?>/?controller=install" class="white-box">
				<div class="body-box">
					<div class="center red-font">LAMP_CMS 许可协议</div>
					<div class="license">
						<ol>
							<li>LAMP_CMS内容管理系统的所有权归<a href="http://bbs.lampbrother.net"><span class="red-font">adang</span></a>所有,未经允许不得用于任何商业用途.</li>
							<li>用户可以无条件使用并传播本软件的测试版本</li>
						    <li>任何自愿使用测试版本的用户,不承担任何因使用本软件而产生问题的相关责任。</li>
						<ol>
					</div>
				</div>
			<div class="center body-box">
			<input type="hidden" name="step" value="2">
			<input type="submit"  class="button" value="我同意">
			<input type="button" class="button" value="我不同意" onclick="window.close()">
			</div>
			</form>
	<?php } if(isset($step)&& $step == '2'){ ?>
			<div class="body-box <?php echo isset($error)? $error : 'tip-msg' ?> "><?php echo $info ?></div>
			<form method="POST" action="http://<?php echo $_SERVER['SERVER_NAME'].'/'.$app_name; ?>/?controller=install" class="white-box">
			<ul>
				<li class="light-row">
					<span class="col_width">数据库主机名称</span>
					<input type="text" class="text-box" name="DB_HOST" value="<?php echo $db_info['DB_HOST'] ?>">
					数据库服务器地址, 一般为 localhost
				</li>

				<li class="dark-row">
					<span class="col_width">数据库用户名</span>
					<input type="text" class="text-box" name="DB_USER" value="<?php echo $db_info['DB_USER'] ?>" >
					数据库账号用户名
				</li>

				<li class="light-row">
					<span class="col_width">数据库密码</span>
					<input type="password" class="text-box" name="DB_PWD" value="<?php echo $db_info['DB_PWD'] ?>">
					数据库账号密码
				</li>

				<li class="dark-row">
					<span class="col_width">数据库名称</span>
					<input type="text" class="text-box" name="DB_NAME" value="<?php echo $db_info['DB_NAME'] ?>">
					数据库名称
				</li>

				<li class="light-row">
					<span class="col_width">表名前缀</span>
					<input type="text" class="text-box" name="TAB_PREFIX" value="<?php echo $db_info['TAB_PREFIX'] ?>">
					同一数据库安装多CMS时可改变默认
				</li>

				<li class="dark-row">
					<span class="col_width">网站名称</span>
					<input type="text" class="text-box" name="APP_NAME" value="<?php echo $db_info['APP_NAME'] ?>">
					用于在标题栏上显示
				</li>
			</ul>
			<div class="center body-box">
				<input type="hidden" name="step" value="3">
				<input type="button" class="button" value="上一步" onclick="history.back()">
				<input type="submit" class="button" value="下一步" >
			</div>
			</form>
    <?php
		}
	 if(isset($step) && $step =='3'){
	?>
		<div class="body-box <?php echo isset($error)? $error : 'tip-msg' ?> "><?php echo $info ?></div>
		 <form method="POST" action="http://<?php echo $_SERVER['SERVER_NAME'].'/'.$app_name; ?>/?controller=install" class="white-box">
			<ul>
				<li class="light-row liimg">
					<span class="col_width">管理员帐号</span>
					<input type="text" class="text-box" name="ADMIN_USER" value="<?php echo $admin_info['ADMIN_USER'] ?>">
				</li>
				<li class="dark-row liimg">
					<span class="col_width">管理员密码</span>
					<input type="password" class="text-box" name="ADMIN_PWD" value="<?php echo $admin_info['ADMIN_PWD'] ?>">
				</li>
				<li class="light-row liimg">
					<span class="col_width">重复密码</span>
					<input type="password" class="text-box" name="ADMIN_REPWD" value="<?php echo $admin_info['ADMIN_REPWD'] ?>">
				</li>
				<li class="dark-row liimg">
					<span class="col_width">管理员邮箱</span>
					<input type="text" class="text-box" name="ADMIN_MAIL" value="<?php echo $admin_info['ADMIN_MAIL'] ?>">
				</li>
			</ul>
			<div class="center body-box">
				<input type="hidden" name="step" value="4">
				<input type="button" class="button" value="上一步" onclick="history.back()">
				<input type="submit" class="button" value="下一步" >
			</div>
			</form>
	<?php } ?>
	<?php   if(isset($step) && $step =='4'){ ?>
		<div class="body-box tip-msg">
			安装信息显示！！
			</div>
			<div class="body-box">
				<div class="license">
					<?php  echo $message; ?>
				</div>
				</div>
			<div class="center body-box">
			<form method="POST" action="http://<?php echo $_SERVER['SERVER_NAME'].'/'.$app_name; ?>/?controller=install">
			<?php  if($installStatus=='ok'){ ?>
				<input type="hidden" name="step" value="5">
				<input type="submit" class="button" value="恭喜您安装成功，点击进入首页" >
			<?php
				}else{
			?>
				<input type="button" class="button" value="安装失败，请返回" onclick="history.back()">
			<?php
				}
			?>
			</form>
			</div>
	<?php } if(isset($step) && $step == "5") { echo $finish_info ;}?>

	<!--配置主体-->
	</div>
	</body>
</html>
