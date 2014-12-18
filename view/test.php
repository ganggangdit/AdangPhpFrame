<?php
/*
 * Created on 2014-6-12
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
?>
<html>
	<head>
		<title>
		</title>
	</head>
	<body>
		<?php echo $name;

		  $rootPath=dirname(dirname($_SERVER["SCRIPT_FILENAME"]));
		  $root =  $rootPath ."/";                     // D:/Apache2.2/htdocs/framework/
		  echo $_SERVER['DOCUMENT_ROOT']."<br />";    //D:/Apache2.2/htdocs
		  $test = "/".ltrim($root, $_SERVER["DOCUMENT_ROOT"]);  //取得应用名字
		  echo $test."<br />";
		?>
	</body>
</html>