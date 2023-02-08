<?php
	//ini_set('display_errors',1);

	//各種設定
	require_once(__DIR__.'/config.php');
	
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

	//SalesForceと通信するためのクラス
	class MySalesForceAccessForEvent {
		
		//各種設定の変数をclass内のプライベートにするため変数を宣言
		private $nameSpace1;
		private $nameSpace2;
		private $wsdl;
		private $logindata;
		
		//関数SOAPforSFLoginで取得した結果
		private $metaServerUrl;
		private $sessionId;
		private $serverUrl;
		private $SFClientLogin;  //SFから値を取る場合

		//曜日設定
		private $weekNumberTranslate = ['日','月','火','水','木','金','土'];
		public  $getTodayInfo;

		//個別相談会用の設定
		public $lastDayNumber;     //今月末の曜日を入れる変数
		public $reserveDayNumber;  //個別登録会の最終日を入れる変数

		//コンストラクタ（クラス宣言時に処理される）
		public function __construct(){
			
			//各種設定にある変数
			global $nameSpace1;
			global $nameSpace2;
			global $wsdl;
			global $logindata;
			
			//プライベート変数に格納
			$this->nameSpace1 = $nameSpace1;
			$this->nameSpace2 = $nameSpace2;
			$this->wsdl       = $wsdl;
			$this->logindata  = $logindata;
			$this->getTodayInfo = date("Y-m-d");
		}
		
		//SFへのログイン関数
		public function SOAPforSFLogin(){
			
			// システム管理者でログイン実行
			try {
				$soapClient = new SoapClient($this->wsdl, array(
					'trace' => true,
					'cache_wsdl' => WSDL_CACHE_NONE
				));
				$response = $soapClient->login($this->logindata);
			} catch(exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
				$errMessage = '予期せぬエラーが発生しました。';
				die();
			}
			
			//ログイン後にSFから返ってくる各値を設定
			if (isset($response->result->userInfo)){
				$this->metaServerUrl  = $response->result->metadataServerUrl;
				$this->sessionId      = $response->result->sessionId;
				$this->serverUrl      = $response->result->serverUrl;

				// SFにSOAP通信でアクセスするためのヘッダーの設定
				$headers = array();
				$headers[] = new SoapHeader($this->nameSpace2, "SessionHeader", array ('sessionId' => $this->sessionId));
				
				//Locationの設定
				$soapClient->__setLocation($this->serverUrl);

				//SessionHeaderの設定
				$soapClient->__setSoapHeaders($headers);
				
				//SFへログインする管理者情報を格納
				$this->SFClientLogin  = $soapClient;
				
				return true;
			} else {
				$errMessage = '予期せぬエラーが発生しました。';
				return false;
			}
		}

		/*////////////////////////////////////////////
		 変換系メソッド
		////////////////////////////////////////////*/

		//配列データを移動して元のデータを削除するメソッド（関数）
		public function arrayReduce($data,$dataArrayFlg,$targetKeyName,$addKeyName){
			//$dataが配列なら、$dataArrayFlgはtrueにする。
			if($dataArrayFlg){
				foreach($data as $key => $val){
					if(isset($data[$key][$targetKeyName])){
						if($val[$targetKeyName]['size'] > 1){
							$data[$key][$addKeyName] = $val[$targetKeyName]['records'];
							unset($data[$key][$targetKeyName]);
						} else {
							$data[$key][$addKeyName][0] = $val[$targetKeyName]['records'];
							unset($data[$key][$targetKeyName]);
						}
					//$targetKeyName ががないので、削除する。
					} else {
						unset($data[$key]);
					}
				}
				return $data;
			} else {
				if(isset($data[$targetKeyName])){
					if($data[$targetKeyName]['size'] > 1){
						$data[$addKeyName] = $data[$targetKeyName]['records'];
						unset($data[$targetKeyName]);
					} else {
						$data[$addKeyName][0] = $data[$targetKeyName]['records'];
						unset($data[$targetKeyName]);
					}
				} else {
					unset($data[$key]);
				}
				return $data;
			}
		}

		//日付フォーマットを変換するメソッド（関数）
		public function dateConvertFunc($data,$key,$val,$targetKeyName){
			if(!is_null($key) && !is_null($val) && !is_null($targetKeyName)){
				foreach($val[$targetKeyName] as $key2 => $val2) {
					//開催日 … EventDate__c がある場合の日付変換処理
					if(isset($val2['EventDate__c'])){
						$data[$key][$targetKeyName][$key2]['EventDate_cnv'] = str_replace("-", "/", $val2['EventDate__c']);
						$data[$key][$targetKeyName][$key2]['EventDate_ynj'] = date("Y年n月j日", strtotime($val2['EventDate__c']));
						$data[$key][$targetKeyName][$key2]['EventDate_yn']  = date("Y年n月", strtotime($val2['EventDate__c']));
	
						$singleDayNumber = date('w',strtotime($val2['EventDate__c']));
	
						$data[$key][$targetKeyName][$key2]['EventDate_dayOfWeek'] = '('.$this->weekNumberTranslate[$singleDayNumber].')';
						$data[$key][$targetKeyName][$key2]['EventDate_cnv2'] = $data[$key][$targetKeyName][$key2]['EventDate_cnv'].$data[$key][$targetKeyName][$key2]['EventDate_dayOfWeek'];
						$data[$key][$targetKeyName][$key2]['EventDate_ynj2'] = $data[$key][$targetKeyName][$key2]['EventDate_ynj'].$data[$key][$targetKeyName][$key2]['EventDate_dayOfWeek'];
					}
				}
				//$data[$key]["compareDateForList"] = $data[$key][$targetKeyName][0]['EventDate_cnv'];
				return $data;
			} else {
				foreach($data as $key => $val) {
					if(isset($val['EventDate__c'])){
						$data[$key]['EventDate_cnv'] = str_replace("-", "/", $val['EventDate__c']);
						$data[$key]['EventDate_ynj'] = date("Y年n月j日", strtotime($val['EventDate__c']));
						$data[$key]['EventDate_yn']  = date("Y年n月", strtotime($val['EventDate__c']));
	
						$singleDayNumber = date('w',strtotime($val['EventDate__c']));
	
						$data[$key]['EventDate_dayOfWeek'] = '('.$this->weekNumberTranslate[$singleDayNumber].')';
						$data[$key]['EventDate_cnv2'] = $data[$key]['EventDate_cnv'].$data[$key]['EventDate_dayOfWeek'];
						$data[$key]['EventDate_ynj2'] = $data[$key]['EventDate_ynj'].$data[$key]['EventDate_dayOfWeek'];
						//$data[$key]["compareDateForList"] = $data[$key]['EventDate_cnv'];
					}
				}
				return $data;
			}
		}

		public function createCompareDateFunc($data,$key,$val,$targetKeyName){
			$count = count($val[$targetKeyName]);
			if($count > 1) {
				$data[$key]["compareDateForList"] = $data[$key][$targetKeyName][$count-1]['EventDate_cnv'];
			} else {
				if(isset($data[$key][$targetKeyName][0]['EventDate_cnv'])){
					$data[$key]["compareDateForList"] = $data[$key][$targetKeyName][0]['EventDate_cnv'];
				}
			}
			return $data;
		}

		//公開期限の日付フォーマットを変換するメソッド（関数）
		public function dateLimitConvertFunc($data,$targetKeyName){
			//セミナー公開期限 … EventLimit__c がある場合の日付変換処理
			$data['EventLimit_cnv'] = str_replace("-", "/", $data[$targetKeyName]);
			$data['EventLimit_ynj'] = date("Y年n月j日", strtotime($data[$targetKeyName]));
			$data['EventLimit_hi']   = date("H:i", strtotime($data[$targetKeyName]));

			$singleDayNumber = date('w',strtotime($data[$targetKeyName]));
			$data['EventLimit_dayOfWeek'] = '('.$this->weekNumberTranslate[$singleDayNumber].')';
			return $data;
		}

		//日付順に並び変えるメソッド（関数）
		public function dataSortFunc($data,$order,$sort_flg,$foreachKeyName,$targetKeyName){
			$sort_criterion = array();
			if(!is_null($targetKeyName)){
				foreach ($data[$foreachKeyName] as $key => $val){
					$sort_criterion[$key] = $val[$targetKeyName];
				}
				$result = array_multisort($sort_criterion, $order, $sort_flg, $data[$foreachKeyName]);
				if($result){
					return $data;
				} else {
					echo 'ソートエラーです。';
					die();
				}
			} else {
				foreach ($data[$foreachKeyName] as $key => $val){
					$sort_criterion[] = $val;
				}
				$result = array_multisort($sort_criterion, $order, $sort_flg, $data[$foreachKeyName]);
				if($result){
					return $data;
				} else {
					echo 'ソートエラーです。';
					die();
				}
			}
		}

		public function listDataSortFunc($data,$order,$sort_flg,$targetKeyName){
			$sort_criterion = array();
			foreach ($data as $key => $val){
				if(isset($data[$key][$targetKeyName])){
					$sort_criterion[$key] = $val[$targetKeyName];
				} else {
					$sort_criterion[$key] = '1970/01/01';
				}
			}
			$result = array_multisort($sort_criterion, $order, $sort_flg, $data);
			if($result){
				return $data;
			} else {
				echo 'ソートエラーです。';
				die();
			}
		}

		//EventDate_cnv に結合した値を入れる
		public function dataJoinFunc($data,$createKeyName,$targetKeyName,$targetKeyName2){
			if(!is_null($targetKeyName2)){
				$count = count($data[$targetKeyName]);
				$data[$createKeyName] = $data[$targetKeyName][$count-1][$targetKeyName2]."～". $data[$targetKeyName][0][$targetKeyName2];
				return $data;
			} else {
				$count = count($data[$targetKeyName]);
				$data[$createKeyName] = $data[$targetKeyName][$count-1]."～". $data[$targetKeyName][0];
				return $data;
			}
		}
		
		//個別相談会向け 月の最終日を算出し日程として設定するメソッド（関数）  
		public function dateConvertFuncForLastOfDay($data,$key,$val,$targetKeyName){
			$this->lastDayNumber = date('w', strtotime('last day of this month'));
			switch ($this->lastDayNumber) {
				case 0: //日曜
				case 6: //土曜
					//上記の場合は、営業日である金曜日にする
					$this->reserveDayNumber = date('w', strtotime('last fri of this month'));
					$data[$key][$targetKeyName][0]['EventDate__c']  = date("Y-m-d", strtotime('last fri of this month'));
					$data[$key][$targetKeyName][0]['EventDate_cnv'] = date("Y/m/d", strtotime('last fri of this month'));
					$data[$key][$targetKeyName][0]['EventDate_ynj'] = date("Y年n月j日", strtotime('last fri of this month'));
					$data[$key][$targetKeyName][0]['EventDate_yn'] = date("Y年n月", strtotime('last fri of this month'));
					$data[$key][$targetKeyName][0]['EventDate_dayOfWeek'] = "（".$this->weekNumberTranslate[$this->reserveDayNumber]."）";
					$data[$key][$targetKeyName][0]['EventDate_cnv2'] = "～".$data[$key][$targetKeyName][0]['EventDate_cnv'].$data[$key][$targetKeyName][0]['EventDate_dayOfWeek']."まで";
					$data[$key][$targetKeyName][0]['EventDate_ynj2'] = "～".$data[$key][$targetKeyName][0]['EventDate_ynj'].$data[$key][$targetKeyName][0]['EventDate_dayOfWeek']."まで";
					$data[$key]["compareDateForList"] = $data[$key][$targetKeyName][0]['EventDate_cnv'];
				break;
				default: //日曜と土曜以外ならその日の最終日にする
					$data[$key][$targetKeyName][0]['EventDate__c']  = date("Y-m-d", strtotime('last day of this month'));
					$data[$key][$targetKeyName][0]['EventDate_cnv'] = date("Y/m/d", strtotime('last day of this month'));
					$data[$key][$targetKeyName][0]['EventDate_ynj'] = date("Y年n月j日", strtotime('last day of this month'));
					$data[$key][$targetKeyName][0]['EventDate_yn'] = date("Y年n月", strtotime('last day of this month'));
					$data[$key][$targetKeyName][0]['EventDate_dayOfWeek'] = "（".$this->weekNumberTranslate[$this->lastDayNumber]."）";
					$data[$key][$targetKeyName][0]['EventDate_cnv2'] = "～".$data[$key][$targetKeyName][0]['EventDate_cnv'].$data[$key][$targetKeyName][0]['EventDate_dayOfWeek']."まで";
					$data[$key][$targetKeyName][0]['EventDate_ynj2'] = "～".$data[$key][$targetKeyName][0]['EventDate_ynj'].$data[$key][$targetKeyName][0]['EventDate_dayOfWeek']."まで";
					$data[$key]["compareDateForList"] = $data[$key][$targetKeyName][0]['EventDate_cnv'];
			}
			return $data;
		}

		//金額表記を変換および最安値と最高値の結合 ex.14,500円、ex2.14,500円～19,000円
		public function priceConvert($data,$customApiName){
			//価格データが複数存在する場合のフラグ用変数
			$maxPriceSortFlg;

			foreach($data as $key => $val){
				if(isset($val['RelEventPage__r'])){
					//日付データが2つ以上ある場合
					if($val['RelEventPage__r']['size'] > 1){
						foreach($val['RelEventPage__r']['records'] as $key2 => $val2){
							if (isset($val2[$customApiName])){
								if(strpos($val2[$customApiName],'～') !== false){
									$data[$key]['Price_exp'][] = explode("～", $val2[$customApiName]);
								} else {
									$data[$key]['Price'][] = $val2[$customApiName];
								}
							}
						}
						$maxPriceSortFlg = true;
					//日付データが1つの場合
					} else {
						if (isset($val['RelEventPage__r']['records'][$customApiName])){
							$data[$key]['Price'][0] = $val['RelEventPage__r']['records'][$customApiName];
						}
						$maxPriceSortFlg = false;
					}

					//ソートフラグがtrueなら、価格を降順にし、最安値と最高値を結合する。
					if(isset($data[$key]['Price_exp']) && $maxPriceSortFlg){
						$data[$key]['Price_exp_sort'] = array_reduce($data[$key]['Price_exp'],"array_merge",array());
						rsort($data[$key]['Price_exp_sort']);
						$data[$key] = $this->dataJoinFunc($data[$key], 'Price_exp_sort_cnv', 'Price_exp_sort', NULL);
					} elseif (isset($data[$key]['Price']) && $maxPriceSortFlg) {
						rsort($data[$key]['Price']);
						$data[$key]['Price'] = array_unique($data[$key]['Price']);
						if(count($data[$key]['Price']) > 1 ){
							$data[$key]['Price'] = array_values($data[$key]['Price']);
							$data[$key] = $this->dataJoinFunc($data[$key], 'Price_sort_cnv', 'Price', NULL);
						}
					}
				}
			}			
			return $data;
		}

		//詳細用 イベント詳細が複数ある場合に使用
		public function customEventDetailTableData($data,$customApiName){
			//値がセットされていれば処理、なければ処理せず$dataをそのまま戻す
			if(isset($data[$customApiName])){
				$data[$customApiName] = explode("\n\n[section]\n\n", $data[$customApiName]);

				foreach($data[$customApiName] as $key => $val ){
					$data[$customApiName][$key] = explode("\n$$$", $data[$customApiName][$key]);
					foreach($data[$customApiName][$key] as $key2 => $val2 ){
						$data[$customApiName][$key][$key2] = explode(";\n", $data[$customApiName][$key][$key2]);
					}
				}
				return $data;
			} else {
				return $data;
			}
		}

		//詳細用 フォームなど複数の情報がある場合の分割処理
		public function eventDetailDataExplode($data,$sfApiName,$explode1,$explode2){
			//値がセットされていれば処理、なければ処理せず$dataをそのまま戻す
			//開催日時（複数） … RegistFormMultiple__c がある場合、改行(\n)で分割する。
			if(isset($data[$sfApiName])){
				if(isset($explode1)){
					$data[$sfApiName] = explode($explode1, $data[$sfApiName]);
				}
				if(isset($explode2)){
					foreach($data[$sfApiName] as $key => $val ){
						$data[$sfApiName][$key] = explode($explode2, $data[$sfApiName][$key]);
					}
				}
				return $data;
			} else {
				return $data;
			}
		}

		//日付変換して追加するメソッド（関数）
		public function eventDateConvert($data){

			//セミナーイベント日程に関する日付変換処理
			foreach($data as $key => $val){
				if($val['WebDisplayCategory__c'] === 'consultation' || $val['WebDisplayCategory__c'] === 'indregistration'){
					//個別相談会や個別登録会のレコードタイプだが、日付データがセットされている場合は、日付変換して戻す
					if(isset($val['allDateData'][0]['EventDate__c']) ){
						$data = $this->dateConvertFunc($data,$key,$val,'allDateData');

					//個別相談会や個別登録会のレコードタイプで、開催日データがない場合、開催日を自動算出し、日付変換して戻す
					} else {
						$data = $this->dateConvertFuncForLastOfDay($data,$key,$val,'allDateData');
					}
				} else {
					//個別相談会や個別登録会のレコードタイプ以外は、日付を変換して戻す
					if(isset($val['allDateData'])){
						$data = $this->dateConvertFunc($data,$key,$val,'allDateData');
					}
				}
			}
			
			//紐づいている日程データの並び変え
			foreach($data as $key => $val){
				//日程データが2つ以上あれば、日付の並び変え
				if(isset($val['allDateData']) && count($val['allDateData']) > 1){
					$data[$key] = $this->dataSortFunc($data[$key], SORT_DESC, SORT_REGULAR, 'allDateData', 'EventDate__c');
					$data[$key] = $this->dataJoinFunc($data[$key], 'allDateData_cnv', 'allDateData', 'EventDate_cnv2');
					$data[$key] = $this->dataJoinFunc($data[$key], 'allDateData_cnv2', 'allDateData', 'EventDate_ynj2');
				}
				
				//一覧比較用のデータ作成
				$data = $this->createCompareDateFunc($data,$key,$val,'allDateData');
			}

			//セミナーイベント公開期限に関する処理
			foreach($data as $key => $val){
				if(isset($val['EventLimit__c'])){
					$data[$key] = $this->dateLimitConvertFunc($data[$key],"EventLimit__c");
				}
			}
			return $data;
		}

		/*////////////////////////////////////////////
		 取得メソッド
		////////////////////////////////////////////*/

		//転職セミナー作成用のページId取得メソッド（関数）
		public function GetEventPageId(){
			try {
				$SOQL = "SELECT Name FROM EventPage__c WHERE Category1st__c = '転職セミナー' ";
				$query = $SOQL;
				$response = $this->SFClientLogin->query(array (
						'queryString' => $query
				));
				
				if($response->result->size > 0){
					//取得してるデータはオブジェクトなので配列に変換
					$response = json_decode(json_encode($response->result->records),true);
					return $response;
				} else {
					return false;
				}
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
				die();
			}		
		}


		//一覧用の情報を取得するメソッド（関数）
		public function GetEventListData($sqlQuery,$flg_array){
			try {
				$response = $this->SFClientLogin->query(array (
						'queryString' => $sqlQuery
				));
				
				if($response->result->size > 0){
					//取得してるデータはオブジェクトなので配列に変換
					$response = json_decode(json_encode($response->result->records),true);
					
					//金額表記の変換
					if($flg_array['priceConvert']){
						$response = $this->priceConvert($response,"Price__c");
					}

					//取得した日付の配列データの移動し、重複データ（元データ）削除
					if($flg_array['arrayReduce']){
						$response = $this->arrayReduce($response, true, "RelEventPage__r","allDateData");
					}

					//日付変換
					if($flg_array['eventDateConvert']){
						$response = $this->eventDateConvert($response);
					}

					//日付変換
					if($flg_array['dateConvertFunc']){
						$response = $this->dateConvertFunc($response,NULL,NULL,NULL);
					}
					
					return $response;
				} else {
					return false;
				}
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
				die();
			}		
		}

		//任意のイベントIDの情報を取得するメソッド（関数）
		public function GetEventEpData($sqlQuery,$flg_array){
			try {
				$response = $this->SFClientLogin->query(array (
						'queryString' => $sqlQuery
				));
				
				if($response->result->size === 1){
					//取得してるデータはオブジェクトなので配列に変換
					$cnv_response = json_decode(json_encode($response->result->records),true);
					
					$response = array();
					$response[0] = $cnv_response;

					echo '<pre>';
					var_dump($response);
					echo '</pre>';
					
					//金額表記の変換
					if($flg_array['priceConvert']){
						$response = $this->priceConvert($response,"Price__c");
					}

					//取得した日付の配列データの移動し、重複データ（元データ）削除
					if($flg_array['arrayReduce']){
						$response = $this->arrayReduce($response, true, "RelEventPage__r","allDateData");
					}

					//日付変換
					if($flg_array['eventDateConvert']){
						$response = $this->eventDateConvert($response);
					}

					//日付変換
					if($flg_array['dateConvertFunc']){
						$response = $this->dateConvertFunc($response,NULL,NULL,NULL);
					}
					
					return $response;
				} else {
					return false;
				}
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
				die();
			}		
		}

		//転職セミナー用 詳細用の情報を取得する
		public function GetEventDetailData($pageId,$dateDatum){
			try {
				$SOQL = "SELECT 
				RecordType.DeveloperName,
				EventPageTitle__c,
				EventPlaceFlg__c,
				EventDiscountFlg__c,
				EventDiscountFlgURL__c,
				EventMetaKeyword__c,
				EventMetaDescription__c,
				EventMetaCanonicalFlg__c,
				EventMetaCanonicalURL__c,
				EventPageId__c,
				WebDisplayCategory__c,
				EventOpenFlg__c,
				EventDetailCss__c,
				EventDateDisplayFlg__c,
				EventPickUpFlg__c,
				ImageURL1__c,
				ImageURL2__c,
				EventLimit__c,
				EventDateException__c,
				EventLecturesName__c,
				RegistFormType__c,
				RegistFormId__c,
				RegistFormSeminarId__c,
				RegistFormMultiple__c,
				LoginFormId__c,
				LoginFormType__c,
				LoginFormMultiple__c,
				EventTemplateId__c,
				EventTemplateDateSupplement__c,
				CustomTemplateTitle1__c,
				CustomTemplateContents1__c,
				CustomTemplateTitle2__c,
				CustomTemplateContents2__c,
				CustomTemplateTitle3__c,
				CustomTemplateContents3__c,
				CustomTemplateTitle4__c,
				CustomTemplateContents4__c,
				CustomTemplateTitle5__c,
				CustomTemplateContents5__c,
				CustomTemplateTitle6__c,
				CustomTemplateContents6__c,
				CustomTemplateTitle7__c,
				CustomTemplateContents7__c,
				CustomTemplateTableTitle__c,
				CustomTemplateTable__c,
				CustomTemplateTableTitle2__c,
				CustomTemplateTable2__c,
				CustomTemplateLocation__c,
				EventSchemaType__c,
				EventSchemaFlg__c,
				EventSchemaPlace__c,
				EventSchemaAddress__c,
				EventSchemaStartDate__c,
				EventSchemaEndDate__c,
				EventSchemaCurrency__c,
				EventSchemaValidFromDate__c,
				EventSchemaPerformer__c,
				EventSchemaOrganizer__c,
				EventSchemaOrganizerURL__c 
				FROM EventPage__c WHERE Name = '%s'";
				$query = sprintf($SOQL,$pageId);
				$response = $this->SFClientLogin->query(array (
						'queryString' => $query
				));

				
				if($response->result->size > 0){
					//取得してるデータはオブジェクトなので配列に変換
					$response = json_decode(json_encode($response->result->records),true);

					//一覧で変換した日付データを追加
					if(isset($dateDatum['allDateData'])){
						$response['allDateData']          = $dateDatum['allDateData'];
					}
					if(isset($dateDatum['allDateData_cnv'])){
						$response['allDateData_cnv']      = $dateDatum['allDateData_cnv'];
					}
					if(isset($dateDatum['allDateData_cnv2'])){
						$response['allDateData_cnv2']      = $dateDatum['allDateData_cnv2'];
					}
					if(isset($dateDatum['EventLimit_cnv'])){
						$response['EventLimit_cnv']       = $dateDatum['EventLimit_cnv'];
					}
					if(isset($dateDatum['EventLimit_ynj'])){
						$response['EventLimit_ynj']       = $dateDatum['EventLimit_ynj'];
					}
					if(isset($dateDatum['EventLimit_hi'])){
						$response['EventLimit_hi']        = $dateDatum['EventLimit_hi'];
					}
					if(isset($dateDatum['EventLimit_dayOfWeek'])){
						$response['EventLimit_dayOfWeek'] = $dateDatum['EventLimit_dayOfWeek'];
					}
					
					if(isset($response['RelEventLecturers__r'])){
						$response = $this->arrayReduce($response, false, "RelEventLecturers__r","lecturesData");
					}

					//カスタム項目でテーブル型の枠に記載した場合のデータ処理
					$response = $this->customEventDetailTableData($response, 'CustomTemplateTable__c');
					$response = $this->customEventDetailTableData($response, 'CustomTemplateTable2__c');

					//項目を分割して配列にし値を戻す処理 "\n"は改行を表す
					$response = $this->eventDetailDataExplode($response,'EventLecturesName__c',";",null);
					$response = $this->eventDetailDataExplode($response,'CustomTemplateLocation__c',"\n",null);
					$response = $this->eventDetailDataExplode($response,'RegistFormMultiple__c',"\n",",");
					$response = $this->eventDetailDataExplode($response,'LoginFormMultiple__c',"\n",",");
					$response = $this->eventDetailDataExplode($response,'EventTemplateContents2__c',"\n",null);

					return $response;
				} else {
					return false;
				}
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
				die();
			}		
		}
	}
?>
