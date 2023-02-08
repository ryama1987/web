<?php 
 	//ini_set("display_errors",1);
	ini_set("soap.wsdl_cache_enabled", 0);

	// 「キャッシュしない」ためのルールを定義。
	header('Cache-Control:no-cache,no-store,must-revalidate,max-age=0');

	// IE用にCache-Controlの拡張ヘッダを定義。
	header('Cache-Control:pre-check=0,post-check=0',false);

	// HTTP/1.0クライアントとの下位互換性のために使用されるもの。
	// Cache-Control: no-cacheと同じ働きをするコード。
	header('Pragma:no-cache');

	//セッションの有効期限設定　※session_start()の前に設定することに注意
	session_cache_expire(30);
	session_cache_limiter('private');

	date_default_timezone_set('Asia/Tokyo');


	//includeのPATHをセットする
	set_include_path("/var/www/html/data/lib/");
	$include_path = "/var/www/html/data";

	//Zendを読み込んで、データベースからID/PASSを取得
	require_once 'Zend/Db.php';
	require_once 'Zend/Config/Ini.php';

	// 初期設定 Namespacesなど
	$nameSpace1 = "urn:sobject.enterprise.soap.sforce.com";
	$nameSpace2 = "urn:enterprise.soap.sforce.com";
	$wsdl       = $include_path.'/enterprise.wsdl.xml';

	$logindata = [
		'username' => $data['user_id'],
		'password' => $data['password'],
	];
?>
