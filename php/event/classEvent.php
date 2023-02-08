<?php

//ini_set('display_errors',1);
require_once("../common.php");

date_default_timezone_set('Asia/Tokyo');

//セミナー一覧情報の格納先
$jsonListFilePathSet  = '../assets_2009/json/event/event_list_info.json';

//エスケープ処理（単体）
function strEscape($post,$charset){
	return htmlspecialchars($post, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, $charset, true);
}

//エスケープ処理（配列）
function arrEscape($posts,$charset){
	$arrEscapesData = array();
	foreach($posts as $key => $value) {
		$arrEscapesData[$key] = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, $charset, true);
	}
	return $arrEscapesData;
}

class eventPageCreate {
	//本日の日付関連の情報
	public $today;
	public $today_limit;

	//設定（公開時に変更する）
	private $php_dir = 'event';
	private $tpl_dir = 'event';

	//各種設定の変数をclass内のプライベートにするため変数を宣言
	private $jsonListFilePath;
	private $eventListData   = array();
	private $eventDetailData = array();

	//セミナーイベントページ詳細用変数
	private $fileNamePath;
	private $filePathInfo;
	private $file_name_ext;
	private $file_name;
	private $event_page_id;
	private $schema_data = array();
	private $detailMainTplPath;

	//IPアドレス
	private $permission_ip_list =array (
		"xxx.xxx.x.x", "xxx.xxx.x.x"
	);
	private $access_ip;
	private $access_flg;

	//構造化データ
	const JUS_PLACE       = '森ビル';
	const JUS_ADDRESS     = '東京都港区新橋XXXXXX';
	const JUS_PRICEPLAN   = '無料';
	const JUS_CURRENCY    = 'JPY';
	const JUS_COMPANYNAME = 'XXXXXXX株式会社';
	const JUS_COMPANYURL  = 'https://www.xxxxx.xx.xx/';
	const JUS_LOGOIMG     = 'https://www.xxxxx.xx.xx/xxxx.svg';
	
	//コンストラクタ（クラス宣言時に処理される）
	public function __construct(){
		global $jsonListFilePathSet;
		$this->jsonListFilePath  = $jsonListFilePathSet;

		$this->today       = date("Y-m-d");
		$this->today_limit = date("Y-m-d H:i");

		$this->access_ip = $_SERVER['REMOTE_ADDR'];
	}

	//セミナー・イベントの詳細ページ用データの取得メソッド
	public function getEventDetailPageData(){
		//ファイル名取得
		$this->fileNamePath    = $_SERVER['REQUEST_URI'];
		$this->fileNamePath    = parse_url($this->fileNamePath, PHP_URL_PATH);
		$this->fileNamePath    = pathinfo($this->fileNamePath);

		$this->file_name_ext  = $this->fileNamePath['basename'];
		$this->file_name      = $this->fileNamePath['filename'];
		
		if (!empty($_GET['eid'])){
			$this->event_page_id = $_GET['eid'];
			$this->filePathInfo  = $this->file_name_ext.'?eid='.$_GET['eid'];
		} else {
			$this->event_page_id = $this->file_name;
			$this->filePathInfo  = $this->file_name_ext;
		}

		$json_path = file_get_contents('../assets_2009/json/event/event_detail_'.$this->event_page_id.'.json');

		//$json_pathがtrueならJSONデータを返す、falseならエラーテンプレート
		if($json_path){
			$this->eventDetailData  = json_decode($json_path, true);
			return $this->eventDetailData;
		} else {
			$this->errSmartyTemplateSet();
			die();
		}
	}

