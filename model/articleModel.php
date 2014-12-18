<?php
/*
 * @Created on 2014-7-29
 * @封装来自 cms_article 数据表的记录
 * @Creted By adang
 */
class articleModel extends Model{
	public function __construct(){
 		parent::__construct();
 		$this->tabName = TAB_PREFIX."article";   //数据库名
		$this->fieldList=array("id","title", "summary", "postTime", "author","ComeFrom","content","keyword","catId","audit","recommend","views");
 	}
 	//==========================================
		// 函数:  getAuditArts($catId, $offset=0, $num=10)
		// 功能: 获取所有审核过的文章
		// 参数: catId是文章类别ID，offset是提取文章的偏移位置，num是获取文章的记录个数
		// 返回: 指定个数的文章记录数组
	//==========================================
	function getAuditArts($catId,$offset=0,$num=10){
		$sql = "SELECT id, title,author,postTime FROM {$this->tabName}
				WHERE catId={$catId} and audit=1 ORDER BY id DESC LIMIT $offset, $num";
		$result=$this->db->query($sql);
		while($row=$this->db->fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
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
			$result=$this->db->query($sql);

			while($row=$this->db->fetch_assoc($result)){
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
		function getNew($offset=0,$num=10){
			$sql = "select id,title,author,postTime,audit FROM {$this->tabName} where postTime > ".(time()-3600*24*7)." and audit=1 order by postTime desc limit $offset, $num";
			$result = $this->db->query($sql);
			while($row = $this->db->fetch_assoc($result)){
				$data[] = $row;
			}
			return $data;
		}
		function getHot($catId=0,$offset=0,$num=10){
			if($catId !=0 ){
				$sql = "SELECT id, title,author,postTime,audit FROM {$this->tabName}
				WHERE catId={$catId} and audit=1 and postTime > ".(time()-60*60*24*30)." ORDER BY views DESC LIMIT $offset, $num";
			}else{
				$sql = "SELECT id, title,author,postTime,audit FROM {$this->tabName}
				WHERE postTime > ".(time()-60*60*24*30)."  and audit=1 ORDER BY views DESC LIMIT $offset, $num";
			}
			$result=$this->db->query($sql);

			while($row=$this->db->fetch_assoc($result)){
				$data[]=$row;
			}
			return $data;
		}
}
?>
