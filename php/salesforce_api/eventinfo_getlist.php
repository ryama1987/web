<?php 
ini_set("display_errors",1);

//設定
require_once(__DIR__.'/sf_setting/class_for_salesforce.php');

// 一覧データの変数
$result_list   = array();

// 終了したデータの変数
$archive_list  = array();

// 詳細データの変数
$result_detail = array();


//クラス宣言
$sf = new MySalesForceAccessForEvent();

//SFへログイン
$sf->SOAPforSFLogin();

///////// ** SQLクエリ―設定 ** /////////

// 転職セミナー 最新30件
$SOQL_job30 = "SELECT Name,RecordType.DeveloperName,EventPageTitle__c,EventPageId__c,WebDisplayCategory__c,EventOpenFlg__c,EventPickUpFlg__c,ImageURL1__c,EventLimit__c,CreatedDate,LastModifiedDate,(SELECT SeminarName__c,EventTableTabName__c,EventType__c,EventDate__c,EventDateSupText__c,EventTime__c,EventArea__c,Plan__c,Price__c,URL__c FROM RelEventPage__r WHERE EventDate__c >= %s OR EventDate__c = NULL) FROM EventPage__c WHERE RecordType.DeveloperName != 'EventStudy' AND EventOpenFlg__c = 'open' ORDER BY LastModifiedDate DESC LIMIT 30";

$query_job30 = sprintf($SOQL_job30, $sf->getTodayInfo);

// メソッド使用のフラグ
$flg_job30 = array (
    'eventDateConvert' => true,
    'priceConvert'     => true,
    'arrayReduce'      => true,
    'dateConvertFunc'  => false,
);

// 教育 最新30件
$SOQL_edu30 = "SELECT Name,RecordType.DeveloperName,EventPageTitle__c,WebDisplayCategory__c,EventOpenFlg__c,EventPickUpFlg__c,ImageURL1__c,EventLimit__c,CreatedDate,LastModifiedDate,(SELECT SeminarName__c,EventTableTabName__c,EventType__c,EventDate__c,EventDateSupText__c,EventTime__c,EventArea__c,Plan__c,Price__c,URL__c FROM RelEventPage__r WHERE EventDate__c >= %s OR EventDate__c = NULL) FROM EventPage__c WHERE RecordType.DeveloperName = 'EventStudy' AND EventOpenFlg__c = 'open' ORDER BY LastModifiedDate DESC LIMIT 30";

$query_edu30 = sprintf($SOQL_edu30, $sf->getTodayInfo);

// メソッド使用のフラグ
$flg_edu30 = array (
    'eventDateConvert' => true,
    'priceConvert'     => true,
    'arrayReduce'      => true,
    'dateConvertFunc'  => false,
);

// 過去開催セミナー
$SOQL_archive = "SELECT SeminarName__c,EventTableTabName__c,URL__c,EventType__c,EventDate__c,EventDateSupText__c,EventTime__c,EventArea__c,Plan__c,Price__c,Category1st__c,EventPageId__r.Name FROM Seminar__c WHERE EventDate__c < %s ORDER BY EventDate__c DESC";

$query_archive = sprintf($SOQL_archive, $sf->getTodayInfo);

// メソッド使用のフラグ
$flg_archive = array (
    'eventDateConvert' => false,
    'priceConvert'     => false,
    'arrayReduce'      => false,
    'dateConvertFunc'  => true,
);

// 全件取得
$SOQL_all = "SELECT Name,RecordType.DeveloperName,EventPageTitle__c,EventPageId__c,WebDisplayCategory__c,EventOpenFlg__c,EventPickUpFlg__c,ImageURL1__c,EventLimit__c,CreatedDate,LastModifiedDate,(SELECT SeminarName__c,EventTableTabName__c,EventType__c,EventDate__c,EventDateSupText__c,EventTime__c,EventArea__c,Plan__c,Price__c,URL__c FROM RelEventPage__r) FROM EventPage__c WHERE RecordType.DeveloperName != 'EventStudy' ORDER BY LastModifiedDate DESC";

