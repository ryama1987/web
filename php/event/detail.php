<?php
//ini_set('display_errors',1);

//セミナーのクラスを呼び出す
require_once ("./classEvent.php");

//イベントページ作成の宣言
$event = new eventPageCreate();

//パラメータのid(eid)に一致したデータを取得
$result = $event->getEventDetailPageData();

//構造化データの作成がautoなら自動で対応（ただし、ジャスネット主催の場合）
if(isset($result['EventSchemaType__c']) && $result['EventSchemaType__c'] === 'auto'){
    $result = $event->autoCreateSchemaData($result);
}

$event->smartyTemplateSet($result);
die();
?>
