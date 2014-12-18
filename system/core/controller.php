<?php
/**
 * @核心控制器
 * @所有控制器类都继承自该类
 */
class Controller{

        public function __construct() {
               header('Content-type:text/html;chartset=utf-8');
               $this->app_dir = dirname(dirname(dirname(__FILE__)));  //应用根目录
               $this->model_dir = $this->app_dir."/model/";           //模型目录
        }
        /**
         * 实例化模型
         * @access      final   protected
         * @param       string  $model  模型名称
         */
        final protected function model($model) {
                if (empty($model)) {
                        trigger_error('不能实例化空模型');
                }
                require($this->model_dir.$model .'Model.php');
                $model_name = $model .'Model';
                return new $model_name;        //实例化需要加载的模型
        }
        /**
         * 加载类库
         * @param string $lib   类库名称
         * @param Bool  $my     如果FALSE默认加载系统自动加载的类库，如果为TRUE则加载非自动加载类库
         * @return type
         */
        final protected function load($lib,$auto = TRUE){
                if(empty($lib)){
                        trigger_error('加载类库名不能为空');
                }elseif($auto === TRUE){
                        return Application::$_lib[$lib];
                }elseif($auto === FALSE){
                        return  Application::newLib($lib);
                }
        }
        /**
         * 加载系统配置,默认为系统配置 $CONFIG['system'][$config]
         * @access      final   protected
         * @param       string  $config 配置名
         */
        final   protected function config($config){
                return Application::$_config[$config];
        }
        /**
         * 加载模板文件
         * @access      final   protect
         * @param       string  $path   模板路径
         * @return      string  模板字符串
         */
        final protected function _view($path,$data = array()){
                $template =  $this->load('template');
                $template->init($path,$data);
                $template->outPut();
        }
}


