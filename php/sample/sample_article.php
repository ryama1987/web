<?php
//ini_set("display_errors",1);
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");

require_once ('../constructor.php');
require_once ('../common.php');

$s = new MySmarty();

//記事情報
require_once ('./article_array.php');

//検索フォームの設定
$param = "";
$s->assign("area", "");

//page関連
$fileNamePath = $_SERVER['REQUEST_URI'];
$fileNamePath = parse_url($fileNamePath, PHP_URL_PATH);
$fileNamePath = pathinfo($fileNamePath);

$file_name = $fileNamePath['filename']; //ファイル名取得
$current_dir = str_replace('/', '', $fileNamePath['dirname']); //ディレクトリ名取得

//当該記事の変数
$article_array = array();

foreach ($article_cpa_array as $key => $value){
	if($article_cpa_array[$key]['file'] === $file_name){
		$article_array[$file_name] = $article_cpa_array[$key]; //ファイル名に該当する記事情報（配列）を取得
	} else {
	}
}

foreach ($article_cpa_array as $key => $value){
	if($article_cpa_array[$key]['file']  === $article_array[$file_name]['cate']){
		$article_array[$file_name]['cateTitle'] = $article_cpa_array[$key]['title'];//ファイル名に該当する記事タイトルを取得し、配列に追加。
	} else {
	};
}

$page_title = ""; //SEO対策用
if(empty($page_title)){
	$page_title = $article_array[$file_name]['title']." ".$article_array[$file_name]['subtitle'];
} else {
	$page_title;
}

$keyword = $article_array[$file_name]['keyword'];
$description = $article_array[$file_name]['description'];

//目次
$doc_index_array = [
	'cnt_01' => [
		'title' => '■会計士の目線',
		'sub' =>[
			'cnt_01_01' => '■変貌を遂げる地方の証券取引所。その存在意義は？',
			'cnt_01_02' => '■これからの地方の証券取引所の役割とは？',
		],
	],
];

foreach ($doc_index_array as $indexNo => $index){
	$s->assign($indexNo, $index['title']); //h3のタイトルの変数　アンカーリンク名で出力できる
	if(isset($doc_index_array[$indexNo]['sub'])){
		foreach ($doc_index_array[$indexNo]['sub'] as $subIndexNo => $subIndex){
		$s->assign($subIndexNo, $subIndex); //h4のタイトル変数　アンカーリンク名で出力できる
		}	
	} else {
	}
}

//meta_image
$meta_image = $article_array[$file_name]['mainImgPath'];
$imgProp = getimagesize($meta_image);

//公開日時・更新日時
date_default_timezone_set('Asia/Tokyo'); // タイムゾーン設定
$timeStamp = filemtime($fileNamePath['basename']); // ファイルの更新日時を取得
$datePublished = "2021-12-15T09:23:00+0900"; //公開日時
$dateModified = date("Y-m-d\TH:i:s\+0900", $timeStamp);//更新日時

$s->assign("news", $article_cpa_array);
$s->assign("article", $article_array[$file_name]);
$s->assign("doc_index", $doc_index_array);
$s->assign("current_dir", $current_dir);
$s->assign("file_name", $file_name);	
$s->assign("page_title", $page_title);
$s->assign("keywords", $keyword);
$s->assign("description", $description);
$s->assign("meta_image", $meta_image);
$s->assign("imgProp", $imgProp);
$s->assign("datePublished", $datePublished);
$s->assign("dateModified", $dateModified);

//boydに付与するID名
$s->assign("main_id", "cpa-article");

// タイトルの上書き用
require_once ('./common_title.php');

//コンテンツ部分のtplを指定
$s->assign("tpl_mainpage", "cc/$current_dir/renew_cpa/$file_name.tpl");

//レイアウト管理のtplを呼び出す
$s->display("cc/renew_2009/site_frame_for_cpa_article.tpl");
?>
