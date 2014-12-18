<?php
/**
 *系统默认控制器
 */
class homeController extends Controller {
    var $app_dir = '';
	public function __construct() {
	        parent::__construct();
	        $this->app_dir = dirname(dirname(__FILE__));
	        require $this->app_dir."/config/config.inc.php";
	}
	public function index() {
			if(!file_exists($this->app_dir."/install_lock.txt")){
				$data['install_notice'] = "对不起！本系统没有安装不能使用. &nbsp;&nbsp;&nbsp;&nbsp; 请点击 <a href='?controller=install'>安装</a>";
				$this->_view('install/home',$data);
			}
			else{
				$this->getindexpage();  //进入首页面
			}
	}
	public function getindexpage(){
		   echo md5("123456");
			/*加载网页首页*/
			$data['app_name'] = APP_NAME;       					   //应用程序名称
			$data["BASE_DIR"] = $_SERVER['SERVER_NAME'].'/'.APP_NAME;  //应用根目录
			$data['stylePath'] = STYLE_PATH.APP_STYLE;   			   //样式或者图片的路径
			//文章分类对象Columns,文章对象Article以及友情链接Flink三个类
			$modelcolum = $this->model('columns');
            $menu = $modelcolum->getColumnList(1);        //列出大的新闻分类
            $cols = $modelcolum->getAllColumn(1);         //获取所有的一级栏目对象数组
			$article = $modelcolum->art;
			$flink = $this->model('flink');
			//推荐文章
			$recommends = $article->getRecommend();
			//最新文章
			$news = $article->getNew();
			//热门文章
			$hot = $article->getHot();
			//获取所有友情链接对象数组
			$data['links'] = $flink->getLinks();
			$data['recommends'] = $recommends;
			$data['news'] = $news;
			$data['hot'] = $hot;
			$data['cols'] = $cols;
			$data['menu'] = $menu;
			$this->_view('index/index',$data);
}
}