	//構造化データに関するメソッド
	public function autoCreateSchemaData($data){		
		//もし株式会社xxxxxが主催であれば、構造化データ（リッチリザルト）を自動設定する
		$count = count($data['allDateData']);
		if ($count > 2){
			$data['EventSchemaStartDate__c']     = $data['allDateData'][$count-1]['EventDate__c'];
			$data['EventSchemaEndDate__c']       = $data['allDateData'][0]['EventDate__c'];
			$data['EventSchemaValidFromDate__c'] = date('Y-m-d',strtotime($data['EventSchemaStartDate__c'].'-2 week'));

			if(!isset($data["EventSchemaPlace__c"])){
				$data['EventSchemaPlace__c']         = self::JUS_PLACE;
			}
			if(!isset($data['EventSchemaAddress__c'])){
				$data['EventSchemaAddress__c']       = self::JUS_ADDRESS;
			}
			if(!isset($data['EventSchemaPlan__c'])){
				$data['EventSchemaPlan__c']          = self::JUS_PRICEPLAN;
			}
			if(!isset($data['EventSchemaCurrency__c'])){
				$data['EventSchemaCurrency__c']      = self::JUS_CURRENCY;
			}
			if(!isset($data['EventSchemaPerformer__c'])){
				$data['EventSchemaPerformer__c']     = self::JUS_COMPANYNAME;
			}
			if(!isset($data['EventSchemaOrganizer__c'])){
				$data['EventSchemaOrganizer__c']     = self::JUS_COMPANYNAME;
			}
			if(!isset($data['EventSchemaOrganizerURL__c'])){
				$data['EventSchemaOrganizerURL__c']  = self::JUS_COMPANYURL;
			}
		} else {
			if(isset($data['allDateData'][0]['EventDate__c']) && ( ($data['WebDisplayCategory__c'] === 'consultation' && $data['EventPageId__c'] != 'coy001') || $data['WebDisplayCategory__c'] === 'indregistration')){
				$data['EventSchemaStartDate__c']     = date('Y-m-d',strtotime('first day of '.$data['allDateData'][0]['EventDate__c']));
			} else {
				$data['EventSchemaStartDate__c']     = $data['allDateData'][0]['EventDate__c'];
			}
			$data['EventSchemaEndDate__c']       = $data['allDateData'][0]['EventDate__c'];
			$data['EventSchemaValidFromDate__c'] = date('Y-m-d',strtotime($data['allDateData'][0]['EventDate__c'].'-2 week'));
			
			if(!isset($data["EventSchemaPlace__c"])){
				$data['EventSchemaPlace__c']         = self::JUS_PLACE;
			}
			if(!isset($data['EventSchemaAddress__c'])){
				$data['EventSchemaAddress__c']       = self::JUS_ADDRESS;
			}
			if(!isset($data['EventSchemaPlan__c'])){
				$data['EventSchemaPlan__c']          = self::JUS_PRICEPLAN;
			}
			if(!isset($data['EventSchemaCurrency__c'])){
				$data['EventSchemaCurrency__c']      = self::JUS_CURRENCY;
			}
			if(!isset($data['EventSchemaPerformer__c'])){
				$data['EventSchemaPerformer__c']     = self::JUS_COMPANYNAME;
			}
			if(!isset($data['EventSchemaOrganizer__c'])){
				$data['EventSchemaOrganizer__c']     = self::JUS_COMPANYNAME;
			}
			if(!isset($data['EventSchemaOrganizerURL__c'])){
				$data['EventSchemaOrganizerURL__c']  = self::JUS_COMPANYURL;
			}
		}
		return $data;
	}

	//エラー表示用のメソッド
	public function errSmartyTemplateSet(){
		$template = new MySmarty();
		$template->assign("page_title",'お探しのページは見つかりません');
		$template->assign("keywords", 'xxxxx,404');
		$template->assign("description", 'お探しのページは見つかりません');
		$template->assign("tpl_mainpage", "xx/event/err.tpl");
		$template->display("path/path2/path.tpl");
		die();
	}

