<?php
/*
 * @Created on 2014-7-29
 * @测试model
 */
 class testController  extends Controller {
 	public function __construct(){
 		parent::__construct();
 	}
 	public function index(){

 	}
 	public function testdb(){
 		$modtest = $this->model('tests');
 		$databases = $modtest->getColumnList(1);
 		print_r($databases);
 	}
 }
?>
