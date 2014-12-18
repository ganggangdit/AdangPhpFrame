<?php
/*
 * @Created on 2014-7-29
 * @coloun操作模型
 */
 class columnsModel extends Model{
 	public function __construct(){
 		parent::__construct();
 		$this->tabName = TAB_PREFIX."columns";   //数据库名
		$this->fieldList=array("colPid","colPath", "picId", "colTitle", "description");
		$this->pic = $this->model('picture');
		$this->art = $this->model('article');
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
	function getAllColumn($colPid){
			$sql="SELECT colId, colPid, colTitle, picId, description FROM {$this->tabName} WHERE colPid={$colPid} ORDER BY ord";
			$result=$this->db->query($sql);
			$record=array();
			while($row=$this->db->fetch_assoc($result)){
				$row["picId"]=$this->pic->getPicPath($row["picId"]);
				$row["subCol"]=$this->getColumnList($row["colId"]);
				$row["art"]=$this->art->getAuditArts($row["colId"]);
				$record[]=$row;
			}
			return $record;
		}
 }
?>
