<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Facades\SF;

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Shared\ZipArchive;

class MypageResumeCreateController extends Controller
{

    public function wordCreate(Request $request)
    {
		$data = $request->all();

        // テンプレート読み込み
		//md5(uniqid().mt_rand()
		$wordFilePath = "/var/www/html/mypage/resources/word/";
        $template = new TemplateProcessor($wordFilePath.'sample1.docx');

		foreach($data as $key => $val){
			if(!is_array($val)){
				$template->setValue($key, $val);
			} else {
				if($key === "history_list"){
					$his_data_1page = [];
					$his_data_2page = [];

					foreach($val as $key2 => $val2){
						if($key2 < 15){
							$his_data_1page[$key2]['year']         = $val2[0];
							$his_data_1page[$key2]['mon']          = $val2[1];
							$his_data_1page[$key2]['name']         = $val2[2];
						} else {
							$his_data_2page[$key2]['year']         = $val2[0];
							$his_data_2page[$key2]['mon']          = $val2[1];
							$his_data_2page[$key2]['name']         = $val2[2];
						}
					}

					$template->cloneBlock('history1', 15, true, false, $his_data_1page);
					$template->cloneBlock('history2', 10, true, false, $his_data_2page);

				} elseif ($key === "license_list"){
					$lic_data = [];
					foreach($val as $key2 => $val2){
						$lic_data[$key2]['year']         = $val2[0];
						$lic_data[$key2]['mon']          = $val2[1];
						$lic_data[$key2]['name']         = $val2[2];
					}
					$template->cloneBlock('license', 6, true, false, $lic_data);
				}
			}
		}

		$filePath = $wordFilePath.$data["_token"].'.docx';
		$dl_name = "履歴書_".date('Ymd')."_".$data["basic_full_name"].".docx";

		//保存
		$template->saveAs($filePath);

		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=$dl_name");
		header("Content-Length:".filesize($filePath));
		readfile($filePath);

		//削除
		unlink($filePath);
		die();


		/*
		"basic_edit_year" => "2023"
		"basic_edit_month" => "2"
		"basic_edit_date" => "21"
		"basic_full_name" => "テストAWSテスト"
		"basic_name_kana" => "てすとてすと"
		"basic_gender" => "男"
		"basic_birth_year" => "1990"
		"basic_birth_month" => "10"
		"basic_birth_date" => "16"
		"basic_phone1" => "090-8888-7777"
		"basic_phone2_1" => "080"
		"basic_phone2_2" => "2212"
		"basic_phone2_3" => "2231"
		"basic_email" => "ishiyama.jsnet+20220428aws05@gmail.com"
		"basic_postal" => " 010-1653 "
		"basic_address" => "栃木県宇都宮市テスト町3丁目8-5 モンスターハウス201"
		"basic_address_kana" => ""
		"contact_postal_1" => "150"
		"contact_postal_2" => "001"
		"contact_address" => "東京都渋谷区神宮前５丁目３４−３"
		"contact_address_kana" => "とうきょうとしぶやくじんぐうまえ5ちょうめ34の3"
		"contact_phone1_1" => "080"
		"contact_phone1_2" => "1122"
		"contact_phone1_3" => "3322"
		"contact_phone2_2" => "1122"
		"contact_phone2_3" => "2211"
		"edu_history_order" => array:21 [▶]
		"edu_history_year" => array:21 [▶]
		"edu_history_month" => array:21 [▶]
		"edu_history_school" => array:21 [▶]
		"edu_history_category" => array:21 [▶]
		"edu_history_toggle" => array:21 [▶]
		"career_history_order" => array:22 [▶]
		"career_history_year" => array:22 [▶]
		"career_history_month" => array:22 [▶]
		"career_history_company" => array:22 [▶]
		"career_history_category" => array:22 [▶]
		"career_history_toggle" => array:22 [▶]
		"licence_history_order" => array:7 [▶]
		"licence_history_year" => array:7 [▶]
		"licence_history_month" => array:7 [▶]
		"licence_history_name" => array:7 [▶]
		"licence_history_category" => array:7 [▶]
		"licence_history_toggle" => array:7 [▶]
		"commuting_hour" => "0"
		"commuting_minute" => "45"
		"dependents_number" => "5"
		"spouse_exist" => "1"
		"motivation" => "志望の動機、特技、好きな学科など志望の動機、特技、好きな学科など志望の動機、特技、好きな学科など志望の動機、特技、好きな学科など志望の動機、特技、好きな学科など志望の動機、特技、好きな学科など志望の動機、特技、好きな学科など志望の動機、特技、好きな学科など"
		"treatment" => """
		本人希望記入欄（特に給料・職種・勤務時間・勤務地・その他についての希望などがあれば記入）本人希望記入欄（特に給料・職種・勤務時間・勤務地・その他についての希望などがあれば記入）本人希望記入欄（特に給料・職種・勤務時間・勤務地・その他についての希望などがあれば記入）
		（特に給料・職種・勤務時間・勤務地・その他についての希望などがあれば記入）（特に給料・職種・勤務時間・勤務地・その他についての希望などがあれば記入）（特に給料・職種・勤務時間・勤務地・その他についての希望などがあれば記入）
		"""
		"_token" => "G08dhrNbXuY1HeNvZ8iSsrBL8c9as3AmRZ29dPCc"
		"mode" => "debug"
		"submit" => "Wordファイルで出力する"
		"basic_age" => 32
		"history_list" => array:25 [▶]
		"license_list" => array:7 [▶]
		"spouse_exist_display" => "有"
		"spouse_support_display" => "無"
		*/
    }

