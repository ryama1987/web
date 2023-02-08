<?php
//キャリアセミナー情報を格納
//ini_set('display_errors',1);
require_once ("./classEvent.php");

$eventList = new eventPageCreate();

//セミナーカテゴリ判定
$cateid = empty($_GET['cate']) ? 'top' : $_GET['cate'];

//セミナー・イベントのJSONデータ取得
$listData = $eventList->getEventListJson();

$result = $eventList->smartyTemplateSetForList($listData,$cateid);

?>
