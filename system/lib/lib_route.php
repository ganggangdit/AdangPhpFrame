<?php
/**
 * @URL处理类
 * @核心类之一,URL映射到具体的控制器
 */
final class Route{
        public $url_query;
        public $url_type;
        public $route_url = array();


        public function __construct() {
                $this->url_query = parse_url($_SERVER['REQUEST_URI']);       /*$_server['request_uri'] 取除域名外后面的完整的地址路径*/
                															 /*$this->url_query['query'],取到url ? 之后的参数*/
        }
        /**
         * 设置URL类型
         * @access      public
         */
        public function setUrlType($url_type = 2)
        {
                if($url_type > 0 && $url_type <3){
                        $this->url_type = $url_type;
                }else{
                        trigger_error("指定的URL模式不存在！");
                }
        }

        /**
         * 获取数组形式的URL
         * @access      public
         */
        public function getUrlArray(){
                $this->makeUrl();
                return $this->route_url;
        }
        /**
         * @access      public
         */
        public function makeUrl(){
                switch ($this->url_type){
                        case 1:
                                $this->querytToArray();  /*为普通模式    index.php?c=controller&a=action&id=2*/
                                break;
                        case 2:
                                $this->pathinfoToArray();  /*为PATHINFO   index.php/controller/action/id/2,需要 $_SERVER['pathinfo']*/
                                break;
                }
        }
        /**
         * 将query形式的URL转化成数组
         * @access      public
         */
        public function querytToArray(){
                $arr = !empty ($this->url_query['query']) ?explode('&', $this->url_query['query']) :array();
                $array = $tmp = array();
                if (count($arr) > 0) {
                        foreach ($arr as $item) {
                                $tmp = explode('=', $item);
                                $array[$tmp[0]] = $tmp[1];
                        }
                        if (isset($array['app'])) {
                                $this->route_url['app'] = $array['app'];
                                unset($array['app']);
                        }
                        if (isset($array['controller'])) {
                                $this->route_url['controller'] = $array['controller'];
                                unset($array['controller']);
                        }
                        if (isset($array['action'])) {
                                $this->route_url['action'] = $array['action'];
                                unset($array['action']);
                        }
                        if(count($array) > 0){
                                $this->route_url['params'] = $array;
                        }
                }else{
                        $this->route_url = array();
                }
        }
        /**
         * 将PATH_INFO的URL形式转化为数组
         * @access      public
         */
        public function pathinfoToArray(){
					//$arr = !empty($)
        }
}


