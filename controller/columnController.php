<?php
/*
 * @Created on 2014-7-28
 * @文章栏目分类管理类
 */


 class columnsController extends BaselogicController {
		private $pic;          //图片对象
		private $art;          //文章对象

		function __construct($showError=TRUE){
			parent:: __construct($showError);
			$this->tabName = TAB_PREFIX."columns";
			$this->fieldList=array("colPid","colPath", "picId", "colTitle", "description");
			$this->pic=new pictureController();
			$this->art=new Article();
		}
		//当前位置
		function getLocation($path){
			$sql="SELECT colId, colTitle, description FROM {$this->tabName} WHERE colId in (".str_replace("-", ",", $path).")";
			$result=$this->mysqli->query($sql);
			$record=array();
			while($row=$result->fetch_assoc()){
				$record[]=$row;
			}
			return $record;
		}

		function getTree(){
			$sql="SELECT concat(colPath,'-',colId) AS absPath, colId,colPid,colPath, colTitle, description,ord FROM {$this->tabName} ORDER BY absPath, colId";
			$result=$this->mysqli->query($sql);
			$record=array();
			while($row=$result->fetch_assoc()){
				$record[]=$row;
			}
			return $record;
		}

		function getColumnList($colPid){
			$sql="SELECT colId, colTitle,description FROM {$this->tabName} WHERE colPid={$colPid} ORDER BY ord";
			$result=$this->mysqli->query($sql);
			$record=array();
			while($row=$result->fetch_assoc()){
				$record[]=$row;
			}
			return $record;
		}

		function getSubColumn($colPid=1){
			$sql="SELECT colId, colTitle, description FROM {$this->tabName} WHERE colPid={$colPid} ORDER BY ord";
			$result=$this->mysqli->query($sql);
			$record=array();
			while($row=$result->fetch_assoc()){
				$row["subCol"]=$this->getColumnList($row["colId"]);
				$record[]=$row;
			}
			return $record;
		}

		function getColumn($colId, $offset, $num){
			$sql="SELECT colId, colPath, colTitle, picId, description FROM {$this->tabName} WHERE colId={$colId}";
			$result=$this->mysqli->query($sql);
			$record=$result->fetch_assoc();
			$record["picId"]=$this->pic->getPicPath($record["picId"]);
			$record["art"]=$this->art->getAuditArts($record["colId"], $offset, $num);
			return $record;
		}

		function getAllColumn($colPid){
			$sql="SELECT colId, colPid, colTitle, picId, description FROM {$this->tabName} WHERE colPid={$colPid} ORDER BY ord";
			$result=$this->mysqli->query($sql);
			$record=array();
			while($row=$result->fetch_assoc()){
				$row["picId"]=$this->pic->getPicPath($row["picId"]);
				$row["subCol"]=$this->getColumnList($row["colId"]);
				$row["art"]=$this->art->getAuditArts($row["colId"]);
				$record[]=$row;
			}
			return $record;
		}

		function buildSelect($name, $default=null, $attrArray=array()) {
			$option = $this->getTree();

			if (!is_array($option) || empty($option))
				exit("buildSelect error:the option is not an array or the array is empty");

			$htmlStr = '<select class="text-box" name="'.$name.'" id="'.$name.'"';
			$attrStr = ' ';
			if(!empty($attrArray) && is_array($attrArray)) {
				foreach($attrArray as $key => $value) {
					$attrStr.="$key=\"$value\"";
				}
			}
			$htmlStr .= $attrStr . '>';

			foreach($option as $key => $value) {
				if ($value['colPid'] == 0) {
					$colTitle = '≮'.$value['colTitle'].'≯';
					$htmlStr .= '<option value="'.$value['colId'].'">'.$colTitle.'</option>';

				} elseif ($value['colId'] == $default) {
					$colTitle = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',count(explode('-',$value['colPath']))-1) . '→&nbsp;' .  $value['colTitle'];
					$htmlStr .= '<option value="'.$value['colId'].'" selected>'.$colTitle.'</option>';
				} else {
					$colTitle = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',count(explode('-',$value['colPath']))-1) . '→&nbsp;' . $value['colTitle'];
					$htmlStr .= '<option value="'.$value['colId'].'">'.$colTitle.'</option>';
				}
			}
			$htmlStr .= '</select>';

			return $htmlStr;
		}

		function validateForm(){
			$result=true;
			if(!Validate::required($_POST['colTitle'])) {
				$this->messList[] = "栏目名称不能为空.";
				$result=false;
			}
			if(Validate::match($_POST['colTitle'], "|[\\\/\'\"]|")) {
				$this->messList[] = "栏目名称含有非法字符, 不能包含\\ / ' \"等字符.";
				$result=false;
			}
			if(!Validate::noZero($_POST['picId'])) {
				$this->messList[] = "请从相册中为该栏目选择一张图片.";
				$result=false;
			}
			if(!Validate::checkLength($_POST['description'], 200)) {
				$this->messList[] = "栏目描述不能超过100个汉字.";
				$result=false;
			}
			return  $result;
		}

		private function getAbsPath($colPid){
			$sql="SELECT colPath FROM {$this->tabName} WHERE colId='".$colPid."'";
			$result=$this->mysqli->query($sql);
			$data=$result->fetch_assoc();
			return $data["colPath"];
		}

		function getCatPath($colPid) {
			$absPath=$this->getAbsPath($colPid);
			return $absPath.'-'.$colPid;

		}

		function columnAdd($post) {
			$post["colPath"]=$this->getCatPath($post['colPid']);
			if($this->add($post)){
				$this->messList[] = "栏目添加成功.";
				return true;
			}else{
				$this->messList[] = "栏目添加失败.";
				return false;
			}
		}

		function parseTree() {
			$option = $this->getTree();
			$htmlStr='';
			$i=0;
			foreach($option as $value) {
				if($i++%2==0)
					$class='light-row';
				else
					$class='dark-row';

				if($value["colPid"]!=0) {
					$htmlStr .= '<li class='.$class.'>';
					$colTitle = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',count(explode('-',$value['colPath']))-1);
					$colTitle .= '<input style="height:16px" type="text" size="2" maxlength="3" name="colOrder[]" value="'.$value["ord"].'"><strong>&nbsp;<a href="main.php?action=editArticle&catId='.$value["colId"].'">'.$value['colTitle'].'</a></strong>';
					$htmlStr .= '<input type="hidden" name="colIds[]" value="'.$value["colId"].'">';
					$htmlStr .= '<span class="col_width">'.$colTitle.'</span>';
					$htmlStr .= '<span class="col_width">'.$value["description"].'&nbsp;</span>';
					if($value["colPid"]!=0){
						$htmlStr .= '<span class="col_width">';
						$htmlStr .= '【<a href="main.php?action=addCat&edit=mod&colId='.$value["colId"].'">修改</a>】';
						$htmlStr .= '【<a href="main.php?action=editCat&edit=del&colId='.$value["colId"].'" onclick=\'return confirm("确定要删除栏目'.$value['colTitle'].'及子分类吗？")\'>删除</a>】';
						$htmlStr .='</span>';
					}
				}
				$htmlStr .= '</li>';
			}
			return $htmlStr;
		}

		function modOrder($ords, $colIds){
			$stmt=$this->mysqli->prepare("UPDATE {$this->tabName} SET ord=? WHERE colId=?");
			$stmt->bind_param('ii', $ord, $colId);

			$affected_rows=0;
			for($i=0; $i<count($ords); $i++){
				$colId=$colIds[$i];
				$ord=$ords[$i];
				$stmt->execute();
				$affected_rows+=$stmt->affected_rows;
			}
			if($affected_rows > 0) {
				$this->messList[] = "栏目顺序修改成功.";
				return true;
			}else{
				$this->messList[] = "没有需要修改的栏目顺序.";
				return false;
			}
		}

		function remove($colId){
			$colPath=$this->getCatPath($colId);
			$sql = "DELETE FROM {$this->tabName} WHERE colId = '".$colId."' OR colPath LIKE '".$colPath."%'";


			$res=$this->mysqli->query("SELECT colId  FROM {$this->tabName} WHERE colId = '".$colId."' OR colPath LIKE '".$colPath."%'");
			while($colid=$res->fetch_assoc()){
				$colids[]=$colid["colId"];
			}
			$result=$this->mysqli->query($sql);
			if($result && $this->mysqli->affected_rows >0 ){
				if($this->art->delArticleByCid($colids)){
					$this->messList[] = "栏目下的文章删除成功.";
				}
				$this->messList[] = "栏目删除成功.";
				return true;
			}else{
				$this->messList[] = "栏目删除失败.";
				return false;
			}
		}

		function getNode($colId){
			$sql="SELECT colId,colPid, colTitle,picId, description,ord FROM {$this->tabName} WHERE colId='".$colId."'";
			$result=$this->mysqli->query($sql);

			if($result && $result->num_rows >0)
				return $result->fetch_assoc();
			else
				return false;

		}




		private function isSelfNode($colPid,$colId){
			$absPath=$this->getAbsPath($colPid);

			if(in_array($colId, explode("-", $absPath))){
				return true;
			}else{
				return false;
			}
		}

		function columnMod($post,$colId) {
			if($this->isSelfNode($post["colPid"],$colId)){
				$this->messList[] = "不能将[".$post["colTitle"]."]移动到自己的子类别中.";
				$this->messList[] = "栏目修改失败.";
				return false;
			}

			$post["colPath"]=$this->getCatPath($post['colPid']);
			$sql = "UPDATE {$this->tabName} set colPid='".$post["colPid"]."',colPath='".$post["colPath"]."', colTitle='".$post["colTitle"]."',picId='".$post["picId"]."' ,description='".$post["description"]."' WHERE colId='".$colId."'";

			$result=$this->mysqli->query($sql);
			if($result && $this->mysqli->affected_rows >0 ){
				$this->moveTo($colId, $post["colPath"]);
				$this->messList[] = "栏目修改成功.";
				return true;
			}else{
				$this->messList[] = "栏目修改失败.";
				return false;
			}
		}

		private function moveTo($colId,$colPath){
			$select="SELECT colId,colTitle FROM {$this->tabName} WHERE colPid='".$colId."'";
			$result=$this->mysqli->query($select);
			if($result && $result->num_rows>0){
				$colPath=$colPath.'-'.$colId;
				while($data=$result->fetch_assoc()){
					$update="UPDATE {$this->tabName} set colPath='".$colPath."' WHERE colId='".$data["colId"]."'";
					if($this->mysqli->query($update)){
						$this->moveTo($data["colId"], $colPath);
						$this->messList[] = "栏目子类[".$data["colTitle"]."]移动成功.";
					}else{
						$this->messList[] = "栏目子类移动失败.";
					}
				}
			}
		}
	}
?>