    public function view(Request $request)
    {

        $uniqueid = Auth::user()->CandidateID__c;

        $response = new \stdClass();
        $response->Entry__c       = $this->getEntry__c($uniqueid);
        $response->WorkHistory__c = $this->getWorkHistory__c($response->Entry__c->Id);

        $hdata = $this->displayHistory($response);

		$mtb_data = [];
		for($i=date("Y"); $i>date("Y")-70; $i--){
			$mtb_data['year'][] = (int)$i;
		}
		for($i=1; $i<13; $i++){
			$mtb_data['mon'][]  = (int)$i;
		}

        return view('mypage.resume_create.inputform')->with(['data' => $hdata, 'mtb_data' => $mtb_data]);
    }

    public function displayHistory($sfinfo){
		//データを定義
        $data = [];

		//作成日
		$data["basic_edit_year"] = date('Y');
		$data["basic_edit_month"] = date('n');
		$data["basic_edit_date"] = date('j');
		
		//プロフィールデータ
        $data["basic_full_name"] = $sfinfo->Entry__c->Name;
		$data["basic_name_kana"] = mb_convert_kana($sfinfo->Entry__c->KanaLastName__c, "c");
		$data["basic_gender"] = $sfinfo->Entry__c->Seibetsu__c;

		$tmp_birthday = $sfinfo->Entry__c->Birthdate__c;
		$arr_birthday = explode("-",$tmp_birthday);
		$data["basic_display_birthday"] = $arr_birthday["0"]." 年 ".sprintf("%d", $arr_birthday["1"]). " 月 ". sprintf("%d", $arr_birthday["2"]). " 日 ";
		$data["basic_birth_year"] = $arr_birthday["0"];
		$data["basic_birth_month"] = sprintf("%d", $arr_birthday["1"]);
		$data["basic_birth_date"] = sprintf("%d", $arr_birthday["2"]);
		$data["basic_age"] = "";
		$data["basic_phone1"] = $sfinfo->Entry__c->TELM__c;
		
		if (isset($sfinfo->Entry__c->E_mail__c)) { $data["basic_email"] = $sfinfo->Entry__c->E_mail__c; }
		if (isset($sfinfo->Entry__c->Zipcode__c)) { $data["basic_postal"] = $sfinfo->Entry__c->Zipcode__c; }

		$data["basic_address"] = "";
		if (isset($sfinfo->Entry__c->Field6__c)) { $data["basic_address"] .= $sfinfo->Entry__c->Field6__c; }
		if (isset($sfinfo->Entry__c->Field7__c)) { $data["basic_address"] .= $sfinfo->Entry__c->Field7__c; }
		if (isset($sfinfo->Entry__c->Mansion__c)) { $data["basic_address"] .= $sfinfo->Entry__c->Mansion__c; }

		$data["basic_address_kana"] = "";

		$data["edu_history_year"]	= array();
		$data["edu_history_month"]	= array();
		$data["edu_history_school"]	= array();
		$data["edu_history_category"]	= array();
		$data["edu_history_toggle"]	= array();

		for($i = 0; $i < 22; $i++){
			$data["edu_history_year"][$i]		= "";
			$data["edu_history_month"][$i]		= "";
			$data["edu_history_school"][$i]		= "";
			$data["edu_history_category"][$i]	= 1;
			$data["edu_history_toggle"][$i]		= 0;
			$data["edu_history_display"][$i]	= "入力してください";
			$data["edu_history_alert"][$i]		= 1;

			$data["career_history_year"][$i]	= "";
			$data["career_history_month"][$i]	= "";
			$data["career_history_company"][$i]	= "";
			$data["career_history_category"][$i]	= 1;
			$data["career_history_toggle"][$i]	= 0;
			$data["career_history_display"][$i]	= "入力してください";
			$data["career_history_alert"][$i]	= 1;
		}

		//学歴
		if(isset($sfinfo->Entry__c->Field20__c)){
			//入学
			if (isset($sfinfo->Entry__c->Field20__c))	{ $data["edu_history_school"][0] = $sfinfo->Entry__c->Field20__c; }
			if (isset($sfinfo->Entry__c->Major__c))	{ $data["edu_history_school"][0] .= ' '.$sfinfo->Entry__c->Major__c; }
			$data["edu_history_category"][0] = 1;
			$data["edu_history_toggle"][0] = 1;
			$data["edu_history_display"][0]	= "○年○月 ".$data["edu_history_school"][0].' 入学';

			//卒業
			if (isset($sfinfo->Entry__c->GraduationY__c)) { $data["edu_history_year"][1] = $sfinfo->Entry__c->GraduationY__c; $edu_year = $sfinfo->Entry__c->GraduationY__c."年"; } else{ $edu_year = "○年";}
			if (isset($sfinfo->Entry__c->GraduationM__c)) { $data["edu_history_month"][1] = $sfinfo->Entry__c->GraduationM__c; $edu_year = $sfinfo->Entry__c->GraduationM__c."月 "; } else{ $edu_month = "○月";}
			if (isset($sfinfo->Entry__c->Field20__c)) { $data["edu_history_school"][1] = $sfinfo->Entry__c->Field20__c; }
			if (isset($sfinfo->Entry__c->Major__c))	{ $data["edu_history_school"][1] .= ' '.$sfinfo->Entry__c->Major__c; }
			$data["edu_history_category"][1] = 2;
			$data["edu_history_toggle"][1] = 1;
			$data["edu_history_display"][1]	= $edu_year.$edu_month." ".$data["edu_history_school"][1]." 卒業";
			if(
				$data["edu_history_year"][1] == "" ||
				$data["edu_history_month"][1]== "" ||
				$data["edu_history_school"][1] == ""
			){
				$data["edu_history_alert"][1] = 1;
			}else{
				$data["edu_history_alert"][1] = 0;
			}
		}

		//職歴
		if(count($sfinfo->WorkHistory__c) > 0){
			$j = 0;
			foreach($sfinfo->WorkHistory__c as $key => $value){
				if(isset($value->EntryY__c)) {
					$data["career_history_year"][$j] = $value->EntryY__c;
					$career_year = $value->EntryY__c."年";
				}else{
					$data["career_history_year"][$j] = "";
					$career_year = "○年";
				}

				if(isset($value->EntryM__c)) {
					$data["career_history_month"][$j] = $value->EntryM__c;
					$career_month = $value->EntryM__c."月 ";
				}else{
					$data["career_history_month"][$j] = "";
					$career_month = "○月";
				}

				$data["career_history_display"][$j] = $career_year.$career_month.' '.$value->Name." 入社";
				$data["career_history_company"][$j] = $value->Name;
				$data["career_history_category"][$j] = 1;
				$data["career_history_toggle"][$j] = 1;

				if(
					$data["career_history_year"][$j] == "" ||
					$data["career_history_month"][$j]== "" ||
					$data["career_history_company"][$j] == ""
				){
					$data["career_history_alert"][$j] = 1;
				}else{
					$data["career_history_alert"][$j] = 0;
				}

				$j = $j+1;
				if($j > 17){ break; }
				if(isset($value->LeaveY__c) && !is_null($value->LeaveY__c)){
					if(isset($value->LeaveY__c)){
						$data["career_history_year"][$j] = $value->LeaveY__c;
						$career_year = $value->LeaveY__c."年";
					}else{
						$data["career_history_year"][$j] = "";
						$career_year = "○年";
					}
					if(isset($value->LeaveM__c)){
						$data["career_history_month"][$j] = $value->LeaveM__c;
						$career_month = $value->LeaveM__c."月 ";
					}else{
						$data["career_history_month"][$j] = "";
						$career_month = "○月";
					}
					$data["career_history_display"][$j] = $career_year.$career_month.' '.$value->Name." 退職";
					$data["career_history_company"][$j] = $value->Name;
					$data["career_history_category"][$j] = 2;
					$data["career_history_toggle"][$j] = 1;

					if(
						$data["career_history_year"][$j] == "" ||
						$data["career_history_month"][$j]== "" ||
						$data["career_history_company"][$j] == ""
					){
						$data["career_history_alert"][$j] = 1;
					}else{
						$data["career_history_alert"][$j] = 0;
					}

					$j = $j+1;
					if($j > 17){ break; }
				}

			}
		}

		for($i = 0; $i < 7; $i++){
			$data["licence_history_year"][$i]	= "";
			$data["licence_history_month"][$i]	= "";
			$data["licence_history_name"][$i]	= "";
			$data["licence_history_category"][$i]	= 1;
			$data["licence_history_toggle"][$i]	= 0;
			$data["licence_history_display"][$i]	= "入力してください";
			$data["licence_history_alert"][$i]	= 1;
		}

		if(isset($sfinfo->Entry__c->Qualification1__c) && !is_null($sfinfo->Entry__c->Qualification1__c)){
			$q1 = $sfinfo->Entry__c->Qualification1__c;
			if(!is_null($sfinfo->Entry__c->QDetail__c)){
				$qd1 = $sfinfo->Entry__c->QDetail__c;
			}else{
				$qd1 = "";
			}

			$data["licence_history_name"][0] = $q1." ".$qd1;
			$data["licence_history_toggle"][0]	= 1;
			$data["licence_history_display"][0]	= " ○年○月 ".$q1." ".$qd1."　合格"; 
			$data["licence_history_alert"][0]	= 1;
		}

		if(isset($sfinfo->Entry__c->Qualification2__c) && !is_null($sfinfo->Entry__c->Qualification2__c)){
			$q2 = $sfinfo->Entry__c->Qualification2__c;
			if(!is_null($sfinfo->Entry__c->QDetai2__c)){
				$qd2 = $sfinfo->Entry__c->QDetai2__c;
			}else{
				$qd2 = "";
			}

			$data["licence_history_name"][1] = $q2." ".$qd2;
			$data["licence_history_toggle"][1]	= 1;
			$data["licence_history_display"][1]	= " ○年○月 ".$q2." ".$qd2."　合格"; 
			$data["licence_history_alert"][1]	= 1;
		}

		if(isset($sfinfo->Entry__c->Qualification3__c) && !is_null($sfinfo->Entry__c->Qualification3__c)){
			$q3 = $sfinfo->Entry__c->Qualification3__c;
			if(!is_null($sfinfo->Entry__c->QDetai3__c)){
				$qd3 = $sfinfo->Entry__c->QDetai3__c;
			}else{
				$qd3 = "";
			}

			$data["licence_history_name"][2] = $q3." ".$qd3;
			$data["licence_history_toggle"][2]	= 1;
			$data["licence_history_display"][2]	= " ○年○月 ".$q3." ".$qd3."　合格"; 
			$data["licence_history_alert"][2]	= 1;
		}

        return $data;
    }


