<?php
/*
 * @Created on 2014-7-28
 * @文章管理类.
 */
 class articleController extends BaselogicController {
		private $comment;

		//==========================================
		// 函数:  __construct($showError=TRUE)
		// 功能: 初使化对象成员
		// 参数: $showError用户设置是否显示错误报告提示
		// 返回: 无
		//==========================================
		function __construct($showError=TRUE){
			parent:: __construct($showError);
			$this->tabName = TAB_PREFIX."article";
			$this->comment=new Comments();
			$this->fieldList=Array("title","summary","postTime","author","comeFrom","views", "content","keyword","catId","audit","recommend");
		}

		//==========================================
		// 函数: addArticle($post)
		// 功能: 用于向数据库中添加文章
		// 参数: post是用户在表单中提交的文章全部内容数组
		// 返回:true或false
		//==========================================
		function addArticle($post) {
			$post["content"]=stripslashes($post["content"]);
			if($this->add($post)){
				$this->messList[] = "文档添加成功.";
				return true;
			}else{
				$this->messList[] = "文档添加失败.";
				return false;
			}
		}
		//==========================================
		// 函数:  modArticle($postList)
		// 功能: 用户修改指定的文章
		// 参数: postList是用户修改文章表单中的内容数组
		// 返回: true或false
		//==========================================
		function modArticle($postList){
			if($this->mod($postList)){
				$this->messList[] = "文档修改成功.";
				return true;
			}else{
				$this->messList[] = "文档修改失败.";
				return false;
			}
		}
		//==========================================
		// 函数: delArticle($id)
		// 功能: 用于删除指定的文章
		// 参数: id是指定被删除的文章ID
		// 返回: true或false
		//==========================================
		function delArticle($id){
			if($this->del($id)){
				if($this->comment->delCommByUid($id, true)){
					$this->messList[] = "文档的评论删除成功.";
				}
				$this->messList[] = "文档删除成功.";
				return true;
			}else{
				$this->messList[] = "文档删除失败.";
				return false;
			}
		}
		//==========================================
		// 函数: delArticleByCid($cid)
		// 功能: 根据文章的类别ID删除该类别下的所有文章和文章的评论
		// 参数: cid是文章所在类别的ID
		// 返回:true或false
		//==========================================
		function delArticleByCid($cid){
			if(is_array($cid))
				$tmp = "IN (" . join(",", $cid) . ")";
			else
				$tmp = "= $cid";

			$sql = "DELETE FROM {$this->tabName} WHERE catId " . $tmp ;

			$res=$this->mysqli->query("SELECT id FROM {$this->tabName} WHERE catId " . $tmp);
			while($idArr=$res->fetch_assoc()){
				$ids[]=$idArr["id"];
			}
			if($this->comment->delCommByUid($ids, true)){
				$this->messList[] = "文档的评论删除成功.";
			}
			$result=$this->mysqli->query($sql);

			if($result && $this->mysqli->affected_rows >0){
				return true;
			}else{
				return false;
			}
		}

		//==========================================
		// 函数: getRecommend($catId=0,$offset=0, $num=10)
		// 功能: 获取推荐文章
		// 参数: catId是文章类别ID，offset是提取文章的偏移位置，num是获取文章的记录个数
		// 返回: 指定个数的文章记录数组
		//==========================================
		function getRecommend($catId=0,$offset=0, $num=10){
			if($catId !=0 ){
				$sql = "SELECT id, title,author,postTime,audit FROM {$this->tabName}
				WHERE catId={$catId} and recommend=1  and audit=1 ORDER BY id DESC LIMIT $offset, $num";
			}else{
				$sql = "SELECT id, title,author,postTime,audit FROM {$this->tabName}
				WHERE recommend=1  and audit=1 ORDER BY id DESC LIMIT $offset, $num";
			}

			$result=$this->mysqli->query($sql);

			while($row=$result->fetch_assoc()){
				$data[]=$row;
			}
			return $data;
		}
		//==========================================
		// 函数: getNew($offset=0, $num=10)
		// 功能: 获取最近更新，在一周之内的更新
		// 参数: offset是提取文章的偏移位置，num是获取文章的记录个数
		// 返回: 指定个数的文章记录数组
		//==========================================
		function getNew($offset=0, $num=10){
			$sql = "SELECT id, title,author,postTime,audit FROM {$this->tabName}
				WHERE postTime > ".(time()-60*60*24*7)." and audit=1 ORDER BY postTime DESC LIMIT $offset, $num";
			$result=$this->mysqli->query($sql);

			while($row=$result->fetch_assoc()){
				$data[]=$row;
			}
			return $data;
		}
		//==========================================
		// 函数: getHot($catId=0,$offset=0, $num=10)
		// 功能: 获取本月热门文章，在一月之内的
		// 参数: catId是文章类别ID，offset是提取文章的偏移位置，num是获取文章的记录个数
		// 返回: 指定个数的文章记录数组
		//==========================================
		function getHot($catId=0,$offset=0, $num=10){
			if($catId !=0 ){
				$sql = "SELECT id, title,author,postTime,audit FROM {$this->tabName}
				WHERE catId={$catId} and audit=1 and postTime > ".(time()-60*60*24*30)." ORDER BY views DESC LIMIT $offset, $num";
			}else{
				$sql = "SELECT id, title,author,postTime,audit FROM {$this->tabName}
				WHERE postTime > ".(time()-60*60*24*30)."  and audit=1 ORDER BY views DESC LIMIT $offset, $num";
			}
			$result=$this->mysqli->query($sql);

			while($row=$result->fetch_assoc()){
				$data[]=$row;
			}
			return $data;
		}
		//==========================================
		// 函数: getRowTotal($catId)
		// 功能: 获取指定文章类别下的文章记录的总数，在分页中使用
		// 参数: catId是文章的类别ID
		// 返回: 记录总数
		//==========================================
		function getRowTotal($catId){
			$result=$this->mysqli->query("SELECT * FROM {$this->tabName} WHERE  catId={$catId}");
			return $result->num_rows;
		}
		//==========================================
		// 函数: getAllArts($catId, $offset, $num)
		// 功能: 获取所有文章
		// 参数: catId是文章类别ID，offset是提取文章的偏移位置，num是获取文章的记录个数
		// 返回: 指定个数的文章记录数组
		//==========================================
		function getAllArts($catId, $offset, $num) {
			$sql = "SELECT id, title,author,postTime,audit FROM {$this->tabName}
				WHERE catId={$catId} ORDER BY id DESC LIMIT $offset, $num";
			$result=$this->mysqli->query($sql);

			while($row=$result->fetch_assoc()){
				$row["comms"]=$this->comment->getTotal($row["id"]);
				$data[]=$row;
			}
			return $data;
		}
		//==========================================
		// 函数:  getAuditArts($catId, $offset=0, $num=10)
		// 功能: 获取所有审核过的文章
		// 参数: catId是文章类别ID，offset是提取文章的偏移位置，num是获取文章的记录个数
		// 返回: 指定个数的文章记录数组
		//==========================================
		function getAuditArts($catId, $offset=0, $num=10) {
			$sql = "SELECT id, title,author,postTime FROM {$this->tabName}
				WHERE catId={$catId} and audit=1 ORDER BY id DESC LIMIT $offset, $num";
			$result=$this->mysqli->query($sql);

			while($row=$result->fetch_assoc()){
				$data[]=$row;
			}
			return $data;
		}
		//==========================================
		// 函数: auditArticle($id)
		// 功能: 对文章进行审核
		// 参数: id是需要审核的文章ID
		// 返回: true或false
		//==========================================
		function auditArticle($id) {
			$sql = "UPDATE {$this->tabName} SET audit=1 WHERE id ";
			if(is_array($id)) {
				$sql .= "IN (" . join(",", $id) . ")";

			}else{
				$sql .= "= $id";
			}

			if($this->mysqli->query($sql)){
				$this->messList[] = "审核成功.";
				return true;
			}else{
				$this->messList[] = "审核失败.";
				return false;
			}
		}
		//==========================================
		// 函数: lockArticle($id)
		// 功能: 用户锁定指定的文章
		// 参数: id是需要被锁定文章的ID
		// 返回: true或false
		//==========================================
		function lockArticle($id) {
			$sql = "UPDATE {$this->tabName} SET audit=0 WHERE id ";
			if(is_array($id)) {
				$sql .= "IN (" . join(",", $id) . ")";

			}else{
				$sql .= "= $id";
			}

			if($this->mysqli->query($sql)){
				$this->messList[] = "锁定成功.";
				return true;
			}else{
				$this->messList[] = "锁定失败.";
				return false;
			}
		}
		//==========================================
		// 函数: getArticleTitle($id)
		// 功能: 获取指定文章的标题
		// 参数: id是需要被获取文章的ID
		// 返回:成功返回文章的标题，失败则返回false
		//==========================================
		function getArticleTitle($id){
			$sql = "SELECT id, title FROM {$this->tabName} WHERE id ={$id}";

			$result=$this->mysqli->query($sql);

			if($result && $result->num_rows ==1){
				return $result->fetch_assoc();
			}else{
				return false;
			}
		}
		//==========================================
		// 函数:  setViews($id)
		// 功能: 用户设置文章的访问次数
		// 参数: id是被更新文章的ID
		// 返回:无
		//==========================================
		function setViews($id){
			$this->mysqli->query("UPDATE {$this->tabName} SET views=views+1 WHERE id={$id}");
		}
		//==========================================
		// 函数: getNextArticle($colId, $id)
		// 功能: 获取对应指定文章的下一篇文章
		// 参数: colId是本文章所在的类别ID， id是当前文章的ID
		// 返回: 成功返回下一篇文章，失败则返回false
		//==========================================
		function getNextArticle($colId, $id){
			$sql="SELECT  id, title,author,postTime FROM {$this->tabName} WHERE  id > {$id} and audit=1 and catId={$colId} ORDER BY id ASC LIMIT 0, 1";
			$result=$this->mysqli->query($sql);

			if($result && $result->num_rows ==1){
				return $result->fetch_assoc();
			}else{
				return false;
			}
		}
		//==========================================
		// 函数: getPrevArticle($colId, $id)
		// 功能: 获取对应指定文章的上一篇文章
		// 参数: colId是本文章所在的类别ID， id是当前文章的ID
		// 返回: 成功返回上一篇文章，失败则返回false
		//==========================================
		function getPrevArticle($colId, $id){
			$sql="SELECT  id, title,author,postTime FROM {$this->tabName} WHERE  id < {$id} and audit=1 and catId={$colId} ORDER BY id DESC LIMIT 0, 1";
			$result=$this->mysqli->query($sql);

			if($result && $result->num_rows ==1){
				return $result->fetch_assoc();
			}else{
				return false;
			}
		}
		//==========================================
		// 函数: getSearchResult($type, $condition, $offset, $num)
		// 功能: 获取文章的搜索结构列表数据
		// 参数: type是指定搜索的类型，condition是指定搜索的内容，offset是提取文章的偏移位置，num是获取文章的记录个数
		// 返回: 解析后的相册列表内容，在模板中使用
		//==========================================
		function getSearchResult($type, $condition, $offset, $num){
			$sql = "SELECT id, title,author,postTime,views FROM {$this->tabName} WHERE audit=1 ";
			$tmp = "";
			$condition=trim($condition);
			switch($type){
				case "title":               //按标题搜索
					$tmp .= "AND title LIKE '%{$condition}%' ";
					break;
				case "content":               //按内容搜索
					$tmp .= "AND content LIKE '%{$condition}%' ";
					break;
				case "keyword":               //按关键字搜索
					$tmp .= "AND keyword LIKE '%{$condition}%' ";
					break;
			}

			$tmp .= "ORDER BY postTime DESC LIMIT {$offset}, {$num}";
			$sql .= $tmp;

			$result=$this->mysqli->query($sql);
			while($row=$result->fetch_assoc()){
				$data[]=$row;
			}
			return $data;
		}

		//==========================================
		// 函数: getSearchTotal($type, $condition)
		// 功能: 获取文章的搜索结构列表数据总记录数
		// 参数: type是指定搜索的类型，condition是指定搜索的内容
		// 返回: 返回搜索结构的总记录数
		//==========================================
		function getSearchTotal($type, $condition){
			$sql = "SELECT id, title,author,postTime,views FROM {$this->tabName} WHERE audit=1 ";
			$tmp = "";
			$condition=trim($condition);
			switch($type){
				case "title":               //按标题搜索
					$tmp .= "AND title LIKE '%{$condition}%' ";
					break;
				case "content":               //按内容搜索
					$tmp .= "AND content LIKE '%{$condition}%' ";
					break;
				case "keyword":               //按关键字搜索
					$tmp .= "AND keyword LIKE '%{$condition}%' ";
					break;
			}

			$tmp .= "ORDER BY postTime DESC";
			$sql .= $tmp;

			$result=$this->mysqli->query($sql);
			return $result->num_rows;
		}
		//==========================================
		// 函数: validateForm()
		// 功能: 对添加的文章或修改的文章内容进行验证
		// 参数: 无
		// 返回: true或false
		//==========================================
		function validateForm(){
			$result=true;
			if(!Validate::required($_POST['title'])){
				$this->messList[] = "文章标题不能为空.";
				$result=false;
			}
			if(!Validate::checkLength($_POST['title'], 100)) {
				$this->messList[] = "文章标题不能超过50个字符.";
				$result=false;
			}
			if(!Validate::required($_POST['postTime'])){
				$this->messList[] = "发布时间不能为空.";
				$result=false;
			}
			if(!Validate::match($_POST['postTime'], "/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/")) {
				$this->messList[] = "发布时间格式不正确.";
				$result=false;
			}
			if(!Validate::checkLength($_POST['summary'], 300)) {
				$this->messList[] = "文章摘要不能超过300个字符.";
				$result=false;
			}
			if(!Validate::required($_POST['author'])) {
				$this->messList[] = "文章作者不能为空.";
				$result=false;
			}
			if(!Validate::checkLength($_POST['author'], 30)) {
				$this->messList[] = "文章作者不能超过30个字符.";
				$result=false;
			}
			if(!Validate::required($_POST['keyword'])) {
				$this->messList[] = "关键字不能为空.";
				$result=false;
			}
			if(!Validate::checkLength($_POST['keyword'], 20)){
				$this->messList[] = "关键字不能超过20个字符.";
				$result=false;
			}
			if(!Validate::required($_POST['content'])) {
				$this->messList[] = "文章内容不能为空.";
				$result=false;
			}
			return  $result;
		}
	}
?>
