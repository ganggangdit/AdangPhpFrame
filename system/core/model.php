<?php
/**
 * @核心控制器类
 * @所有模型类都继承自该类
 * @Created By adang
 */
class Model {
        protected $db = null;
         public function __construct() {
        		require (dirname(dirname(dirname(__FILE__))).'/config/config.inc.php');
        		$this->app_dir = dirname(dirname(dirname(__FILE__)));  //应用根目录
                $this->model_dir = $this->app_dir."/model/";           //模型目录

                header('Content-type:text/html;chartset=utf-8');
                $this->db = $this->load('mysql');   //载入数据库操作类
                $config_db = $this->config('db');   //数据库配置项
                $this->db->init(
                        $config_db['db_host'],
                        $config_db['db_user'],
                        $config_db['db_password'],
                        $config_db['db_database'],
                        $config_db['db_conn'],
                        $config_db['db_charset']
                        );                                            //初始话数据库类
        }
        /**
         * 根据表前缀获取表名
         * @access   final   protected
         * @param    string  $table_name表名
         */
        final protected function table($table_name){
                $config_db = $this->config('db');
                return $config_db['db_table_prefix'].$table_name;
        }
        /**
         * 加载类库
         * @param string $lib   类库名称
         * @param Bool   $my    如果FALSE默认加载框架自动加载的类库，如果为TRUE则加载自定义类库
         * @return type
         */
        final protected function load($lib,$my = FALSE){
                if(empty($lib)){
                        trigger_error('加载类库名不能为空');
                }elseif($my === FALSE){
                        return Application::$_lib[$lib];
                }elseif($my === TRUE){
                        return  Application::newLib($lib);
                }
        }
        /**
         * 加载系统配置,默认为系统配置 $CONFIG['system'][$config]
         * @access      final   protected
         * @param       string  $config 配置名
         */
        final  protected function config($config=''){
                return Application::$_config[$config];
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



}


