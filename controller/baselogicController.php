<?php
/*
 * @Created on 2014-7-28
 * @created by adang
 */

 class BaselogicController extends MyDb{
		protected $tabName;		//表的名称
		protected $fieldList;
		protected $messList;
		//==========================================
		// 函数: add($postList)
		// 功能: 添加
		// 参数: $postList 提交的变量列表
		// 返回: 刚插入的自增ID
		//==========================================
		function add($postList) {
			$fieldList='';
			$value='';
			foreach ($postList as $k=>$v) {
				if(in_array($k, $this->fieldList)){
					$fieldList.=$k.",";
					if (!get_magic_quotes_gpc())
						$value .= "'".addslashes($v)."',";
					else
						$value .= "'".$v."',";
				}
			}

			$fieldList=rtrim($fieldList, ",");
			$value=rtrim($value, ",");

			$sql = "INSERT INTO {$this->tabName} (".$fieldList.") VALUES(".$value.")";
			$result=$this->mysqli->query($sql);

			if($result && $this->mysqli->affected_rows >0 )
				return $this->mysqli->insert_id;
			else
				return false;
		}


		//==========================================
		// 函数: mod($postList)
		// 功能: 修改表数据
		// 参数: $postList 提交的变量列表
		//==========================================
		function mod($postList) {
			$id=$postList["id"];
			unset($postList["id"]);
			$value='';
			foreach ($postList as $k=>$v) {
				if(in_array($k, $this->fieldList)){
					if (!get_magic_quotes_gpc())
						$value .= $k." = '".addslashes($v)."',";
					else
						$value .= $k." = '".$v."',";
				}
			}
			$value=rtrim($value, ",");
			$sql = "UPDATE {$this->tabName} SET {$value} WHERE id={$id}";
			return $this->mysqli->query($sql);
		}

		//==========================================
		// 函数: del($id)
		// 功能: 删除
		// 参数: $id 编号或ID列表数组
		// 返回: 0 失败 成功为删除的记录数
		//==========================================
		function del($id) {
			if(is_array($id))
				$tmp = "IN (" . join(",", $id) . ")";
			else
				$tmp = "= $id";

			$sql = "DELETE FROM {$this->tabName} WHERE id " . $tmp ;
			return $this->mysqli->query($sql);

		}


		function get($id) {
			$sql = "SELECT * FROM {$this->tabName} WHERE id ={$id}";

			$result=$this->mysqli->query($sql);

			if($result && $result->num_rows ==1){
				return $result->fetch_assoc();
			}else{
				return false;
			}

		}
		function getMessList(){
			$message="";
			if(!empty($this->messList)){
				foreach($this->messList as $value){
					$message.=$value."<br>";
				}
			}
			return $message;
		}
	}
?>
