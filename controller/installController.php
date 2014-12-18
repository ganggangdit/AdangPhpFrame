<?php
/*
 * @Created on 2014-6-17
 * @Edit By adang
 * @系统初始化安装
 */
class installController extends Controller{
	private $process;
	private $installFrom;
	private $messageList;
	private $app_dir;
	private $installinfo;
	private $sqlFile;
	/**
	 * 初始化成员属性
	 */
	public function __construct(){
		parent::__construct();
		$this->app_dir = dirname(dirname(__FILE__));
		$this->sqlFile = $this->app_dir.'/config/lampcms.sql';  /*数据库脚本*/
		require $this->app_dir."/config/config.inc.php";
		/*载入安装类*/
		$this->process = $this->load('process',false);          //载入adang_frame/lib 目录下my_process类
		$this->messageList = "";
		$this->installinfo = '';
	}
	public function index(){
		if(file_exists($this->app_dir."/install_lock.txt")){
			$data['install_lock']  = '<div class="error">你已经安装过本系统<br>如果需要重新安装请删除文件'.CMS_ROOT.'install_lock.txt</div>';
			$this->_view('install/index',$data);
			exit;
		}
		/*系统安装*/
		else{
			/*获取应用的名字*/
            $app= explode('/',$_SERVER['REQUEST_URI']);
            $data['app_name'] = $app[1];
            /*安装步骤*/
			$step = !empty($_POST['step'])? $_POST['step'] : 1;

			switch($step){
				case 1:
					$data['step'] = $step;
					$this->_view('install/index',$data);
					break;
				case 2:
					$this->messageList = '请在下面的表单中正确填写数据库连接的配置信息';
					$db_info = array(
							"DB_HOST"=>DB_HOST,
							"DB_USER"=>DB_USER,
							"DB_PWD"=>DB_PWD,
							"DB_NAME"=>DB_NAME,
							"TAB_PREFIX"=>TAB_PREFIX,
							"APP_NAME"=>APP_NAME
					);

					$data['info'] = $this->messageList;  //卡片提示信息
					$data['db_info'] = $db_info;
					$data['step'] = $step;
					$this->_view('install/index',$data);
					break;
				case 3:
					if(!$this->validateDbFrom($_POST)){    //数据库配置校验,若是错误则返回到第二步
						$step = 2;
						$data["info"] = $this->messageList;
						$data['db_info'] = $_POST;
						$data['step'] = $step;
						$data['error'] = 'error';          //错误信息样式
						$this->_view('install/index',$data);
					}
					else{
						if($this->configSYS($_POST) != false ){  //$this->process->configSYS($_POST)
							$this->messageList = '请在下面的表单中正确填写管理员账号信息';
							$admin_info = array("ADMIN_USER"=>"adang",
												"ADMIN_PWD"=>"",
												"ADMIN_REPWD"=>"",
												'ADMIN_MAIL'=>''
												);
							$data['info'] = $this->messageList;    //卡片提示信息
							$data['step'] = $step;
							$data['admin_info'] = $admin_info;
							$this->_view("install/index",$data);
						}
						else{										//写入配置文件失败
							$step = 2;
							$data["info"] = "写入配置文件失败!!";
							$data['db_info'] = $_POST;
							$data['step'] = $step;
							$data['error'] = 'error';          //错误信息样式
							$this->_view('install/index',$data);
						}
					}
					break;
				case 4:
					if(!$this->validateAdminFrom($_POST)){
						$step = 3;
						$data['info'] = $this->messageList;
						$data['admin_info'] = $_POST;
						$data['step'] = $step;
						$data['error'] = 'error';
						$this->_view('install/index',$data);
					}
					else{
						if($this->process->createDb($_POST)){
							$installStatus = 'ok';
						}
						else{
							$installStatus = 'not_ok';
						}
						$data['message'] = $this->process->getInstallInfo();
						$data['installStatus'] = $installStatus;
						$data['step'] = $step;
						$this->_view('install/index',$data);

					}
					break;
				case 5:
					if(file_put_contents($this->app_dir."/install_lock.txt","CMS INATALL OK ...")){
						$finish_info = '<script>window.location="'.APP_PATH. '"</script>';
						$data['finish_info'] = $finish_info;
						$data['step'] = $step;
						$this->_view("install/index",$data);
					}
					break;
			}
		}
	}

