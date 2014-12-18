<?php
/*
 * @Created on 2014-6-12
 * @用于测试
 */
 class testsModel extends Model{
 	public function __construct(){
 		parent::__construct();
 		$this->tabName = TAB_PREFIX."columns";   //数据库名
		$this->fieldList=array("colPid","colPath", "picId", "colTitle", "description");
 	}
 	function getColumnList($colPid){
			$sql="SELECT colId, colTitle,description FROM {$this->tabName} WHERE colPid={$colPid} ORDER BY ord";
			$result=$this->db->query($sql);
			$record=array();
			while($row=$this->db->fetch_assoc($result)){
				$record[]=$row;
			}
			return $record;
		}
 }
?>
