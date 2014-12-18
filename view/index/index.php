<?php
/*
 * @Created on 2014-7-8
 * @网站首页
 * @Created By wanggang
 */
?>
<?php include "header.php" ?>
			<!--以上为页面中头部-->
				<div id="main">
					<?php foreach($cols as $key=>$value) { ?>
						<?php if($key%2==0){?>
							<div class="leftbox">
						<?php } else {?>
							<div class="rightbox">
						<?php } ?>
			       				<div class="dt"><strong><a href="?controller=column&pid=<?php  echo  $value['colId'] ?>"><?php echo substr($value['colTitle'],0,25)  ?></a></strong><span class="more"><a href="?controller=column&pid=<?php echo   $value['colId'] ?>">更多...</a></span></div>
			        			<div class="dd">
								<div class="left">
									<a href="?controller=column&pid=<?php  $value['colId'] ?>"><img src="<?php  $value['colId'] ?>" border="0" width="80" height="80"></a>
									<?php if(!empty($value['subCol'] )){?>
										<ul>
											<?php foreach($value['subCol'] as $subcol){ ?>
												<li><a href="?controller=column&pid=<?php echo  $subcol['subcol'] ?>">&nbsp;&nbsp;&nbsp;<?php  echo  $subcol['colTitle'] ?></a></li>
											<?php } ?>
										</ul>
									<?php } ?>
								</div>
								<div class="right dot">
									<ul>
									<?php if(!empty($value['art'])){?>
										<?php foreach($value['art'] as $art){?>
											<li><a href="?controller=article&aid=<? echo $art['id'] ?>"><?php  echo substr($art['title'],0,25) ?></a></li>
										<?php }
										} else { ?>
											<li>该栏目中没有任何文章</li>
										<?php } ?>
									</ul>
								</div>

			       				</div>
			       		 	</div>
								<div class="nav"> </div>
					<?php } ?>
				</div>

				<div id="sidebar">
					<div class="sidebox">
			       			<div class="dt"><strong>强烈推荐</strong></div>
			        		<div class="dd dot">
			          			<ul>

								<?php if(!empty($recommends)){
										foreach($recommends as $recommend){
									?>
									<li><a href="?controller=article&aid=<?php echo  $recommend['id'] ?>"><?php echo  substr($recommend['title'],0,25)?></a></li>
								<?php }
									}else{
							    ?>
									<li>目前没有任何推荐文章</li>
								<?php } ?>
			          			</ul>
						</div>
			       		 </div>
					<div class="nav"> </div>
					<div class="sidebox">
			       			<div class="dt"><strong>最近更新</strong></div>
			        		 <div class="dd dot">
			            			<ul>
								<?php if(!empty($news)) {
										foreach($news as $new){
									?>
									<li><a href="controller=article&aid=<?php echo  $new['id'] ?>"><?php  echo  substr($new['title'],0,25) ?></a></li>
								<?php }
									} else {?>
									<li>目前没有任何推荐文章</li>
								<?php } ?>
			          			</ul>
						</div>
			       		 </div>
					<div class="nav"> </div>
					<div class="sidebox">
			       			<div class="dt"><strong>本月热点</strong></div>
			        		 <div class="dd dot">
			          			<ul>
								<?php if(!empty($hot)){
									foreach($hots as $hot){
								?>
									<li><a href="?controller=article&aid=<?php echo  $hot['id'] ?>"><?php  echo substr($hot['title'],0,25) ?></a></li>
								<? }
								} else{ ?>
									<li>目前没有任何推荐文章</li>
								<?php } ?>
			          			</ul>
						</div>
			       		 </div>
					<div class="nav"> </div>
				</div>

				<div class="nav"> </div>
				<div id="link">
			       		<div class="dt"><strong><span>友情链接</span></strong></div>
			        	<div class="dd">
			               <ul>
							<?php foreach($links as $link){?>
								<li><a href="<?php echo   $link['url'] ?>" target="_blank">
									<?php if(!empty($link['list'])){ ?>
										<img height="40" alt="<?php echo  $link['webName'] ?>" src="<?php echo  $link['logo'] ?>" border="0" >
									<?php }else{?>
										<?php echo $link['webName'] ?>
									<?php
									}} ?>
								</a></li>
							<?php }
							?>
			          		</ul>
					</div>
			      	 </div>
				<div class="nav"> </div>
				<!--一下为页脚-->
				<?php include "footer.php"?>