	//表示用のメソッド
	public function smartyTemplateSet($seminar_data){

		//フラグが「非表示(none)」となっている場合は、社内の場合は確認できるようフラグ設定
		if($seminar_data["EventOpenFlg__c"] == "none"){
			$ip_chk_result = in_array($this->access_ip, $this->permission_ip_list);
			if($ip_chk_result){
				$this->access_flg = true;
			} else {
				$this->access_flg = false;
			}
		} else {
			$this->access_flg = true;
		}
		
		//smartyの宣言
		$template = new MySmarty();

		//記事テンプレートで使うデータを渡す変数設定
		$template->assign("edata", $seminar_data);
		
		//ヘッダー及びフッターなどの共通テンプレートに渡す変数設定
		$template->assign("fileName",      $this->file_name);
		$template->assign("filePathInfo",  $this->filePathInfo);
		$template->assign("php_dir",       $this->php_dir);
		$template->assign("tpl_dir",       $this->tpl_dir);
		$template->assign("today_limit",   $this->today_limit);
		$template->assign("keywords",      $seminar_data['EventMetaKeyword__c']);
		$template->assign("description",   $seminar_data['EventMetaDescription__c']);
		$template->assign("open_flg",      $seminar_data['EventOpenFlg__c']);
		$template->assign("canonical_flg", $seminar_data['EventMetaCanonicalFlg__c']);
		$template->assign("page_title",    $seminar_data['EventPageTitle__c']);

		if(isset($seminar_data['EventDetailCss__c'])){
			$template->assign("css_flg",   $seminar_data['EventDetailCss__c']);
		}

		//セミナー・イベントの講師データがあれば、設定
		if(isset($seminar_data['lecturesData'])){
			$template->assign("lecturers", $seminar_data['lecturesData']);
		}

		//meta_imageの設定
		if(isset($seminar_data['ImageURL2__c'])){
			$template->assign("meta_image", $seminar_data['ImageURL2__c']);
		} else {
			if(isset($seminar_data['ImageURL1__c'])){
				$template->assign("meta_image", $seminar_data['ImageURL1__c']);
			} else {
				$template->assign("meta_image", self::JUS_LOGOIMG);
			}
		}

		//CanonicalFlgがtureならば、URLを設定
		if($seminar_data['EventMetaCanonicalFlg__c']){
			$template->assign("canonical", $seminar_data['EventMetaCanonicalURL__c']);
		}

		//カテゴリIDからパンくずのタイトルを設定
		switch($seminar_data['WebDisplayCategory__c']){
			case 'recruit':
			$template->assign("cate_title", '採用セミナー一覧');
			break;
				
			case 'careerup':
			$template->assign("cate_title", 'キャリアアップセミナー一覧');
			break;
				
			case 'skillup':
			$template->assign("cate_title", 'スキルアップセミナー一覧');
			break;
				
			case 'consultation':
			$template->assign("cate_title", '個別転職相談会一覧');
			break;
				
			case 'indregistration':
			$template->assign("cate_title", '個別登録会一覧');
			break;
				
			default:
			$template->assign("cate_title", 'セミナー・イベント');
		}
		$template->assign("cateid", $seminar_data['WebDisplayCategory__c']);

		//構造化データ
		if(isset($seminar_data["allDateData"][0]["EventType__c"])){
			$this->schema_data["EventSchemaVenueType__c"]     = $seminar_data["allDateData"][0]["EventType__c"];
		}
		if(isset($seminar_data["allDateData"][0]["Plan__c"])){
			$this->schema_data["EventSchemaPlan__c"]          = $seminar_data["allDateData"][0]["Plan__c"];
		}
		if(isset($seminar_data["allDateData"][0]["Price__c"])){
			$this->schema_data["EventSchemaPrice__c"]         = $seminar_data["allDateData"][0]["Price__c"];
		}
		if(isset($seminar_data["EventSchemaAddress__c"])){
			$this->schema_data["EventSchemaAddress__c"]       = $seminar_data["EventSchemaAddress__c"];
		}
		if(isset($seminar_data["EventSchemaCurrency__c"])){
			$this->schema_data["EventSchemaCurrency__c"]      = $seminar_data["EventSchemaCurrency__c"]; 
		}
		if(isset($seminar_data["EventSchemaFlg__c"])){
			$this->schema_data["EventSchemaFlg__c"]           = $seminar_data["EventSchemaFlg__c"];
		}
		if(isset($seminar_data["EventSchemaOrganizerURL__c"])){
			$this->schema_data["EventSchemaOrganizerURL__c"]  = $seminar_data["EventSchemaOrganizerURL__c"];
		}
		if(isset($seminar_data["EventSchemaOrganizer__c"])){
			$this->schema_data["EventSchemaOrganizer__c"]     = $seminar_data["EventSchemaOrganizer__c"];
		}
		if(isset($seminar_data["EventSchemaPerformer__c"])){
			$this->schema_data["EventSchemaPerformer__c"]     = $seminar_data["EventSchemaPerformer__c"];
		}
		if(isset($seminar_data["EventSchemaPlace__c"])){
			$this->schema_data["EventSchemaPlace__c"]         = $seminar_data["EventSchemaPlace__c"];
		}
		
		if(isset($seminar_data["EventSchemaStartDate__c"])){
			$this->schema_data["EventSchemaStartDate__c"]     = $seminar_data["EventSchemaStartDate__c"];
		} else {
			// EventPageId__c に cokという文字が含まれていれば、自動算出した日付を入れる。（一致しないと戻り値はfalse）
			if(strpos($seminar_data["EventPageId__c"],"cok") !== false ) {
				$this->schema_data["EventSchemaStartDate__c"] = date("Y-m-d",strtotime('first day of this month'));
			}
		}

		if(isset($seminar_data["EventSchemaEndDate__c"])){
			$this->schema_data["EventSchemaEndDate__c"]       = $seminar_data["EventSchemaEndDate__c"];
		} else {
			// EventPageId__c に cokという文字が含まれていれば、自動算出した日付を入れる。（一致しないと戻り値はfalse）
			if(strpos($seminar_data["EventPageId__c"],"cok") !== false ) {
				$this->schema_data["EventSchemaEndDate__c"] = $seminar_data["allDateData"][0]["EventDate__c"];
			}
		}

		if(isset($seminar_data["EventSchemaValidFromDate__c"])){
			$this->schema_data["EventSchemaValidFromDate__c"] = $seminar_data["EventSchemaValidFromDate__c"];
		} else {
			// EventPageId__c に cokという文字が含まれていれば、自動算出した日付を入れる。（一致しないと戻り値はfalse）
			if(strpos($seminar_data["EventPageId__c"],"cok") !== false ) {
				$this->schema_data["EventSchemaValidFromDate__c"] = date("Y-m-d",strtotime('first day of this month'));
			}
		}
	
		$template->assign("schema", $this->schema_data);

		//タイトルの上書き用
		require_once ("./common_title.php");

		//boydに付与するID名
		$template->assign("main_id", "event-detail");
		
		if($this->access_flg){
			//コンテンツ部分のtplを指定 [.]で文字列をつないでいる
			if($seminar_data['EventTemplateId__c'] === 'landing_page'){
				$this->detailMainTplPath =  "cc/".$this->tpl_dir."/".$this->event_page_id.".tpl";
				$template->display($this->detailMainTplPath);
				die();
			} elseif($seminar_data['EventTemplateId__c'] === 'read_custom_tpl'){
				$this->detailMainTplPath =  "cc/".$this->tpl_dir."/".$this->event_page_id.".tpl";
			} else {
				$this->detailMainTplPath =  "cc/".$this->tpl_dir."/".$seminar_data['EventTemplateId__c'].".tpl";
			}
			$template->assign("tpl_mainpage", $this->detailMainTplPath);

			//レイアウト管理のtplを呼び出す
			$template->display("cc/renew_2009/site_frame_for_event.tpl");
			die();
		} else {
			$this->errSmartyTemplateSet();
			die();
		}
	}

