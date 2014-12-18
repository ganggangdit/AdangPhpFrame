<?php
/**
 * @应用入口文件
 * @单入口
 */
require dirname(__FILE__).'/system/app.php';
require dirname(__FILE__).'/config/config.php'; //引入配置文件,且为一个数组 $CONFIG
Application::run($CONFIG);