	public function validateDbFrom($post){
		$result = true;
		$this->messageList = '';
		if(trim($post['DB_HOST'] == ''))
		{
			$this->messageList .='数据库主机名不能为空!!<br>';
			$result = false;
		}
		if(trim(($post['DB_USER'])=="")){
				$this->messageList.="数据库用户名不能为空!!<br>";
				$result=false;
			}
		if(trim(($post['DB_PWD'])=="")){
			$this->messageList.="数据库密码不能为空!!<br>";
			$result=false;
		}
		if(trim(($post['DB_NAME'])=="")){
			$this->messageList.="数据库名称不能为空!!<br>";
			$result=false;
		}
		if(trim(($post['TAB_PREFIX'])=="")){
			$this->messageList.="表名的前缀不能为空!!<br>";
			$result=false;
		}
		if(trim(($post['APP_NAME'])=="")){
			$this->messageList.="网站名称不能为空!!<br>";
			$result=false;
		}
		if(!$result){
			return false;
		}
		if(!@mysql_connect($post['DB_HOST'],$post['DB_USER'],$post['DB_PWD'])) {
			$this->messageList.="数据库连接失败,请检查用户名密码!!<br>";
			$result=false;
		}
		return $result;
	}
	//===============================================
	// 函数: validateAdminFrom($post)
	// 功能: 对输入管理员配置信息的表单进行验证
	// 参数: $post是表单中用户输入的管理员信息的数据数组
	// 返回: true或false
	//===============================================
	function validateAdminFrom($post){
			$result=true;
			if(trim(($post['ADMIN_USER'])=="")){
				$this->messageList.="管理员帐号不能为空!!<br>";
				$result=false;
			}
			if(trim(($post['ADMIN_PWD'])=="")){
				$this->messageList.="管理员密码不能为空!!<br>";
				$result=false;
			}
			if(trim(($post['ADMIN_REPWD'])=="")){
				$this->messageList.="重复输出的密码不能为空!!<br>";
				$result=false;
			}
			if(trim($post['ADMIN_PWD'])!=trim($post['ADMIN_REPWD'])){
				$this->messageList.="两次密码输入不一致!!<br>";
				$result=false;
			}
			if(trim(($post['ADMIN_MAIL'])=="")){
				$this->messageList.="管理员邮箱不能为空!!<br>";
				$result=false;
			}elseif(!preg_match("/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $post['ADMIN_MAIL'])){
				$this->messageList.="不是合法的电子邮箱格式!!<br>";
				$result=false;
			}
			return $result;
	}
	//===============================================
	// 函数: configSYS($config)
	// 功能: 对cms应用配置文件进行重写
	// 参数: $config是安装表单提交信息
	// 返回: true或false
	//===============================================
	public function configSYS($configArray){
		$configText = file_get_contents($this->app_dir."/config/config.inc.php");
		$configArray['CMS_ROOT'] = $this->process->getRoot();                //获取应用完整路径
		$configArray['APP_PATH'] = $this->process->getAppPath();             //应用名称
		unset($configArray['step']);
		foreach($configArray as $key => $val){
			$pattern[] = '/define\(\"'.$key.'\",\s*.*\);/';
			$repContent[] = 'define("'.$key.'", "'.$val.'");';
		}
		$configText = preg_replace($pattern,$repContent,$configText);
		return file_put_contents($this->app_dir."/config/config.inc.php",$configText);
	}
}
?>