	//一覧用のJSONデータ（全イベント）取得メソッド
	public function getEventListJson(){
		$json_path = file_get_contents($this->jsonListFilePath);
		if($json_path){
			$this->eventListData  = json_decode($json_path, true);
			return $this->eventListData;
		} else {
			$this->errSmartyTemplateSet();
			die();
		}
	}

	//一覧の表示用メソッド
	public function smartyTemplateSetForList($seminar_data,$categoryid){

		//変数受け渡し
		$template = new MySmarty();
		$template->assign("fileName", 'index');
		$template->assign("php_dir",  $this->php_dir);
		$template->assign("tpl_dir",  $this->tpl_dir);
		$template->assign("canonical_flg", false);

		//GETパラメータで設定されるmeta情報の設定
		switch($categoryid){
			case 'recruit':
			$template->assign("page_images",'');
			$template->assign("page_title", '採用セミナー一覧');
			$template->assign("keywords", 'セミナー,イベント,,xxxxxx');
			$template->assign("description",'xxxxxxで開催中の採用セミナー一覧はこちら。人気の監査法人や大手企業の採用担当者と直接話せる採用説明会・選考会です');
			break;
				
			case 'careerup':
			$template->assign("page_images",'');
			$template->assign("page_title", 'キャリアアップセミナー一覧');
			$template->assign("keywords", 'セミナー,イベント,,xxxxxx');
			$template->assign("description",'xxxxxxで開催中のキャリアアップセミナー一覧はこちら。会計業界の動向や求人動向、転職ノウハウなどを学べます');
			break;
				
			case 'skillup':
			$template->assign("page_images",'');
			$template->assign("page_title", 'スキルアップセミナー一覧');
			$template->assign("keywords", 'セミナー,イベント,,xxxxxx');
			$template->assign("description",'xxxxxxで開催中のスキルアップセミナー一覧はこちら。仕事に活かせる実務などを学べます');
			break;
				
			case 'consultation':
			$template->assign("page_images",'');
			$template->assign("page_title", '個別転職相談会一覧');
			$template->assign("keywords", 'セミナー,イベント,,xxxxxx');
			$template->assign("description",'xxxxxxで開催中の個別転職相談会一覧はこちら。');
			break;
				
			case 'indregistration':
			$template->assign("page_images",'');
			$template->assign("page_title", '個別登録会一覧');
			$template->assign("keywords", 'セミナー,イベント,,xxxxxx');
			$template->assign("description",'xxxxxxで開催中の個別登録会一覧はこちら。');
			break;
				
			case 'kansai':
			$template->assign("page_images",'');
			$template->assign("page_title", '関西のセミナー・イベント');
			$template->assign("keywords", '関西,大阪,キャリア相談,,xxxxxx');
			$template->assign("description",'xxxxxxxは公認会計士の方を対象とした無料の転職相談を行っております。');
			break;
			
			default:
			$template->assign("page_images",'');
			$template->assign("page_title", 'セミナー・イベント');
			$template->assign("keywords", 'セミナー,イベント,,xxxxxx');
			$template->assign("description",'xxxxxxで開催中のセミナー・イベントはこちら。');
		}
		
		$template->assign("css_flg", 'top');
		$template->assign("edata", $seminar_data);
		$template->assign("today_limit", $this->today_limit);
		$template->assign("cateid", $categoryid);

		$this->schema_data["EventSchemaFlg__c"] = false;
		$template->assign("schema", $this->schema_data);

		//タイトルの上書き用
		require_once ("./common_title.php");

		//boydに付与するID名
		$template->assign("main_id", "event-index");

		//コンテンツ部分のtplを指定
		$template->assign("tpl_mainpage", "path/".$this->tpl_dir."/index_".$categoryid.".tpl");

		//レイアウト管理のtplを呼び出す
		$template->display("path1/path2/path.tpl");
		die();
	}
}
?>