// メソッド使用のフラグ
$flg_all = array (
    'eventDateConvert' => true,
    'priceConvert'     => false,
    'arrayReduce'      => true,
    'dateConvertFunc'  => false,
);


// edu.jusnet.co.jp用
$SOQL_edusite = "SELECT Name,RecordType.DeveloperName,EventPageTitle__c,WebDisplayCategory__c,EventOpenFlg__c,ImageURL1__c,EventLimit__c,EventMetaDescription__c,EventDiscountFlg__c,EventDiscountFlgURL__c,CreatedDate,LastModifiedDate,(SELECT SeminarName__c,EventTableTabName__c,EventType__c,EventDate__c,EventDateSupText__c,EventTime__c,EventArea__c,Plan__c,Price__c,URL__c FROM RelEventPage__r WHERE EventDate__c > %s OR EventDate__c = NULL) FROM EventPage__c WHERE RecordType.DeveloperName = 'EventStudy' AND EventOpenFlg__c != 'close' ORDER BY LastModifiedDate DESC LIMIT 30";

$query_edusite = sprintf($SOQL_edusite , $sf->getTodayInfo);

// メソッド使用のフラグ
$flg_edusite = array (
    'eventDateConvert' => true,
    'priceConvert'     => true,
    'arrayReduce'      => true,
    'dateConvertFunc'  => false,
);

// edu.jusnet.co.jp用 簡易版
$SOQL_edusite_simple = "SELECT SeminarName__c,EventTableTabName__c,URL__c,EventType__c,EventDate__c,EventDateSupText__c,EventTime__c,EventArea__c,Plan__c,Price__c,Category1st__c,EventPageId__r.Name FROM Seminar__c WHERE EventDate__c > %s AND Category1st__c != '事業部セミナー' AND EventPageId__r.Name != NULL ORDER BY EventDate__c ASC";

$query_edusite_simple = sprintf($SOQL_edusite_simple, $sf->getTodayInfo);

// メソッド使用のフラグ
$flg_edusite_simple = array (
    'eventDateConvert' => false,
    'priceConvert'     => false,
    'arrayReduce'      => false,
    'dateConvertFunc'  => true,
);

// edu.jusnet.co.jp用過去開催セミナー
$SOQL_edusite_archive = "SELECT SeminarName__c,EventTableTabName__c,URL__c,EventType__c,EventDate__c,EventDateSupText__c,EventTime__c,EventArea__c,Plan__c,Price__c,Category1st__c,EventPageId__r.Name FROM Seminar__c WHERE EventDate__c < %s AND Category1st__c != '事業部セミナー' ORDER BY EventDate__c DESC";

$query_edusite_archive = sprintf($SOQL_edusite_archive, $sf->getTodayInfo);

// メソッド使用のフラグ
$flg_edusite_archive = array (
    'eventDateConvert' => false,
    'priceConvert'     => false,
    'arrayReduce'      => false,
    'dateConvertFunc'  => true,
);

// 経理の薬用
$SOQL_kusuri = "SELECT SeminarName__c,EventTableTabName__c,URL__c,EventType__c,EventDate__c,EventDateSupText__c,EventTime__c,EventArea__c,Plan__c,Price__c,Category1st__c,EventPageId__r.Name FROM Seminar__c WHERE EventDate__c > %s ORDER BY EventDate__c ASC LIMIT 50";

$query_kusuri = sprintf($SOQL_kusuri, $sf->getTodayInfo);

// メソッド使用のフラグ
$flg_kusuri = array (
    'eventDateConvert' => false,
    'priceConvert'     => false,
    'arrayReduce'      => false,
    'dateConvertFunc'  => true,
);