    //SOQL関数1
    public function getEntry__c($can_id){
        $soqlQuery = "SELECT Id,
        Name, KanaLastName__c, Seibetsu__c, Birthdate__c, TELM__c, TEL__c, E_mail__c, Zipcode__c, Field6__c, Field7__c, Mansion__c, Field17__c, Graduation__c, Field20__c, Major__c, GraduationY__c, GraduationM__c, Qualification1__c, Qualification2__c, Qualification3__c, QDetail__c, QDetail2__c, QDetail3__c from Entry__c WHERE CandidateID__c = '".$can_id."'";

        try {
            $result = SF::select($soqlQuery);
        } catch(Expection $e) {
            Log::channel('mypage')->error('"Entryのデータ取得処理で例外が発生しました。' . $e->getMessage());
            return response()->json(['is_success' => false, 'message' => "処理に失敗しました。"]);
        }
       
        return $result->records[0];
    }

    //SOQL関数2
    public function getWorkHistory__c($id){
        $soqlQuery = "SELECT Id, Name, EntryY__c, EntryM__c, LeaveY__c, LeaveM__c from WorkHistory__c WHERE Entry__c = '".$id."' ORDER BY EntryY__c DESC";

        try {
            $result = SF::select($soqlQuery);
        } catch(Expection $e) {
            Log::channel('mypage')->error('"WorkHistory__cのデータ取得処理で例外が発生しました。' . $e->getMessage());
            return response()->json(['is_success' => false, 'message' => "処理に失敗しました。"]);
        }

        return $result->records;
    }
}
