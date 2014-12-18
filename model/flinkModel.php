<?php
/*
 * @Created on 2014-8-12
 * @link链接模型
 */
 class flinkModel extends Model{
 	public function __construct(){
 		parent:: __construct();
		$this->tabName = TAB_PREFIX."flink";
		$this->fieldList=array("webName", "url", "logo", "email","dtime", "msg", "list", "ord");
 	}
 	function getLinks(){
 		$sql = "SELECT id, webName, url, logo, list FROM {$this->tabName} ORDER BY ord";
 		$result = $this->db->query($sql);
 		while($row = $this->db->fetch_assoc($result)){
 			$data[] = $row;
 		}
 		return $data;
 	}
 }
?>