///////// ** 標準 ** /////////
if(empty($_GET) ) {

    // 一覧用の情報を取得して、日付を昇順
    $result_list['career'] = $sf->GetEventListData($query_job30, $flg_job30);
    $result_list['career'] = $sf->listDataSortFunc($result_list['career'],SORT_ASC, SORT_REGULAR,"compareDateForList");

    $result_list['edu']    = $sf->GetEventListData($query_edu30, $flg_edu30);
    $result_list['edu']    = $sf->listDataSortFunc($result_list['edu'],SORT_ASC, SORT_REGULAR,"compareDateForList");

    // 終了した情報を取得
    $archive_list['archive'] = $sf->GetEventListData($query_archive,$flg_archive);

    // 詳細用のデータを取得（キャリアのみ）
    foreach($result_list['career'] as $key => $val){
        $result_detail[$val['Name']] = $sf->GetEventDetailData($val['Name'],$val);
    }

    //一覧のデータをJSONファイルにし、エクスポートする
    $result_list_json = json_encode($result_list,JSON_UNESCAPED_UNICODE);
    file_put_contents("../assets_2009/json/event/event_list_info.json", $result_list_json);

    //終了したセミナーの一覧データをJSONファイルにし、エクスポートする
    $archive_list_json = json_encode($archive_list,JSON_UNESCAPED_UNICODE);
    file_put_contents("../assets_2009/json/event/event_list_archive_info.json", $archive_list_json);

    //詳細のデータをJSONファイルにし、エクスポートする
    foreach($result_detail as $key => $val){
        $result_detail_json[$key] = json_encode($result_detail[$key],JSON_UNESCAPED_UNICODE);
        file_put_contents("../assets_2009/json/event/event_detail_".$key.".json", $result_detail_json[$key]);
    }


    //edu.jusnet.co.jp用のデータ
    $eduData = $sf->GetEventListData($query_edusite, $flg_edusite);
    $eduData = $sf->listDataSortFunc($eduData,SORT_ASC, SORT_REGULAR,"compareDateForList");

    //edu.jusnet.co.jp用左サイドナビ用のデータ
    $eduDataSimple = $sf->GetEventListData($query_edusite_simple, $flg_edusite_simple);

    //edu.jusnet.co.jp用アーカイブデータ
    $eduDataArchive  = $sf->GetEventListData($query_edusite_archive, $flg_edusite_archive);

    //edu.jusnet.co.jp用のデータをJSONファイルにし、エクスポートする
    $eduData_json = json_encode($eduData,JSON_UNESCAPED_UNICODE);
    file_put_contents("../assets_2009/json/event/for_edujusnetcojp/event_data.json", $eduData_json);

    $eduDataSimple_json = json_encode($eduDataSimple,JSON_UNESCAPED_UNICODE);
    file_put_contents("../assets_2009/json/event/for_edujusnetcojp/event_data_simple.json", $eduDataSimple_json);

    $eduDataArchive_json = json_encode($eduDataArchive,JSON_UNESCAPED_UNICODE);
    file_put_contents("../assets_2009/json/event/for_edujusnetcojp/event_data_archive.json", $eduDataArchive_json);


    //経理の薬用のデータ
    $kusuriData = $sf->GetEventListData($query_kusuri, $flg_kusuri);
    $kusuriData_json = json_encode($kusuriData,JSON_UNESCAPED_UNICODE);
    file_put_contents("../assets_2009/json/event/for_kusuri/event_data.json", $kusuriData_json);

    echo '<pre>';
    echo '一覧用データ<br>';
    var_dump($result_list);
    echo '</pre>';

    echo '<pre>';
    echo '終了したデータ<br>';
    var_dump($archive_list);
    echo '</pre>';

    echo '<pre>';
    echo '詳細用のデータ<br>';
    var_dump($result_detail);
    echo '</pre>';

    echo '<pre>';
    echo 'edujusnet用のデータ<br>';
    var_dump($eduData);
    echo '</pre>';

    echo '<pre>';
    echo 'edujusnet用のアーカイブデータ<br>';
    var_dump($eduDataArchive);
    echo '</pre>';

    echo '<pre>';
    echo '経理の薬用データ<br>';
    var_dump($kusuriData);
    echo '</pre>';
    die();
}

