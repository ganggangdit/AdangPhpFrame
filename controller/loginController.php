<?php
/*
 * @Created on 2014-8-12
 * @author by adang
 */
class loginController extends Controller{
	var $app_dir = "";
	public function __construct() {
	        parent::__construct();
	        $this->app_dir = dirname(dirname(__FILE__));
	        require $this->app_dir."/config/config.inc.php";
	}
	function index(){
       $user = $this->model('user');
       $message = '';
       if(!$_SESSION["isLogin"] && $_SESSION["uid"]!=1)
	   {
		if (isset($_POST['action']) && $_POST['action'] == "login") //已经提交表单
		{
			$log= $user->login($_POST['uname'], $_POST['pwd']); //检验管理员登录
			if($log){ //登录成功
				$this->_view("admin/index");
				//$this->_redirect("admin/index");
				exit;
			} else { //登录失败
				$message = "用户名或密码错误, 请重试.";
				$data['className'] = "login-msg";
				$data['message'] = $message;
			}
		}else{       //没有提交表单.显示登录界面
			$data['className'] = "login-msg";
			$data['message'] = $message;
		}
		}else{
			$user->logout();
		}
		$data['app_name'] = APP_NAME;       					   //应用程序名称
		$data["BASE_DIR"] = $_SERVER['SERVER_NAME'].'/'.APP_NAME;  //应用根目录
		$this->_view("login/index",$data);
	}
}
?>
