<?php
/*
 * @Created on 2014-8-11
 * @图片处理模型
 */
 class pictureModel extends Model{
	public function __construct(){
 		parent::__construct();
 		$this->tabName = TAB_PREFIX."picture";   //数据库名
		$this->fieldList=array("id","picTitle", "description", "picName", "catId","hasThumb","hasMark");
 	}
 	function getPicPath($id){
		$sql="SELECT picName, description, hasThumb,hasMark FROM {$this->tabName} WHERE id={$id}";
		$result = $this->db->query($sql);
		$pic = $this->db->fetch_assoc($result);
		if($pic['hasMark']==1 || $pic['hasThumb'] == 1){
			$picPath = str_replace(".","_new.",$pic['picName']);
		}else{
			$picPath = $pic['name'];
		}

		if(file_exists(GALLERY_REAL_PATH.$picPath)){
			return  GALLERY_PATH.$picPath;
		}else{
			return false;
		}

 	}
}
?>
