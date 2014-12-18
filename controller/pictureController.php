<?php
/*
 * @Created on 2014-7-28
 * @图片处理类.
 */

 class pictureController extends BaselogicController {

		function __construct($showError=TRUE){
			parent:: __construct($showError);
			$this->tabName = TAB_PREFIX."picture";
 			$this->fieldList=array("picTitle", "description", "picName", "catId", "hasThumb", "hasMark");
		}

		private function uploadPic($fileUpload,$file){
			if($fileUpload->uploadFile($file["uploadPic"])<0){
				$this->messList[] = $fileUpload->getErrorMsg();
				return false;
			}else{
				$this->messList[] = "图片上传成功！";
				return true;
			}
		}

		function modPic($post){
			if($this->validateForm($post)){
				$gdImage=new GDImage(GALLERY_REAL_PATH,$post["picName"]);
				global $pictureSize;
				$gdImage->makeThumb($pictureSize["maxWidth"], $pictureSize["maxHeight"], false);
				if(!empty($post['hasThumb'])){ //生成缩略图
					global $thumbSize;
					if($gdImage->makeThumb($thumbSize["width"], $thumbSize["height"])){
						$this->messList[] = "修改缩略图片成功！";
					}else{
						$this->messList[] = "修改缩略图片失败！";
						return false;
					}
				}else{
					$post['hasThumb']=0;
				}
				if(!empty($post['hasMark'])){	//加水印
					global $waterText;
					if($gdImage->waterMark($waterText)){
						$this->messList[] = "添加水印成功！";
					}else{
						$this->messList[] = "添加水印失败！";
						return false;
					}
				}else{
					$post['hasMark']=0;
				}
				if($post["hasThumb"]==0 && $post["hasMark"]==0){
					list($fileName, $extension)=explode(".", $post["picName"]);
					$newFile=GALLERY_REAL_PATH.$fileName."_new.".$extension;
					if(file_exists($newFile)){
						unlink($newFile);
					}
				}

				if($this->mod($post)){
					$this->messList[] = "数据表中图片信息修改成功！";
				}else{
					$this->messList[] = "数据表中图片信息修改失败！";
					return false;
				}
				return true;
			}else{
				$this->messList[] = "图片修改失败！";
				return false;
			}
		}

		function delPic($id){
			if($this->delPicFile($id)){
				if($this->del($id)){
					$this->messList[] = "数据表记录删除成功！";
				}else{
					$this->messList[] = "数据表记录删除失败！";
					return false;
				}
				$this->messList[] = "图片文件删除成功！";

			}else{
				$this->messList[] = "图片文件删除失败！";
				return false;
			}

			return true;
		}

		private function delPicFile($id){
			if($picNames=$this->getPicName($id)){
				return $this->delPicByName($picNames);
			}else{
				return false;
			}

		}

		private function delPicByName($picNames) {
			foreach($picNames as $picName){
				$picPath= GALLERY_REAL_PATH.str_replace(".","_new.",$picName);
				$srcPicPath=GALLERY_REAL_PATH.$picName;
				if(file_exists($picPath)){
					unlink($picPath);
				}
				if(file_exists($srcPicPath)){
					unlink($srcPicPath);
				}
			}
			return true;
		}

		function delPicByCid($cid){
			if(is_array($cid))
				$tmp = "IN (" . join(",", $cid) . ")";
			else
				$tmp = "= $cid";

			$sql = "DELETE FROM {$this->tabName} WHERE catId " . $tmp ;

			$res=$this->mysqli->query("SELECT picName FROM {$this->tabName} WHERE catId " . $tmp);
			while($nameArr=$res->fetch_assoc()){
				$names[]=$nameArr["picName"];
			}
			if($this->delPicByName($names)){
				$this->messList[] = "图片删除成功.";
			}
			$result=$this->mysqli->query($sql);

			if($result && $this->mysqli->affected_rows >0){
				return true;
			}else{
				return false;
			}
		}

		private function getPicName($id){
			if(empty($id)){
				$this->messList[] = "没有选择需要的图片！";
				return false;
			}
			if(is_array($id)){
				$tmp = "IN (" . join(",", $id) . ")";
			}else{
				$tmp = "= $id";
			}
			$sql="SELECT picName FROM {$this->tabName} WHERE id " . $tmp;

			$result=$this->mysqli->query($sql);

			$picNames=array();
			while($data=$result->fetch_assoc()){
				$picNames[]=$data["picName"];
			}
			return $picNames;
		}

		function addPic($fileUpload, $post, $file){
			if($this->validateForm($post)){
				if($this->uploadPic($fileUpload,$file)){
					$post["picName"]=$fileUpload->getNewFileName();
					$gdImage=new GDImage(GALLERY_REAL_PATH,$post["picName"]);

					global $pictureSize;
					$gdImage->makeThumb($pictureSize["maxWidth"], $pictureSize["maxHeight"], false);
					if(!empty($post['hasThumb'])){ //生成缩略图
						global $thumbSize;
						if($gdImage->makeThumb($thumbSize["width"], $thumbSize["height"])){
							$this->messList[] = "生成缩略图片成功！";
						}else{
							$this->messList[] = "生成缩略图片失败！";
							return false;
						}
					}

					if(!empty($post['hasMark'])){	//加水印
						global $waterText;
						if($gdImage->waterMark($waterText)){
							$this->messList[] = "添加水印成功！";
						}else{
							$this->messList[] = "添加水印失败！";
							return false;
						}
					}

					if($this->add($post)){
						$this->messList[] = "图片添加成功！";
						return true;
					}else{
						$this->messList[] = "图片添加失败！";
						return false;
					}
				}else{
					$this->messList[] = "图片上传失败！";
					return false;
				}
			}else{
				$this->messList[] = "图片上传失败！";
				return false;
			}
		}


		 function getRowTotal($catId){
			$result=$this->mysqli->query("SELECT * FROM {$this->tabName} WHERE  catId={$catId}");
			return $result->num_rows;
		}

		function getAllPic($catId, $offset, $num) {
			$sql = "SELECT id, picTitle, picName, description, hasThumb, hasMark FROM {$this->tabName}
				WHERE catId={$catId} ORDER BY id DESC LIMIT $offset, $num";
			$result=$this->mysqli->query($sql);

			while($row=$result->fetch_assoc()){
				$data[]=$row;
			}
			return $data;
		}

		function getPicPro($id){
			$pic=$this->get($id);

			$info=$this->getPicInfo($pic["picName"]);
			$pic["width"]=$info["width"];
			$pic["height"]=$info["height"];
			$pic["size"]=$info["size"];

			if($pic["hasThumb"]){
				$newPic=str_replace(".", "_new.", $pic["picName"]);
				$infoH=$this->getPicInfo($newPic);
				$pic["width_h"]=$infoH["width"];
				$pic["height_h"]=$infoH["height"];
				$pic["size_h"]=$infoH["size"];
				$pic["newName"]=$newPic;
			}
			return $pic;
		}

		private function getPicInfo($picName){
			$file=GALLERY_REAL_PATH.$picName;
			$data	= getimagesize($file);
			$imageInfo["width"] = $data[0];
			$imageInfo["height"]= $data[1];
			$imageInfo["size"]  = Common::sizeCount(filesize($file));
			return $imageInfo;
		}

		function getPicPath($id){
			$sql="SELECT picName, description, hasThumb,hasMark FROM {$this->tabName} WHERE id={$id}";
			$result=$this->mysqli->query($sql);
			$pic=$result->fetch_assoc();
			if($pic["hasMark"]==1 || $pic["hasThumb"]==1){
				$picPath=str_replace(".", "_new.", $pic["picName"]);
			}else{
				$picPath=$pic["picName"];
			}

			if(file_exists(GALLERY_REAL_PATH.$picPath)){
				return GALLERY_PATH.$picPath;
			}else{
				return false;
			}
		}

		function validateForm($post){
		        $result=true;

			if(!Validate::required($post['picTitle'])) {
				$this->messList[] = "图片标题不能为空.";
				$result=false;
			}
			if(!Validate::checkLength($post['description'], 200)) {
				$this->messList[] = "图片描述不能超过200个字符.";
				$result=false;
			}
			return  $result;
		}
	}
?>
