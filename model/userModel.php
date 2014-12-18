<?php
/*
 * @Created on 2014-8-12
 * @用户模型
 */
 class userModel extends Model{
 	public function __construct(){
 		parent::__construct();
 		$this->tabName = TAB_PREFIX."user";   //数据库名
 		$this->fieldList=array("userName", "userPwd", "email","safequestion", "safeanswer", "sex", "regTime");
 	}
 	function login($uname,$pwd,$admin =null){
 		if(empty($admin)){
 			$sql = "select id from {$this->tabName} where userName='$uname' and userPwd = MD5('{$pwd}')";
 		}else if($amdin = "admin"){
 			$sql = "select id from {$this->tabName} where userName = '$uname' and userPwd = MD5('{$pwd}') and id=1 ";
 		}
 		$result = $this->db->query($sql);
 		if($result&&$this->db->db_num_rows($result)>0){
 			$data = $this->db->fetch_assoc($result);
 			$_SESSION['isLogin'] = true;
 			$_SESSION['uid'] = $data['id'];
 			$_SESSION['uname'] = $uname;
 			return 1;
 		}else{
 			return 0;
 		}
 	}
 	function logout(){
 		$_SESSION = array();
 		if(isset($_COOKIE[session_name()])){
 			setcookie(session_name(), '',time()-4200,'/');
 		}
 		session_destroy();
 	}
 }
?>
