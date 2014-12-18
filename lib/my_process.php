<?php
	/*==================================================================*/
	/*		文件名:Process_class.php                            */
	/*		概要: 安装处理类，用于改写配置文章创建数据库和数据表*/
	/*		copyright (c)2009 lampteacher@gmail.com             */
	/*==================================================================*/
	class  MyProcess {
		private $configFile;
		private $sqlFile;
		private $info;
		//==========================================
		// 函数: __construct($configFile="../config.inc.php", $sqlFile="lampcms.sql")
		// 功能: 构造方法用于初使化对象的成员属性
		// 参数: configFile是指定需要修改的配置文件，sqlFile是需要安装数据表的SQL语句文件
		// 返回: 无
		//==========================================
		function __construct(){
			$this->configFile= dirname(dirname(__FILE__))."/config/config.inc.php";
			$this->sqlFile= dirname(dirname(__FILE__))."/config/lampcms.sql";
			$this->info="";
		}
		//==========================================
		// 函数: configSYS($configArray)
		// 功能: 用于修改指定配置文件中的内容
		// 参数: configArray需要在配置文件中修改的内容数组
		// 返回: true或false
		//==========================================
		function configSYS($configArray) {
			$configText = file_get_contents($this->configFile);
			$configArray["CMS_ROOT"]=$this->getRoot();
			$configArray["APP_PATH"]=$this->getAppPath();
			foreach($configArray as $key => $val) {
				$pattern[]='/define\(\"'.$key.'\",\s*.+\);/';
				$repContent[]='define("'.$key.'", "'.$val.'");';
			}
			$configText = preg_replace($pattern, $repContent, $configText);
			return file_put_contents($this->configFile, $configText);
		}
		//==========================================
		// 函数: getRoot()
		// 功能: 用于获取系统的根所在的绝对路径
		// 参数: 无
		// 返回: 根所在的绝对路径
		//==========================================
		public function getRoot(){
			$rootPath=dirname($_SERVER["SCRIPT_FILENAME"]);
			return $rootPath."/";

		}
		//==========================================
		// 函数: getAppPath()
		// 功能: 用于获取系统的应用路径
		// 参数: 无
		// 返回: 系统的应用路径
		//==========================================
		public function getAppPath(){
				$rootPath=$this->getRoot();       //获取应用所在路径
				$length_str = strlen($_SERVER['DOCUMENT_ROOT']);      //获取网站根目录总长度
				return substr($rootPath,$length_str);
		}
		//==========================================
		// 函数:  createDb($user)
		// 功能: 用于创建系统所需要的数据库、数表表，以及添加管理员用户和一些分类的默认记录
		// 参数: user是在安装界面中指定的管理员用户信息
		// 返回: true或false
		//==========================================
		function createDb($user){
			$mysqli=new mysqli(DB_HOST, DB_USER, DB_PWD);
			if(mysqli_connect_errno()) {
				$this->info.='<font color="red">连接失败，原因为：'.mysqli_connect_error().'</font>';
				$mysqli=false;
				return false;
			}

			if($mysqli->query('create database if not exists '.DB_NAME)){
				$this->info.='创建数据库<b>'.DB_NAME.'</b>成功！<br>';
			}else{
				$this->info.='<font color="red">创建数据库<b>'.DB_NAME.'</b>失败！<font>';
				$mysqli->close();
				return false;
			}

			if($mysqli->select_db(DB_NAME)){
				$this->info.='数据库<b>'.DB_NAME.'</b>选择成功<br>';
			}else{
				$this->info.='<font color="red">选择数据库<b>'.DB_NAME.'</b>失败！<font>';
				$mysqli->close();
				return false;
			}

			$sql=file_get_contents($this->sqlFile);
			$sql=str_replace("\r", "\n", str_replace(' cms_', ' '.TAB_PREFIX, $sql));
			$num=0;
			foreach(explode(";\n", trim($sql)) as $query) {
				$queries = explode("\n", trim($query));
				foreach($queries as $query) {
					$ret[$num] .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query;
				}
				$num++;
			}
			unset($sql);
			foreach($ret as $query) {
				$query = trim($query);
				if($query) {
					if(substr($query, 0, 12) == 'CREATE TABLE') {
						$name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
						if($mysqli->query($query)){
							$this->info.='建立数据表'.' <b>'.$name.'</b> ... '.'成功！！<br>';
						}else{
							$this->info.='<font color="red">建立数据表'.' '.$name.' ... '.'失败！</font>';
						       	$mysqli->close();
							return false;
						}
					} else {
						if(!$mysqli->query($query)){
							$this->info.='<font color="red">查询语句'.$query.'执行失败！</font>';
							$mysqli->close();
							return false;
						}
					}
				}

			}

			$insert="INSERT INTO ".TAB_PREFIX."user(userName, userPwd, email) VALUES('".$user["ADMIN_USER"]."', '".md5($user["ADMIN_PWD"])."','".$user["ADMIN_MAIL"]."')";

			if($mysqli->query($insert)){
				$this->info.='添加管理员用户<b>'.$user["ADMIN_USER"].'</b>成功！<br>';
			}else{
				$this->info.='<font color="red">添加管理员用户<b>'.$user["ADMIN_USER"].'</b>失败！<font>';
				$mysqli->close();
				return false;
			}

			$mysqli->close();
			return true;
		}
		//==========================================
		// 函数: getInstallInfo()
		// 功能: 用于安装过程中的提示信息
		// 参数: 无
		// 返回: 返回安装过程中的提示信息字符串
		//==========================================
		function getInstallInfo(){
			return $this->info;
		}
	}
?>