///////// ** 一覧のみ更新したい場合 ** /////////
if($_GET['data'] === 'recent'){
    $result_list['career'] = $sf->GetEventListData($query_job30, $flg_job30);
    $result_list['career'] = $sf->listDataSortFunc($result_list['career'],SORT_ASC, SORT_REGULAR,"compareDateForList");

    $result_list['edu']    = $sf->GetEventListData($query_edu30, $flg_edu30);
    $result_list['edu']    = $sf->listDataSortFunc($result_list['edu'],SORT_ASC, SORT_REGULAR,"compareDateForList");

    $result_list_json = json_encode($result_list,JSON_UNESCAPED_UNICODE);
    var_dump($result_list_json);
    file_put_contents("../assets_2009/json/event/event_list_info.json", $result_list_json);
    die();
}

///////// ** 終了したリストのみ更新したい場合 ** //////////
if($_GET['data'] === 'archive'){
    $archive_list['archive'] = $sf->GetEventListData($query_archive,$flg_archive);
    $archive_list_json = json_encode($archive_list,JSON_UNESCAPED_UNICODE);
    var_dump($archive_list_json);
    file_put_contents("../assets_2009/json/event/event_list_archive_info.json", $archive_list_json);
    die();
}

///////// ** 特定のイベントページIDのJSON作成 ** /////////
if(isset($_GET['eid'])){
    
    $SOQL_eid = "SELECT Name,RecordType.DeveloperName,EventPageTitle__c,EventPageId__c,WebDisplayCategory__c,EventOpenFlg__c,EventPickUpFlg__c,ImageURL1__c,EventLimit__c,CreatedDate,LastModifiedDate,(SELECT SeminarName__c,EventTableTabName__c,EventType__c,EventDate__c,EventDateSupText__c,EventTime__c,EventArea__c,Plan__c,Price__c,URL__c FROM RelEventPage__r WHERE EventDate__c > %s) FROM EventPage__c WHERE Name = '%s' ";

    $flg_eid = array (
        'eventDateConvert' => true,
        'priceConvert'     => true,
        'arrayReduce'      => true,
        'dateConvertFunc'  => false,
    );

    $query_eid = sprintf($SOQL_eid, $sf->getTodayInfo, $_GET['eid']);
    
    $result_list = $sf->GetEventEpData($query_eid,$flg_eid);
    $result_detail[$result_list[0]['Name']] = $sf->GetEventDetailData($result_list[0]['Name'],$result_list[0]);

    $result_detail_json = json_encode($result_detail[$result_list[0]['Name']],JSON_UNESCAPED_UNICODE);
    file_put_contents("../assets_2009/json/event/event_detail_".$result_list[0]['Name'].".json", $result_detail_json);
    echo 'ページID「'.$result_list[0]['Name'].'」のJSONファイルが生成されました<br>';

    echo '<pre>';
    var_dump($result_detail);
    echo '</pre>';
    die();
}

///////// ** 詳細ページ全JSON作成のみ ** /////////
if($_GET['data'] === 'detailAll'){
    $result_list['career'] = $sf->GetEventListData($SOQL_all,$flg_all);

    foreach($result_list['career'] as $key => $val){
        $result_detail[$val['Name']] = $sf->GetEventDetailData($val['Name'],$val);
    }

    foreach($result_detail as $key => $val){
        $result_detail_json[$key] = json_encode($result_detail[$key],JSON_UNESCAPED_UNICODE);
        file_put_contents("../assets_2009/json/event/event_detail_".$key.".json", $result_detail_json[$key]);
        echo 'キャリア関連セミナー ページID「'.$key.'」のJSONファイルが生成されました<br>';
    }
    //echo '<pre>';
    //var_dump($result_detail);
    //echo '</pre>';
    die();
}
?>

